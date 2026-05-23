<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>classes</title>
<link href="<?= base_url();?>/admin/css/base.css" rel="stylesheet" type="text/css">
</head>
<body background='<?= base_url();?>/admin/images/allbg.gif' leftmargin='8' topmargin='8'>

<form action="<?= $_SESSION['admin_path'].'findpass/find'?>">
  <input type="text" name="search"/>
  <INPUT TYPE=BUTTON OnClick="submit();" VALUE="Search">
</form>
<br><br>
<?php if(isset($matches) && is_array($matches) && count($matches) > 0) {?>
<table width="99%" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#D6D6D6">
  <tr bgcolor="#FBFCE2">
    <th width="3%" align="center">id</th>
    <th width="10%" align="center">email</th>
    <th width="6%" align="center">relation</th>
    <th width="8%" align="center">e_name</th>
    <th width="8%" align="center">c_name</th>
    <th width="8%" align="center">phone</th>
    <th width="6%" align="center">alt relation</th>
    <th width="8%" align="center">alt_e_name</th>
    <th width="8%" align="center">alt_c_name</th>
    <th width="8%" align="center">alt_phone</th>
    <th width="8%" align="center">alt_email</th>
    <th align="center">pass</th>
  </tr>
  <?php

  foreach($matches as $k=>$v){ ?>
  <tr align="center" bgcolor="#FFEEEE" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFEEEE';">
    <td><?= $v['parent_id']?></td>
    <td><?= $v['email']?></td>
    <td><?= $v['primary_relationship']?></td>
    <td><?= $v['primary_en_name']?></td>
    <td><?= $v['primary_cn_name']?></td>
    <td><?= $v['primary_phone']?></td>
    <td><?= $v['alter_relationship']?></td>
    <td><?= $v['alter_en_name']?></td>
    <td><?= $v['alter_cn_name']?></td>
    <td><?= $v['alter_phone']?></td>
    <td><?= $v['alter_contact_email']?></td>
    <td><?= $v['passwd']?></td>
  </tr>
    <?php }?>
</table>
<?php } ?>
</body>
</html>