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
            <div class="row pull-left" style="padding-left: 2.2em;">
              <form action="<?php echo site_url('homework/index')?>">
                Semester: <select id="semester_id" name="semester_id" onchange="submit();">
                  <? foreach($semesters as $semester) {?>
                    <option value="<?=$semester['semester_id']?>" <? if($semester['semester_id'] == session()->get('semester_id')) echo 'selected'; ?> >
                      <?=$semester['semester_year']?> <?=$semester['semester_name']?>
                    </option>
                  <? } ?>
                </select>
              </form>
              </div>

              <div class="row pull-right" style="padding-right: 1em;">
                <p data-wow-delay="200ms" data-wow-duration="300ms" class="wow fadeInUp animated"
                   style="visibility: visible; animation-duration: 300ms; animation-delay: 200ms; animation-name: fadeInUp;">
                  <a href="/homework/add_homework/Add" class="btn btn-primary btn-lg" id="add_new_homework">Add Homework</a>
                </p>
              </div>

              <?foreach($classes as $ck => $c) {?>
                <table class="table table-hover table-bordered fixed-table-layout">
                  <thead>
                  <tr class="success">
                    <th>
                      Class Code
                    </th>
                    <th>
                      Classe Name
                    </th>
                    <th>
                      Time
                    </th>
                    <th>
                      Classroom
                    </th>
                    <th>
                      Notes
                    </th>
                    <th>
                      Fees
                    </th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr class="info"><td><?=$c['class_code']?></td>
                    <td><?=$c['class_name']?></td>
                    <td><?=$c['meeting_time']?></td>
                    <td><?=$c['classroom']?></td>
                    <td><?=$c['notes']?></td>
                    <td><?=$c['tuition']?>+<?=$c['book_fee']?>+<?=$c['material_fee']?></td>
                  </tr>
                  <?if(count($homeworks[$ck]) > 0){?>
                    <tr><th colspan="6">
                        Homework
                      </th></tr>

                    <thead>
                    <tr class="bg-primary">
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
                        Action
                      </th>
                    </tr>
                    </thead>

                  <? } ?>
                  <? $i = 0;
                      foreach($homeworks[$ck] as $hk => $w) {?>
                    <tr><td><?=++$i?></td>
                      <td><?=$w['title']?></td>
                      <td><?=substr($w['note'], 0, 100)?></td>
                      <td><?=$w['link']?></td>
                      <td><?=$w['due_date']?></td>
                      <td>
                        <a href="/homework/grade/<?= $w['homework_id'] ?>" class="btn btn-primary btn-md check_homework" wid="<?= $w['homework_id'] ?>">Student Submissions</a>
                        <a href="/homework/add_homework/Edit/<?= $w['homework_id'] ?>" class="btn btn-info btn-md edit_homework" wid="<?= $w['homework_id'] ?>">Edit</a>
                        <a href="#" class="btn btn-danger btn-md remove_homework" wid="<?= $w['homework_id'] ?>"
                           wtitle="<?= $w['title'] ?>">Delete</a></td>
                    </tr>
                  <? } ?>
                  </tbody>

                </table>

              <? } ?>

            <?php }?>
          </div>
        </div>
        <br><br>

      </div>
    </div>
  </section>


<?php echo view($_SESSION['tm'].'uc/footer.php') ?>