<?php namespace App\Libraries;
/****************************************************
* File		: tree.class.php
* Descript	: 用于将数据库中的数组按照从属关系整理成树或列表
***************************************************/

class Tree {
	public  $id;
	public  $reid;
	public $sqlresult;
	public $arr			= array(); //原始节点数组
	public $child_tree	= array();
	public $parent_tree	= array();

	public function __construct($sqlresult, $id='id', $reid='reid') {
		$this->init($sqlresult,$id,$reid);
	}

	// 初始化运行
	public function init($sqlresult,$id,$reid){
		$this->id = $id;
		$this->reid = $reid;
		
		if (is_array($sqlresult)) {
			$this->arr = $sqlresult;
			
		}else{
			$this->$sqlresult = $sqlresult;
		}
		$this->_setArr();
	}

	// 得到全部节点数组
	protected function _setArr(){
		if ( empty($this->arr) && !is_array($this->arr) ) {
			$cachename = md5($this->sql);			
			if (S($cachename)) {
				$arr = S($cachename);
			}else{
				$result = M()->query( $this->sql );
				$arr = array();
				foreach ($result as $row){
					$arr[$row[$this->id]] = $row;
				}				
				S($cachename,$arr);
			}
			$this->arr = $arr;
		}
		foreach ($this->arr as $value) {
			$this->child_tree[$value[$this->reid]][$value[$this->id]] = $value;
			$this->parent_tree[$value[$this->id]] = null; //$this->arr[$value[$this->reid]];
		}
	}

	// 返回父列表
	public function getParentList($node_id = 0) {
		return $this->_parent($node_id);
	}

	// 返回子列表
	public function getChildList($node_id = 0 , $level = 0) {
		return $this->_child($node_id , $level);
	}

	// 返回子树
	public function getChildTree($node_id = 0 , $level = 0) {
		return $this->_child($node_id , $level , 'tree');
	}

	// 返回节点
	public function getNode($node_id = 0) {
		return $this->arr[$node_id];
	}

	// 返回全部节点
	public function getAllNode(){
		return $this->arr;
	}

	// 删除分类缓存
	public function delCache(){
		@S(md5($this->sql),NULL);
	}

	protected function _child($node_id , $level = 0, $type = 'list' , $this_level = 0) {
		$arr	= $this->child_tree[$node_id] ?? null;
		$new_arr	= array();

		if ($arr) {
			$this_level++;
			foreach ($arr as $id => $node) {
				$arr[$id]['level']	= $this_level;
				$arr[$id]['child_count'] = isset($this->child_tree[$id]) && is_array($this->child_tree[$id]) ? count($this->child_tree[$id]) : 0;

				if ($type == 'list') {
					$new_arr	= $new_arr + array($id => $arr[$id]);
				}

				if ($level == 0 || $this_level < $level) {
					if (isset($this->child_tree[$id])) {
						$child	= $this->_child($id , $level , $type , $this_level);
						if ($type == 'tree') {
							$arr[$id]['child']	= $child;
						} else  {
							$new_arr	= $new_arr + $child;
						}
					}
				}
			}

			if (count($new_arr)) {
				return $new_arr;
			}
			return $arr;
		}
	}

	protected function _parent($node_id , $level = 0, $this_level = 0) {
		$t	= $this->parent_tree[$node_id];
		$parent_id	= $t[$this->id];
		$parent[$parent_id]	= $t;
		if (!$parent[$parent_id])
		return null;

		if ($this->parent_tree[$parent_id])
		{
			$node	= $this->_parent($parent_id);
			if ($node)
			{
				$parent	= $node + $parent;
			}
		}
		return $parent;
	}
}
