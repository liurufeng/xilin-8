<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Schooluser--add</title>
<link href='<?php echo base_url();?>/admin/css/base.css' rel='stylesheet' type='text/css'>
<script language='javascript'>
	function checkSubmit()
  {
     if(document.form1.name.value==""){
	     alert("School User's name is not null!");
	     document.form1.name.focus();
	     return false;
     }
     return true;
 }
</script>
<script language='javascript' src="<?php echo base_url();?>/admin/js/main.js"></script>
<script>
var HTTPURL = '<?php echo site_url()?>';
var BASEURL = '<?php echo base_url()?>';
</script>
 <?php 
  
$posturl = site_url('schooluser/add');
$picupurl = site_url('schooluser/add');
	
  ?>
</head>
<body background='<?php echo base_url();?>/admin/images/allbg.gif' leftmargin='8' topmargin='8'>
<table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#D6D6D6">
  <tr>
    <td height="19" background="<?php echo base_url();?>/admin/images/tbg.gif" bgcolor="#E7E7E7">
      <table width="96%" border="0" cellspacing="1" cellpadding="1">
        <tr> 
          <td width="24%" style="padding-left:10px;"><b><strong>schooluser add</strong></b> </td>
          <td width="76%" align="right"><strong><a href="<?=$_SESSION['admin_path']?>schooluser/index"><u>schooluser list</u></a></strong></td>
        </tr>
      </table></td>
</tr>
<tr>
    <td height="215" align="center" valign="top" bgcolor="#FFFFFF">
	<form name="form1" enctype="multipart/form-data" action="<?=$_SESSION['admin_path']?>schooluser/add" onSubmit="return checkSubmit();" method="post">
	<input type="hidden" name="dopost" value="add" />
  		<table width="98%" border="0" cellspacing="1" cellpadding="1">
            <tr>
                <td height="30"><span style="color:red;">*</span>Semester:</td>
                <td style="text-align:left;">
                    <select id="semester_id" name="semester_id">
                        <?php foreach($semesters as $semester) { ?>
                        <option value="<?=$semester['semester_id']?>" <?php if($semester['semester_id'] == session()->get('semester_id')) echo 'selected'; ?> >
                        <?=$semester['semester_year']?> <?=$semester['semester_name']?>
                        </option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td height="30"><span style="color:red;">*</span>Parent:</td>
                <td style="text-align:left;">
                    <select id="parent_id" name="parent_id">
                        <?php foreach($parents as $p) {?>
                        <option value="<?=$p['parent_id']?>" >
                            <?=$p['parent_id']?> | <?=$p['email']?> | <?=$p['primary_en_name']?> | <?=$p['alter_en_name']?> | <?=$p['alter_contact_email']?>
                        </option>
                        <?php } ?>
                    </select>
            </tr>
            <tr>
                <td height="30"><span style="color:red;">*</span>Name:</td>
                <td style="text-align:left;"><input name="name" type="text" id="name" size="16" style="width:200px" /></td>
            </tr>
          <tr> 
            <td height="30">Phone:</td>
            <td style="text-align:left;"><input name="phone" type="text" id="phone" size="16" style="width:200px" /></td>
          </tr>        
          <tr> 
            <td height="30">Email:</td>
            <td style="text-align:left;"><input name="email" type="text" id="email" size="16" style="width:200px" /></td>
          </tr>      
          <tr> 
            <td height="30">Type:</td>
            <td style="text-align:left;">
            	 <select name='type' style='width:200px'>
			  	<?php
				if($groupList){
					foreach($groupList as $k=>$v)
					{
						echo "<option value='".$v['id']."'>".$v['ename']."</option>\r\n";
					}
					}
			  	?>
			  	</select>
		  	 </td>
          </tr>          
          <tr> 
            <td height="30">Show:</td>
            <td style="text-align:left;">
            	<input type="radio" name="isshow" value="1" checked/>yes
            	<input type="radio" name="isshow" value="0" />no
            </td>
          </tr>
        <tr>
          <td height="30">Show Order:</td>
          <td style="text-align:left;"><input name="show_order" type="text" id="show_order" size="16" style="width:200px"  value=""/></td>
          </td>
        </tr>
        <tr>
            <td height="30">Image:</td>
            <td style="text-align:left;">
			    <?php $posturl = '';?>
			    <table width="100%" border="0" cellspacing="1" cellpadding="1">
	                <tr>
	                  <td height="30">
	                  <input name="picname" type="hidden" id="picname" style="width:240px" value="" />
	                  <input type="button"  value="upload" style="width:70px;cursor:pointer;" /> 
	                  <iframe name='uplitpicfra' id='uplitpicfra' src='' width='200' height='200' style='display:none'></iframe>
	                  <span class="litpic_span"><input name="litpic" type="file" id="litpic"  onChange="SeePicNew(this, 'divpicview', 'uplitpicfra', 165, '<?php echo $picupurl?>');" size="1" class='np coolbg'/></span>
	                  </td>
	                </tr>
                </table>
            </td>
          </tr>  
          <tr>
         	 <td height="30">&nbsp;</td>
         	 <td>
         	 	<div id='divpicview' class='divpre'></div>
         	 	<input type="hidden" value="" id="icon" name="icon" />
         	 </td>
          </tr>          
          <tr> 
            <td height="30">Desc:</td>
            <td style="text-align:left;"><input name="desc" type="text" id="desc" size="16" style="width:200px" /></td>
          </tr>
          <tr> 
            <td height="60">&nbsp;</td>
            <td><input type="submit" name="Submit" value="add " class="coolbg np" /></td>
          </tr>
        </table>
      </form>
	  </td>
</tr>
</table>
</body>
</html>