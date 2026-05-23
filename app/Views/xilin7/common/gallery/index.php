<?php echo view($_SESSION['tm'].'uc/header.php') ?>
<section class="title">
  <div class="container">
    <div class="row-fluid">
      <div class="span6">
        <h1>Gallery</h1>
      </div>
      <div class="span6">
        <ul class="breadcrumb pull-right">
          <li><a href="/index">Home</a> <span class="divider">/</span></li>
          <li class="active">Photo Gallery</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<div id="wrap">

  <!-- wrapper -->

  <div id="container">

    <!-- page container -->

    <div class="page" id="gallery">
      <div class="text-center">

      <!-- page gallery -->

      <h3 class="page_title"> </h3>
        <p class="gap"> </p>
      <div class="page_content">

        <p> </p>



        <div class="clear"> </div>

        <div id="works" style="margin-left: auto; margin-right: auto;">

          <!-- works -->

          <a rel="prettyPhoto[gallery]" href="<?php echo base_url(); ?>/<?=$_SESSION['tm']?>images/photos/01.jpg"> <img class="work js" src="<?php echo base_url(); ?>/<?=$_SESSION['tm']?>images/photos/01.jpg" alt="" /> </a>
          <a rel="prettyPhoto[gallery]" href="<?php echo base_url(); ?>/<?=$_SESSION['tm']?>images/photos/03.jpg"> <img class="work css" src="<?php echo base_url(); ?>/<?=$_SESSION['tm']?>images/photos/03.jpg" alt="" /> </a>
          <a rel="prettyPhoto[gallery]" href="<?php echo base_url(); ?>/<?=$_SESSION['tm']?>images/photos/04.jpg"> <img class="work html_php" src="<?php echo base_url(); ?>/<?=$_SESSION['tm']?>images/photos/04.jpg" alt="" /> </a>
          <a rel="prettyPhoto[gallery]" href="<?php echo base_url(); ?>/<?=$_SESSION['tm']?>images/photos/05.jpg"> <img class="work html_php" src="<?php echo base_url(); ?>/<?=$_SESSION['tm']?>images/photos/05.jpg" alt="" /> </a>
          <a rel="prettyPhoto[gallery]" href="<?php echo base_url(); ?>/<?=$_SESSION['tm']?>images/photos/06.jpg"> <img class="work css" src="<?php echo base_url(); ?>/<?=$_SESSION['tm']?>images/photos/06.jpg" alt="" /> </a>
          <a rel="prettyPhoto[gallery]" href="<?php echo base_url(); ?>/<?=$_SESSION['tm']?>images/photos/07.jpg"> <img class="work js" src="<?php echo base_url(); ?>/<?=$_SESSION['tm']?>images/photos/07.jpg" alt="" /> </a>
          <a rel="prettyPhoto[gallery]" href="<?php echo base_url(); ?>/<?=$_SESSION['tm']?>images/photos/08.jpg"> <img class="work html_php" src="<?php echo base_url(); ?>/<?=$_SESSION['tm']?>images/photos/08.jpg" alt="" /> </a>
          <a rel="prettyPhoto[gallery]" href="<?php echo base_url(); ?>/<?=$_SESSION['tm']?>images/photos/09.jpg"> <img class="work js" src="<?php echo base_url(); ?>/<?=$_SESSION['tm']?>images/photos/09.jpg" alt="" /> </a>
          <a rel="prettyPhoto[gallery]" href="<?php echo base_url(); ?>/<?=$_SESSION['tm']?>images/photos/10.jpg"> <img class="work html_php" src="<?php echo base_url(); ?>/<?=$_SESSION['tm']?>images/photos/10.jpg" alt="" /> </a>

        <div class="clear"> </div>

      </div>


    </div>

    </div>

  </div>

</div>
  <p class="gap"> </p>
  <?php echo view($_SESSION['tm'].'uc/footer.php') ?>
