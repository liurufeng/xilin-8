<?php
/**
 *
 * 系统加密解密函数
 *
 * @param string $string 字符串
 * @param string $operation DECODE　解密 UNCODE 解密
 * @param string $key 加密串
 */
function authcode($string, $operation='DECODE', $key = '') {

  $index_key = 'mazda';
  $key = md5($key ? $key : md5($index_key.$_SERVER['HTTP_USER_AGENT']));
  $key_length = strlen($key);

  $string = $operation == 'DECODE' ? base64_decode($string) : substr(md5($string.$key), 0, 8).$string;
  $string_length = strlen($string);

  $rndkey = $box = array();
  $result = '';

  for($i = 0; $i <= 255; $i++) {
    $rndkey[$i] = ord($key[$i % $key_length]);
    $box[$i] = $i;
  }

  for($j = $i = 0; $i < 256; $i++) {
    $j = ($j + $box[$i] + $rndkey[$i]) % 256;
    $tmp = $box[$i];
    $box[$i] = $box[$j];
    $box[$j] = $tmp;
  }

  for($a = $j = $i = 0; $i < $string_length; $i++) {
    $a = ($a + 1) % 256;
    $j = ($j + $box[$a]) % 256;
    $tmp = $box[$a];
    $box[$a] = $box[$j];
    $box[$j] = $tmp;
    $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
  }

  if($operation == 'DECODE') {
    if(substr($result, 0, 8) == substr(md5(substr($result, 8).$key), 0, 8)) {
      return substr($result, 8);
    } else {
      return '';
    }
  } else {
    return str_replace('=', '', base64_encode($result));
  }
}
/**
 *
 * 成功josn输出
 * @param array $data json格式数据
 * @param string $msg 成功的提示
 * @param int $code 成功的标记code
 */
function go_200($data,$msg = 'success',$code = 1000){
  @header ("Cache-Control: no-cache, must-revalidate");
  @header ("Pragma: no-cache");
  @header('Content-type: application/json');
  set_status_header(200);
  echo json_encode(array('code' => $code,'msg' => $msg,'data' => $data));exit;
}
/**
 *
 * 当异常时候输出
 * @param string $msg
 * @param array $data
 * @param int $code
 */
function go_404($msg = '参数丢失',$data = '',$code = 1001){
  @header ("Cache-Control: no-cache, must-revalidate");
  @header ("Pragma: no-cache");
  @header('Content-type: application/json');
  set_status_header(200);
  echo json_encode(array('code' => $code,'msg' => $msg,'data' => $data));exit;
}
// 获取客户端IP地址
function get_client_ip(){
  if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
    $ip = getenv("HTTP_CLIENT_IP");
  else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
    $ip = getenv("HTTP_X_FORWARDED_FOR");
  else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
    $ip = getenv("REMOTE_ADDR");
  else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
    $ip = $_SERVER['REMOTE_ADDR'];
  else
    $ip = "0.0.0.0";
  return(ip2long($ip));
}

// 浏览器友好的变量输出
function dump($var, $echo=true,$label=null, $strict=true)
{
  $label = ($label===null) ? '' : rtrim($label) . ' ';
  if(!$strict) {
    if (ini_get('html_errors')) {
      $output = print_r($var, true);
      $output = "<pre>".$label.htmlspecialchars($output,ENT_QUOTES)."</pre>";
    } else {
      $output = $label . " : " . print_r($var, true);
    }
  }else {
    ob_start();
    var_dump($var);
    $output = ob_get_clean();
    if(!extension_loaded('xdebug')) {
      $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
      $output = '<pre>'. $label. htmlspecialchars($output, ENT_QUOTES). '</pre>';
    }
  }
  if ($echo) {
    echo($output);
    return null;
  }else
    return $output;
}

/*
 * 将整数转换到指定的区间
 * $num:需转换的整数
 * $min:最小值
 * $max:最大值
*/
function toLimitLng($num, $min, $max=0){
  $num = (int)($num);
  $min = (int)($min);
  $max = (int)($max);

  if ($num < $min){
    return $min;
  }

  if ($max > 0 && $num > $max){
    return $max;
  }
  return $num;
}

//获取时间
function toDate($time, $format = 'Y-m-d H:i:s') {
  if (empty($time)) {
    return '';
  }
  $format = str_replace('#', ':', $format);
  return date($format, $time);
}

