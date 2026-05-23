<?php

/*
 * 将整数转换到指定的区间
 * $num:需转换的整数
 * $min:最小值
 * $max:最大值
*/
function to_limit_lng($num, $min, $max=0){
	$num 	= (int)($num);
	$min	= (int)($min);
	$max	= (int)($max);

	if ($num < $min){
		return $min;
	}

	if ($max > 0 && $num > $max){
		return $max;
	}
	return $num;
}

/*
 * 分页函数
 * $page : 当前页
 * $record_count : 总记录数
 * $page_size : 每页记录数
*/

function page($page_url, $record_count, $page_size, $page){
	if( ! $page_url){
		$page_url = $_SERVER['PHP_SELF'] . "?";
	}else{
		if(stripos($page_url, "?") === false){
			$page_url .= "?";
		}else{
			if (substr($page_url, -1) != "&") $page_url .= "&";
		}
	}

	if( ! $record_count) return;

	$page_size	= to_limit_lng($page_size, 1, "");
	$page_count = ceil($record_count / $page_size);
	$page		= to_limit_lng($page, 1, $page_count);

	$page_str  	= "共 <font color='red'>". $record_count ."</font> 条&nbsp;&nbsp;( <font color='red'>" . $page . "</font> / " . $page_count . " )&nbsp;&nbsp;";

	if($page == 1){
		$page_str	.= "<a href='javascript:;' class='page'>首页</a>&nbsp;&nbsp;";
		$page_str	.= "<a href='javascript:;' class='page'>上一页</a>&nbsp;&nbsp;";
	}else{
		$page_str	.= "<a href='" . $page_url . "page=1' class='page'>首页</a>&nbsp;&nbsp";
		$page_str	.= "<a href='" . $page_url . "page=" . ($page-1) . "' class='page'>上一页</a>&nbsp;&nbsp";
	}

	if ($page == $page_count){
		$page_str	.= "<a href='javascript:;' class='page'>下一页</a>&nbsp;&nbsp;";
		$page_str	.= "<a href='javascript:;' class='page'>末页</a>&nbsp;&nbsp;";
	}else{
		$page_str	.= "<a href='" . $page_url . "page=" . ($page+1) . "' class='page'>下一页</a>&nbsp;&nbsp;";
		$page_str	.= "<a href='" . $page_url . "page=$page_count' class='page'>末页</a>&nbsp;&nbsp;";
	}

	$page_str	.= '<input type="text" name="page" value="" size="4" class="inputText1" />';
	$page_str	.= '<input type="button" value="Go" class="tMiddle" onclick="location.href=\''. $page_url . 'page=' . '\' + this.previousSibling.value;" />';
	return $page_str;
}

function page1($page_url, $record_count, $page_size, $page){
	if( ! $page_url){
		$page_url = $_SERVER['PHP_SELF'] . "?";
	}else{
		if(stripos($page_url, "?") === false){
			$page_url .= "?";
		}else{
			if (substr($page_url, -1) != "&") $page_url .= "&";
		}
	}

	if( ! $record_count) return;

	$page_size	= to_limit_lng($page_size, 1, "");
	$page_count = ceil($record_count / $page_size);
	$page		= to_limit_lng($page, 1, $page_count);

	$page_str  	= "共 <font color='red'>". $record_count ."</font> 条&nbsp;&nbsp;( <font color='red'>" . $page . "</font> / " . $page_count . " )&nbsp;&nbsp;";

	if($page == 1){
		$page_str	.= "<a href='javascript:;' class='page'>首页</a>&nbsp;&nbsp;";
		$page_str	.= "<a href='javascript:;' class='page'>上一页</a>&nbsp;&nbsp;";
	}else{
		$page_str	.= "<a href='" . $page_url . "page=1' class='page'>首页</a>&nbsp;&nbsp";
		$page_str	.= "<a href='" . $page_url . "page=" . ($page-1) . "' class='page'>上一页</a>&nbsp;&nbsp";
	}

	if ($page == $page_count){
		$page_str	.= "<a href='javascript:;' class='page'>下一页</a>&nbsp;&nbsp;";
		$page_str	.= "<a href='javascript:;' class='page'>末页</a>&nbsp;&nbsp;";
	}else{
		$page_str	.= "<a href='" . $page_url . "page=" . ($page+1) . "' class='page'>下一页</a>&nbsp;&nbsp;";
		$page_str	.= "<a href='" . $page_url . "page=$page_count' class='page'>末页</a>&nbsp;&nbsp;";
	}

	$page_str .= "<select name='page' onchange=\"window.location=this.options[this.selectedIndex].value\">\n";
	for ($i = 1; $i <= $page_count; $i++){
		$URL = $page_url . "page=$i";
		if ($page == $i){
			$page_str .= "<option value='$i' selected>$i</option>\n";
		}else{
			$page_str .= "<option value=\"$URL\">$i</option>\n";
		}
	}
	$page_str .= "</select>";

	return $page_str;
}


