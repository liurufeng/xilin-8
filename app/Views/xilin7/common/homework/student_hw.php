<?php echo view($_SESSION['tm'].'uc/header.php') ?>

  <section>
    <div class="container-wrapper">
      <div class="container">
        <div class="row">
          <?php echo view($_SESSION['tm'].'uc/account_header.php') ?>
          <div style="clear:both;"></div>
          <? if (session()->flashdata('success_msg')) { ?>
            <div class="row success-flash"><b><?= session()->flashdata('success_msg'); ?></b></div>
          <? } ?>
          <div class="Profile">
            <h2 style="text-align: center;">Homework</h2>
            <?php if ($usertype === 1) { ?>
              <div class="row account-table">
                <?php foreach ($students as $student) { ?>
                  <h3><u><?= $student['en_name'] ?> <?= $student['cn_name'] ? $student['cn_name'] : ''?> </u></h3>
                  <? foreach($student_classes[$student['student_id']] as $sc) { ?>
                    <h4><?= $sc['class_name'] ?> <?= $sc['class_code'] ?> </h4>
                    <? if(count($hws[$student['student_id']][$sc['class_id']]) > 0) { ?>
                    <table class="table table-hover table-bordered fixed-table-layout">
                    <thead>
                    <tr class="info">
                      <th>
                        Number
                      </th>
                      <th>
                        Title
                      </th>
                      <th>
                        Notes
                      </th>
                      <th>
                        Link
                      </th>
                      <th>
                        Due Date
                      </th>
                      <th>
                        Submission
                      </th>
                      <th>
                        My Note
                      </th>
                      <th>
                        Submit Date
                      </th>
                      <th>
                        Grade
                      </th>
                      <th>
                        Comments
                      </th>
                      <th>
                        Actions
                      </th>
                    </tr>
                    </thead>
                    <tbody>
                      <? $i=0; foreach($hws[$student['student_id']][$sc['class_id']] as $hw) { ?>
                          <tr>
                            <td><?=++$i?></td>
                            <td><?=$hw['title']?></td>
                            <td><?=substr($hw['note'], 0, 100)?></td>
                            <td><?=$hw['link']?></td>
                            <td><?=$hw['due_date']?></td>
                            <td><?=$hw['sublink']?></td>
                            <td><?=substr($hw['subnote'], 0, 100)?></td>
                            <td><?=$hw['sub_date']?></td>
                            <td><?=$hw['grade']?></td>
                            <td><?=$hw['comment']?></td>
                            <td>
                              <? if(! $hw['sublink']) {?>
                              <a href="/homework/do_homework/<?= $hw['homework_id'] ?>/<?= $student['student_id']?>/<?= $sc['class_id']?>"
                                 class="btn btn-primary btn-md">View & Submit Homework</a>
                              <?} else { ?>
                              Submitted
                              <? } ?>
                            </td>
                          </tr>

                    <?php } ?>
                    </tbody>
                    </table>
                    <?php } else { ?>
                      <p>No homework assigned yet.</p>
                    <?}?>
                  <?php } ?>
                <?php } ?>
              </div>
            <?php } ?>
          </div>
        </div>
        <br><br>

      </div>
    </div>
  </section>


<?php echo view($_SESSION['tm'].'uc/footer.php') ?>