<?php echo view($_SESSION['tm'].'uc/header.php')?>
<link rel="stylesheet" href="/common/css/signin-card.css?v=1">
    <div class="col-md-9 main-content">
<div class="container-wrapper">
          <div class="signin-card">
            <h3>Forgot your password?</h3>
            <div class="card-help">
              Enter the email address you registered with and we'll email you a 6-digit code to choose a new password.
            </div>
            <?php if (!empty($error_msg)) { ?>
              <div class="alert alert-danger"><?= esc($error_msg) ?></div>
            <?php } ?>
            <form method="POST" action="/password/forgot">
              <div class="card-field">
                <label for="forgot_email">Email</label>
                <input class="card-input" id="forgot_email" name="email" type="email" value="<?= esc($email ?? '', 'attr') ?>"
                  autocomplete="email" autofocus required>
              </div>
              <button type="submit" class="btn btn-info card-submit">Email me a code</button>
            </form>
            <div class="card-more">
              Remembered it? <a href="/signin">Back to sign in</a>
            </div>
          </div>
</div>

    </div>


    </div>
    </div>
    </section>

<?php echo view($_SESSION['tm'].'uc/footer.php')?>
