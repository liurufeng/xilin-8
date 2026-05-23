<?php

/**
 * CI_CMS api
 * @author  ciogao@gmail.com
 * @version 1.0
 */
class Ci_api extends Model
{
  function Model_name()
  {
    parent::Model();
  }

  /**
   *从数据库中获取信息  Powered by ciogao@gmail.com
   *cig_get(tablename,array_name,array_where,array_order,limit_start,limit_end,array_xml,cache,cache_time);
   *1、 tablename      表名
   *2、 array_name      array('字段1','字段2',…)
   *3、 array_where      array('条件1','条件2',…)
   *4、 array_order      array('排序1','排序2',…)
   *5、 limit_start      limit开始值  默认为0
   *6、 limit_end        limit结束值  默认为10
   *7、 if_back        0返回array 1返回xml 2返回json 默认为0
   *8、 cache        0不使用缓存 1使用缓存  默认为0
   *9、 cache_time      缓存时间 单位为分钟  默认为2
   */
  function cig_get($table, $array_name, $array_where, $array_order, $limit_start = 0, $limit_end = 10, $if_back = 0, $cache = 0, $time = 2)
  {

    if ($cache != 0) {
      $this->output->CACHE($time);
    }

    $s_n = '';
    foreach ($array_name as $v) {
      $s_n .= $v . ',';
    }
    $s_n = substr($s_n, 0, -1);


    $s_w = 'where';
    foreach ($array_where as $k => $v) {
      $s_w = $s_w . ' ' . $k . '"' . $v . '" and ';
    }
    $s_w .= '1=1';

    $s_o = 'order by';
    $i = 0;
    foreach ($array_order as $k => $v) {
      $i++;
      if ($i < count($array_order)) {
        $s_o .= ' ' . $k . ' ' . $v . ',';
      } else {
        $s_o .= ' ' . $k . ' ' . $v;
      }
    }

    $limit_start = empty($limit_start) ? 0 : $limit_start;
    $limit_end = empty($limit_end) ? 10 : $limit_end;

    $sql = "SELECT $s_n FROM $table $s_w $s_o";
    if ($limit_end != 0) {
      $sql .= " limit $limit_start,$limit_end";
    }
    //echo $sql;

    $sql_count = "SELECT count(1) as a FROM $table $s_w $s_o";
    if ($limit_end != 0) {
      $sql_count .= " limit $limit_start,$limit_end";
    }
    $count = $this->db->query($sql_count)->getResultArray();
    if ($count[0]['a'] > 0) {
      $data['count']['count'] = $count[0]['a'];
      $query = $this->db->query($sql);
      foreach ($query->result_array() as $row) {
        $data[] = $row;
      }
    } else {
      $data['count']['count'] = '0';
    }

    switch ($if_back) {
      case 0:
        return $data;
        break;
      case 1:
        header("Content-type: text/xml");
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?><datalist>";
        foreach ($data as $v_array) {
          echo '<xml_data>';
          foreach ($v_array as $k => $v) {
            echo '<xml_' . $k . '>' . $v . '</xml_' . $k . '>';
          }
          echo '</xml_data>';
        }
        echo '</datalist>';
        break;
      case 2:
        echo json_encode($data);
        break;
    }
  }


  /**
   *向数据库中推送信息  Powered by ciogao@gmail.com
   *cig_post(tablename,array_k_v,if_back);
   *
   * 1、 tablename      表名
   *2、 array_k_v        array(字段1=>值1,字段2=>值2,…)
   *3、 if_back        1为返回插入id，2为返回XML，3为返回json 0为不返回 默认为0
   */
  function cig_post($table, $array_k_v, $if_back = 0)
  {
    if ($this->db->insert($table, $array_k_v)) {
      $success = $this->db->insert_id();
    } else {
      $success = 0;
      //$this->info("添加信息操作失败！");
    }

    switch ($if_back) {
      case 1:
        return $success;
        break;
      case 2:
        header("Content-type: text/xml");
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?><datalist>";
        echo '<xml_success>' . $success . '</xml_success>';
        echo '</datalist>';
        break;
      case 3:
        echo json_encode($success);
        break;
    }
  }

