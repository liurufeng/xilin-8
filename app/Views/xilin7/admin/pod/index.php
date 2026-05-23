<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>POD Management</title>
<link href="<?php echo base_url();?>/admin/css/base.css" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url();?>/admin/css/pod.css" rel="stylesheet" type="text/css">
  <script src="<?php echo base_url();?>/admin/js/pod.js"></script>
  <script src="<?php echo base_url();?>/admin/js/jquery.js"></script>
</head>
<body background='<?php echo base_url();?>/admin/images/allbg.gif' leftmargin='8' topmargin='8'>
<script language="javascript">
  var event_dates = <?=json_encode($event_dates)?>;
</script>
<p><div style="font-family:verdana;font-size:25pt;font-weight:bold;text-align:center;">
  Xilin NS Chinese School Parent-On-Duty Sign Up Sheet</div>
</p>
<div id="dialogPanelBg"></div>
<div id="dialogPanel" ></div>
<table border="0" cellpadding="0" cellspacing="1" class="Calendar">
  <tr>
    <td align="center" class="DefaultValues">
      默认值：开始时间 <input type="text" name="dst" id="dst" size="6" value="14:00"> &nbsp; &nbsp; &nbsp; 结束时间 <input type="text" name="det" id="det" size="6" value="16:00"> &nbsp; &nbsp; &nbsp; 所需人数  <input type="text" name="dhn" id="dhn" size="2" value="3"> &nbsp; &nbsp; &nbsp; 最晚取消允许天数 <input type="text" name="dcd" id="dcd" size="2" value="7">
      &nbsp; &nbsp; &nbsp; <span style="background-color: #ffff00;"><b>学期</b> <select id="semester_id" name="semester_id" style="background-color: #ffff00;">
        <?php foreach($semesters as $semester) {?>
          <option value="<?=$semester['semester_id']?>" <?php if($semester['semester_id'] == session()->get('semester_id')) echo 'selected'; ?> >
            <?=$semester['semester_year']?> <?=$semester['semester_name']?>
          </option>
        <?php } ?>
      </select></span>
      <br/><input type="button" name="dl" id="dl" value="Download helper details for future events" onclick="downloadHelperInfo();" />
    </td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="1" class="Calendar" id="caltable">
  <thead>
  <tr align="center" valign="middle">
    <td colspan="7" class="Title">
      <table border=0 cellpadding=0 cellspacing=0 width=100%>
        <tr>
          <td colspan="2" class="Title" align="left">
            <a href="javascript:subMonth();" title="上一月" class="DayButton">&lt;&lt; 上一月</a>
          </td>
          <td colspan="3" class="Title">
            <input name="year" type="text" size="4" maxlength="4" onkeydown="if (event.keyCode==13){setDate()}" onkeyup="this.value.replace(/[^0-9]/g,'')" onpaste="this.value.replace(/[^0-9]/g,'')"> 年 <input name="month" type="text" size="1" maxlength="2" onkeydown="if(event.keycode==13){setDate()}" onkeyup="this.value.replace(/[^0-9]/g,'')" onpaste="this.value.replace(/[^0-9]/g,'')"> 月
          </td>
          <td colspan="2" class="Title" align="right">
            <a href="javascript:addMonth();" title="下一月" class="DayButton">下一月 &gt;&gt;</a>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr align="center" valign="middle">
    <script language="javascript">
      document.write("<TD class=DaySunTitle id=diary>"+days[0]+"</TD>");
      for(var intLoop=1;intLoop < days.length-1;intLoop++)
        document.write("<TD class=DayTitle id=diary>"+days[intLoop]+"</TD>");
      document.write("<TD class=DaySatTitle id=diary>"+days[intLoop]+"</TD>");
    </script>
  </tr>
  </thead>
  <TBODY border=1 cellspacing="0" cellpadding="0" id="calendar" align="center">
  <script language="javascript">
    for(var intWeeks=0;intWeeks < 6;intWeeks++) {
      document.write("<TR>");
      for(var intDays=0;intDays<days.length;intDays++) document.write("<TD class=CalendarTD></TD>");
      document.write("</TR>");
    }
  </script>
  </TBODY>
</table>
<script language="javascript">
  Calendar();
</script>



</body>
</html>