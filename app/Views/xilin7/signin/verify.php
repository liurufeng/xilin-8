<?php echo view($_SESSION['tm'].'uc/header.php')?>
<style>
  .code-card { max-width: 440px; margin: 40px auto; padding: 30px 32px 24px; background: #fff; border: 1px solid #e3e3e3;
    border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,.06); text-align: center; font-family: Arial, sans-serif; }
  .code-card h3 { margin: 0 0 10px; font-size: 22px; font-weight: bold; color: #333; }
  .code-card .code-help { color: #666; font-size: 14px; line-height: 1.5; margin-bottom: 22px; }
  .code-card .code-help b { color: #333; }
  .code-card .code-input { display: block; width: 100%; max-width: 260px; margin: 0 auto 18px; padding: 10px 0 10px 0.5em;
    font: bold 30px/1.2 "Courier New", Courier, monospace; letter-spacing: 0.5em; text-align: center; color: #333;
    border: 2px solid #ccc; border-radius: 6px; outline: none; transition: border-color .15s, box-shadow .15s; }
  .code-card .code-input:focus { border-color: #5bc0de; box-shadow: 0 0 0 3px rgba(91,192,222,.25); }
  .code-card .code-input::placeholder { color: #ddd; }
  .code-card .code-submit { width: 100%; max-width: 260px; padding: 10px; font-size: 16px; }
  .code-card .alert { text-align: left; font-size: 14px; }
  .code-card .code-more { margin-top: 22px; padding-top: 16px; border-top: 1px solid #eee; color: #777; font-size: 13px; }
  .code-card .code-more .btn-link { padding: 0; font-size: 13px; vertical-align: baseline; }
</style>
    <div class="col-md-9 main-content">
<div class="container-wrapper">
          <div class="code-card">
            <h3>Enter your sign-in code</h3>
            <div class="code-help">
              We sent a 6-digit code to <b><?= esc($masked_email ?? '') ?></b>.<br>
              It works for 10 minutes. If it isn't in your inbox, please check the spam folder.
            </div>
            <?php if (!empty($error_msg)) { ?>
              <div class="alert alert-danger"><?= esc($error_msg) ?></div>
            <?php } ?>
            <?php if (!empty($success_msg)) { ?>
              <div class="alert alert-success"><?= esc($success_msg) ?></div>
            <?php } ?>
            <form method="POST" action="/login/verify" autocomplete="off">
              <input class="code-input" name="code" type="text" maxlength="6" inputmode="numeric" pattern="[0-9]{6}"
                placeholder="000000" aria-label="6-digit code" autocomplete="one-time-code" autofocus required>
              <button type="submit" class="btn btn-info code-submit">Sign in</button>
            </form>
            <form method="POST" action="/login/resend" class="code-more">
              Didn't get the code? <button type="submit" class="btn btn-link">Send a new code</button>
              &middot; <a href="/signin">Start over</a>
            </form>
          </div>
</div>

    </div>


    </div>
    </div>
    </section>

<?php echo view($_SESSION['tm'].'uc/footer.php')?>
