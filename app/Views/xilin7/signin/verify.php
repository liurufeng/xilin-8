<?php echo view($_SESSION['tm'].'uc/header.php')?>
    <div class="col-md-9 main-content">
<div class="container-wrapper">
          <br><br>
          <h3>Enter your sign-in code</h3>
          <p><span style="font-size:12.0pt;font-family:Arial">
            We sent a 6-digit code to <b><?= esc($masked_email ?? '') ?></b>. It works for 10 minutes.
            If it isn't in your inbox, please check the spam folder.
          </span></p>
          <?php if (!empty($error_msg)) { ?>
            <div class="alert alert-danger"><?= esc($error_msg) ?></div>
          <?php } ?>
          <?php if (!empty($success_msg)) { ?>
            <div class="alert alert-success"><?= esc($success_msg) ?></div>
          <?php } ?>
          <form method="POST" action="/login/verify" autocomplete="off">
            <table cellspacing="0" cellpadding="4" border="0">
              <tbody>
              <tr>
                <td><span style="font-size:12.0pt;font-family:Arial">Code:</span></td>
                <td><input name="code" type="text" size="10" maxlength="6" inputmode="numeric" pattern="[0-9]{6}" autocomplete="one-time-code" autofocus required></td>
              </tr>
              <tr>
                <td></td>
                <td><button type="submit" class="btn btn-md btn-info">Sign in</button></td>
              </tr>
              </tbody>
            </table>
          </form>
          <form method="POST" action="/login/resend" style="margin-top:20px;">
            Didn't get the code? <button type="submit" class="btn btn-xs btn-default">Send a new code</button>
            or <a href="/signin">start over</a>.
          </form>
</div>

    </div>


    </div>
    </div>
    </section>

<?php echo view($_SESSION['tm'].'uc/footer.php')?>
