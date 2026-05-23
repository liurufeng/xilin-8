<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>classes</title>
<link href="<?php echo base_url();?>/admin/css/base.css" rel="stylesheet" type="text/css">
</head>
<body background='<?php echo base_url();?>/admin/images/allbg.gif' leftmargin='8' topmargin='8'>
<table width="98%" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#D6D6D6">
  <tr>
    <td height="27" colspan="10" background="<?php echo base_url();?>/admin/images/tbg.gif" bgcolor="#E7E7E7">
    	<table width="96%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td width="30%" style="padding-left:5px;">
            Available POD Dates
          </td>

          <td width="15%" align="right"><b>
          	<a href="<?=$_SESSION['admin_path']?>event_dates/addEventDate"><u>add pod date</u></a>
          	</b>
          </td>
        </tr>
      </table>
     </td>
  </tr>
  <tr bgcolor="#FBFCE2">
    <td width="5%" height="24" align="center">date id</td>
    <td width="10%" align="center">year</td>
    <td width="10%" align="center">month</td>
    <td width="10%" align="center">date</td>
    <td width="10%" align="center">operation</td>
  </tr>
  <?php foreach($event_dates as $k=>$v){ ?>
  <tr align="center" bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';">
    <td><?php echo $v['event_date_id']?></td>
    <td><?php echo $v['year']?></td>
    <td><?php echo $v['month']+1?></td>
    <td><?php echo $v['date']?></td>
    <td>
		<a href='<?=$_SESSION['admin_path']?>event_dates/editEventDate?eid=<?php echo $v['event_date_id']?>'><u>edit</u></a> |
		<a href='<?=$_SESSION['admin_path']?>event_dates/deleteEventDate?eid=<?php echo $v['event_date_id']?>' onclick="return confirm('Are you sure to delete this event?')"><u>delete</u></a>
    </td>
  </tr>
  <?php } ?>
</table>
</body>
</html>