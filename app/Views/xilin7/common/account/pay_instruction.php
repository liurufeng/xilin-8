<?php echo view($_SESSION['tm'].'uc/header.php') ?>

  <section>
  <div class="container-wrapper">
  <div class="container">
  <div class="row">
    <?php echo view($_SESSION['tm'].'uc/account_header.php') ?>
  <div style="clear:both;"></div>

  <div class="Profile">



          <div class="Section1" style="padding: 0 15px;">
            <?= $instruction[0]['body'];?>
          </div>



  </div>


<?php echo view($_SESSION['tm'].'uc/footer.php') ?>
