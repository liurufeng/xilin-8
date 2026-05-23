<?php echo view($_SESSION['tm'].'uc/header.php') ?>

<div class="col-md-9">

    <div id="rev_slider-wrapper" class="rev_slider_wrapper fullwidthbanner-container" data-alias="classicslider1" style="max-height: 540px !important; height: 500px !important;">
        <div class="tp-banner-container">
            <div class="tp-banner" style="height: 500px !important;">
                <ul>
                    <?php for($i = -5; $i<1; $i++) { ?>
                    <li data-transition="fade" data-slotamount="10" data-masterspeed="2000" data-delay="4000"
                        data-saveperformance="on"  data-title="Xilin Slide">
                        <!-- MAIN IMAGE -->
                        <img src="<?php echo base_url();?>/<?=$_SESSION['tm']?>img/slides/bg<?=$i?>.jpg"  alt="2" data-lazyload="<?php echo base_url();?>/<?=$_SESSION['tm']?>img/slides/bg<?=$i?>.jpg"
                             data-bgposition="center center" data-kenburns="off" data-duration="12000"
                             data-ease="Power0.easeInOut" data-bgfit="115" data-bgfitend="100"
                             data-bgpositionend="center center"
                             >

                        <a href="#announcements" class="pull-left tp-caption lfb tp-resizeme rs-parallaxlevel-0"
                           data-x="20"
                           data-y="20"
                           data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;
                                               scaleY:0;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"

                           data-start="1200"

                           data-splitin="none"
                           data-splitout="none"
                           data-elementdelay="0.1"
                           data-endelementdelay="0.1"
                           data-linktoslide="next"
                           style="z-index: 12; max-width: 200px; max-height: 30px; white-space: nowrap;padding:13px 25px;
                                               color: #7d1a1ae6;text-transform: uppercase;
                                               border: none; background:rgb(202, 186, 186);
                                               font-size: 13px; letter-spacing: 3px;
                                               font-family: Montserrat; border-radius: 30px;
                                               display: table; transition: .4s;"><b>Announcements</b></a>
                    </li>
                    <?php } ?>

                </ul>
                <div class="tp-bannertimer"></div>
            </div>
        </div>
    </div><!-- END REVOLUTION SLIDER -->

    <div class="about_widget widget" id="announcements">
        <div class="heading1">
            <h2><a href="#">Announcement</a></h2>
        </div><!-- heading -->

      <?php if ($announcements && isset($announcements[0])) { ?>
          <div class="card-body">
              <h5><?= $announcements[0]['lastupdate'] ?></h5>
              <p class="card-text"><?= html_entity_decode($announcements[0]['body']) ?></p>
          </div>
      <?php } ?>

        <br>
        <h3><a href="https://drive.google.com/file/d/1pgOJ_x7v_Pbg2DsuDnJ7IYVgEIKLPbgd/view?usp=sharing" target="_blank" style="text-decoration: underline;">
                <b>New family registration step by step guide (chinese version)</b></a></h3>
        <br>
        <h5>Welcome to listen to our Xilin school song - 希望之林</h5>
        <audio controls>
            <source src="https://xilinnschinese.org/uploadfiles/misc_info/xilin.mp3" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>

    </div>
</div>
</div>
</div>
</section>

<?php echo view($_SESSION['tm'].'uc/footer.php') ?>
