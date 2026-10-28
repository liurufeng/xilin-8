<?php echo view($_SESSION['tm'].'uc/header.php')?>
<link rel="stylesheet" href="/common/css/signin-card.css?v=1">
    <div class="col-md-9 main-content">
<div class="container-wrapper">
          <div class="signin-card">
            <h3>Choose a new password</h3>
            <?php if (!empty($error_msg)) { ?>
              <div class="alert alert-danger"><?= esc($error_msg) ?></div>
            <?php } ?>
            <div class="card-help"><?= esc(\App\Libraries\Passwords::RULES) ?></div>
            <form method="POST" action="/password/save" autocomplete="off">
              <div class="card-field">
                <label for="new_passwd">New password</label>
                <input class="card-input" id="new_passwd" name="passwd" type="password" autocomplete="new-password"
                  minlength="<?= \App\Libraries\Passwords::MIN_LENGTH ?>" autofocus required>
              </div>
              <div class="card-field">
                <label for="new_passwd2">Type it again</label>
                <input class="card-input" id="new_passwd2" name="passwd2" type="password" autocomplete="new-password" required>
              </div>
              <button type="submit" class="btn btn-info card-submit">Save password</button>
            </form>
            <div class="card-more">
              <a href="/signin">Back to sign in</a>
            </div>
          </div>
</div>

    </div>


    </div>
    </div>
    </section>

<?php echo view($_SESSION['tm'].'uc/footer.php')?>
