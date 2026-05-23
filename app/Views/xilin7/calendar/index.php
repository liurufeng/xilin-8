<?php echo view($_SESSION['tm'].'uc/header.php') ?>

<div class="col-md-9 main-content">
  <?php foreach ($semester as $s) {
  if($s['show_calendar']) {
    ?>
      <div class="row" style="margin: 1em;">
        <div class="col-md-12">
        <a><h3 class="section-title text-center"><?= $s['semester_name'] .' '. $s['semester_year']; ?> Calendar</h3></a>
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
                    <?= $c['date'] ?>
                  </td>
                  <td width="100">
                    <?= $c['session'] ?>
                  </td>
                  <td width="300">
                    <?= $c['note'] ?>
                  </td>
              </tr>
                <?php } } ?>
            </tbody>
          </table>
        </div>

      </div>
    <?php } } ?>
</div>

</div>
</div>
</section>

<?php echo view($_SESSION['tm'].'uc/footer.php') ?>