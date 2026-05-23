<?php namespace App\Controllers;

use CodeIgniter\Controller;
use Google\Client;
use Google\Service\Gmail;
use Google\Service\Gmail\ModifyMessageRequest;

class Payment extends Controller
{
  public function auth()
  {
    $client = new Client();
    $client->setAuthConfig(WRITEPATH . 'credentials.json'); // credentials.json from Google Cloud Console
    $client->setRedirectUri(base_url('payment/callback'));
    //$client->addScope(Gmail::GMAIL_READONLY);
    $client->setScopes([
      Gmail::GMAIL_MODIFY,
      Gmail::GMAIL_READONLY,
    ]);
    $client->setAccessType('offline');
    $client->setPrompt('consent');

    return redirect()->to($client->createAuthUrl());
  }

  public function callback()
  {
    $client = new Client();
    $client->setAuthConfig(WRITEPATH . 'credentials.json');
    $client->setRedirectUri(base_url('payment/callback'));
    $client->addScope(Gmail::GMAIL_READONLY);
    $client->setAccessType('offline');

    $code = $this->request->getGet('code');
    if (!$code) {
      return 'Authorization failed.';
    }

    $token = $client->fetchAccessTokenWithAuthCode($code);
    if (isset($token['error'])) {
      return 'Error fetching access token: ' . $token['error_description'];
    }

    file_put_contents(WRITEPATH . 'token.json', json_encode($token));
    return 'Token saved successfully. You can now run the email reader.';
  }

