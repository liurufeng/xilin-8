<?php echo view($_SESSION['tm'].'uc/header.php')?>
    <div class="col-md-9 main-content">
<div class="container-wrapper">
          <br><br>
          <h3>Choose a new password</h3>
          <?php if (!empty($error_msg)) { ?>
            <div class="alert alert-danger"><?= esc($error_msg) ?></div>
          <?php } ?>
          <?php if ($token !== '') { ?>
          <form method="POST" action="/password/save" autocomplete="off">
            <input type="hidden" name="token" value="<?= esc($token, 'attr') ?>">
            <table cellspacing="0" cellpadding="4" border="0">
              <tbody>
              <tr>
                <td><span style="font-size:12.0pt;font-family:Arial">New password:</span></td>
                <td><input name="passwd" type="password" size="40" autocomplete="new-password" required></td>
              </tr>
              <tr>
                <td><span style="font-size:12.0pt;font-family:Arial">Type it again:</span></td>
                <td><input name="passwd2" type="password" size="40" autocomplete="new-password" required></td>
              </tr>
              <tr>
                <td></td>
                <td><button type="submit" class="btn btn-md btn-info">Save password</button></td>
              </tr>
              </tbody>
            </table>
          </form>
          <?php } ?>
</div>

    </div>


    </div>
    </div>
    </section>

<?php echo view($_SESSION['tm'].'uc/footer.php')?>