  /**
   *更新数据库中的信息  Powered by ciogao@gmail.com
   *cig_update(tablename,array_k_v,array_where,if_back);
   *
   *1、 tablename      表名
   *2、 array_k_v        array(字段1=>值1,字段2=>值2,…)
   *3、 array_where        array(条件1,条件2,…)
   *4、 if_back        1为返回受修改行数，2为返回包含受修改行的XML，3为返回包含受修改行的json 0为不返回 默认为0
   */
  function cig_update($table, $array_k_v, $array_where, $if_back)
  {
    $s_set = 'set ';
    foreach ($array_k_v as $k => $v) {
      if (strstr($k, ',')) { //判断是否存在,
        $k = explode(',', $k);
        switch ($k[1]) {
          case '0': //如果为0则转义
            $s_set .= "$k[0]=" . $this->db->escape($v) . ",";
            break;
          case '1':
            $s_set .= "$k[0]=$v,";
            break;
        }
      } else {
        $s_set .= "$k[0]=" . $this->db->escape($v) . ",";
      }

    }

    $s_set = substr($s_set, 0, -1);

    $s_where = 'where ';
    foreach ($array_where as $k => $v) {
      $s_where .= "$k $v and ";
    }
    $s_where .= '1=1';

    $sql = "update $table $s_set $s_where";
    //echo $sql;exit;
    $query = $this->db->query($sql);
    $num = mysql_affected_rows();

    switch ($if_back) {
      case 1:
        return $num;
        break;
      case 2:
        header("Content-type: text/xml");
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?><datalist>";
        echo '<xml_success>' . $num . '</xml_success>';
        echo '</datalist>';
        break;
      case 3:
        echo json_encode($num);
        break;
    }
  }


  /**
   *删除数据库中的信息  Powered by ciogao@gmail.com
   *cig_del(tablename,array_where,if_back);
   *
   *1、 tablename      表名
   *2、 array_where        array('条件1','条件2',…)
   *3、 if_back        1为返回结果，2为返回包含结果的XML，3为返回包含结果的json 0为不返回 默认为0
   */
  function cig_del($table, $array_where, $if_back = 0)
  {
    $array_where_tem = $array_where;
    foreach ($array_where_tem as $k => $v) {
      $array_where_k = $k;
      $array_where_v = $v;
    }

    $this->db->where_in($array_where_k, $array_where_v);
    $this->db->delete('news_info');
    $num = mysql_affected_rows();
    if ($num > 0) {
      $success = $num; //成功
    } else {
      $success = 0; //失败
    }

    switch ($if_back) {
      case 1:
        echo $success;
        break;
      case 2:
        header("Content-type: text/xml");
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?><datalist>";
        echo '<xml_success>' . $success . '</xml_success>';
        echo '</datalist>';
        break;
      case 3:
        echo json_encode($success);
        break;
    }
  }

