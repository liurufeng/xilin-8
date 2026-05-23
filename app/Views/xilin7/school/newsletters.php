<?php echo view($_SESSION['tm'].'uc/header.php') ?>
<?php $i = 1;
$yearly = $with_icon = array();
foreach ($newsletters as $item) {
  $year_month = explode(' ', $item['name']);
  $year = isset($year_month[1]) ? $year_month[1] : 'Special Edition '.''.$i;
  $month = isset($year_month[0]) ? $year_month[0] : 'Special Edition';

  if($i<3) {
      $with_icon1[] = $item;
  } elseif($i<5) {
    $with_icon2[] = $item;
  } else {
      $yearly[$year][$month] = $item;
  }
  $i++;
} ?>
<div class="col-md-9 main-content">
    <div class="about_widget widget">
        <div class="heading1">
            <h2><a href="#">News Letters</a></h2>
        </div><!-- heading -->
      </div>

    <div class="single-post-sec">
        <div class="blog-post vehicul-post">

            <div class="vehiculs-sec">
                <div class="vehiculs-list">

                    <div class="vehiculs-content">
                        <div class="row">
                        <?php foreach($with_icon1 as $month => $nl) { ?>
                            <div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
                                <div class="vehiculs-box">
                                    <div class="vehiculs-thumb">
                                        <img src="<?=$nl['img_url'] ?>" alt=""></a>
                                        <span class="spn-status"> <?=$nl['name'] ?></span>
                                        <a class="proeprty-sh-more" href="<?php echo $nl['url']; ?>" target="_blank"><i class="fa fa-angle-double-right"> </i><i class="fa fa-angle-double-right"> </i></a>
                                    </div>
                                </div>
                            </div>
                        <?php  } ?>
                        </div>

                        <div class="row">
                          <?php foreach($with_icon2 as $month => $nl) { ?>
                              <div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
                                  <div class="vehiculs-box">
                                      <div class="vehiculs-thumb">
                                          <img src="<?=$nl['img_url'] ?>" alt=""></a>
                                          <span class="spn-status"> <?=$nl['name'] ?></span>
                                          <a class="proeprty-sh-more" href="<?php echo $nl['url']; ?>" target="_blank"><i class="fa fa-angle-double-right"> </i><i class="fa fa-angle-double-right"> </i></a>
                                      </div>
                                  </div>
                              </div>
                          <?php  } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

      <div class="faqs-accordian-sec">

        <div id="toggle-widget" class="experties">
            <div><br><br>
                <h2>Archived</h2>
                <br><br>
            </div>
            <?php foreach ($yearly as $year => $ym) { ?>

                <h2 <!--class="--><?/*= $i < 1 ? 'active' : ''*/?>">
                    <span style="font-size: 1.5em; color: #bd2b2b;" class="fa fa-angle-right">
                  <?=$year?></span></h2>

                <div class="content" style="display: none;">
                    <ul>
                    <?php foreach($ym as $month => $nl) { ?>
                      <li><a href="<?php echo $nl['url']; ?>" target="_blank" style="font-size: 1.2em; color:
                      #bd2b2b;">
                            <?=$nl['name'] ?>
                              <?php if($nl['img_url']) { ?>
                                  <img src="<?=$nl['img_url'] ?>" style="margin-left: 35px; height: 200px; ">
                              <?php } ?>
                          </a>
                      </li>
                    <?php } ?>
                    </ul>
                </div>
            <?php } ?>

        </div>
      </div>
</div>


</div>
</div>
</section>

<?php echo view($_SESSION['tm'].'uc/footer.php') ?>