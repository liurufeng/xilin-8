<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>manual_pod_record</title>
<link href="<?php echo base_url();?>/admin/css/base.css" rel="stylesheet" type="text/css">
</head>
<body background='<?php echo base_url();?>/admin/images/allbg.gif' leftmargin='8' topmargin='8'>
<table width="98%" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#D6D6D6">
  <tr>
    <td height="27" colspan="10" background="<?php echo base_url();?>/admin/images/tbg.gif" bgcolor="#E7E7E7">
    	<table width="96%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td width="30%" style="padding-left:5px;">
            <form action="<?=$_SESSION['admin_path']?>podreports/index">
              School Year <select id="school_year_id" name="school_year_id" onchange="submit();">
              <?php foreach($school_year as $sy) {?>
              <option value="<?=$sy['id']?>" <?php if($sy['id'] == session()->get('school_year_id')) echo 'selected'; ?> >
                <?=$sy['name']?>
              </option>
              <?php } ?>
            </select>
            </form>
          </td>
          <td width="55%" align="right" style="white-space: nowrap">

          </td>

        </tr>
      </table>
     </td>
  </tr>
  <tr bgcolor="#FBFCE2">
    <td width="8%" height="24" align="center">parent id</td>
    <td width="15%" align="center">name</td>
    <td width="25%" align="center">email</td>
    <td width="10%" align="center">phone</td>
    <td width="8%" align="center">need</td>
    <td width="8%" align="center">done</td>
    <td width="8%" align="center">manual</td>
    <td width="8%" align="center">miss</td>
    <td width="8%" align="center">registered</td>
  </tr>
  <?php foreach($parents as $p){
    $missed = $pod[$p['parent_id']]['missed'];
    $todo = $pod[$p['parent_id']]['todo'];
    $status = $missed - $todo;

    ?>
  <tr align="center" bgcolor="<?= $missed > 0 ? '#FFCC33' : '#FFFFFF' ?>" onMouseMove="javascript:this.bgColor='<?= $missed > 0 ? '#FFF581' : '#FCFDEE' ?>';" onMouseOut="javascript:this.bgColor='<?= $missed > 0 ? '#FFCC33' : '#FFFFFF' ?>'">
    <td><a onclick="window.open('/administrator.php/podreports/detail?parent_id=<?=$p['parent_id']?>&sy_id=<?=session()->get('school_year_id')?>', 'null', 'location=no,toolbar=no,menubar=no,height=450,width=730,scrollbars=yes');" style="cursor: pointer; text-decoration: underline">
         <?php echo $p['parent_id']?></a></td>
    <td><?php echo $p['primary_en_name']?></td>
    <td><?php echo $p['email']?></td>
    <td><?php echo $p['primary_phone']?></td>
    <td><?php echo $pod[$p['parent_id']]['need']?></td>
    <td><?php echo $pod[$p['parent_id']]['done']?></td>
    <td><?php echo $pod[$p['parent_id']]['manually']?></td>
    <td><?php echo $missed?></td>
    <td><?php echo $todo?></td>
  </tr>
  <?php } ?>
</table>
</body>
</html>