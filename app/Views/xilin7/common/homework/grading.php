<?php echo view($_SESSION['tm'].'uc/header.php') ?>

  <section>
    <div class="container-wrapper">
      <div class="container">
        <div class="row">
          <ul class="account-header">
            <li><a href="/account/index">Class Details</a></li>
            <li><a href="/account/teacher_info">Update Teacher Info</a></li>
            <li><a href="/homework" class="active_menu">Homework</a></li>
            <li><a href="/login/logout">Logout</a></li>
          </ul>
          <div style="clear:both;"></div>
          <? if (session()->flashdata('success_msg')) { ?>
            <div class="row success-flash"><b><?= session()->flashdata('success_msg'); ?></b></div>
          <? } ?>
          <div class="Profile">
            <?php if($usertype == 2){?>
              <h3>Homework Submissions for <?= isset($hws[0]) ? $hws[0]['title'] : ''?></h3>
              <table class="table table-hover table-bordered fixed-table-layout">
              <thead>
              <tr class="bg-primary">
                <th>
                  Number
                </th>
                <th>
                  Student Name
                </th>
                <th>
                  Homework Link
                </th>
                <th>
                  Student Note
                </th>
                <th>
                  Submitted Time
                </th>
                <th>
                  Grade
                </th>
                <th>
                  Comment
                </th>
                <th>
                  Action
                </th>
              </tr>
              </thead>
              <? $i = 0; foreach($hws as $hw) {?>
                  <tbody>
                  <tr><td><?= ++$i?></td>
                    <td><?=$hw['en_name']?> <?=$hw['cn_name'] ? '('.$hw['cn_name'].')' : '' ?></td>
                    <td><?=$hw['link']?></td>
                    <td><?=substr($hw['note'], 0, 100)?></td>
                    <td><?=$hw['sub_date']?></td>
                    <td><?=$hw['grade']?></td>
                    <td><?=substr($hw['comment'], 0, 100)?></td>
                    <td><a href="/homework/grade_homework/<?= $hw['sid'] ?>/<?= $hw['class_id'] ?>/<?= $hw['hid'] ?>/<?= $hw['hw_sub_id'] ?>"
                           class="btn btn-primary btn-md">Grade Homework</a></td>
                  </tr>
                  </tbody>
              <? } ?>
              </table>
            <?php }?>
          </div>
        </div>
        <br><br>
      </div>
    </div>
  </section>


<?php echo view($_SESSION['tm'].'uc/footer.php') ?>