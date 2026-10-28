<?php

namespace App\Controllers;

class Careers extends BaseController
{
  // resumes accepted, and their size limit in bytes
  private const RESUME_TYPES = ['pdf', 'doc', 'docx'];
  private const RESUME_MAX_SIZE = 5 * 1024 * 1024;

  public function index()
  {
    return view($_SESSION['tm'] . 'school/careers');
  }

  public function applyjob()
  {
    $field = fn(string $name) => trim((string)$this->request->getPost($name));
    $first_name = $field('first_name');
    $last_name = $field('last_name');
    $email_addr = $field('email');
    $phone = $field('phone');
    $city = $field('city');
    $state = $field('state');
    $education = $field('education');
    $experience = $field('experience');
    $available = $field('available_date');
    $message = $field('message');

    $resume = $this->request->getFile('resume');

    if ($first_name === '' || $last_name === '' || filter_var($email_addr, FILTER_VALIDATE_EMAIL) === false
      || !$resume || !$resume->isValid()) {
      session()->setFlashdata('error', 'Please provide your name, email address, and resume.');
      return redirect()->to('/careers');
    }

    $extension = strtolower($resume->getClientExtension());
    if (!in_array($extension, self::RESUME_TYPES, true) || $resume->getSize() > self::RESUME_MAX_SIZE) {
      session()->setFlashdata('error', 'Your resume must be a PDF or Word file of 5 MB or less.');
      return redirect()->to('/careers');
    }

    $subjects = $this->request->getPost('subjects');
    $subjectText = is_array($subjects) ? implode(', ', array_map('strval', $subjects)) : '';

    // resumes are kept outside public/, so they can't be downloaded from the site; the email carries a copy
    $uploadDir = WRITEPATH . 'uploads/resumes/';
    $fileName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
    $resume->move($uploadDir, $fileName);

    $subject = "Teacher Application - $first_name $last_name";

    $body =
      "Teacher Application

-----------------------------------------

First Name:
$first_name

Last Name:
$last_name

Email:
$email_addr

Phone:
$phone

City:
$city

State:
$state

Education:
$education

Experience:
$experience

Available Date:
$available

Subjects:
$subjectText

Additional Information:

$message

Resume:
attached (" . $resume->getClientName() . ")
";

    $email = service('email');
    $email->setFrom('no-reply@xilinnschinese.org', 'Xilin Northshore Chinese School');
    $email->setReplyTo($email_addr, "$first_name $last_name");
    $email->setTo('ec@xilinnschinese.org');
    $email->setSubject($subject);
    $email->setMessage($body);
    $email->attach($uploadDir . $fileName, 'attachment', $resume->getClientName());

    if ($email->send(false)) {
      session()->setFlashdata('success', 'Thank you! Your application has been submitted.');
    } else {
      log_message('error', 'xilin: teacher application from ' . $email_addr . ' not sent: ' . $email->printDebugger([]));
      session()->setFlashdata('error', 'Unable to send your application. Please try again.');
    }

    return redirect()->to('/careers');
  }
}
