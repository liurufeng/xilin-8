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

<?php if(isset($teacher) && !empty($teacher)){?>

  <section>
    <div class="container-wrapper">
      <div class="container">
        <form action="<?php echo site_url('homework/do_add/'.$action)?>" method="POST">
          <input type="hidden" name="homework_id" value="<?=$action == 'Edit' ? $homework_id : ''?>">
          <input type="hidden" name="semester_id" value="<?=$semester_id?>">
          <input type="hidden" name="teacher_id" value="<?=$teacher['teacher_id']?>">
          <div class="row">
            <ul class="account-header">
              <li><a href="/account/index">Class Details</a></li>
              <li><a href="/account/teacher_info">Update Teacher Info</a></li>
              <li><a href="/homework" class="active_menu">Homework</a></li>
              <li><a href="/login/logout">Logout</a></li>
            </ul>
            <div style="clear:both;"></div>

            <div class="Profile">
              <?php if($usertype == 2){?>
                <p>

                <table class="table table-hover table-bordered fixed-table-layout" style="width: 66% !important;">
                  <tr>
                    <th colspan="2" style="text-align:center;" class="info"><?=$action?> Homework</th>
                  </tr>
                  <tr>
                    <td style="text-align:right;"><span style="color:red;">*</span>Class:</td>
                    <td style="padding:3px; text-align:left;">
                      <select id="class_id" style="height:35px; width:450px; text-align:left;margin-left:10px; padding: 3px;"
                              name="class_id">
                        <?foreach($classes as $k => $c) { ?>
                          <option value="<?=$k?>" <?= $action == 'Edit' && $h['class_id'] == $k ? 'selected' : '' ?>><?=$c['class_name']?> (<?=$c['class_code']?> <?=$c['meeting_time']?>)</option>
                        <? } ?>
                      </select>
                    </td>
                  </tr>

                  <tr>
                    <td style="text-align:right;"><span style="color:red;">*</span>Homework Title:</td>
                    <td style="padding:3px;"><input type="text" value="<?= $action == 'Edit' ? $h['title'] : ''?>" name="title" required
                                                    style="width:450px; margin-left:10px; padding: 3px;"/></td>
                  </tr>

                  <tr>
                    <td style="text-align:right;">Notes:</td>
                    <td style="padding:3px;">
                      <textarea name="note" rows="10" cols="10" style="width:450px; margin-left:10px; padding: 3px;"><?= $action == 'Edit' ? $h['note'] : ''?></textarea>
                    </td>
                  </tr>

                  <tr>
                    <td style="text-align:right;">Homework Link:</td>
                    <td style="padding:3px;"><input type="text" value="<?= $action == 'Edit' ? $h['link'] : ''?>" name="link"
                                                    style="width:450px; margin-left:10px; padding: 3px;"/></td>
                  </tr>

                  <tr>
                    <td style="text-align:right;">Due Date:</td>
                    <td style="padding:3px;" id="sandbox-container"><input type="text" value="<?= $action == 'Edit' ? $h['due_date'] : ''?>" name="due_date"
                                                    style="width:450px; margin-left:10px; padding: 3px;"/></td>
                  </tr>

                  <tr>
                    <td style="text-align:right;">Email the Class: </td>
                    <td style="padding:3px;"><input type="checkbox" name="send_email"
                                                    style="width:40px; padding: 3px;"/></td>
                  </tr>

                  <tr>
                    <td style="text-align:right;">Other Emails:</td>
                    <td style="padding:3px;"><input type="text" value="" name="other_emails" placeholder="Emails seperated by comma (,)"
                                                    style="width:450px; margin-left:10px; padding: 3px;"/></td>
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