function page2($page_url, $record_count, $page_size, $page){
	if (!$page_url){
		$page_url = $_SERVER['PHP_SELF'] . "?";
	}else{
		if (stripos($page_url, "?") === false){
			$page_url .= "?";
		}else{
			if (substr($page_url, -1) != "&") $page_url .= "&";
		}
	}

	if (!$record_count) return;

	$page_size	= to_limit_lng($page_size, 1, "");
	$page_count = ceil($record_count / $page_size);
	$page		= to_limit_lng($page, 1, $page_count);

	//上5页
	if ($page <= 5){
//		$page_str	= "<a href='javascript:;'>上5页</a>";
		$page_str	= "";
	}else{
		//$page_str	= "<a href='" . $page_url . "page=" . ($page-5) . "'>上5页</a>";
	}

	//上一页
	if ($page == 1){
		$page_str	.= '<a href="javascript:;" id="pageleft" class="page_fy"><span>上一页</span><em></em></a>';
	}else{
		$page_str	.= '<a href="'. $page_url .'page=' . ($page-1) . '" id="pageleft" class="page_fy"><span>上一页</span><em></em></a>';
		//$page_str	.= "<a href='" . $page_url . "page=" . ($page-1) . "'>上一页</a>";
	}

	$count	= 4;	//显示页码数
	$i 		= ($page-(int)($count/2) > 0) ? ($page-(int)($count/2)) : 1;	//起始页码
	$count 	= ($i + $count < $page_count) ? ($i + $count) : $page_count;	//终止页码
	
	while($i<=$count){
		if ($i == $page){
			$page_str 	.= '<a href="'. $page_url .'page=' . $i . '" class="current" ><span>'. $i .'</span><em></em></a>';
			//$page_str 	.= "<a href='". $page_url ."page=" . $i . "' class='cur'>" . $i . "</a>";
		}else{
			//$page_str 	.= "<a href='". $page_url ."page=" . $i . "'>" . $i . "</a>";
			$page_str 	.= '<a href="'. $page_url .'page=' . $i . '"><span>'. $i .'</span><em></em></a>';
		}
		$i++;
	}

	//后一页
	if ($page == $page_count){
		$page_str	.='<a href="javascript:;" class="page_fy " id="pageright"><span>下一页</span><em></em></a>';
		//$page_str	.= "<a href='javascript:;'>下一页</a>";
	}else{
		$page_str	.='<a href="'. $page_url . 'page=' . ($page+1) . '" class="page_fy " id="pageright"><span>下一页</span><em></em></a>';
		//$page_str	.= "<a href='" . $page_url . "page=" . ($page+1) . "'>下一页</a>";
	}

	//后5页
	if ($page >= $page_count-5){
		//$page_str	.= "<a href='javascript:;'>下5页</a>";
	}else{
		//$page_str	.= "<a href='" . $page_url . "page=" . ($page+5) . "'>下5页</a>";
	}
	return $page_str;
}

