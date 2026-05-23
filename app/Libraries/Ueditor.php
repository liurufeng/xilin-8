<?php
namespace App\Libraries;

/**
 * 编辑器类
 */
class Ueditor {
	
	public $height; // 高
	public $width; // 宽
	public $type;  // simple 简版   other
	public $charset = 'utf-8'; // 编码
	public $base = '';
	public $url = '/plugins/ueditor/';
	public $content = '请输入……';
    private $CI;
    private $HTTP;
    private $index_page;
    private $uurl;
	public function __construct(){
		/*$this->CI =& get_instance();
        $this->HTTP = $this->CI->config->config['base_url'];
        $this->index_page = $this->CI->config->config['index_page'];*/
    $config = \CodeIgniter\Config\Services::request()->config;
    $this->HTTP = $config->baseURL;
    $this->index_page = $config->indexPage;
    $this->uurl = $this->HTTP.$this->url;
       
	}
    
	/**
	 * 获取编辑器
	 */
	public function getueditor($type,$width,$heght,$charset,$content=''){
		
		if(!empty($content)){
			$this->content = $content;
		}
		$this->charset = 'urf-8';
		
		
		$ueditorstr = '';
		$ueditorstr .= '<style type="text/css">
					      
					    </style>';
		$ueditorstr .= '<script type="text/javascript">
						
    					var HTTP = "'.$this->HTTP.'";
                        var index_page = "'.$this->index_page.'";
    					var URLU = "'.$this->url.'";
    					</script>';
		$ueditorstr .= '<script type="text/javascript" charset="'.$this->charset.'" src="'.$this->uurl.'editor_config.js"></script>
					    <script type="text/javascript" charset="utf-8" src="'.$this->uurl.'editor_all.js"></script>
					    <link rel="stylesheet" type="text/css" href="'.$this->uurl.'themes/default/ueditor.css"/>';
		$ueditorstr .= '<script type="text/plain" id="myEditor" style="width:100%;">'.$this->content.'</script>';
		$ueditorstr .= '<script type="text/javascript">
						var editor_a = new baidu.editor.ui.Editor();
    					editor_a.render( \'myEditor\' );
    					
    					</script>';
		
		return $ueditorstr;
	}
	
}

/* End of file ueditor_helper.php */