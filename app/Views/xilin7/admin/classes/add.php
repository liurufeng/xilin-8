<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Classes--add</title>
<link href='<?php echo base_url();?>/admin/css/base.css' rel='stylesheet' type='text/css'>
<script language='javascript'>
	function checkSubmit()
  {
     if(document.form1.class_code.value==""){
	     alert("Class Code can not be empty!");
	     document.form1.class_code.focus();
	     return false;
     }
     if(document.form1.class_name.value==""){
	     alert("Class Name can not be empty!");
	     document.form1.class_name.focus();
	     return false;
     }
     return true;
 }
</script>
</head>
<body background='<?php echo base_url();?>/admin/images/allbg.gif' leftmargin='8' topmargin='8'>
<table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#D6D6D6">
  <tr>
    <td height="19" background="<?php echo base_url();?>/admin/images/tbg.gif" bgcolor="#E7E7E7"> 
      <table width="96%" border="0" cellspacing="1" cellpadding="1">
        <tr> 
          <td width="24%" style="padding-left:10px;"><b><strong>Classes add</strong></b> </td>
          <td width="76%" align="right"><strong><a href="<?=$_SESSION['admin_path']?>classes/index.php?semester_id='.session()->get('semester_id'))?>"><u>Classes list</u></a></strong></td>
        </tr>
      </table></td>
</tr>
<tr>
    <td height="215" align="center" valign="top" bgcolor="#FFFFFF">
	<form name="form1" action="<?=$_SESSION['admin_path']?>classes/add" onSubmit="return checkSubmit();" method="post">
	<input type="hidden" name="dopost" value="add" />
  		<table width="98%" border="0" cellspacing="1" cellpadding="1">
          <tr> 
            <td width="16%" height="30"><span style="color:red;">*</span>Subject:</td>
            <td width="84%"  style="text-align:left;">
	            <select name='subject_id' style='width:200px'>
				  	<?php
					if($subject){
					foreach($subject as $k=>$v)
					{
						echo "<option value='".$v['subject_id']."'>".$v['subject_name']."</option>\r\n";
					}
					}
			  	?>
			   </select>
            </td>
          </tr>
          <tr> 
            <td height="30"><span style="color:red;">*</span>Semester:</td>
            <td style="text-align:left;">
	            <select name='semester_id' style='width:200px'>
				  	<?php
					if($semester){
					foreach($semester as $k=>$v)
					{
						echo "<option value='".$v['semester_id']."'>".$v['semester_name']."</option>\r\n";
					}
					}
			  	?>
			   </select>
           </td>
          </tr>
          <tr> 
            <td height="30"><span style="color:red;">*</span>Teacher:</td>
            <td style="text-align:left;">
	            <select name='teacher_id' style='width:200px'>
				  	<?php
					if($teachers){
					foreach($teachers as $k=>$v)
					{
						echo "<option value='".$v['teacher_id']."'>".$v['en_name']."</option>\r\n";
					}
					}
			  	?>
			  </select>
          </tr>
          <tr> 
            <td height="30"><span style="color:red;">*</span>Class Code</td>
            <td style="text-align:left;"><input name="class_code" type="text" id="class_code" size="16" style="width:200px" /></td>
          </tr>
        
          <tr> 
            <td height="30"><span style="color:red;">*</span>Class Name:</td>
            <td style="text-align:left;"><input name="class_name" type="text" id="class_name" size="16" style="width:200px" /></td>
          </tr>
        
          <tr> 
            <td height="30">Notes:</td>
            <td style="text-align:left;"><input name="notes" type="text" id="notes" size="16" style="width:200px" /></td>
          </tr>
        
          <tr> 
            <td height="30">Syl Link:</td>
            <td style="text-align:left;"><input name="syl_link" type="text" id="syl_link" size="16" style="width:200px" /></td>
          </tr>
        
          <tr> 
            <td height="30">Tuition:</td>
            <td style="text-align:left;"><input name="tuition" type="text" id="tuition" size="16" style="width:200px" /></td>
          </tr>
         
          <tr> 
            <td height="30">Late Tuition:</td>
            <td style="text-align:left;"><input name="late_tuition" type="text" id="late_tuition" size="16" style="width:200px" /></td>
          </tr>
         
          <tr> 
            <td height="30">Book Fee:</td>
            <td style="text-align:left;"><input name="book_fee" type="text" id="book_fee" size="16" style="width:200px" /></td>
          </tr>
         
          <tr> 
            <td height="30">Late Book Fee:</td>
            <td style="text-align:left;"><input name="late_book_fee" type="text" id="late_book_fee" size="16" style="width:200px" /></td>
          </tr>
         
          <tr> 
            <td height="30">Material Fee:</td>
            <td style="text-align:left;"><input name="material_fee" type="text" id="material_fee" size="16" style="width:200px" /></td>
          </tr>
         
          <tr> 
            <td height="30">Meeting Time:</td>
            <td style="text-align:left;"><input name="meeting_time" type="text" id="meeting_time" size="16" style="width:200px" /></td>
          </tr>
         
          <tr> 
            <td height="30">Classroom:</td>
            <td style="text-align:left;"><input name="classroom" type="text" id="classroom" size="16" style="width:200px" /></td>
          </tr>
         
          <tr> 
            <td height="30">Student Amount Limit:</td>
            <td style="text-align:left;"><input name="student_amount_limit" type="text" id="student_amount_limit" size="16" style="width:200px" /></td>
          </tr>
         
<!--          <tr>
            <td height="30">New Class Flag:</td>
            <td style="text-align:left;">
            	<input type="radio" name="new_class_flag" value="1" checked />是
            	<input type="radio" name="new_class_flag" value="0" />否
            </td>
          </tr>-->
         
          <tr> 
            <td height="30">Seq:</td>
            <td style="text-align:left;"><input name="seq" type="text" id="seq" size="16" style="width:200px" /></td>
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