  public function read($token = null)
  {
    // Optional token for securing the web endpoint
    if ($token !== 'YOUR_SECRET_TOKEN') {
      return $this->response->setStatusCode(403)->setBody('Unauthorized');
    }

    $tokenPath = WRITEPATH . 'token.json';
    if (!file_exists($tokenPath)) {
      return 'Token not found. Please authenticate first.';
    }

    $client = new Client();
    $client->setAuthConfig(WRITEPATH . 'credentials.json');
    $client->addScope(Gmail::GMAIL_READONLY);
    $client->setAccessType('offline');
    $client->setAccessToken(json_decode(file_get_contents($tokenPath), true));

    if ($client->isAccessTokenExpired()) {
      if ($client->getRefreshToken()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
      } else {
        return 'Refresh token expired. Please re-authenticate.';
      }
    }

    $service = new Gmail($client);
    $messages = $service->users_messages->listUsersMessages('me', ['labelIds' => 'INBOX', 'maxResults' => 5]);
    $output = '';
    $processed = $notpay = $manual_process = $avoid_dup = [];
    $db = db_connect();
    helper('pay_code');
    $processed_labelId = $this->getOrCreateLabel($service, 'Processed');
    $notpay_labelId = $this->getOrCreateLabel($service, 'NotPayment');
    $manual_process_labelId = $this->getOrCreateLabel($service, 'ManuelProcess');

    foreach ($messages->getMessages() as $msg) {
      $messageId = $msg->getId();
      $msgDetail = $service->users_messages->get('me', $msg->getId(), ['format' => 'full']);
      $payload = $msgDetail->getPayload();
      $headers = $msgDetail->getPayload()->getHeaders();

      $subject = $from = $date = '';
      foreach ($headers as $header) {
        switch (strtolower($header->getName())) {
          case 'subject': $subject = $header->getValue(); break;
          case 'from':    $from = $header->getValue(); break;
          case 'date':    $date = $header->getValue(); break;
        }
      }

      $body = $this->getBodyFromPayload($payload);
      $to = 'ec@xilinnschinese.org';
      $sent_info = [];
      if(strpos($subject, ' received money ') !== false) {
        $memo = stristr($body, 'Memo</td>');
        $memo = stristr($memo, '<br>', true);
        $memo = str_ireplace('<br>', '', $memo);
        //$memo = stristr($memo, '</td>', true);

        $amount = stristr($body, 'Amount</td>');
        $amount = stristr($amount, 'sent on', true);
        $amount = stristr($amount, '>$');
        $amount = stristr($amount, '</td>', true);
        //$amount = str_ireplace('sent on', '', $amount);
        //$amount = str_ireplace('Amount', '', $amount);
        $amount = str_ireplace('>$', '', $amount);
        //$amount = stristr($amount, '</td>', true);
        $sent_info[1] = trim($amount);

        $serder_name = stristr($body, ' sent you money', true);
        $serder_name = strrchr($serder_name, '">');
        //$serder_name = str_ireplace(' sent you money</td>', '', $serder_name);
        $serder_name = $serder_name ? substr($serder_name, 7) : 'Parent';
        $sent_info[0] = $serder_name;
      } else {
        // Not a payment email, move it to NotPayment folder
        $notpay[] = $messageId;
        $mods = new ModifyMessageRequest([
          'removeLabelIds' => ['INBOX'],
          'addLabelIds' => [$notpay_labelId]
        ]);
        $service->users_messages->modify('me', $messageId, $mods);
        continue;
      }

      if( strpos($from, 'Chase') !== false) {
        //Ok, this is a payment, let's process it
        $pdate = date("Y-m-d H:i:s", strtotime($date));
        $memo_err = 0;
        $parent_id = '';
        $email_chk = '';
        $parent_email = '';
        $memo = strtoupper(trim($memo));
        $original_memo = $memo;

        $sql = "select semester_id from semester where status = 1 and semester_status = 'Current'";
        $result = $db->query($sql)->getRowArray();
        $semester_id = (int)$result['semester_id'];

        preg_match("/[\d]{1,4}X[\d]{4}/", $memo, $matches);

        if(!empty($matches) && isset($matches[0])){
          $memo = $matches[0];
          if($memo) {
            $memo_arr = explode('X', $memo);
          } else {
            $memo_err = 1;
          }

          if(count($memo_arr) != 2) {
            $memo_err = 1;
          }

          $parent_id = intval($memo_arr[0]);
          $email_chk = intval($memo_arr[1]);

        } else {
          $memo_err = 1;
        }


          $sql = "select * from parents where parent_id = " . (int)$parent_id . " limit 1";
          $result = $db->query($sql)->getRowArray();

          if($result) {
            $parent_email = $result['email'];
            $db_pay_code = get_code($parent_email);
            if($db_pay_code != $email_chk) {
              $memo_err = 1;
            }
          }
          if(!$result || $memo_err){
            $manual_process[] = $msg['uid'];
            $mods = new ModifyMessageRequest([
              'removeLabelIds' => ['INBOX'],
              'addLabelIds' => [$manual_process_labelId]
            ]);
            $service->users_messages->modify('me', $messageId, $mods);

            $subject = 'FAILED Online Payment Notice';
            $msg_school = "<p>Dear Online Administrator,</p>";
            $msg_school .= "<p>There is a new online payment, however the parent ID and payment code can't resolve to an existing parent record.
          Please login on the school's Chase account to verify and then process it manually using the school's admin tool.</p><br>";

            $message = "<p><b>Parent Name:</b> {$sent_info[0]}</p>";
            $message .= "<p><b>Memo:</b> {$original_memo}</p>";
            $message .= "<p><b>Total Paid:</b> ". $sent_info[1] . "</p>";
            $message .= "<p><b>Date & Time:</b> {$pdate}</p>";
            $message .= "<br><p>Thank you,</p><p>Web Admin</p>";

            $headers = "From: Xilin Online Payment <paymentconfirmation@xilinnschinese.org>\r\n";
            $headers .= 'Bcc: ' . implode(',', ['rufeng_liu@hotmail.com']) . "\r\n";
            $headers .= "Reply-To: ec@xilinnschinese.org\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            @mail($to, $subject, $message, $headers, '-f paymentconfirmation@xilinnschinese.org');

            continue;
          }

        $checking = $parent_id.$pdate.$sent_info[1];
        if(!in_array($checking, $avoid_dup)) {
          $avoid_dup[] = $checking;
          $check_amount = floatval($sent_info[1]); //floatval(preg_replace('/[^0-9\.]+/', '', $sent_info[1]));

          $sqlx = "select check_id from checks where parent_id = ".$parent_id." and check_amount = ".$check_amount." and check_date >= NOW() - INTERVAL 30 MINUTE";
          $resultx = $db->query($sqlx)->getRowArray();
          if(isset($resultx) && isset($resultx['check_id']) && !empty($resultx['check_id'])) continue;

          $insertdata1['semester_id'] = $semester_id;
          $insertdata1['parent_id'] = $parent_id;
          $insertdata1['check_number'] =  $sent_info[0];
          $insertdata1['check_amount'] = $check_amount;
          $insertdata1['check_date'] = $pdate; //date("Y-m-d H:i:s");
          $insertdata1['received_by'] = 'Chase QP';
          $insertdata1['pay_type'] = 'Online';

          $result1 = $db->table('checks')
            ->insert($insertdata1);

          if ($result1) {
            $subject = 'Successful Online Payment Notice';
            $msg_school = "<p>Dear Online Administrator,</p>";
            $msg_school .= "<p>There is a new online payment in the following details. It's been automatically processed.
            Please login on the school's Chase account and the school's admin tool to double check.</p><br>";
            $message = "<p><b>Parent Id:</b> {$parent_id}</p>";
            $message .= "<p><b>Memo:</b> {$original_memo}</p>";
            $message .= "<p><b>Parent Name:</b> {$sent_info[0]}</p>";
            $message .= "<p><b>Total Paid:</b> ". $sent_info[1] . "</p>";
            $message .= "<p><b>Date & Time:</b> {$pdate}</p>";
            $message .= "<br><p>Thank you,</p><p>Web Admin</p>";

            $headers = "From: Xilin Online Payment <paymentconfirmation@xilinnschinese.org>\r\n";
            $headers .= 'Bcc: ' . implode(',', ['rufeng_liu@hotmail.com']) . "\r\n";
            $headers .= "Reply-To: ec@xilinnschinese.org\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            @mail($to, $subject, $message, $headers, '-f paymentconfirmation@xilinnschinese.org');

            //send email to the payer
            $msg_p = "<p>Dear {$sent_info[0]},</p>";
            $msg_p .= "<p>We have processed your QuickPay by Zelle electronic payment of {$sent_info[1]} successfully, your account balance has been updated. For any payment/invoice issues, please contact ec@xilinnschinese.org.</p><br>";
            $msg_p .= "<br><p>Regards,</p><p>Xilin NS Chinese School</p>";

            $headers = "From: Xilin Online Payment <paymentconfirmation@xilinnschinese.org>\r\n";
            $headers .= 'Bcc: ' . implode(',', ['rufeng_liu@hotmail.com']) . "\r\n";
            $headers .= "Reply-To: ec@xilinnschinese.org\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            @mail($parent_email, $subject, $msg_p, $headers, '-f paymentconfirmation@xilinnschinese.org');

            $processed[] = $messageId;
            $mods = new ModifyMessageRequest([
              'removeLabelIds' => ['INBOX'],
              'addLabelIds' => [$processed_labelId]
            ]);
            $service->users_messages->modify('me', $messageId, $mods);

          } else {
            // email alert EC to process manually since the database insertion failed
            $subject = 'FAILED Online Payment Notice';
            $msg_school = "<p>Dear Online Administrator,</p>";
            $msg_school .= "<p>There is a new online payment but failed to be inserted into the database.
            Please login on the school's Chase account to verify and then process it manually using the school's admin tool.</p><br>";

            $message = "<p><b>Parent Id:</b> {$parent_id}</p>";
            $message .= "<p><b>Memo:</b> {$original_memo}</p>";
            $message .= "<p><b>Parent Name:</b> {$sent_info[0]}</p>";
            $message .= "<p><b>Total Paid:</b> ". $sent_info[1] . "</p>";
            $message .= "<p><b>Date & Time:</b> {$pdate}</p>";
            $message .= "<br><p>Thank you,</p><p>Web Admin</p>";

            $headers = "From: Xilin Online Payment <paymentconfirmation@xilinnschinese.org>\r\n";
            $headers .= 'Bcc: ' . implode(',', ['rufeng_liu@hotmail.com']) . "\r\n";
            $headers .= "Reply-To: ec@xilinnschinese.org\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            @mail($to, $subject, $message, $headers, '-f paymentconfirmation@xilinnschinese.org');

            continue;
          }
        }

        $output .= "From: $from\nSubject: $subject\nDate: $date\n\n";
        $output .= "Memo: $memo\nAmount: $amount\nSender: $serder_name\n\n";
        // $output .= "<strong>Body:</strong><br>$body<hr>";
      } else {
        // Not a payment email, move it to NotPayment folder
        $notpay[] = $messageId;
        $mods = new ModifyMessageRequest([
          'removeLabelIds' => ['INBOX'],
          'addLabelIds' => [$notpay_labelId]
        ]);
        $service->users_messages->modify('me', $messageId, $mods);
        continue;
      }

    }

    echo 'p ';
    var_dump($processed);
    echo 'n ';
    var_dump($notpay);
    echo 'm ';
    var_dump($manual_process);
    //return nl2br($output);
  }

