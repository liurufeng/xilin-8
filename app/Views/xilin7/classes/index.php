<?php echo view($_SESSION['tm'].'uc/header.php') ?>
<div class="col-md-9 main-content">
  <div class="row" style="">
    <div class="col-md-12">
        <div class="heading4">
            <h2><?=session()->get('current_semester')['semester_year'] .' ' . session()->get('current_semester')['semester_name']?> Classes</h2>
            <?php if(session()->get('current_semester')['registration_fee'] > 0) { ?>
            <span>Fall Registration Fee: 15 dollars per family; Spring Registration Fee: 20 dollars per family</span>
            <?php } ?>
            <!--<p class="text-center wow fadeInDown text-danger"><?/*= session()->get('current_semester')['semester_name']*/?> Classes will be offered online.</p>-->
        </div>
    </div>

    <div class="col-md-12">
        <!-- Recent Purchase Table -->
        <div class=" table-responsive">
            <table class="table table-bordered table-striped">
                <!-- Table Header -->
                <thead class="cal-header">
                <tr>
                    <th>Time</th>
                    <th>Class</th>
                    <th>Notes</th>
                    <th>Teacher</th>
                    <th>Early bird rate: Tuition+book<?php if(session()->get('current_semester')['registration_fee'] > 0) { ?> +Material<?php }?></th>
                    <th>After<br><?=session()->get('late_date')?></th>
                    <th>Class room</th>
                    <th>Enroll</th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($subjects as $subject) { ?>
                <tr>
                    <td colspan="8"><a name="<?= $subject['subject_name'] ?>"><b><?= $subject['subject_name'] ?></b></a>
                    <?php if ($subject['subject_name'] == 'Heritage Chinese') { ?>
                        <a href="https://heritagechinese.com/" target="_blank" style="text-decoration: underline">Ma Liping Heritage Chinese Website</a>
                    <?php } ?>
                    </td></tr>
                  <?php foreach ($classes as $class) {
                    if ($class['subject_id'] == $subject['subject_id']) {
                      ?>
                    <tr>
                        <td><?= $class['meeting_time'] ?></td>
                        <td><a href="<?= $class['syl_link'] ?>" target="_blank"><?= $class['class_name'] ?></a></td>
                        <td><?= $class['notes'] ?></td>
                        <td nowrap>
                            <a href="<?= empty($class['desc_link']) ? 'mailto: '.$class['email'] : $class['desc_link'] ?>" target="_blank" style="float: left;"><?= $class['en_name'] ?></a>
                            <a href="mailto: <?=$class['email'] ?>" target="_blank" style="float: right;"><i class="fa fa-envelope" aria-hidden="true"></i></a>
                        </td>
                        <td>$<?= round($class['tuition']) ?>+<?= round($class['book_fee']) ?><?php if(session()->get('current_semester')['registration_fee'] > 0) { ?>+<?= round($class['material_fee']) ?><?php } ?></td>
                        <td>$<?= round($class['late_tuition']) ?>+<?= round($class['late_book_fee']) ?><?php if(session()->get('current_semester')['registration_fee'] > 0) { ?>+<?= round($class['material_fee']) ?><?php } ?></td>
                        <td><?= $class['classroom'] ?></td>
                        <td><?= $class['student_amount_limit'] > $class['enrolled'] ? 'Open' : 'Full' ?></td>
                    </tr>
                      <?php
                    }
                  } ?>
                  <?php }?>
                </tbody>
            </table>
        </div>
    </div>
  </div>
</div>


</div>
</div>
</section>


<?php echo view($_SESSION['tm'].'uc/footer.php') ?>