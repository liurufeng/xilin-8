<?php echo view($_SESSION['tm'].'uc/header.php') ?>
<?php if (isset($parent) && !empty($parent)) {
  $p = $parent;
  ?>

<div class="col-md-9">
<div class="row" style="text-align: center">
  <?php echo view($_SESSION['tm'].'uc/account_header.php') ?>
    <div style="clear:both;"></div>
  </div>

  <div class="row account-top" id="view_profile">
    <?php if (isset($success_msg)) { ?>
      <div class="row success-flash"><b><?= $success_msg ?></b></div>
    <?php } ?>
    <?php if (isset($error_msg)) { ?>
      <div class="row error-flash"><b><?= $error_msg ?></b></div>
    <?php } ?>
    <?php if (session()->getFlashdata('success_msg')) { ?>
      <div class="row success-flash"><b><?= session()->flashdata('success_msg'); ?></b></div>
    <?php } ?>

    <?php if (session()->getFlashdata('error_msg')) { ?>
      <div class="row error-flash"><b><?= session()->flashdata('error_msg'); ?></b></div>
    <?php } ?>
    <div class="Profile account-table" data-wow-delay="200ms" data-wow-duration="300ms" class="wow fadeInUp animated"
         style="visibility: visible; animation-duration: 300ms; animation-delay: 200ms; animation-name: fadeInUp;">
      <?php if ($usertype == 1) { ?>
        <table class="table table-hover table-bordered fixed-table-layout" style="width: 90%">
          <tr>
            <th colspan="2" style="text-align:center;" class="info">Parent Profile Information (Parent #<?= $p['parent_id'] ?>)</th>
          </tr>

          <tr>
            <td style="text-align:right;">Email:</td>
            <td class="right"><?= $p['email'] ?>
            </td>
          </tr>
          <tr>
            <td style="text-align:right;">Password:</td>
            <td class="right">******
            </td>
          </tr>
          <tr>
            <td style="text-align:right;">Primary Contact English Name:</td>
            <td class="right">
              <?= $p['primary_en_name'] ?>
            </td>
          </tr>
          <tr>
            <td style="text-align:right;">Chinese Name:</td>
            <td class="right">
              <?= $p['primary_cn_name'] ?>
            </td>
          </tr>
          <tr>
            <td style="text-align:right;">Relationship:</td>
            <td class="right">
              <?= $p['primary_relationship'] ?>
            </td>
          </tr>
          <tr>
            <td style="text-align:right;">Phone:</td>
            <td class="right">
              <?= $p['primary_phone'] ?>
            </td>
          </tr>
          <tr>
            <td style="text-align:right;">Alternative Contact Email:</td>
            <td class="right">
              <?= $p['alter_contact_email'] ?>
            </td>
          </tr>
          <tr>
            <td style="text-align:right;">Alternative Contact English Name:</td>
            <td class="right">
              <?= $p['alter_en_name'] ?>
            </td>
          </tr>
          <tr>
            <td style="text-align:right;">Chinese Name:</td>
            <td class="right">
              <?= $p['alter_cn_name'] ?>
            </td>
          </tr>
          <tr>
            <td style="text-align:right;">Relationship:</td>
            <td class="right">
              <?= $p['alter_relationship'] ?>
            </td>
          </tr>
          <tr>
            <td style="text-align:right;">Phone:</td>
            <td class="right">
              <?= $p['alter_phone'] ?>
            </td>
          </tr>
          <tr>
            <td style="text-align:right;">Street Address:</td>
            <td class="right">
              <?= $p['address'] ?>
            </td>
          </tr>
          <tr>
            <td style="text-align:right;">City:</td>
            <td class="right">
              <?= $p['city'] ?>
            </td>
          </tr>
          <tr>
            <td style="text-align:right;">State:</td>
            <td class="right">
              <?= $p['state'] ?>
            </td>
          </tr>
          <tr>
            <td style="text-align:right;">Zip:</td>
            <td class="right">
              <?= $p['zip'] ?>
            </td>
          </tr>
        </table>
      <?php } ?>
    </div>

      <div class="col-md-12 account-table">
          <p data-wow-delay="200ms" data-wow-duration="300ms" class="wow fadeInUp animated"
             style="visibility: visible; animation-duration: 300ms; animation-delay: 200ms; animation-name: fadeInUp;">
              <a href="#" onclick="update_profile(); return false;" class="btn btn-primary btn-lg">Update My Profile</a>
              <a href="/account/students" class="btn btn-success btn-lg">Go To Registration</a>
          </p>
      </div>
      <br><br>
  </div>



  <div id="update_profile" class="account-top">
    <form action="<?php echo '/account/update_parent' ?>" method="POST">
      <div class="row">
        <?php if (isset($success_msg)) { ?>
          <div class="row success-flash"><b><?= $success_msg ?></b></div>
        <?php } ?>
        <?php if (isset($error_msg)) { ?>
          <div class="row error-flash"><b><?= $error_msg ?></b></div>
        <?php } ?>
        <div class="Profile account-table" data-wow-delay="200ms" data-wow-duration="300ms" class="wow fadeInUp animated"
             style="visibility: visible; animation-duration: 300ms; animation-delay: 200ms; animation-name: fadeInUp;">
          <?php if ($usertype == 1) { ?>
            <table class="table table-hover table-bordered fixed-table-layout">
              <tr>
                <th colspan="2" style="text-align:center;" class="info">Parent Profile Information (Parent #<?= $p['parent_id'] ?>)</th>
              </tr>

              <tr>
                <td style="text-align:right;"><span style="color:red;">*</span>Email:</td>
                <td class="right"><input type="email" value="<?= $p['email'] ?>" name="email" required
                                         style="width:300px; padding: 3px;"/>
                </td>
              </tr>
              <tr>
                <td style="text-align:right;"><span style="color:red;">*</span>Password:</td>
                <td class="right"><input type="password" value="<?= $p['passwd'] ?>" name="passwd" required
                                         style="width:300px; padding: 3px;"/>
                </td>
              </tr>
              <tr>
                <td style="text-align:right;"><span style="color:red;">*</span>Primary Contact English Name:</td>
                <td class="right">
                  <input type="text" value="<?= $p['primary_en_name'] ?>" name="primary_en_name" required
                         style="width:300px; padding: 3px;"/>
                </td>
              </tr>
              <tr>
                <td style="text-align:right;">Chinese Name:</td>
                <td class="right">
                  <input type="text" value="<?= $p['primary_cn_name'] ?>" name="primary_cn_name"
                         style="width:300px; padding: 3px;"/>
                </td>
              </tr>
              <tr>
                <td style="text-align:right;"><span style="color:red;">*</span>Relationship:</td>
                <td class="right">
                  <input type="text" value="<?= $p['primary_relationship'] ?>" name="primary_relationship" required
                         style="width:300px; padding: 3px;"/>
                </td>
              </tr>
              <tr>
                <td style="text-align:right;"><span style="color:red;">*</span>Phone:</td>
                <td class="right">
                  <input type="text" value="<?= $p['primary_phone'] ?>" name="primary_phone" required
                         style="width:300px; padding: 3px;"/>
                </td>
              </tr>
              <tr>
                <td style="text-align:right;">Alternative Contact Email:</td>
                <td class="right">
                  <input type="text" value="<?= $p['alter_contact_email'] ?>" name="alter_contact_email"
                         style="width:300px; padding: 3px;"/>
                </td>
              </tr>
              <tr>
                <td style="text-align:right;">Alternative Contact English Name:</td>
                <td class="right">
                  <input type="text" value="<?= $p['alter_en_name'] ?>" name="alter_en_name"
                         style="width:300px; padding: 3px;"/>
                </td>
              </tr>
              <tr>
                <td style="text-align:right;">Chinese Name:</td>
                <td class="right">
                  <input type="text" value="<?= $p['alter_cn_name'] ?>" name="alter_cn_name"
                         style="width:300px; padding: 3px;"/>
                </td>
              </tr>
              <tr>
                <td style="text-align:right;">Relationship:</td>
                <td class="right">
                  <input type="text" value="<?= $p['alter_relationship'] ?>" name="alter_relationship"
                         style="width:300px; padding: 3px;"/>
                </td>
              </tr>
              <tr>
                <td style="text-align:right;">Phone:</td>
                <td class="right">
                  <input type="text" value="<?= $p['alter_phone'] ?>" name="alter_phone"
                         style="width:300px; padding: 3px;"/>
                </td>
              </tr>
              <tr>
                <td style="text-align:right;"><span style="color:red;">*</span>Street Address:</td>
                <td class="right">
                  <input type="text" value="<?= $p['address'] ?>" name="address" required
                         style="width:300px; padding: 3px;"/>
                </td>
              </tr>
              <tr>
                <td style="text-align:right;"><span style="color:red;">*</span>City:</td>
                <td class="right">
                  <input type="text" value="<?= $p['city'] ?>" name="city" required
                         style="width:300px; padding: 3px;"/>
                </td>
              </tr>
              <tr>
                <td style="text-align:right;"><span style="color:red;">*</span>State:</td>
                <td style="padding:3px;"><select name="state">
                    <option value="Alabama">Alabama</option>
                    <option value="Alaska">Alaska</option>
                    <option value="Arizona">Arizona</option>
                    <option value="Arkansas">Arkansas</option>
                    <option value="California">California</option>
                    <option value="Colorado">Colorado</option>
                    <option value="Connecticut">Connecticut</option>
                    <option value="Delaware">Delaware</option>
                    <option value="District of Columbia">District of Columbia</option>
                    <option value="Florida">Florida</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Hawaii">Hawaii</option>
                    <option value="Idaho">Idaho</option>
                    <option value="Illinois" selected="">Illinois</option>
                    <option value="Indiana">Indiana</option>
                    <option value="Iowa">Iowa</option>
                    <option value="Kansas">Kansas</option>
                    <option value="Kentucky">Kentucky</option>
                    <option value="Louisiana">Louisiana</option>
                    <option value="Maine">Maine</option>
                    <option value="Maryland">Maryland</option>
                    <option value="Massachusetts">Massachusetts</option>
                    <option value="Michigan">Michigan</option>
                    <option value="Minnesota">Minnesota</option>
                    <option value="Mississippi">Mississippi</option>
                    <option value="Missouri">Missouri</option>
                    <option value="Montana">Montana</option>
                    <option value="Nebraska">Nebraska</option>
                    <option value="Nevada">Nevada</option>
                    <option value="New Hampshire">New Hampshire</option>
                    <option value="New Jersey">New Jersey</option>
                    <option value="New Mexico">New Mexico</option>
                    <option value="New York">New York</option>
                    <option value="North Carolina">North Carolina</option>
                    <option value="North Dakota">North Dakota</option>
                    <option value="Ohio">Ohio</option>
                    <option value="Oklahoma">Oklahoma</option>
                    <option value="Oregon">Oregon</option>
                    <option value="Pennsylvania">Pennsylvania</option>
                    <option value="Rhode Island">Rhode Island</option>
                    <option value="South Carolina">South Carolina</option>
                    <option value="South Dakota">South Dakota</option>
                    <option value="Tennessee">Tennessee</option>
                    <option value="Texas">Texas</option>
                    <option value="Utah">Utah</option>
                    <option value="Vermont">Vermont</option>
                    <option value="Virginia">Virginia</option>
                    <option value="Washington">Washington</option>
                    <option value="West Virginia">West Virginia</option>
                    <option value="Wisconsin">Wisconsin</option>
                    <option value="Wyoming">Wyoming</option>
                  </select></td>
              </tr>
              <tr>
                <td style="text-align:right;"><span style="color:red;">*</span>Zip:</td>
                <td class="right">
                  <input type="text" value="<?= $p['zip'] ?>" name="zip" required
                         style="width:300px; padding: 3px;"/>
                </td>
              </tr>
            </table>
          <?php } ?>
        </div>
      </div>

      <div class="row account-table">
        <p data-wow-delay="200ms" data-wow-duration="300ms" class="wow fadeInUp animated"
           style="visibility: visible; animation-duration: 300ms; animation-delay: 200ms; animation-name: fadeInUp;">
          <input type="submit" value=" Submit " name="Update" class="btn btn-primary btn-lg" style="font-size: 16px;
          cursor: pointer;height: 45px;"/>
          <a href="#" onclick="cancel_update_profile(); return false;" class="btn btn-danger btn-lg">Cancel</a>
        </p>
      </div>
      <br><br>
    </form>
  </div>

<?php } else { ?>
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
          <div style="margin-top:20px;">Click <a href=''>here</a> for Financial Aid Information and
            Discount Policy.
          </div>
        </div>
      </div>
    </div>
<!--/#bottom-->
<?php } ?>

</div>

</div>
</div>
</section>

<script type="text/javascript">
    $(document).ready(function () {
        $("#update_profile").hide();
        $("#view_profile").show();
    });

    function update_profile(){
        $("#update_profile").show();
        $("#view_profile").hide();
        return false;
    }
    function cancel_update_profile(){
        $("#update_profile").hide();
        $("#view_profile").show();
        return false;
    }
</script>

<?php echo view($_SESSION['tm'].'uc/footer.php') ?>