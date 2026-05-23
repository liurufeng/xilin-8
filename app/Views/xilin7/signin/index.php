<?php echo view($_SESSION['tm'].'uc/header.php')?>
    <div class="col-md-9 main-content">
<div class="container-wrapper">
          <table width="600" cellspacing="0" cellpadding="0" border="0">
            <tbody><tr valign="top">
              <!-- show the member menu -->
              <td height="1" width="600" valign="middle" align="right">
              </td>
            </tr>
            </tbody></table>

          <br><br>
          <!-- show login form or other contents -->
          <script language="javascript">
            function validateForm() {
              //To validate:
              //	email
              var msg = "";
              if (document.getElementById("email_login").value=="") {
                msg += "- Please provide your email address.\n";
              }
              if(msg!="") {
                alert("Error: \n" + msg);
                return false;
              }
            }
          </script>


          <form method="POST" id="loginForm" name="loginForm" action="/login/check_login">
            <table cellspacing="0" cellpadding="0" border="0">
              <tbody>
              <?php if(session()->get('current_tab')!='teacher'){?>
              <tr>
                <td colspan="2">
                  <p><span style="font-size:15.0pt;font-family:Arial">Please read: <a style="font-size:15.0pt;font-family:Arial" href="/uploadfiles/web_documents/XilinNS_POD_rules.pdf" target="_blank"> Duties and Policy </a></span></p>
                  <br><br>
                </td>
              </tr>

              <tr>
                <td colspan="2">
                  <span style="font-size:24px;">New Parents? </span><a href="/register"><font class="Red">New Parent or Grown-up Register</font></a>
                  <br><br>
                </td>
              </tr>
              <?php }?>
              <tr>
                <td width="80" valign="top" align="left">
                  <b>&nbsp;Email:</b>
                </td>
                <td valign="top" align="left">
                  <input id="email_login" name="email" size="50" maxlength="50" value="" type="email">
                </td>
              </tr>
              <tr height="3"><td colspan="2"></td></tr>
              <tr>
                <td width="80" valign="top" align="left">
                  <b>&nbsp;Password</b>
                </td>
                <td valign="middle" align="left">
                  <input id="password" name="password" size="50" type="password">
                </td>
              </tr>
              <tr height="3"><td colspan="2"></td></tr>
              <tr valign="middle" align="center">
                <td colspan="2" width="100%" valign="middle" style="padding-right: 100px">
                  <br>
                  <input name="submit" value="Login" type="submit">
                  <br>
                  <div style="margin-top:20px;">Forgot password? <button id="findpass1" class="btn btn-xs btn-info">Find password</button>
                  </div>
                </td>
              </tr>
              <tr height="3">
                <td colspan="2" align="left" width="100%" >
                  <br><br>
                  <p><span style="font-size:14.0pt;font-family:Arial">
                    For registration questions, please refer to our <a style="font-size:14.0pt;font-family:Arial" href="https://docs.google.com/document/d/1Wsn9GAg9L3ta-0jEma8oT1oZBd6jZ31huuclsI3cPI0" target="_blank">Registration Guide</a>
                    <br><br>
                    Click <a style="font-size:14.0pt;font-family:Arial" href="/school/scholarship"> here </a>
                             for Financial Aid/Scholarship Information.
                  <br>
                  </span></p>
                </td>
              </tr>
              </tbody></table>
          </form>
</div>

    </div>


    </div>
    </div>
    </section>

<?php echo view($_SESSION['tm'].'uc/footer.php')?>