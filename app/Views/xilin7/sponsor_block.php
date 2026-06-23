<style>
    .sponsor-level {
        margin: 30px 0 20px;
    }

    .sponsor-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #fff;
        padding: 15px;
        margin-bottom: 25px;
        height: 260px;               /* Same height for all */
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all .3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,.08);
    }

    .sponsor-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,.15);
    }

    .sponsor-card img {
        max-width: 100%;
        max-height: 200px;           /* Keeps images same visual size */
        object-fit: contain;
    }

    .sponsor-note {
        font-size: 18px;
        text-align: center;
        margin-bottom: 25px;
    }

    .sponsor-footer {
        font-size: 18px;
        text-align: center;
        margin-top: 30px;
    }

    @media (max-width: 768px) {
        .sponsor-card {
            height: 220px;
        }
    }
</style>

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
          <!--<div class="col-md-5">
          <div align="center" style="font-size:12px;"><a href="<?/*= $s['link']; */?>" target="_blank"><img class="img-responsive"
                  src="<?/*= $s['image_path']; */?>" border="0" style="max-width: 280px;"/></a><br/><br/>
          </div>
            <h6><?/*= $s['note'] ? $s['note'] : '' */?></h6>
          </div>-->

        <div class="col-md-4 col-sm-6">
            <div class="sponsor-card">
                <a href="<?= $s['link']; ?>" target="_blank">
                    <img src="<?= $s['image_path']; ?>"
                         class="img-responsive"
                         alt="<?= $s['note'] ? $s['note'] : '' ?>l">
                </a>
            </div>
        </div>

    <?php }?>
    </div> <!--ending the last sponsor's <div class='row'>-->
  <div class="row" style="font-size: 18px;">
    <hr/>
    Your Position Here!
    <a href="mailto:chairpersonBOD@xilinnschinese.org ">Email us...</a><br/>
  </div>
