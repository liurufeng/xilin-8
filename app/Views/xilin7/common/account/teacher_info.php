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

    <div class="col-md-9 container-wrapper">
        <div class="row account-header">
            <div class="col-md-12 fixed-menu-button"  style="">
                <div><a href="/account/index" class="btn flat-btn active_menu">Class Details</a></div>
                <div><a href="/account/teacher_info" class="btn flat-btn">Update Teacher Info</a></div>
            </div>
        </div>
        <form action="<?php echo '/account/update_teacher'?>" method="POST">
        <div class="account-top row">
            <div style="clear:both;"></div>
            <?php if (isset($success_msg)) { ?>
              <div class="row success-flash"><b><?= $success_msg ?></b></div>
            <?php } ?>
            <?php if (isset($error_msg)) { ?>
              <div class="row error-flash"><b><?= $error_msg ?></b></div>
            <?php } ?>
            <div class="Profile">
              <?php if($usertype == 2){?>
                <p>

                  <table class="table table-hover table-bordered fixed-table-layout" style="width: 66% !important;">
                    <tr>
                      <th colspan="2" style="text-align:center;" class="info">Update Teacher Info</th>
                    </tr>
                    <tr>
                      <td style="text-align:right;"><span style="color:red;">*</span>Teacher Email:</td>
                      <td style="padding:3px;"><input type="email" value="<?=$teacher['email']?>" name="email" disabled
                                                      style="width:300px; margin-left:10px; padding: 3px;"/></td>
                    </tr>
                    <tr>
                      <td style="text-align:right;"><span style="color:red;">*</span>Teacher Password:
                      </td>
                      <td style="padding:3px;"><input type="password" name="passwd" value="<?=$teacher['passwd']?>"
                                                      style="width:300px; margin-left:10px; padding: 3px;"/></td>
                    </tr>
                    <tr>
                      <td style="text-align:right;"><span style="color:red;">*</span>English Name:</td>
                      <td style="padding:3px;"><input type="text" value="<?=$teacher['en_name']?>" name="en_name" required
                                                      style="width:300px; margin-left:10px; padding: 3px;"/></td>
                    </tr>

                    <tr>
                      <td style="text-align:right;">Chinese Name:</td>
                      <td style="padding:3px;"><input type="text" value="<?=$teacher['cn_name']?>" name="cn_name"
                                                      style="width:300px; margin-left:10px; padding: 3px;"/></td>
                    </tr>
                    <tr>
                      <td style="text-align:right;"><span style="color:red;">*</span>Home Phone:</td>
                      <td style="padding:3px;"><input type="text" value="<?=$teacher['phone1']?>" name="phone1" required
                                                      style="width:300px; margin-left:10px; padding: 3px;"/></td>
                    </tr>
                    <tr>
                      <td style="text-align:right;"><span style="color:red;">*</span>Cell Phone:</td>
                      <td style="padding:3px;"><input type="text" value="<?=$teacher['phone2']?>" name="phone2" required
                                                      style="width:300px; margin-left:10px; padding: 3px;"/></td>
                    </tr>
                    <tr>
                      <td style="text-align:right;"><span style="color:red;">*</span>Home Address:</td>
                      <td style="padding:3px;"><input type="text" value="<?=$teacher['address']?>" name="address" required
                                                      style="width:300px; margin-left:10px; padding: 3px;"/></td>
                    </tr>
                    <tr>
                      <td style="text-align:right;">Bio URL:</td>
                      <td style="padding:3px;"><input type="text" value="<?=$teacher['desc_link']?>" name="desc_link"
                                                      style="width:300px; margin-left:10px; padding: 3px;"/></td>
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
              <input type="submit" value=" Update " name="Update" class="btn btn-primary btn-lg"/>
              <a href="/account/index" class="btn btn-warning btn-lg">Cancel</a>
            </p>
          </div>
        </form>
        <br><br>
      </div>
    </div>
    </div>
    </section>
<?php } else { ?>
  
<?php }?>
<?php echo view($_SESSION['tm'].'uc/footer.php')?>