<?php echo view($_SESSION['tm'].'uc/header.php') ?>
    <link href="<?php echo base_url();?>/admin/css/pod.css" rel="stylesheet" type="text/css">
    <script src="<?php echo base_url();?>/admin/js/pod.js"></script>
    <div class="col-md-9 container-wrapper">

    <div class="row">
    <?php echo view($_SESSION['tm'].'uc/account_header.php') ?>
    <div style="clear:both;"></div>

<?php if (isset($userinfodata) && !empty($userinfodata)) { ?>
          <div class="account-top">
              <h4 class="section-title text-center">Xilin NS Chinese School Parent-On-Duty Sign Up Sheet</h4>
          </div>

        <div id="dialogPanelBg"></div>
        <div id="dialogPanel" onmousedown="dragStart(event,'dialogPanel');"></div>

          <table border="0" cellpadding="0" cellspacing="1" class="Calendar" id="caltable">
            <thead>
            <tr align="center" valign="middle">
              <td colspan="7" class="Title" >
                <table border=0 cellpadding=0 cellspacing=0 width=100% align="center">
                  <tr>
                    <td colspan="2" class="Title" align="left">
                      <a href="javascript:subMonth();" title="上一月" class="DayButton">&lt;&lt; 上一月(Previous Month)</a>
                    </td>
                    <td colspan="3" class="Title">
                      <input name="year" type="text" size="4" maxlength="4"
                             onkeyup="this.value.replace(/[^0-9]/g,'');if (Event.keyCode==13){setDate()}"
                             onpaste="this.value.replace(/[^0-9]/g,'')"> 年 <input name="month" type="text" size="1"
                          maxlength="2"
                          onkeyup="this.value.replace(/[^0-9]/g,'');if (Event.keyCode==13){setDate()}"
                          onpaste="this.value.replace(/[^0-9]/g,'')">
                      月
                    </td>
                    <td colspan="2" class="Title" align="right">
                      <a href="javascript:addMonth();" title="下一月" class="DayButton">下一月(Next Month) &gt;&gt;</a>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr align="center" valign="middle">
              <script language="javascript">
                qs = new Querystring();
                userLevel = 0
                thisParentId = <?=$userinfodata[0]['parent_id']?>;
                document.write("<TD class=DaySunTitle id=diary>" + days[0] + "</TD>");
                for (var intLoop = 1; intLoop < days.length - 1; intLoop++)
                  document.write("<TD class=DayTitle id=diary>" + days[intLoop] + "</TD>");
                document.write("<TD class=DaySatTitle id=diary>" + days[intLoop] + "</TD>");
              </script>
            </tr>
            </thead>
            <TBODY border=1 cellspacing="0" cellpadding="0" id="calendar" align="center">
            <script language="javascript">
              for (var intWeeks = 0; intWeeks < 6; intWeeks++) {
                document.write("<TR>");
                for (var intDays = 0; intDays < days.length; intDays++) document.write("<TD class=CalendarTD></TD>");
                document.write("</TR>");
              }
            </script>
            </TBODY>
          </table>
          <script language="javascript">
            qs = new Querystring();
            thism = qs.get("m");
            if (thism != null) {
              month = eval(thism) - 1;
            }
            Calendar();
          </script>

        </div>
<?php } else { ?>
        <div class="row">
          <div class="col-sm-4 col-sm-offset-8">
            <div class="contact-form">
              <h3><a href="/register" style="float:right;">REGISTER</a>LOGIN</h3>

              <form name="contact-form" method="POST" action="/login/check_login">
                <div class="form-group">
                  <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="form-group">
                  <input type="password" name="password" class="form-control" placeholder="Password"
                         required>
                </div>
                <button type="submit" class="btn btn-primary">LOGIN</button>
              </form>
              <div style="margin-top:20px;">Click <a href=''>here</a> for Financial Aid Information and
                Discount Policy.
              </div>
            </div>
          </div>
        </div>
<?php } ?>

    </div>

    </div>
    </div>
    </section>

<?php echo view($_SESSION['tm'].'uc/footer.php') ?>