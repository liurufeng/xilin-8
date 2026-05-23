<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>teachers</title>
<link href="<?php echo base_url();?>/admin/css/base.css" rel="stylesheet" type="text/css">
</head>
<body background='<?php echo base_url();?>/admin/images/allbg.gif' leftmargin='8' topmargin='8'>
<table width="98%" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#D6D6D6">
  <tr>
    <td height="27" colspan="7" background="<?php echo base_url();?>/admin/images/tbg.gif" bgcolor="#E7E7E7">
    	<table width="96%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td width="24%" style="padding-left:10px;"><b>teacher</b> </td>
          <td width="76%" align="right"><b>
          	<a href="<?=$_SESSION['admin_path']?>teacher/add"><u>add teacher</u></a>
          	</b>
          </td>
        </tr>
      </table>
     </td>
  </tr>
  <tr bgcolor="#FBFCE2">
    <td width="20%" height="24" align="center">Email</td>
    <td width="20%" align="center">English Name</td>
    <td width="20%" align="center">teacher type</td>
    <td width="10%" align="center">Chinese Name</td>
    <td width="10%" align="center">Home Phone</td>
    <td width="10%" align="center">Cell Phone</td>
    <td width="10%" align="center">operation</td>
  </tr>
  <?php
  $emails = '';
  foreach($list as $k=>$v){
    $emails .= $v['email'] .',';
    ?>
  <tr align="center" bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';">
    <td><?php echo $v['email']?></td>
    <td><?php echo $v['en_name']?></td>
    <td><?php echo $v['type']?></td>
    <td><?php echo $v['cn_name']?></td>
    <td><?php echo $v['phone1']?></td>
    <td><?php echo $v['phone2']?></td>
    <td>
		<a href='<?=$_SESSION['admin_path']?>teacher/edit?teacher_id=<?php echo $v['teacher_id']?>'><u>edit</u></a> |
		<a href='<?=$_SESSION['admin_path']?>teacher/del?teacher_id=<?php echo $v['teacher_id']?>' onclick="return confirm('Are you sure to delete this teacher?')"><u>delete</u></a>
    </td>
  </tr>
  <?php } ?>
</table>
<br>
<p>Email addresses of active teachers</p>
<textarea cols="80" rows="10"><?=$active_emails?>
</textarea>
<br>
<p>Email addresses of all teachers</p>
<textarea cols="80" rows="10"><?=rtrim($emails,',')?>
</textarea>
</body>
</html>