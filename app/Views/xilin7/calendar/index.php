<?php echo view($_SESSION['tm'].'uc/header.php') ?>

<div class="col-md-9 main-content">
  <?php foreach ($semester as $s) {
  if($s['show_calendar']) {
    ?>
      <div class="row" style="margin: 1em;">
        <div class="col-md-12">
        <a><h3 class="section-title text-center"><?= esc($s['semester_name'] .' '. $s['semester_year']); ?> Calendar</h3></a>
          </div>

        <div class="col-md-12">
          <table class="table table-hover table-bordered fixed-table-layout table-striped">
            <thead>
            <tr class="cal-header">
                <th>
                    Date
                </th>
                <th>
                    Session
                </th>
                <th>
                    Notes
                </th>
            </tr>
            </thead>
            <tbody>
              <?php foreach($calendars as $c) {
              if($c['semester_id'] == $s['semester_id']) {
                ?>
              <tr>
                  <td width="200">
                    <?= esc($c['date']) ?>
                  </td>
                  <td width="100">
                    <?= esc($c['session']) ?>
                  </td>
                  <td width="300">
                    <?= esc($c['note']) ?>
                  </td>
              </tr>
                <?php } } ?>
            </tbody>
          </table>
        </div>

      </div>
    <?php } } ?>
</div>

    <br>
    <div class="">
        <div class="about_widget widget">
            <div class="heading1">
                <h2><a href="#">Our Sponsors</a></h2>
            </div><!-- heading -->
        </div>
      <?php echo view($_SESSION['tm'].'sponsor_block.php') ?>
    </div>

</div>
</div>
</section>

<?php echo view($_SESSION['tm'].'uc/footer.php') ?>