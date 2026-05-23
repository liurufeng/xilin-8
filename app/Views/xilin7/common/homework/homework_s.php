<style type="text/css">
  #tab tr .right {
    text-align: left;
    padding-left: 10px;
  }

  .row ul li {
    float: left;
    list-style: none;
    margin: 20px;

  }
</style>
<?php echo view($_SESSION['tm'].'uc/header.php')?>

<?php if (isset($parent) && !empty($parent)) { ?>

  <section>
    <div class="container-wrapper">
      <div class="container">
        <form action="<?php echo site_url('homework/submit_hw')?>" method="POST">
          <input type="hidden" name="homework_id" value="<?= $homework_id?>">
          <input type="hidden" name="class_id" value="<?=$class_id?>">
          <input type="hidden" name="student_id" value="<?=$student_id?>">
          <div class="row">
            <?php echo view($_SESSION['tm'].'uc/account_header.php') ?>
            <div style="clear:both;"></div>

            <div class="Profile">
              <?php if($usertype == 1){?>
                <p>
                <table class="table table-hover table-bordered fixed-table-layout account-table">
                  <tr>
                    <th colspan="2" style="text-align:center;" class="info">Submit Homework</th>
                  </tr>
                  <tr>
                    <td style="text-align:right;">Class:</td>
                    <td style="padding:3px; text-align:left;">
                      <?=$c['class_name']?> (<?=$c['class_code']?> <?=$c['meeting_time']?>)
                    </td>
                  </tr>

                  <tr>
                    <td style="text-align:right;">Homework Title:</td>
                    <td style="padding:3px;"><?= $h['title'] ?></td>
                  </tr>

                  <tr>
                    <td style="text-align:right;">Notes:</td>
                    <td style="padding:3px;">
                      <?= $h['note'] ?>
                    </td>
                  </tr>

                  <tr>
                    <td style="text-align:right;">Homework Link:</td>
                    <td style="padding:3px;"><?= $h['link'] ?></td>
                  </tr>

                  <tr>
                    <td style="text-align:right;">Due Date:</td>
                    <td style="padding:3px;"><?= $h['due_date'] ?></td>
                  </tr>

                  <tr>
                    <td style="text-align:right;">My Homework Google Doc Link:</td>
                    <td style="padding:3px;"><input type="text" value="" name="link"
                                                    style="width:450px; margin-left:10px; padding: 3px;"/></td>
                  </tr>

                  <tr>
                    <td style="text-align:right;">Notes to teacher:</td>
                    <td style="padding:3px;">
                      <textarea name="note" rows="10" cols="10" style="width:450px; margin-left:10px; padding: 3px;"></textarea>
                    </td>
                  </tr>

                </table>
                </p>
              <?php }?>
            </div>
          </div>
          <br>
          <div class="row">
            <p data-wow-delay="200ms" data-wow-duration="300ms" class="wow fadeInUp animated"
               style="visibility: visible; animation-duration: 300ms; animation-delay: 200ms; animation-name: fadeInUp;">
              <input type="submit" value=" Submit " name="submit" class="btn btn-primary btn-lg"/>
              <a href="/homework/index" class="btn btn-warning btn-lg">Cancel</a>
            </p>
          </div>
        </form>
        <br><br>
      </div>
    </div>
  </section>
<?php } else { ?>
  <section id="contact">
    <div id="google-map" style="height:350px" data-latitude="52.365629" data-longitude="4.871331"></div>
    <div class="container-wrapper">
      <div class="container">
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

            </div>
          </div>
        </div>
      </div>
    </div>
  </section><!--/#bottom-->
<?php }?>
<?php echo view($_SESSION['tm'].'uc/footer.php')?>