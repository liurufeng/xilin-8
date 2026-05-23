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
          <? if (isset($success_msg)) { ?>
              <div class="row success-flash"><b><?= $success_msg ?></b></div>
          <? } ?>
          <? if (isset($error_msg)) { ?>
              <div class="row error-flash"><b><?= $error_msg ?></b></div>
          <? } ?>
          <? if (session()->flashdata('success_msg')) { ?>
              <div class="row success-flash"><b><?= session()->flashdata('success_msg'); ?></b></div>
          <? } ?>

          <? if (session()->flashdata('error_msg')) { ?>
              <div class="row error-flash"><b><?= session()->flashdata('error_msg'); ?></b></div>
          <? } ?>
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

      <?php } ?>

    </div>

    </div>
    </div>
    </section>

<?php echo view($_SESSION['tm'].'uc/footer.php') ?>