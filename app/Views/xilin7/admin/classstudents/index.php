<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>classes</title>
<link href="<?php echo base_url();?>/admin/css/base.css" rel="stylesheet" type="text/css">
</head>
<body background='<?php echo base_url();?>/admin/images/allbg.gif' leftmargin='8' topmargin='8'>
<table width="98%" border="0" align="center" cellpadding="2" cellspacing="1">
  <tr>
    <td height="27" colspan="11" background="<?php echo base_url();?>/admin/images/tbg.gif">
    	<table width="96%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td width="100%" style="padding-left:5px;">
            <form action="<?=$_SESSION['admin_path']?>classstudents/index">
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
        <tr>
          <td height="2" colspan="11"><br>
          </td>
        </tr>
      </table>
     </td>
  </tr>

  <?php
  $all_emails = '';
  foreach($list as $k=>$v){ ?>
    <tr bgcolor="#E4F0F9" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#E4F0F9';">
      <td colspan="11">
        Class:
        <?php echo $v['class_code']?>,
        <?php echo $v['subject']?>,
        <?php echo $v['class_name']?>,
        <?php echo $v['teacher']?>,
        <?php echo $v['teacher_email']?>,
        <?php echo $v['notes']?>,
        <?php /*echo $v['syl_link']*/?>,
        <?php echo $v['meeting_time']?>,
        <?php echo $v['classroom']?>,
        <?=$v['tuition']?> + <?=$v['book_fee']?> + <?=$v['material_fee']?>,
        <?=$v['student_amount_limit']?></td>
    </tr>
    <?php
    $db = db_connect();
    $sql = "select p.*, s.en_name sename, s.gender, s.birthday from studentclasses sc
    join students s on s.student_id = sc.student_id
    join parents p on p.parent_id = s.parent_id
    where sc.deleted = 0 and sc.class_id = ".$v['class_id'];
    $students = $db->query($sql)->getResultArray();
    $num = 1;
    $all_emails = $emails = '';
    foreach($students as $s) {
      $emails .= $s['email'] . ',';
      $alt_email = trim(strval($s['alter_contact_email']));
      $emails .= empty($alt_email) ? '': $alt_email . ',';

      if(strpos($all_emails, $s['email']) === false) $all_emails.=$s['email'].',';
      if(isset($alt_email)
        && !empty($alt_email)
        && strpos($all_emails, $alt_email) === false
        ) $all_emails.=$alt_email.',';
      ?>

      <tr>
        <td colspan="11">
          <?=$num++?>.
          <?php echo $s['sename']?>,
          <?php echo $s['birthday']?>,
          <?php echo $s['gender']?>,
          <?php echo $s['email']?>,
          <?= $s['primary_relationship']?>: <?= $s['primary_en_name']?>,
          <?php echo $s['primary_phone']?>,
          <?= $s['alter_relationship']?>: <?= $s['alter_en_name']?>,
          <?php echo $s['alter_phone']?>,
          <?php echo $s['alter_contact_email']?>,
          <?=$s['address']?> <?=$s['city']?> <?=$s['state']?> <?=$s['zip']?></td>
      </tr>
  <?php }
    if($num > 1) {?>
      <tr bgcolor="#FFFFFF" >
        <td height="27" align="left" colspan="11">Email list of the class: <br>
        <textarea style="width: 99%;"><?=rtrim($emails,',')?></textarea>
        </td>
      </tr>
      <?php }?>
  <tr bgcolor="#FFFFFF" >
    <td height="5" colspan="11"><br>
    </td>
  </tr>
<?php } ?>
</table>
<p>Parent email addresses of all classes in this semester</p>
<textarea style="width: 98%;" rows="10"><?=rtrim($all_emails,',')?>
</textarea>
</body>
</html>