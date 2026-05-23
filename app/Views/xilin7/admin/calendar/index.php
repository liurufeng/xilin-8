<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>calendar</title>
<link href="<?php echo base_url();?>/admin/css/base.css" rel="stylesheet" type="text/css">
</head>
<body background='<?php echo base_url();?>/admin/images/allbg.gif' leftmargin='8' topmargin='8'>
<table width="98%" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#D6D6D6">
  <tr>
    <td height="27" colspan="9" background="<?php echo base_url();?>/admin/images/tbg.gif" bgcolor="#E7E7E7">
    	<table width="96%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td width="24%" style="padding-left:10px;"><b>calendar</b> </td>
          <td width="76%" align="right"><b>
          	<a href="<?=$_SESSION['admin_path']?>calendar/add"><u>add Calendar</u></a>
          	</b>
          </td>
        </tr>
      </table>
     </td>
  </tr>
  <tr bgcolor="#FBFCE2">
    <td width="3%" height="24" align="center">id</td>
    <td width="20%" align="center">semester</td>
    <td width="10%" align="center">header</td>
    <td width="10%" align="center">date</td>
    <td width="10%" align="center">session</td>
    <td width="10%" align="center">note</td>
    <td width="10%" align="center">show_flag</td>
    <td width="10%" align="center">show_order</td>
    <td width="17%" align="center">operation</td>
  </tr>
  <?php foreach($list as $k=>$v){ ?>
  <tr align="center" bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';">
    <td><?php echo $v['calendar_id']?></td>
    <td><?php echo $v['semester_name'] .' '.$v['semester_year'];?></td>
    <td><?php echo $v['header']?></td>
    <td><?php echo $v['date']?></td>
    <td><?php echo $v['session']?></td>
    <td><?php echo $v['note']?></td>
    <td><?php if($v['calendar_show_flag'] > 0 ) { echo "yes";} else { echo "no";}?></td>
    <td><?php echo $v['show_order']?></td>
    <td>
		<a href='<?=$_SESSION['admin_path']?>calendar/edit?calendar_id=<?php echo $v['calendar_id']?>'><u>edit</u></a> |
    <a href='<?=$_SESSION['admin_path']?>calendar/copy?calendar_id=<?php echo $v['calendar_id']?>'><u>copy</u></a> |
		<a href='<?=$_SESSION['admin_path']?>calendar/del?calendar_id=<?php echo $v['calendar_id']?>' onclick="return confirm('Are you sure to delete this entry?')"><u>delete</u></a>
    </td>
  </tr>
  <?php } ?>
</table>
</body>
</html>