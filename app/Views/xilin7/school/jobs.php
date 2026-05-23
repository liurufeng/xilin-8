<?php echo view($_SESSION['tm'].'uc/header.php') ?>

  <section id="mission">
    <div class="container">
      <div class="text-center">
        <p class="wow fadeInUp" data-wow-duration="300ms" data-wow-delay="100ms"><?= $jobs[0]['body'];?></p>
      </div>
    </div>
  </section>

<?php echo view($_SESSION['tm'].'uc/footer.php') ?>