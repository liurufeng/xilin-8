<style>
    .sponsor-item {
        margin-bottom: 25px;
    }

    .sponsor-box {
        padding: 12px;
        border: 1px solid #eee;
        border-radius: 6px;
        background: #fff;
        text-align: center;
        transition: .2s ease;
        min-height: 315px;
    }

    .sponsor-box:hover {
        box-shadow: 0 6px 18px rgba(0,0,0,.12);
        transform: translateY(-2px);
    }

    .sponsor-box img {
        max-width: 100%;
        max-height: 260px;
        display: inline-block;
    }

    .sponsor-note{
        margin-top:10px;
        font-size:13px;
    }

    /* ---------- Preview ---------- */

    #sponsorPreview{
        display:none;
        position:fixed;
        top:20px;
        left:20px;
        z-index:99999;

        background:#fff;
        border:1px solid #ccc;
        border-radius:8px;
        padding:10px;

        box-shadow:0 10px 30px rgba(0,0,0,.35);

        pointer-events:none;
    }

    @media (max-width: 768px) {
        #sponsorPreview {
            display: none !important;
        }
    }

    #sponsorPreview img{
        max-width:500px;
        max-height:500px;
        display:block;
    }
</style>

<div id="sponsorPreview">
    <img src="">
</div>

<div class="row">
    <div class="col-md-12" style="font-size:18px;">
        Please mention Xilin NS Chinese School when you call or visit our sponsors!<br/>
        访问时请说明来自希林芝北中文学校
    </div>
</div>

<br>

<?php

$sponsors = session()->get('sponsors');

if (empty($sponsors)) {
    $db = db_connect();

    $sql = "
SELECT s.*, sl.name level_name
FROM sponsor s
JOIN sponsor_level sl
ON s.level_id=sl.level_id
WHERE s.status=1
AND active>0
ORDER BY sl.show_order,RAND()
";

    $sponsors = $db->query($sql)->getResultArray();
    session()->set('sponsors', $sponsors);
}

$currentLevel=null;

foreach($sponsors as $s):

if($currentLevel!=$s['level_name']):

if($currentLevel!==null){
  echo "</div>";
}

?>

<div class="sponsor-level text-center" style="margin:25px 0;">
    <h2><a href="#"><?= esc($s['level_name']) ?></a></h2>
</div>

<div class="row">

  <?php

  $currentLevel=$s['level_name'];

  endif;

  ?>

    <div class="col-md-6 col-sm-6 sponsor-item">

        <div class="sponsor-box">

            <a class="sponsor-link"
               href="<?= esc($s['link'], 'attr') ?>"
               target="_blank">

                <img
                        src="<?= esc($s['image_path'], 'attr') ?>"
                        class="img-responsive sponsor-logo"
                        alt="<?= esc($s['note'], 'attr') ?>"
                        data-large="<?= esc($s['image_path'], 'attr') ?>">

            </a>

            <div class="sponsor-note">
              <?= esc($s['note']) ?>
            </div>

        </div>

    </div>

  <?php endforeach; ?>

</div>

<br>

<div class="row" style="font-size:18px;">
    <hr>

    Your Position Here!

    <a href="mailto:chairpersonBOD@xilinnschinese.org">
        Email us...
    </a>

</div>

<script>
    $(function () {

        // Disable preview on mobile/tablets
        if ($(window).width() <= 768) {
            return;
        }

        $(".sponsor-link")

            .mouseenter(function () {

                $("#sponsorPreview img").attr(
                    "src",
                    $(this).find("img").data("large")
                );

                $("#sponsorPreview")
                    .stop(true, true)
                    .fadeIn(150);

            })

            .mouseleave(function () {

                $("#sponsorPreview")
                    .stop(true, true)
                    .fadeOut(150);

            });

    });

</script>