function page_vw($page_url, $record_count, $page_size, $page,$id){
	
	if (!$page_url){
		$page_url = $_SERVER['PHP_SELF'] . "?";
	}else{
		if (stripos($page_url, "?") === false){
			$page_url .= "?";
		}else{
			if (substr($page_url, -1) != "&") $page_url .= "&";
		}
	}

	if (!$record_count) return;

	$page_size	= to_limit_lng($page_size, 1, "");
	$page_count = ceil($record_count / $page_size);
	$page		= to_limit_lng($page, 1, $page_count);

	//上5页
	if ($page <= 5){
//		$page_str	= "<a href='javascript:;'>上5页</a>";
		$page_str	= "";
	}else{
		//$page_str	= "<a href='" . $page_url . "page=" . ($page-5) . "'>上5页</a>";
	}

	//上一页
	if ($page == 1){
		$page_str	.= '<a href="javascript:;" id="pageleft" class="page_fy"><span>&lt;&lt;</span><em></em></a>';
	}else{
		$page_str	.= '<a href="'. $page_url .'page=' . ($page-1) . $id .'" id="pageleft" class="page_fy"><span>&lt;&lt;</span><em></em></a>';
		//$page_str	.= "<a href='" . $page_url . "page=" . ($page-1) . "'>上一页</a>";
	}

	$count	= 4;	//显示页码数
	$i 		= ($page-(int)($count/2) > 0) ? ($page-(int)($count/2)) : 1;	//起始页码
	$count 	= ($i + $count < $page_count) ? ($i + $count) : $page_count;	//终止页码
	
	while($i<=$count){
		if ($i == $page){
			$page_str 	.= '<a href="'. $page_url .'page=' . $i . $id .'" class="disable" ><span>'. $i .'</span><em></em></a>';
			//$page_str 	.= "<a href='". $page_url ."page=" . $i . "' class='cur'>" . $i . "</a>";
		}else{
			//$page_str 	.= "<a href='". $page_url ."page=" . $i . "'>" . $i . "</a>";
			$page_str 	.= '<a href="'. $page_url .'page=' . $i . $id .'"><span>'. $i .'</span><em></em></a>';
		}
		$i++;
	}

	//后一页
	if ($page == $page_count){
		$page_str	.='<a href="javascript:;" class="page_fy " id="pageright"><span>&gt;&gt;</span><em></em></a>';
		//$page_str	.= "<a href='javascript:;'>下一页</a>";
	}else{
		$page_str	.='<a href="'. $page_url . 'page=' . ($page+1) .$id . '" class="page_fy " id="pageright"><span>&gt;&gt;</span><em></em></a>';
		//$page_str	.= "<a href='" . $page_url . "page=" . ($page+1) . "'>下一页</a>";
	}

	//后5页
	if ($page >= $page_count-5){
		//$page_str	.= "<a href='javascript:;'>下5页</a>";
	}else{
		//$page_str	.= "<a href='" . $page_url . "page=" . ($page+5) . "'>下5页</a>";
	}
	return $page_str;
}


