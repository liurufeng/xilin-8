<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Classes--add</title>
<link href='<?php echo base_url();?>/admin/css/base.css' rel='stylesheet' type='text/css'>

</head>
<body background='<?php echo base_url();?>/admin/images/allbg.gif' leftmargin='8' topmargin='8'>
<table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#D6D6D6">
  <tr>
    <td height="19" background="<?php echo base_url();?>/admin/images/tbg.gif" bgcolor="#E7E7E7"> 
      <table width="96%" border="0" cellspacing="1" cellpadding="1">
        <tr> 
          <td width="24%" style="padding-left:10px;"><b><strong>pod date edit</strong></b> </td>
          <td width="76%" align="right"><strong><a href="<?=$_SESSION['admin_path']?>event_dates/index"><u>pod date list</u></a></strong></td>
        </tr>
      </table></td>
</tr>
<tr>
    <td height="215" align="center" valign="top" bgcolor="#FFFFFF">
	<form name="form1" action="<?=$_SESSION['admin_path']?>event_dates/editEventDate" method="post">
	<input type="hidden" name="dopost" value="save" />
	<input type="hidden" name="eid" value="<?php echo $event_date['event_date_id']?>" />
    <table width="98%" border="0" cellspacing="1" cellpadding="1">
      <tr>
        <td height="30"><span style="color:red;">*</span>Year</td>
        <td style="text-align:left;">
          <select name='year' style='width:200px'>
            <?php $year = (int) date('Y');
            for($y = $year; $y < $year+3; $y++) {
              ?>
              <option value="<?=$y?>" <?= $event_date['year'] ==  $y ? 'selected':''?> ><?=$y?></option>
            <?php } ?>
          </select>
        </td>
      </tr>

      <tr>
        <td height="30"><span style="color:red;">*</span>Month</td>
        <td style="text-align:left;">
          <select name='month' style='width:200px'>
            <?php for($m = 1; $m < 13; $m++) { ?>
              <option value="<?=$m - 1?>" <?= $event_date['month'] ==  $m ? 'selected':''?> ><?=$m?></option>
            <?php }?>
          </select>
        </td>
      </tr>

      <tr>
        <td height="30"><span style="color:red;">*</span>Date:</td>
        <td style="text-align:left;">
          <select name='date' style='width:200px'>
            <?php for($d = 1; $d < 32; $d++) { ?>
              <option value="<?=$d?>" <?= $event_date['date'] ==  $d ? 'selected':''?> ><?=$d?></option>
            <?php }?>
          </select>
        </td>
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