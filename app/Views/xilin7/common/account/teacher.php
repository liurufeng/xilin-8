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
      <div class="account-top row">
        <div style="clear:both;"></div>
        <div class="Profile">
          <?php if($usertype == 2){?>
            <p>
              <form action="<?php echo '/account/index' ?>">
                Semester: <select id="semester_id" name="semester_id" onchange="submit();">
                  <?php foreach($semesters as $semester) {?>
                    <option value="<?=$semester['semester_id']?>" <?php if($semester['semester_id'] == session()->get('semester_id')) echo 'selected'; ?> >
                      <?=$semester['semester_year']?> <?=$semester['semester_name']?>
                    </option>
                  <?php } ?>
                </select>
              </form>
            </p>


            <?php foreach($classes as $ck => $c) {?>
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

              <tr>
                <td height="30">Syllabus Link:</td>
                <td style="text-align:left;" colspan="5">
                  <input name="syl_link" type="text" id="syl_link_<?=$c['class_id']?>" size="30" style="width:500px" value="<?php echo $c['syl_link']?>" />
                  <input type="submit" value=" Update " name="Save" class="btn btn-primary btn-xs" style="position: relative; top: -2px; cursor: pointer"
                    onclick="updateSL(<?=$c['class_id']?>);">
                </td>
              </tr>

              <?php if(count($students[$ck]) > 0){?>
                <tr><th colspan="6">
                    Students and Parent Info
                </th></tr>

              <?php } ?>
              <?php
              $counter = 0;
              foreach($students[$ck] as $sk => $s) {
                if(!empty($s['student_id'])) {
                ?>

                <tr style="border: 0;">
                  <td colspan="6" style="border: 0;">
                    <?= ++$counter?>. <?=$s['en_name']?> <?=empty($s['cn_name'])?'':'('.$s['cn_name'].')'?>,
                    <?=$s['birthday']?>, <?=$s['gender']?>,
                    <?=$s['primary_relationship']?>: <?=$s['primary_en_name']?>,
                    <?=$s['email']?>, <?= (!empty($s['alter_contact_email']) && $s['alter_contact_email'] != $s['email']) ? $s['alter_contact_email'].', ' : '' ?>
                    <?=$s['primary_phone']?>,
                    <?=$s['address']?> <?=$s['city']?> <?=$s['state']?> <?=$s['zip']?>
                  </td>
                </tr>
              <?php } } ?>
            </tbody>
                <tr bgcolor="#FFFFFF" >
                  <td align="right">* Emails of the class</td>
                  <td height="45" colspan="5">
                    <textarea name="emails_<?=$ck?>" id="emails_<?=$ck?>" style="width: 100%;" rows="4" required=""><?=rtrim($parent_emails[$ck],',')?></textarea>
                  </td>
                </tr>
                <tr>
                  <td colspan="6" style="font-size: 15px; font-weight: bold; border: 0; border-bottom: 2px solid #000000">

                  <a class="btn btn-block btn-primary" href="https://mail.google.com/mail/?view=cm&fs=1&to=<?=$teacher['email']?>&bcc=<?=rtrim($parent_emails[$ck],',')?>" target="_blank"> Email the class parents </a></td>

                </tr>

              </table>

            <?php } ?>

          <?php }?>
        </div>
      </div>
    </div>

    </div>
    </div>
    </section>
<?php } else { ?>

<?php }?>

<script type="text/javascript">
  function updateSL(cid){
    var sl = $("#syl_link_"+cid).val();

    $("#ajax_loader").show();
    $.post('/teachers/update_sl',
      {
        class_id: cid,
        syl_link: sl
      },
      function(result){
          result = JSON.parse(result);
        if(result.success) {
          $("#ajax_loader").hide();
          alert('Syllabus Link updated successfully!');
          return false;
        }
        else {
          $("#ajax_loader").hide();
          alert('Failed to update Syllabus Link, please refresh and try again!');
          return false;
        }
      });
  }
</script>

<?php echo view($_SESSION['tm'].'uc/footer.php')?>