function page_vw_dealer($page_url, $record_count, $page_size, $page){
	
	if (!$page_url){
		$page_url = $_SERVER['PHP_SELF'] . "?";
	}else{
		if (stripos($page_url, "?") === false){
			$page_url .= "?";
		}else{
			if (substr($page_url, -1) != "&") $page_url .= "&";
		}
	}

	if (!$record_count) return;

	$page_size	= to_limit_lng($page_size, 1, "");
	$page_count = ceil($record_count / $page_size);
	$page		= to_limit_lng($page, 1, $page_count);

	//上一页
	if ($page == 1){
		//$page_str	.= '<a href="javascript:;" >&lt;&lt;</a>';
	}else{
		//$page_str	.= '<a href="'. $page_url .'page=' . ($page-1) . '#dealers">&lt;&lt;</a>';
	}

	$count	= 2;	//显示页码数
	$i 		= ($page-(int)($count/2) > 0) ? ($page-(int)($count/2)) : 1;	//起始页码
	$count 	= ($i + $count < $page_count) ? ($i + $count) : $page_count;	//终止页码
	
	while($i<=$count){
		if ($i == $page){
			$page_str 	.= '<a href="'. $page_url .'page=' . $i . '#dealers" class="on" >'. $i .'</a>';
		}else{
			$page_str 	.= '<a href="'. $page_url .'page=' . $i . '#dealers">'. $i .'</a>';
		}
		$i++;
	}
	//后一页
	if ($page == $page_count){
		//$page_str	.='<a href="javascript:;">&gt;&gt;</a>';
	}else{
		//$page_str	.='<a href="'. $page_url . 'page=' . ($page+1) . '#dealers">&gt;&gt;</a>';
	}
	return $page_str;
}
function page_dyk($page_url, $record_count, $page_size, $page){
	
	if( ! $page_url){
		$page_url = $_SERVER['PHP_SELF'] . "?";
	}else{
		if(stripos($page_url, "?") === false){
			$page_url .= "?";
		}else{
			if (substr($page_url, -1) != "&") $page_url .= "&";
		}
	}

	if( ! $record_count) return;

	$page_size	= to_limit_lng($page_size, 1, "");
	$page_count = ceil($record_count / $page_size);
	$page		= to_limit_lng($page, 1, $page_count);

	//$page_str  	= "共 <font color='red'>". $record_count ."</font> 条&nbsp;&nbsp;( <font color='red'>" . $page . "</font> / " . $page_count . " )&nbsp;&nbsp;";

	if($page == 1){
		$page_str	.= "<a disabled='true' class='pgstar nohave'>首页</a>";
		$page_str	.= "<a disabled='true' class='pgup nohave'>上一页</a>";
	}else{
		$page_str	.= "<a href='" . $page_url . "page=1' class='pgstar nohave'>首页</a>";
		$page_str	.= "<a href='" . $page_url . "page=" . ($page-1) . "' class='pgup nohave'>上一页</a>";
	}
	$count	= 4;	//显示页码数
	
	if($page_count > $count +1){
		$i 		= ($page-(int)($count/2) > 0) ? ($page-(int)($count/2)) : 1;	//起始页码
		if($i > $page_count - $count){
			$i = $page_count - $count;
		}
	}else{
		$i 		= ($page-(int)($count/2) > 0) ? ($page-(int)($count/2)) : 1;	//起始页码
	}
	
	if($page_count <= $count+1){
		$i = '1';
	}
	$count 	= ($i + $count < $page_count) ? ($i + $count) : $page_count;	//终止页码
	
	$page_str 	.= "<span class='pagebtn'>";
	while($i<=$count){
		if ($i == $page){
			$page_str 	.= '<a href="'. $page_url .'page=' . $i . '" class="current" >'. $i .'</a>';
		}else{
			$page_str 	.= '<a href="'. $page_url .'page=' . $i . '">'. $i .'</a>';
		}
		$i++;
	}
	$page_str 	.= "</span>";
	if ($page == $page_count){
		$page_str	.= "<a disabled='true' class='pgdn'>下一页</a>&nbsp;&nbsp;";
		$page_str	.= "<a disabled='true' class='pgend'>尾页页</a>&nbsp;&nbsp;";
	}else{
		$page_str	.= "<a href='" . $page_url . "page=" . ($page+1) . "' class='pgdn'>下一页</a>&nbsp;&nbsp;";
		$page_str	.= "<a href='" . $page_url . "page=$page_count' class='pgend'>末页</a>&nbsp;&nbsp;";
	}

	//$page_str	.= '<input type="text" name="page" value="" size="4" class="inputText1" />';
	//$page_str	.= '<input type="button" value="Go" class="tMiddle" onclick="location.href=\''. $page_url . 'page=' . '\' + this.previousSibling.value;" />';
	return $page_str;
}

