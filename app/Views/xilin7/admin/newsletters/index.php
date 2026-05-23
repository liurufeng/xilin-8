<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>newsletters</title>
<link href="<?php echo base_url();?>/admin/css/base.css" rel="stylesheet" type="text/css">
</head>
<body background='<?php echo base_url();?>/admin/images/allbg.gif' leftmargin='8' topmargin='8'>
<table width="98%" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#D6D6D6">
  <tr>
    <td height="27" colspan="10" background="<?php echo base_url();?>/admin/images/tbg.gif" bgcolor="#E7E7E7">
    	<table width="96%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td width="24%" style="padding-left:10px;"><b>newsletters</b> </td>
          <td width="76%" align="right"><b>
          	<a href="<?=$_SESSION['admin_path']?>newsletters/add"><u>add newsletters</u></a>
          	</b>
          </td>
        </tr>
      </table>
     </td>
  </tr>
  <tr bgcolor="#FBFCE2">
    <td width="5%" height="24" align="center">id</td>
    <td width="20%" align="center">name</td>
    <td width="20%" align="center">url</td>
    <td width="10%" align="center">isshow</td>
    <td width="20%" align="center">desc</td>
    <td width="5%" align="center">seq</td>
    <td width="20%" align="center">operation</td>
  </tr>
  <?php foreach($list as $k=>$v){ ?>
  <tr align="center" bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';">
    <td><?php echo $v['id']?></td>
    <td><?php echo $v['name']?></td>
    <td><a href="<?php echo $v['url']?>">click</a></td>
    <td><?php if($v['isshow']){ echo 'yes';}else{ echo 'no';}?></td>
    <td><?php echo $v['desc']?></td>
    <td><?php echo $v['seq']?></td>
    <td>
		<a href='<?=$_SESSION['admin_path']?>newsletters/edit?id=<?php echo $v['id']?>'><u>edit</u></a> |
		<a href='<?=$_SESSION['admin_path']?>newsletters/del?id=<?php echo $v['id']?>' onclick="return confirm('Are you sure to delete this newsletter?')"><u>delete</u></a>
    </td>
  </tr>
  <?php } ?>
</table>
</body>
</html>