//获取日期
function toDay($time, $format = 'Y-m-d') {
  if (empty($time)) {
    return '';
  }
  $format = str_replace('#', ':', $format);
  return date($format, $time);
}

//截取utf8字符串
function leftStr($str, $len){
  $str = strip_tags2($str);
  for($i=0; $i < $len; $i++)
  {
    $temp_str = substr($str, 0 ,1);
    if(ord($temp_str) > 127){
      $i++;
      if ($i < $len){
        $new_str[]	= substr($str, 0, 3);
        $str		= substr($str, 3);
      }
    }else{
      $new_str[]	= substr($str, 0, 1);
      $str		= substr($str, 1);
    }
  }
  return join($new_str);
}

function leftStr2($str, $len){
  $len1	= strlen($str);
  $str = strip_tags2($str);
  for($i=0; $i < $len; $i++){
    $temp_str = substr($str, 0 ,1);
    if(ord($temp_str) > 127){
      $i++;
      if ($i < $len){
        $new_str[]	= substr($str, 0, 3);
        $str		= substr($str, 3);
      }
    }else{
      $new_str[]	= substr($str, 0, 1);
      $str		= substr($str, 1);
    }
  }
  $new_str = join($new_str);
  if (strlen($new_str) < $len1){
    $new_str .= "…";
  }
  return $new_str;
}

function strip_tags2($str){
  $str = strip_tags($str);
  $str = str_replace("&ldquo;","“",$str);
  $str = str_replace("&rdquo;","”",$str);
  $str = str_replace("&nbsp;","",$str);
  $str = str_replace("&quot;","\"",$str);
  return $str;
}

/**
+----------------------------------------------------------
 * 产生随机字串，可用来自动生成密码 默认长度6位 字母和数字混合
+----------------------------------------------------------
 * @param string $len 长度
 * @param string $type 字串类型
 * 0 字母 1 数字 其它 混合
 * @param string $addChars 额外字符
+----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function rand_string($len=6,$type='',$addChars='') {
  $str ='';
  switch($type){
    case 0:
      $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.$addChars;
      break;
    case 1:
      $chars= str_repeat('0123456789',3);
      break;
    case 2:
      $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$addChars;
      break;
    case 3:
      $chars='abcdefghijklmnopqrstuvwxyz'.$addChars;
      break;
    case 4:
      $chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借".$addChars;
      break;
    default :
      // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
      $chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addChars;
      break;
  }
  if($len>10 ) {//位数过长重复字符串一定次数
    $chars= $type==1? str_repeat($chars,$len) : str_repeat($chars,5);
  }
  if($type!=4) {
    $chars   =   str_shuffle($chars);
    $str     =   substr($chars,0,$len);
  }else{
    // 中文随机字
    for($i=0;$i<$len;$i++){
      $str.= msubstr($chars, floor(mt_rand(0,mb_strlen($chars,'utf-8')-1)),1);
    }
  }
  return $str;
}

//Ajax操作返回
function ajaxReturn($data, $info='', $status=1){
  $result	= array('data'=>$data, 'status'=>$status, "info"=>$info);
  header("Content-Type:text/html; charset=utf-8");
  exit(json_encode($result));
}

//Ajax操作成功
function success($info){
  $result	= array('status'=>"1", "info"=>$info);
  header("Content-Type:text/html; charset=utf-8");
  exit(json_encode($result));
}

//Ajax操作失败
function error($info){
  $result	= array('status'=>"0", "info"=>$info);
  header("Content-Type:text/html; charset=utf-8");
  exit(json_encode($result));
}

/*
 * 判断ID是否合法
 * $id为待检查的id
 * $min_level为最小分类层次,默认为0 (0表示可以为空)
 * $max_level为最大分类层次,默认为5
*/
function isClassId($id, $min_level = 0, $max_level = 5){
  return preg_match("/^([1-9]\d{3}){" . $min_level . "," . $max_level . "}$/", $id);
}

//利用UNIX时间戳返回一个唯一的文件名，不含后缀
function getTmpName(){
  list($a, $b) = explode(' ', microtime());
  return (string)$b . (string)substr($a, 2);
}

//得到指定文件的扩展名
function getFileExt($filename = ''){
  return substr($filename, strrpos($filename, '.') + 1);
}

//得到不含路径的文件名
function getFileName($filename = ''){
  return substr($filename, strrpos($filename, '/') + 1);
}