function page_vw_search($page_url, $record_count, $page_size, $page){
	
	if (!$page_url){
		$page_url = $_SERVER['PHP_SELF'] . "?";
	}else{
		if (stripos($page_url, "?") === false){
			$page_url .= "?";
		}else{
			if (substr($page_url, -1) != "&") $page_url .= "&";
		}
	}

	if (!$record_count) return;

	$page_size	= to_limit_lng($page_size, 1, "");
	$page_count = ceil($record_count / $page_size);
	$page		= to_limit_lng($page, 1, $page_count);

	//上一页
	if ($page == 1){
		$page_str	.= '<a href="javascript:;" >&lt;&lt;</a>';
	}else{
		$page_str	.= '<a href="'. $page_url .'page=' . ($page-1) . '">&lt;&lt;</a>';
	}

	$count	= 2;	//显示页码数
	$i 		= ($page-(int)($count/2) > 0) ? ($page-(int)($count/2)) : 1;	//起始页码
	$count 	= ($i + $count < $page_count) ? ($i + $count) : $page_count;	//终止页码
	
	while($i<=$count){
		if ($i == $page){
			$page_str 	.= '<a href="'. $page_url .'page=' . $i . '" class="disable" >'. $i .'</a>';
		}else{
			$page_str 	.= '<a href="'. $page_url .'page=' . $i . '">'. $i .'</a>';
		}
		$i++;
	}
	//后一页
	if ($page == $page_count){
		$page_str	.='<a href="javascript:;">&gt;&gt;</a>';
	}else{
		$page_str	.='<a href="'. $page_url . 'page=' . ($page+1) . '">&gt;&gt;</a>';
	}
	return $page_str;
}
function pageshort($page_url, $record_count, $page_size, $page){

	if( ! $page_url){
		$page_url = $_SERVER['PHP_SELF'] . "?";
	}else{
		if(stripos($page_url, "?") === false){
			$page_url .= "?";
		}else{
			if (substr($page_url, -1) != "&") $page_url .= "&";
		}
	}

	if( ! $record_count) return;

	$page_size	= to_limit_lng($page_size, 1, "");
	$page_count = ceil($record_count / $page_size);
	$page		= to_limit_lng($page, 1, $page_count);
	$page_str = '';
	
	// $page_str  	.= "共 <font color='red'>". $record_count ."</font> 条&nbsp;&nbsp;( <font color='red'>" . $page . "</font> / " . $page_count . " )&nbsp;&nbsp;";

	if($page == 1){
		$page_str	.= "<a href='javascript:;' class=''>首&nbsp;&nbsp;页</a>";
		//$page_str	.= "<a href='javascript:;' class='page'>上一页</a>&nbsp;&nbsp;";
	}else{
		//$page_str	.= "<a href='" . $page_url . "page=1' class='page'>首页</a>";
		$page_str	.= "<a href='" . $page_url . "page=" . ($page-1) . "' class=''>上一页</a>";
	}

	if ($page == $page_count){
		//$page_str	.= "<a href='javascript:;' class=''>下一页</a>";
		$page_str	.= "<a href='javascript:;' class=''>末&nbsp;&nbsp;页</a>";
	}else{
		$page_str	.= "<a href='" . $page_url . "page=" . ($page+1) . "' class=''>下一页</a>";
		//$page_str	.= "<a href='" . $page_url . "page=$page_count' class='page'>末页</a>&nbsp;&nbsp;";
	}

//	//$page_str .= "<select name='page' onchange=\"window.location=this.options[this.selectedIndex].value\">\n";
//	for ($i = 1; $i <= $page_count; $i++){
//		$URL = $page_url . "page=$i";
//		if ($page == $i){
//			$page_str .= "<option value='$i' selected>$i</option>\n";
//		}else{
//			$page_str .= "<option value=\"$URL\">$i</option>\n";
//		}
//	}
	//$page_str .= "</select>";

	return $page_str;
}
//Fckeditor编辑器的内容分页
function page_content($page_url, $page_count, $page){
	$page_count = (int)($page_count);
	if($page_count < 2){
		return;
	}

	$page = toLimitLng($page, 1, $page_count);
	$page_str = "";

	//上一页
	if ($page == 1) {
		$page_str .= "<a href='javascript:;'>上一页</a>";
	} else {
		$page_str .= "<a href='" . $page_url . "?p=" . ($page-1) . "'>上一页</a>";
	}

	$i	= 1;//起始页码
	while($i <= $page_count){
		if ($i == $page) {
			$page_str .= "<a href='". $page_url ."?p=" . $i . "' class='cur'>" . $i . "</a>";
		} else {
			$page_str .= "<a href='". $page_url ."?p=" . $i . "'>" . $i . "</a>";
		}
		$i++;
	}

	//后一页
	if ($page == $page_count) {
		$page_str .= "<a href='javascript:;'>下一页</a>";
	} else {
		$page_str .= "<a href='" . $page_url . "?p=" . ($page+1) . "'>下一页</a>";
	}

	return $page_str;
}

/* End of file page_helper.php */