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
            <form action="<?=$_SESSION['admin_path']?>classes/index">
            <select id="semester_id" name="semester_id" onchange="submit();">
              <?php foreach($semesters as $semester) {?>
              <option value="<?=$semester['semester_id']?>" <?php if($semester['semester_id'] == session()->get('semester_id')) echo 'selected'; ?> >
                <?=$semester['semester_year']?> <?=$semester['semester_name']?>
              </option>
              <?php } ?>
            </select>
            </form>
          </td>
          <td width="55%" align="right" style="white-space: nowrap">
            <form action="<?=$_SESSION['admin_path']?>classes/copyall">
            copy all classes from
              <select id="from_semester_id" name="from_semester_id">
                <?php foreach($semesters as $semester) {?>
                  <option value="<?=$semester['semester_id']?>" <?php if($semester['semester_id'] == session()->get('semester_id')) echo 'selected'; ?> >
                    <?=$semester['semester_year']?> <?=$semester['semester_name']?>
                  </option>
                <?php } ?>
              </select>
              to
            <select id="semester_id" name="semester_id">
              <?php foreach($semesters as $semester) {
                if($semester['semester_status'] == 'Future') {?>
                <option value="<?=$semester['semester_id']?>" <?php if($semester['semester_id'] == session()->get('semester_id')) echo 'selected'; ?> >
                  <?=$semester['semester_year']?> <?=$semester['semester_name']?>
                </option>
              <?php } } ?>
            </select>
              <INPUT TYPE=BUTTON OnClick="submit();" VALUE="Copy">
            </form>
          </td>
          <td width="15%" align="right"><b>
          	<a href="<?=$_SESSION['admin_path']?>classes/add"><u>add classes</u></a>
          	</b>
          </td>
        </tr>
      </table>
     </td>
  </tr>
  <tr bgcolor="#FBFCE2">
    <td width="5%" height="24" align="center">Time</td>
    <td width="10%" align="center">Class</td>
      <td width="10%" align="center">Notes</td>
    <td width="10%" align="center">Teacher</td>
      <td width="10%" align="center">Tuition+Book+Matrl</td>
    <td width="10%" align="center">Late</td>
    <td width="10%" align="center">Class Room</td>
    <td width="10%" align="center">operation</td>
  </tr>
<?php foreach ($subjects as $subject) { ?>
    <tr><td colspan="9"><?= $subject['subject_name'] ?></td></tr>
  <?php foreach($list as $k=>$v){
    if ($v['subject_id'] == $subject['subject_id']) {?>
  <tr align="center" bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';">
    <td><?= $v['meeting_time'] ?></td>
    <td><?php echo $v['class_name']?></td>
    <td><?php echo $v['notes']?></td>
    <td><?php echo $v['teacher']?></td>
    <td>$<?php echo round($v['tuition'])?>+<?php echo round($v['book_fee'])?>+<?= round($v['material_fee']) ?></td>
    <td>$<?= round($v['late_tuition']) ?>+<?= round($v['late_book_fee']) ?>+<?= round($v['material_fee'])
      ?></td>
    <td><?= $v['classroom'] ?></td>
    <td>
		<a href='<?=$_SESSION['admin_path']?>classes/edit?class_id=<?php echo $v['class_id']?>'><u>edit</u></a> |
    <a href='<?=$_SESSION['admin_path']?>classes/copy?class_id=<?php echo $v['class_id']?>'><u>copy</u></a> |
		<a href='<?=$_SESSION['admin_path']?>classes/del?class_id=<?php echo $v['class_id']?>' onclick="return confirm('Are you sure to delete this class?')"><u>delete</u></a>
    </td>
  </tr>
  <?php } } ?>
<?php } ?>
</table>
</body>
</html>