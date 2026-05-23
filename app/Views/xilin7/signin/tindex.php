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
  <div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark" style="margin-left: 6px;"></div>
</div>
  <script>

    function onSignIn(googleUser) {
        console.log(googleUser);
      var id_token = googleUser.getAuthResponse().id_token;
      console.log(id_token);
      window.location.replace('/tlogin/g_login?token='+id_token);
    };
  </script>
</div>

    </div>
    </div>
    </section>
<?php echo view($_SESSION['tm'].'uc/footer.php')?>