//得到指定缩略图文件
function getThumbImg($filename){
  return substr($filename, 0, strrpos($filename, '.')) . "_s." . getFileExt($filename);
}

//判断是否为图片
function isImage($filename){
  $ext = getFileExt($filename);
  return ($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif" || $ext == "bmp");
}

//显示图片或Flash
function picShow($filename, $width=0, $height=0, $url='') {
  $file = str_replace(base_url(),'',$filename);
  if (file_exists($file)==false) {
    exit;
  }
  list($img_width, $img_height) = getimagesize($filename);

  if ($width == 0 && $height == 0){
    $width = $img_width;
    $height = $img_height;
  }elseif($width == 0){
    $width = $img_width * $height / $img_height;
  }elseif($height == 0){
    $height = $width * $img_height / $img_width;
  }

  $ext = getFileExt($filename);
  if ($ext == 'gif' || $ext == 'jpg' || $ext == 'png' || $ext == 'bmp'){
    if($url === ''){
      $picShow = '<img src="'. $filename .'" width="'. $width .'" height="'. $height .'" border="0" />';
    }else{
      $picShow = '<a href="'. $url .'" target="_blank"><img src="'. $filename .'" width="'. $width .'" height="'. $height .'" border="0" /></a>';
    }
  }elseif ($ext == 'swf'){
    $picShow = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="'. $width .'" height="'. $height .'"><param name="movie" value="'. $filename .'"><param name="quality" value="high"><param name="wmode" value="transparent"><embed src='. $filename .'" width="'. $width .'" height="'. $height .'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" wmode="transparent"></embed></object>';
  }else{
    $picShow = "";
  }
  return $picShow;
}

/**
 *  短消息函数,可以在某个动作处理后友好的提示信息
 *
 * @param     string  $msg      消息提示信息
 * @param     string  $gourl    跳转地址
 * @param     int     $onlymsg  仅显示信息
 * @param     int     $limittime  限制时间
 * @return    void
 */
function ShowMsg($msg, $gourl, $onlymsg=0, $limittime=0)
{
  if(empty($GLOBALS['cfg_plus_dir'])) $GLOBALS['cfg_plus_dir'] = '..';

  $htmlhead  = "<html>\r\n<head>\r\n<title>tip</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n";
  $htmlhead .= "<base target='_self'/>\r\n<style>div{line-height:160%;}</style></head>\r\n<body leftmargin='0' topmargin='0' bgcolor='#FFFFFF'>".(isset($GLOBALS['ucsynlogin']) ? $GLOBALS['ucsynlogin'] : '')."\r\n<center>\r\n<script>\r\n";
  $htmlfoot  = "</script>\r\n</center>\r\n</body>\r\n</html>\r\n";

  $litime = ($limittime==0 ? 1000 : $limittime);
  $func = '';

  if($gourl=='-1')
  {
    if($limittime==0) $litime = 5000;
    $gourl = "javascript:history.go(-1);";
  }

  if($gourl=='' || $onlymsg==1)
  {
    $msg = "<script>alert(\"".str_replace("\"","“",$msg)."\");</script>";
  }
  else
  {
    if(preg_match('/close::/',$gourl))
    {
      $tgobj = trim(preg_replace('/close::/', '', $gourl));
      $gourl = 'javascript:;';
      $func .= "window.parent.document.getElementById('{$tgobj}').style.display='none';\r\n";
    }

    $func .= "      var pgo=0;
      function JumpUrl(){
        if(pgo==0){ location='$gourl'; pgo=1; }
      }\r\n";
    $rmsg = $func;
    $rmsg .= "document.write(\"<br /><div style='width:450px;padding:0px;border:1px solid #DADADA;'>";
    $rmsg .= "<div style='padding:6px;font-size:16px;border-bottom:1px solid #DADADA;background:#FBFCE2 url({$GLOBALS['cfg_plus_dir']}/img/wbg.gif)';'><b>prompt message！</b></div>\");\r\n";
    $rmsg .= "document.write(\"<div style='height:130px;font-size:10pt;background:#ffffff'><br />\");\r\n";
    $rmsg .= "document.write(\"".str_replace("\"","“",$msg)."\");\r\n";
    $rmsg .= "document.write(\"";

    if($onlymsg==0)
    {
      if( $gourl != 'javascript:;' && $gourl != '')
      {
        $rmsg .= "<br /><a href='{$gourl}'>If your browser doesn't react, click here...</a>";
        $rmsg .= "<br/></div>\");\r\n";
        $rmsg .= "setTimeout('JumpUrl()',$litime);";
      }
      else
      {
        $rmsg .= "<br/></div>\");\r\n";
      }
    }
    else
    {
      $rmsg .= "<br/><br/></div>\");\r\n";
    }
    $msg  = $htmlhead.$rmsg.$htmlfoot;
  }
  echo $msg;exit;
}
/**
 * 根据概率进行随机抽取程序
+----------------------------------------------------------------------------------------
 * 参数说明：
 * array $pro :以 '样本' => '样本数量' 或 '样本' => '样本概率' 构成的一维数组，必选;
 * array &$res:函数返回数组，结构为 '样本' => '样本数量' 或 '样本' => '样本概率' ，必选;
 * int $num   :函数返回数组的元素数目，可选，初始为1，
 *
 * 程序示例
 * 在 Rand 抽样中，假设 A 事件发生10次， B 事件发生20次， C 事件发生30次， D 事件发生40次，
 * E 事件发生50次，那么则有:
 *
 * $Rand['A']=10;
 * $Rand['B']=20;
 * $Rand['C']=30;
 * $Rand['D']=40;
 * $Rand['E']=50;
 *
 * 现根据已知情况，预测 Rand 抽样下次可能发生的事件

 * pro_rand($Rand, $event)；
 * print_r($event);
 *
 * 再假设每个事件不可以连续发生，则根据已知情况，预测 Rand 抽样接下来两次次可能发生的事件
 *
 * pro_rand($Rand, $event, 2)；
 * print_r($event);
 *
 */
function pro_rand($pro, &$res, $num=1){

  if ($num > count($pro))exit('$num is too long and exit !');
  $max_exp = 0;
  foreach ($pro as $key => $value){
    $exp = strlen(strchr($value,'.')) - 1;
    if ($exp > $max_exp){
      $max_exp = $exp;
    }
  }
  $pow_exp = pow(10, $max_exp);
  if ($pow_exp > 1)
  {
    reset($pro);
    foreach ($pro as $key => $value){
      $pro[$key] = $value*$pow_exp;
    }
  }
  $pro_sum = array_sum($pro);
  for($i = 0; $i < $num; $i++){
    $rand_num = mt_rand(1, $pro_sum);
    reset($pro);
    foreach($pro as $key => $value){
      if ($rand_num <= $value){
        break;
      }else{
        $rand_num -= $value;
      }
    }
    $res[$i] = array($key, $value*1.0/$pow_exp);
    $pro_sum -= $value;
    unset($pro[$key]);
  }
}

/**
 * 时间测试类
 *
 * 使用方法：
 * 	$runtime= new runtime;
$runtime->start();
$runtime->stop();
执行时间: echo $runtime->spent()  毫秒
 */
class runtime
{
  var $StartTime = 0;
  var $StopTime = 0;
  function get_microtime()
  {
    list($usec, $sec) = explode(' ', microtime());
    return ((float)$usec + (float)$sec);
  }

  function start()
  {
    $this->StartTime = $this->get_microtime();
  }

  function stop()
  {
    $this->StopTime = $this->get_microtime();
  }

  function spent()
  {
    return round(($this->StopTime - $this->StartTime) * 1000, 1);
  }
}
/**
 * 添加url随机串
 */
if ( ! function_exists('randnum'))
{
  function randnum() {

    return rand(10000,99999).time().rand(100,999);
  }
}
/**
 * 密码随机方法
 */
if ( ! function_exists('random'))
{
  function random($length, $numeric = 0) {

    $seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
    $seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
    $hash = '';
    $max = strlen($seed) - 1;
    for($i = 0; $i < $length; $i++) {
      $hash .= $seed[mt_rand(0, $max)];
    }
    return $hash;
  }
}

/**
 *  中文截取2，单字节截取模式
 *  如果是request的内容，必须使用这个函数
 *
 * @access    public
 * @param     string  $str  需要截取的字符串
 * @param     int  $slen  截取的长度
 * @param     int  $startdd  开始标记处
 * @return    string
 */
if ( ! function_exists('cn_substrR'))
{
  function cn_substrR($str, $slen, $startdd=0)
  {
    $str = cn_substr(stripslashes($str), $slen, $startdd);
    return addslashes($str);
  }
}

/**
 *  中文截取2，单字节截取模式
 *
 * @access    public
 * @param     string  $str  需要截取的字符串
 * @param     int  $slen  截取的长度
 * @param     int  $startdd  开始标记处
 * @return    string
 */
if ( ! function_exists('cn_substr'))
{
  function cn_substr($str, $slen, $startdd=0)
  {
    $cfg_soft_lang = 'utf-8';
    if($cfg_soft_lang=='utf-8')
    {
      return cn_substr_utf8($str, $slen, $startdd);
    }
    $restr = '';
    $c = '';
    $str_len = strlen($str);
    if($str_len < $startdd+1)
    {
      return '';
    }
    if($str_len < $startdd + $slen || $slen==0)
    {
      $slen = $str_len - $startdd;
    }
    $enddd = $startdd + $slen - 1;
    for($i=0;$i<$str_len;$i++)
    {
      if($startdd==0)
      {
        $restr .= $c;
      }
      else if($i > $startdd)
      {
        $restr .= $c;
      }

      if(ord($str[$i])>0x80)
      {
        if($str_len>$i+1)
        {
          $c = $str[$i].$str[$i+1];
        }
        $i++;
      }
      else
      {
        $c = $str[$i];
      }

      if($i >= $enddd)
      {
        if(strlen($restr)+strlen($c)>$slen)
        {
          break;
        }
        else
        {
          $restr .= $c;
          break;
        }
      }
    }
    return $restr;
  }
}

/**
 *  utf-8中文截取，单字节截取模式
 *
 * @access    public
 * @param     string  $str  需要截取的字符串
 * @param     int  $slen  截取的长度
 * @param     int  $startdd  开始标记处
 * @return    string
 */
if ( ! function_exists('cn_substr_utf8'))
{
  function cn_substr_utf8($str, $length, $start=0)
  {
    if(strlen($str) < $start+1)
    {
      return '';
    }
    preg_match_all("/./su", $str, $ar);
    $str = '';
    $tstr = '';

    //为了兼容mysql4.1以下版本,与数据库varchar一致,这里使用按字节截取
    for($i=0; isset($ar[0][$i]); $i++)
    {
      if(strlen($tstr) < $start)
      {
        $tstr .= $ar[0][$i];
      }
      else
      {
        if(strlen($str) < $length + strlen($ar[0][$i]) )
        {
          $str .= $ar[0][$i];
        }
        else
        {
          break;
        }
      }
    }
    return $str;
  }
}

/**
 *  HTML转换为文本
 *
 * @param    string  $str 需要转换的字符串
 * @param    string  $r   如果$r=0直接返回内容,否则需要使用反斜线引用字符串
 * @return   string
 */
if ( ! function_exists('Html2Text'))
{
  function Html2Text($str,$r=0)
  {
    if(!function_exists('SpHtml2Text'))
    {

    }
    if($r==0)
    {
      return SpHtml2Text($str);
    }
    else
    {
      $str = SpHtml2Text(stripslashes($str));
      return addslashes($str);
    }
  }
}
function SpHtml2Text($str)
{
  $str = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<!--(.*)-->/isU","",$str);
  $alltext = "";
  $start = 1;
  for($i=0;$i<strlen($str);$i++)
  {
    if($start==0 && $str[$i]==">")
    {
      $start = 1;
    }
    else if($start==1)
    {
      if($str[$i]=="<")
      {
        $start = 0;
        $alltext .= " ";
      }
      else if(ord($str[$i])>31)
      {
        $alltext .= $str[$i];
      }
    }
  }
  $alltext = str_replace("　"," ",$alltext);
  $alltext = preg_replace("/&([^;&]*)(;|&)/","",$alltext);
  $alltext = preg_replace("/[ ]+/s"," ",$alltext);
  return $alltext;
}

/**
 *  文本转HTML
 *
 * @param    string  $txt 需要转换的文本内容
 * @return   string
 */
if ( ! function_exists('Text2Html'))
{
  function Text2Html($txt)
  {
    $txt = str_replace("  ", "　", $txt);
    $txt = str_replace("<", "&lt;", $txt);
    $txt = str_replace(">", "&gt;", $txt);
    $txt = preg_replace("/[\r\n]{1,}/isU", "<br/>\r\n", $txt);
    return $txt;
  }
}

/**
 *  获取半角字符
 *
 * @param     string  $fnum  数字字符串
 * @return    string
 */
if ( ! function_exists('GetAlabNum'))
{
  function GetAlabNum($fnum)
  {
    $nums = array("０","１","２","３","４","５","６","７","８","９");
    //$fnums = "0123456789";
    $fnums = array("0","1","2","3","4","5","6","7","8","9");
    $fnum = str_replace($nums, $fnums, $fnum);
    $fnum = preg_replace("/[^0-9\.-]/", '', $fnum);
    if($fnum=='')
    {
      $fnum=0;
    }
    return $fnum;
  }
}
if ( ! function_exists('GetPinyin'))
{

  /**
   *  获取拼音信息
   *
   * @access    public
   * @param     string  $str  字符串
   * @param     int  $ishead  是否为首字母
   * @param     int  $isclose  解析后是否释放资源
   * @return    string
   */
  function GetPinyin($str, $ishead=0, $isclose=1)
  {
    $str = iconv('utf-8','gbk//ignore',$str);

    $pinyins = array();
    $restr = '';
    $str = trim($str);
    $slen = strlen($str);
    if($slen < 2)
    {
      return $str;
    }
    if(count($pinyins) == 0)
    {
      $fp = fopen('./public/data/pinyin.dat', 'r');

      while(!feof($fp))
      {
        $line = trim(fgets($fp));

        $pinyins[$line[0].$line[1]] = substr($line, 3, strlen($line)-3);
      }
      fclose($fp);
    }

    for($i=0; $i<$slen; $i++)
    {
      if(ord($str[$i])>0x80)
      {
        $c = $str[$i].$str[$i+1];
        $i++;
        if(isset($pinyins[$c]))
        {
          if($ishead==0)
          {
            $restr .= $pinyins[$c];
          }
          else
          {
            $restr .= $pinyins[$c][0];
          }
        }else
        {
          $restr .= "_";
        }
      }else if( preg_match("/[a-z0-9]/i", $str[$i]) )
      {
        $restr .= $str[$i];
      }
      else
      {
        $restr .= "_";
      }
    }
    if($isclose==0)
    {
      unset($pinyins);
    }

    return $restr;
  }

}
/**
 * 字符截取 支持UTF8/GBK
 * @param $string
 * @param $length
 * @param $dot
 */
if ( ! function_exists('str_cut'))
{
  function str_cut($string, $length, $dot = '...') {
    $strlen = strlen($string);
    if($strlen <= $length) return $string;
    $string = str_replace(array(' ','&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array('∵',' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
    $strcut = '';
    if(strtolower(CHARSET) == 'utf-8') {
      $length = intval($length-strlen($dot)-$length/3);
      $n = $tn = $noc = 0;
      while($n < strlen($string)) {
        $t = ord($string[$n]);
        if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
          $tn = 1; $n++; $noc++;
        } elseif(194 <= $t && $t <= 223) {
          $tn = 2; $n += 2; $noc += 2;
        } elseif(224 <= $t && $t <= 239) {
          $tn = 3; $n += 3; $noc += 2;
        } elseif(240 <= $t && $t <= 247) {
          $tn = 4; $n += 4; $noc += 2;
        } elseif(248 <= $t && $t <= 251) {
          $tn = 5; $n += 5; $noc += 2;
        } elseif($t == 252 || $t == 253) {
          $tn = 6; $n += 6; $noc += 2;
        } else {
          $n++;
        }
        if($noc >= $length) {
          break;
        }
      }
      if($noc > $length) {
        $n -= $tn;
      }
      $strcut = substr($string, 0, $n);
      $strcut = str_replace(array('∵', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), array(' ', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), $strcut);
    } else {
      $dotlen = strlen($dot);
      $maxi = $length - $dotlen - 1;
      $current_str = '';
      $search_arr = array('&',' ', '"', "'", '“', '”', '—', '<', '>', '·', '…','∵');
      $replace_arr = array('&amp;','&nbsp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;',' ');
      $search_flip = array_flip($search_arr);
      for ($i = 0; $i < $maxi; $i++) {
        $current_str = ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
        if (in_array($current_str, $search_arr)) {
          $key = $search_flip[$current_str];
          $current_str = str_replace($search_arr[$key], $replace_arr[$key], $current_str);
        }
        $strcut .= $current_str;
      }
    }
    return $strcut.$dot;
  }
}
/* End of file common_helper.php */