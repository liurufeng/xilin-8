<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Email list of current semester</title>
<link href="<?php echo base_url();?>/admin/css/base.css" rel="stylesheet" type="text/css">
</head>
<body background='<?php echo base_url();?>/admin/images/allbg.gif' leftmargin='8' topmargin='8'>
<table width="98%" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#D6D6D6">
  <tr>
    <td height="27" colspan="11" background="<?php echo base_url();?>/admin/images/tbg.gif" bgcolor="#E7E7E7">
    	<table width="96%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td width="100%" style="padding-left:5px;">
            <form action="<?=$_SESSION['admin_path']?>email_list/index">
            <select id="semester_id" name="semester_id" onchange="submit();">
              <?php foreach($semesters as $semester) {?>
              <option value="<?=$semester['semester_id']?>" <?php if($semester['semester_id'] == session()->get('semester_id')) echo 'selected'; ?> >
                <?=$semester['semester_year']?> <?=$semester['semester_name']?>
              </option>
              <?php } ?>
            </select>
            </form>
          </td>
        </tr>
      </table>
     </td>
  </tr>

  <?php
  $all_emails = '';
  if(count($plist) >= 1) {
    foreach($plist as $s) {
      if(strpos($all_emails, $s['email']) === false) $all_emails.=$s['email'].',';
      if($s['alter_contact_email'] && strpos($all_emails, $s['alter_contact_email']) === false && ! empty($s['alter_contact_email'])) $all_emails.=$s['alter_contact_email'].',';
      }
    ?>
      <tr bgcolor="#FFFFFF" >
        <td align="center"><b>Email addresses of parents:</b></td>
      </tr>
      <tr bgcolor="#FFFFFF" >
        <td colspan="60">
          <textarea style="width: 99%;" rows="38"><?=rtrim($all_emails,',') ?></textarea>
        </td>
      </tr>
      <?php }?>
  <tr bgcolor="#FFFFFF">
    <td align="center"><hr></td>
  </tr>
  <?php
  $all_emails = '';
  if(count($tlist) >= 1) {
    foreach($tlist as $s) {
      if(strpos($all_emails, $s['email']) === false) $all_emails.=$s['email'].',';
    }
    ?>
    <tr bgcolor="#FFFFFF" >
      <td align="center"><b>Email addresses of teachers:</b></td>
    </tr>
    <tr bgcolor="#FFFFFF" >
      <td colspan="60">
        <textarea style="width: 99%;" rows="6"><?=rtrim($all_emails,',') ?></textarea>
      </td>
    </tr>
  <?php } ?>
</table>

</body>
</html>