  private function getBodyFromPayload($payload)
  {
    $body = '';

    // Case: plain text email (no parts)
    if ($payload->getBody() && $payload->getBody()->getData()) {
      $bodyData = $payload->getBody()->getData();
      $body = base64_decode(strtr($bodyData, '-_', '+/'));
    }

    // Case: multipart email
    foreach ($payload->getParts() as $part) {
      $mimeType = $part->getMimeType();
      if ($mimeType === 'text/plain' || $mimeType === 'text/html') {
        $bodyData = $part->getBody()->getData();
        if ($bodyData) {
          $body = base64_decode(strtr($bodyData, '-_', '+/'));
          break; // Stop at the first readable part
        }
      }
    }

    return $body;
    //return nl2br(htmlspecialchars($body));
  }

  private function getOrCreateLabel($service, $labelName)
  {
    $labels = $service->users_labels->listUsersLabels('me');
    foreach ($labels->getLabels() as $label) {
      if (strtolower($label->getName()) === strtolower($labelName)) {
        return $label->getId();
      }
    }

    // Create new label
    $label = new \Google\Service\Gmail\Label([
      'name' => $labelName,
      'labelListVisibility' => 'labelShow',
      'messageListVisibility' => 'show'
    ]);
    $createdLabel = $service->users_labels->create('me', $label);

    return $createdLabel->getId();
  }
}