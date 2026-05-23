<?php namespace App\Models\Admin;
use CodeIgniter\Model;
/**
 *
 * member_model
 * @author Rufeng Liu3
 * @version v1.0
 */
class Member_model extends Model
{

  function __construct()
  {
    parent::__construct();
  }

  /**
   * @param str username 用户名
   * @param str password 密码
   * @param bool is_system 是否是管理后台验证 默认false
   *
   * 登录
   */
  function user_login($username, $password)
  {

    $username = !empty($username) ? trim($username) : '';
    $password = !empty($password) ? $password : '';

    if (empty($username)) {
      return '-1001'; // 用户名不能为空
    }
    if (empty($password)) {
      return '-1002'; // 密码不能为空
    }

    $sql = "select id,name,pass,group_id 
            from admin
            where name = '{$username}'
            and state = 1";
    $userinfo = $this->db->query($sql)->getResultArray();
    if (empty($userinfo)) {
      return '-1003'; // 用户信息不存在
    }

    if ($password != $userinfo[0]['pass']) {
      $login_logdata = array(
        'typeid' => '1',
        'username' => $username,
        'password' => $password,
        'logintime' => time(),
        'loginip' => get_client_ip(),
      );

      $this->db->table('login_log')
        ->insert($login_logdata);
      //$this->db->insert('login_log', $login_logdata);

      return '-1004'; // 密码错误
    }

    $ip = get_client_ip() ? get_client_ip() : 0;
    $this->db->table('admin')
      ->where(['id' => $userinfo[0]['id']])
      ->update([
        'lastLoginTime' =>time(),
        'lastLoginIp' => $ip
      ]);
    return $userinfo[0]['id']; // 成功登录
  }

  /**
   * 获取用户信息
   * @param int uid 用户uid
   */
  function getuserinfo($uid)
  {
    if (empty($uid)) {
      return false;
    }
    $sql = "select a.id,a.name,a.group_id,a.realname,a.loginCount,r.nodeids
            from admin a
            join admin_role r on r.id = a.group_id
            where a.id = {$uid}
            ";

    $row = $this->db->query($sql)->getResultArray();
    return $row[0];
  }

  /**
   * 获取所有的有效管理组
   */
  function getGroupList()
  {
    $sql = "SELECT id,name,description FROM
    		  admin_role
    		  WHERE is_check = '1' 
    		  ";
    $groupList = $this->db->query($sql)->getResultArray();

    if ($groupList) {
      return $groupList;
    } else {
      return FALSE;
    }
  }
}
/* End of file member.php */
