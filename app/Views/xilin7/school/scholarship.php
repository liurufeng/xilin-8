<?php echo view($_SESSION['tm'].'uc/header.php') ?>

<div class="col-md-9 main-content">
    <div class="row">
        <div class="col-md-12">
            <div class="about_widget widget">
                <div class="heading1">
                    <h2><a href="#">Scholarship</a></h2>
                </div><!-- heading -->
            </div>
          <?= $scholarship[0]['body'];?>
      </div>
  </div>
</div>

</div>
</div>
</section>

<?php echo view($_SESSION['tm'].'uc/footer.php') ?>