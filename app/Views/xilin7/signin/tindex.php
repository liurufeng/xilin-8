<?php echo view($_SESSION['tm'].'uc/header.php')?>
  <!--<iframe id="logoutframe" src="https://accounts.google.com/logout" style="display: none"></iframe>-->
<div class="col-md-9 container-wrapper">

          <!-- show login form or other contents -->
          <!--<script language="javascript">
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
          </script>-->
<div class="row">
  <h4>&nbsp;Teachers - please login with your xilinnschinese.org Google account</h4>
    <br>
  <!-- Google Identity Services: the button returns an ID token, which Tlogin::g_login verifies -->
  <div id="g_id_onload"
       data-client_id="<?=esc(xilin_secret('google_client_id', '787488937189-p0prt8vpmf7vrg3c51681u1d7q6kchv2.apps.googleusercontent.com'), 'attr')?>"
       data-callback="onSignIn"
       data-ux_mode="popup"
       data-auto_prompt="false">
  </div>
  <div class="g_id_signin" data-type="standard" data-theme="filled_blue" data-size="large"
       data-text="signin_with" data-shape="rectangular" data-logo_alignment="left" style="margin-left: 6px;"></div>
</div>
  <script>
    function onSignIn(response) {
      window.location.replace('/tlogin/g_login?token=' + encodeURIComponent(response.credential));
    }
  </script>
  <script src="https://accounts.google.com/gsi/client" async></script>
</div>

    </div>
    </div>
    </section>
<?php echo view($_SESSION['tm'].'uc/footer.php')?>