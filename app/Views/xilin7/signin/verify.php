<?php echo view($_SESSION['tm'].'uc/header.php')?>
<?php
// the sign-in code (Login::verify) unless the controller sets these up for another code (Password::code)
$title = $title ?? 'Enter your sign-in code';
$action = $action ?? '/login/verify';
$resend_action = $resend_action ?? '/login/resend';
$start_over = $start_over ?? '/signin';
$submit_label = $submit_label ?? 'Sign in';
?>
<link rel="stylesheet" href="/common/css/signin-card.css?v=1">
    <div class="col-md-9 main-content">
<div class="container-wrapper">
          <div class="signin-card">
            <h3><?= esc($title) ?></h3>
            <div class="card-help">
              We sent a 6-digit code to <b><?= esc($masked_email ?? '') ?></b>.<br>
              It works for 10 minutes. If it isn't in your inbox, please check the spam folder.
            </div>
            <?php if (!empty($error_msg)) { ?>
              <div class="alert alert-danger"><?= esc($error_msg) ?></div>
            <?php } ?>
            <?php if (!empty($success_msg)) { ?>
              <div class="alert alert-success"><?= esc($success_msg) ?></div>
            <?php } ?>
            <form method="POST" action="<?= esc($action, 'attr') ?>" autocomplete="off">
              <input class="code-input" name="code" type="text" maxlength="6" inputmode="numeric" pattern="[0-9]{6}"
                placeholder="000000" aria-label="6-digit code" autocomplete="one-time-code" autofocus required>
              <button type="submit" class="btn btn-info card-submit"><?= esc($submit_label) ?></button>
            </form>
            <form method="POST" action="<?= esc($resend_action, 'attr') ?>" class="card-more">
              Didn't get the code? <button type="submit" class="btn btn-link">Send a new code</button>
              &middot; <a href="<?= esc($start_over, 'attr') ?>">Start over</a>
            </form>
          </div>
</div>

    </div>


    </div>
    </div>
    </section>

<?php echo view($_SESSION['tm'].'uc/footer.php')?>
