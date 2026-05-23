<?php echo view($_SESSION['tm'].'uc/header.php') ?>

<div class="col-md-9 container-wrapper">
        <div class="row">
          <?php echo view($_SESSION['tm'].'uc/account_header.php') ?>
          <div style="clear:both;"></div>
            <div class="col-md-12 account-top">
          <?php if (session()->getFlashdata('register_success')) { ?>
            <div class="row success-flash"><b><?= session()->getFlashdata('register_success'); ?></b></div>
          <?php } ?>

            <h2 class="section-title text-center wow fadeInDown">Register <?= $student_info[0]['en_name'] ?>
              for <?= session()->get('current_semester')['semester_year'] . ' ' . session()->get('current_semester')['semester_name'] ?>
              Classes</h2>
            <?php if(session()->get('current_semester')['registration_fee'] > 0) { ?>
            <p class="text-center wow fadeInDown text-danger">Non-refundable Registration
              Fee: <?= session()->get('current_semester')['registration_fee'] ?> dollars per family</p>
            <?php } ?>
                <!--<p class="text-center wow fadeInDown text-danger"><?/*= session()->get('current_semester')['semester_name']*/?> Classes will be offered online.</p>-->

            <input type="hidden" id="stdid" value="<?= $student_info[0]['student_id'] ?>">
            <input type="hidden" id="stname" value="<?= $student_info[0]['en_name'] ?>">

            <p class="text-danger"><input type="checkbox" id="agreed" <?= (session()->get('userresult')[0]['agreed'] == session()->get('current_semester')['semester_id']) ? 'checked disabled' : '' ?> value="<?=session()->get('current_semester')['semester_id']?>">
              - <strong>I HAVE READ AND AGREE TO THE XILIN NS <a href="/account/invoice/agreement" target="_blank">TERMS & AGREEMENT</a>.</strong>
            </p>
          </div>

          <?php foreach ($subjects as $subject) { ?>
            <div class="col-md-12">
              <h3 class="section-title wow fadeInDown"><?= $subject['subject_name'] ?>
              <?php if ($subject['subject_name'] == 'Heritage Chinese') { ?>
                  <a href="https://heritagechinese.com/" target="_blank" style="text-decoration: underline; font-size: 15px;">Ma Liping Heritage Chinese Website</a>
              <?php } ?>
              </h3>
            </div>
            <div class="col-md-12 table-responsive">
              <table class="table table-hover table-bordered fixed-table-layout" width="100%">
                <thead>
                <tr class="info">
                  <th>
                    Time
                  </th>
                  <th>
                    Class
                  </th>
                  <th>
                    Notes
                  </th>
                  <th>
                    Teacher
                  </th>
                  <th>
                      Early bird rate:
                    Tuition+Book
                  </th>
                  <th>
                    After <?= session()->get('late_date') ?>
                  </th>
                  <th>
                    Class room
                  </th>
                  <th>
                    Status
                  </th>
                  <th>
                    Actions
                  </th>
                </tr>
                </thead>
                <?php foreach ($classes as $class) {
                  if ($class['subject_id'] == $subject['subject_id']) {
                    ?>
                    <tbody>
                    <tr class="<?= array_key_exists($class['class_id'], $student_classes) ? 'success' : '' ?>">
                      <td>
                        <?= $class['meeting_time'] ?>
                      </td>
                      <td>
                        <a href="<?= $class['syl_link'] ?>" target="_blank"><?= $class['class_name'] ?></a>
                      </td>
                      <td>
                        <?= $class['notes'] ?>
                      </td>
                      <td>
                        <a href="<?= empty($class['desc_link']) ? 'mailto: '.$class['email'] : $class['desc_link'] ?>" target="_blank"><?= $class['en_name'] ?></a>
                      </td>
                      <td>
                        $<?= round($class['tuition']) ?>+$<?= round($class['book_fee']) ?><?php if(session()->get('current_semester')['registration_fee'] > 0) { ?>+$<?= round($class['material_fee']) ?><?php }?>
                      </td>
                      <td>
                        $<?= round($class['late_tuition']) ?>+$<?= round($class['late_book_fee']) ?><?php if(session()->get('current_semester')['registration_fee'] > 0) { ?>+$<?= round($class['material_fee']) ?><?php }?>
                      </td>
                      <td>
                        <?= $class['classroom'] ?>
                      </td>
                      <td>
                        <?php if (array_key_exists($class['class_id'], $student_classes)) {
                          echo 'Registered';
                        } else { ?>
                          <?= $class['student_amount_limit'] > $class['enrolled'] ? 'Open' : 'Full' ?>
                        <?php } ?>
                      </td>
                      <td>
                        <?php if (array_key_exists($class['class_id'], $student_classes)) {
                          $withbook = $student_classes[$class['class_id']]['buy_book'];
                          ?>
                          <?php if($class['book_fee'] > 0) { ?>
                          <a href="#" class="btn btn-warning btn-xs change_class"
                             cid="<?= $class['class_id'] ?>"
                             ctime="<?= $class['meeting_time'] ?>"
                             cname="<?= $class['class_name'] ?>"
                             bookfee="<?= session()->get('is_late') ? $class['late_book_fee'] : $class['book_fee'] ?>"
                             withbook="<?= $withbook ?>">Change</a>
                          <?php } ?>

                          <a href="#" class="btn btn-danger btn-xs unregister_class"
                             cid="<?= $class['class_id'] ?>"
                             ctime="<?= $class['meeting_time'] ?>"
                             cname="<?= $class['class_name'] ?>"
                             bookfee="<?= session()->get('is_late') ? $class['late_book_fee'] : $class['book_fee'] ?>"
                             withbook="<?= $withbook ?>">Un-Register</a>
                        <?php } else if ($class['student_amount_limit'] <= $class['enrolled']) { ?>
                          Unavailable
                        <?php } else { ?>
                          <a href="" class="btn btn-primary btn-xs register_class"
                             cid="<?= $class['class_id'] ?>"
                             ctime="<?= $class['meeting_time'] ?>"
                             cname="<?= $class['class_name'] ?>"
                             bookfee="<?= session()->get('is_late') ? $class['late_book_fee'] : $class['book_fee'] ?>">Register</a>
                        <?php } ?>

                      </td>
                    </tr>
                    </tbody>
                  <?php
                  }
                } ?>
              </table>
            </div>
          <?php } ?>
        </div>
        <br><br>
      </div>

</div>
</div>
</section>

<?php echo view($_SESSION['tm'].'uc/footer.php') ?>