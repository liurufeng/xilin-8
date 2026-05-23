<div class="row">
  <div class="col-md-12" style="font-size: 18px;">
      Please mention Xilin NS Chinese School when you call or visit our sponsors!<br/>
      访问时请说明来自希林芝北中文学校
  </div>
</div>
<br>
    <?php
    $db = db_connect();
    $sname_arr = array();
      $sql = "select s.*, sl.name as level_name
          from sponsor s
          join sponsor_level sl on s.level_id = sl.level_id
          where s.status = 1 and active > 0
          order by sl.show_order asc,s.show_order asc";
    $sponsors = $db->query($sql)->getResultArray();
    ?>
    <?php $start = false;
    foreach ($sponsors as $s) {
      if(!in_array($s['level_name'], $sname_arr)) {
        if($start) echo "</div>";
        ?>
        <div class="heading1 row text-center sponsor-level">
            <h2><a href="#"><?=$s['level_name'] ?></a></h2>
        </div>
      <?php

        $sname_arr[] = $s['level_name'];
        echo "<div class='row'>";
        $start = true;

      }
      ?>
          <div class="col-md-5">
          <div align="center" style="font-size:12px;"><a href="<?= $s['link']; ?>" target="_blank"><img class="img-responsive"
                  src="<?= $s['image_path']; ?>" border="0" style="max-width: 280px;"/></a><br/><br/>
          </div>
            <h6><?= $s['note'] ? $s['note'] : '' ?></h6>
          </div>

    <?php }?>
    </div> <!--ending the last sponsor's <div class='row'>-->
  <div class="row" style="font-size: 18px;">
    <hr/>
    Your Position Here!
    <a href="mailto:chairpersonBOD@xilinnschinese.org ">Email us...</a><br/>
  </div>