  /**
   * 加密与解密函数 Powered by ciogao@gmail.com
   * cig_auth($key,$value_tem,$de_or_en='de',$ci_or_dz='ci',$if_cookie=0,$cookie_com='',$expire=86500,$dz_key='cig')
   *
   *1、key      应用cookie时键名 $key
   *2、value_tem    加密或解密时的值 可为数组或字符串
   *3、de_or_en    de为解密,en为加密 默认为de
   *4、ci_or_dz    是否应用dz函数，默认不应用
   *5、if_cookie    是否应用cookie，默认不应用
   *6、cookie_com    应用cookie时作用域
   *7、expire      应用cookie时生命时间，默认一天
   *8、dz_key    应用dz函数时的密钥
   */
  function cig_auth($key, $value_tem, $de_or_en = 'de', $ci_or_dz = 'ci', $if_cookie = 0, $cookie_com = '', $expire = 86500, $dz_key = 'cig')
  {

    if ($de_or_en == 'en') {
      switch ($ci_or_dz) {
        case 'ci':
          $value = base64_encode(json_encode($value_tem));
          break;
        case 'dz':
          $value = self::authcode(json_encode($value_tem), 'encode', $dz_key, $expire);
          break;
      }
    } else {
      switch ($ci_or_dz) {
        case 'ci':
          $value = json_decode(base64_decode($value_tem), true);
          break;
        case 'dz':
          $value = json_decode(self::authcode($value_tem, 'DECODE', $dz_key));
          break;
      }
    }

    switch ($if_cookie) {
      case 0:
        return $value;
        break;
      case 1:
        $this->load->helper('cookie');
        $cookie = array(
          'name' => $key,
          'value' => $value,
          'expire' => time() + $expire,
          'domain' => $cookie_com,
          'path' => '/',
          'prefix' => '',
        );
        set_cookie($cookie);
        break;
    }

  }

  /**
   * 辅助加密与解密函数 取自康盛
   *
   * $string： 明文 或 密文
   * $operation：DECODE表示解密,其它表示加密
   * $key： 密匙
   * $expiry：密文有效期
   */
  function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
  {
    // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
    $ckey_length = 4;

    // 密匙
    $key = md5($key ? $key : 'ciogao@gmail.com');

    // 密匙a会参与加解密
    $keya = md5(substr($key, 0, 16));
    // 密匙b会用来做数据完整性验证
    $keyb = md5(substr($key, 16, 16));
    // 密匙c用于变化生成的密文
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
    // 参与运算的密匙
    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);
    // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
    // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    // 产生密匙簿
    for ($i = 0; $i <= 255; $i++) {
      $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
    for ($j = $i = 0; $i < 256; $i++) {
      $j = ($j + $box[$i] + $rndkey[$i]) % 256;
      $tmp = $box[$i];
      $box[$i] = $box[$j];
      $box[$j] = $tmp;
    }
    // 核心加解密部分
    for ($a = $j = $i = 0; $i < $string_length; $i++) {
      $a = ($a + 1) % 256;
      $j = ($j + $box[$a]) % 256;
      $tmp = $box[$a];
      $box[$a] = $box[$j];
      $box[$j] = $tmp;
      // 从密匙簿得出密匙进行异或，再转成字符
      $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if ($operation == 'DECODE') {
      // substr($result, 0, 10) == 0 验证数据有效性
      // substr($result, 0, 10) - time() > 0 验证数据有效性
      // substr($result, 10, 16) == substr(md5(substr($result,26).$keyb), 0, 16) 验证数据完整性
      // 验证数据有效性，请看未加密明文的格式
      if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
        return substr($result, 26);
      } else {
        return '';
      }
    } else {
      // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
      // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
      return $keyc . str_replace('=', '', base64_encode($result));
    }
  }

  /**
   * 获取客户端IP
   * @auther Powered by ciogao@gmail.com
   * @return $ip
   */
  function get_ip()
  {
    $ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
      foreach ($matches[0] as $xip) {
        if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
          $ip = $xip;
          break;
        }
      }
    }
    return $ip;
  }

  /**
   * 获取客户端地址
   * @auther Powered by ciogao@gmail.com
   * @param string $ip 默认为当前IP
   * @return $ip_adr
   */
  function get_adr($ip = 'here')
  {
    if ($ip == 'here') {
      $a = $_SERVER['HTTP_X_FORWARDED_FOR'];
      $b = explode(',', $a);
      $ip = $b[0];
    }

    $ip_adr = iconv('gbk', 'utf-8', file_get_contents('http://int.dpool.sina.com.cn/iplookup/?ip=' . $ip));
    $ip_adr = preg_split('/[\s,;]+/', $ip_adr);
    return $ip_adr;
  }

}

/* End of file ci_api.php */
