<?php
namespace App\Controllers\Xilin_ns_admin;

use App\Controllers\BaseController;
/*
 *
 */
class MY_Controller extends BaseController
{
	/** 用户ID
	 *
	 * @var _uid
	 */
	public  $_uid;
	/**
	 * 用户名
	 *
	 * @var _name
	 */
	public $_name;
	/**
	 * 用户真实姓名
	 *
	 * @var _realname
	 */
	public $_realname;
	/**
	 * 登录次数
	 *
	 * @var _loginCount
	 */
	public $_loginCount;
	/**
	 * 用户组
	 */
	public $_groupId;
	/**
	 * 
	 * 编码
	 */
	public $_cfg_soft_lang = 'utf-8';
	/**
	 * 名称
	 */
	public $_sitename = 'Admin';
	/**
	 * 类库名称
	 */
	public $_class;
	/**
	 * 方法名称
	 */
	public $_classname;
	/**
	 * 所有需要做权限节点的方法体
	 */
	public $_methods = array();
	/**
	 *  是否是权限节点
	 */
	public $_issystem = FALSE;
	
	function _Mycontroller()
	{
		@header ("Cache-Control: no-cache, must-revalidate");  
		@header ("Pragma: no-cache");
		@header ("Content-type: text/html; charset=utf-8"); 
		//parent::__construct();
    $this->db = db_connect();
		$this->_class = get_class($this);
		$this->_dosystem();
	}
	
	/**
	 * 检测登录并获取个人属性
	 *
	 */
	function _check_login(){
		
		
		if(self::_is_login())
		{
			self::_get_user();
		}
		else
		{
			$currenturl = current_url();
      return redirect()->to(site_url('/login').'?back='.$currenturl);
		}
		
		// 权限检测
		self::dopower();
	}
	/**
	 * 权限检测
	 */
	function dopower(){
		return TRUE;
		if($this->_issystem || $this->_groupId == '1'){
      $dofunctionname = '';
      $uri = service('uri');
      if($uri->getTotalSegments() > 2 ) {
        $dofunctionname = $uri->getSegment(3);
      }
			//$dofunctionname = $this->uri->segment(3);
			
			if(empty($dofunctionname)){
				return TRUE;
			}
			if(empty($this->_methods[$dofunctionname])){
				return TRUE;
			}
			// 根据类名称查找
			$sql = "SELECT id FROM admin_node WHERE code = '".strtolower($this->_class)."' LIMIT 1";
			$reidarr = $this->db->query($sql)->getRowArray();
			var_dump($reidarr);
			$sql_function = "SELECT id FROM admin_node  
			WHERE reid = '".$reidarr['id']."' AND code = '".strtolower($dofunctionname)."'
			LIMIT 1";
			$functionarr = $this->db->query($sql_function)->getRowArray();
			
			$sql_do = "SELECT id FROM admin_role 
						WHERE id = '".$this->_groupId."' 
						AND ( nodeids like '%,".$reidarr['id'].",%' OR nodeids like '%,".$functionarr['id'].",%' OR nodeids ='all' ) 
						LIMIT 1";
			$ispower = $this->db->query($sql_do);
			
//			if($ispower->num_rows() == '1'){
//				return TRUE;
//			}else{
//				ShowMsg('您暂时无该操作权限',site_url('index/index_body'));
//			}
		}else{
			return TRUE;
		}
		
	}
	
	/**
	 * 判断是否登录
	 * 
	 */
	function _is_login()
	{		

		if(session()->get('name') && session()->get('id') && session()->get('group_id'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	/**
	 * 根据会话获取用户数据
	 *
	 * @return private 用户信息
	 */
	function _get_user(){
		
		$this->_uid 		= session()->get('id');
		$this->_name 		= session()->get('name');
		$this->_realname 	= session()->get('realname');
		$this->_loginCount 	= session()->get('loginCount');
		$this->_groupId 	= session()->get('group_id');
		
	}
	
	/**
	 * 返回用户信息常量
	 *
	 * @return array
	 */
	function _return_userinfo(){
		
		$user = array(
			'uid' 			=> $this->_uid,
			'name' 			=> $this->_name,
			'realname' 		=> $this->_realname,
			'loginCount' 	=> $this->_loginCount,
			'gtoupId' 		=> $this->_groupId,
		);
		return $user;
	}
	
	/**
	 * 执行权限节点的方法
	 */
	function _dosystem(){
		// 查看是否是系统权限节点
		if($this->_issystem && $this->_class && $this->_methods){
			// 查看是否入库
			$sql = "SELECT id FROM admin_node 
					WHERE code = '".$this->_class."' AND reid = '0'";
			$info = $this->db->query($sql)->getResultArray();
			// 为空 注入数据库
			if(empty($info)){
				$data['reid'] = '0';
				$data['typeid'] = '0';
				$data['name'] = $this->_classname;
				$data['code'] = $this->_class;
				$data['sort'] = '10';
				$data['is_check'] = '1';
				//$this->db->insert('admin_node',$data);
        $ins_obj = $this->db->table('admin_node')
          ->insert($data);
        //var_dump($this->db->insertID()); exit;
        $info['id'] = $this->db->insertID(); //$ins_obj->connID->insert_id;
				//$info['id'] = $this->db->insert_id();
			}		
			// 注入所有的包含更新
			foreach ($this->_methods as $k=>$v){
			
				$where['code'] = $k;
				$where['reid'] = $info['id'];
				$info_id = $info['id'];
				//$ishave = $this->db->get_where('admin_node',$where)->countAllResults();
				$sql = "select * from admin_node 
                where code = '".$k."' and reid = ".$info_id;
				$ishave = $this->db->query($sql)->getResultArray();
				// 这里只处理添加 对于修改名称 与删除 不做处理 请到页面直接修改或者删除
				if(!$ishave){
					unset($data);
					$data['reid'] = $info['id'];
					$data['typeid'] = '1';
					$data['name'] = $v;
					$data['code'] = $k;
					$data['sort'] = '10';
					$data['is_check'] = '1';
					//$this->db->insert('admin_node',$data);
          $this->db->table('admin_node')
            ->insert($data);
				}
			}
			return TRUE;
		}else{
			return TRUE;
		}
		
	}
	
}

/* End of file MY_Controller.php */