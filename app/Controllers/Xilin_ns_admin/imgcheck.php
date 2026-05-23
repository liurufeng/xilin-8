<?php namespace App\Controllers\Xilin_ns_admin;

/**
 *
 * 验证码
 * @author zhangjian <bdzj1230655@163.com>
 * @version v2.0 2012年7月20日 14:39:40
 *
 */
class Imgcheck extends MY_Controller
{

  function __construct()
  {
    parent::_Mycontroller();
  }

  /**
   *
   * 验证码验证
   */
  function vdimgck()
  {
    exit();
    $this->load->helper('validate');

    $fp = @fopen('./public/words/words.txt', 'rb');
    if (!$fp) return FALSE;

    $fsize = filesize('./public/words/words.txt');
    if ($fsize < 32) return FALSE;

    if ($fsize < 128) {
      $max = $fsize;
    } else {
      $max = 128;
    }

    fseek($fp, rand(0, $fsize - $max), SEEK_SET);
    $data = fread($fp, 128);
    fclose($fp);
    $data = preg_replace("/\r?\n/", "\n", $data);

    $start = strpos($data, "\n", rand(0, 100)) + 1;
    $end = strpos($data, "\n", $start);
    $rndstring = strtolower(substr($data, $start, $end - $start));

//		$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
//
//		$str = '';
//		for ($i = 0; $i < 6; $i++)
//		{
//			$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
//		}
//        $rndstring .= $str;
    $vals = array(

      'img_path' => './captcha/',
      'img_url' => $_SESSION['admin_path'].) . '/captcha/',
      //'font_path' => './public/fonts/ggbi.ttf',
      'img_width' => 200,
      'img_height' => 25,
      'expiration' => 7200,
      'word' => $rndstring
    );


    list($usec, $sec) = explode(" ", microtime());
    $now = ((float)$usec + (float)$sec);

    $data = array(
      'captcha_time' => $now,
      'ip_address' => $this->input->ip_address(),
      'word' => $rndstring
    );

    $query = $this->db->insert_string('captcha', $data);
    $this->db->query($query);

    $cap = create_captcha($vals);


//		echo $cap['image'];exit;
//		
//		dump($cap);exit;
//		echo '提交下面的验证码:';
//		echo '<form name="" action="'.$_SESSION['admin_path'].'imgcheck/imgis').'" method="post">';
//		echo $cap['image'];
//		echo '<input type="text" name="captcha" value="" />';
//		echo '<input type="submit" name="submit" value="提交" /></form>';

  }

  function vimg()
  {

    $this->load->helper('validate_m');
    $config = array(
      'font_size' => 14,
      'img_height' => '24',
      'word_type' => 3, // 1:数字  2:英文   3:单词
      'img_width' => '68',
      'use_boder' => TRUE,
      'font_file' => FCPATH . 'public/fonts/ggbi.ttf',
      'wordlist_file' => FCPATH . 'public/words/words.txt',
      'filter_type' => 5);
    $sessSavePath = FCPATH . "data/sessions";

    // Session保存路径
    if (is_writeable($sessSavePath) && is_readable($sessSavePath)) {
      session_save_path($sessSavePath);
    }

    if (!echo_validate_image($config)) {
      // 如果不成功则初始化一个默认验证码
      @session_start();
      $_SESSION['securimage_code_value'] = strtolower('abcd');
      $im = @imagecreatefromjpeg(FCPATH . 'data/vdcode.jpg');
      header("Pragma:no-cache\r\n");
      header("Cache-Control:no-cache\r\n");
      header("Expires:0\r\n");
      imagejpeg($im);
      imagedestroy($im);
    }
  }

  /*
   * 验证
   */
  function imgis()
  {
    exit();
    $expiration = time() - 7200; // 2小时限制
    $this->db->query("DELETE FROM ci_captcha WHERE captcha_time < " . $expiration);

    // 然后再看是否有验证码存在:
    $sql = "SELECT COUNT(*) AS count FROM ci_captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
    $binds = array($this->request->getVar('captcha'], $this->input->ip_address(), $expiration);

    $query = $this->db->query($sql, $binds);
    $row = $query->row();

    if ($row->count == 0) {
      echo "你必须提交图像上显示的验证码";
    }

  }


}

/* End of file login.php */
