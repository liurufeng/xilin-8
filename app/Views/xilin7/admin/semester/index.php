<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>semester</title>
<link href="<?php echo base_url();?>/admin/css/base.css" rel="stylesheet" type="text/css">
</head>
<body background='<?php echo base_url();?>/admin/images/allbg.gif' leftmargin='8' topmargin='8'>
<table width="98%" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#D6D6D6">
  <tr>
    <td height="27" colspan="11" background="<?php echo base_url();?>/admin/images/tbg.gif" bgcolor="#E7E7E7">
    	<table width="96%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td width="24%" style="padding-left:10px;"><b>semester</b> </td>
          <td width="76%" align="right"><b>
          	<a href="<?=$_SESSION['admin_path']?>semester/add"><u>add semester</u></a>
          	</b>
          </td>
        </tr>
      </table>
     </td>
  </tr>
  <tr bgcolor="#FBFCE2">
    <td width="2%" height="24" align="center">id</td>
    <td width="8%" height="24" align="center">semester year</td>
    <td width="10%" height="24" align="center">semester name</td>
    <td width="10%" height="24" align="center">semester status</td>
    <td width="10%" height="24" align="center">late reg date</td>
    <td width="10%" height="24" align="center">reg fee</td>
    <td width="10%" height="24" align="center">late reg fee</td>
    <td width="12%" align="center">parent discount base</td>
    <td width="12%" align="center">teacher discount base</td>
    <td width="5%" align="center">calendar</td>
    <td width="12%" align="center">operation</td>
  </tr>
  <?php foreach($list as $k=>$v){ ?>
  <tr align="center" bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';">
    <td><?php echo $v['semester_id']?></td>
    <td><?php echo $v['semester_year']?></td>
    <td><?php echo $v['semester_name']?></td>
    <td><?php echo $v['semester_status']?></td>
    <td><?php echo $v['late_registration']?></td>
    <td><?php echo $v['registration_fee']?></td>
    <td><?php echo $v['late_registration_fee']?></td>
    <td><?php echo $v['parent_discount_base']?></td>
    <td><?php echo $v['teacher_discount_base']?></td>
    <td><?= $v['show_calendar'] > 0 ? 'Show' : 'No'?></td>
    <td>
		<a href='<?=$_SESSION['admin_path']?>semester/edit?semester_id=<?php echo $v['semester_id']?>'><u>edit</u></a> |
		<a href='<?=$_SESSION['admin_path']?>semester/copy?semester_id=<?php echo $v['semester_id']?>'><u>copy</u></a> |
		<a href='<?=$_SESSION['admin_path']?>semester/del?semester_id=<?php echo $v['semester_id']?>' onclick="return confirm('Are you sure to delete this semester?')"><u>delete</u></a>
    </td>
  </tr>
  <?php } ?>
</table>
</body>
</html>