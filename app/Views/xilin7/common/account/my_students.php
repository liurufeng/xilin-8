<?php echo view($_SESSION['tm'].'uc/header.php') ?>

<div class="col-md-9 container-wrapper">

    <div class="row">
      <?php echo view($_SESSION['tm'].'uc/account_header.php') ?>
        <div style="clear:both;"></div>
      <?php if (session()->getFlashdata('success_msg')) { ?>
        <div class="row success-flash account-top"><b><?= session()->flashdata('success_msg'); ?></b></div>
      <?php } ?>
      <?php if (isset($address_error) && $address_error) { ?>
        <script>
            var msg = 'MAKE SURE TO UPDATE THE ADDRESS, CITY, ZIP AND STATE DATA!';
            var header_text = 'PLEASE COMPLETE YOUR ADDRESS INFORMATION';
            var redirect_to = 'return';
            var elem1 = "<div class='container-fluid modal-alert'><div class='row'><div class='col-md-12'><div class='modal fade in' id='modal-container' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' style='display: block; padding-right: 17px;'><div class='modal-dialog'><div class='modal-content'><div class='modal-header'><button type='button' class='close close-alert1' data-dismiss='modal' aria-hidden='true'> × </button><h3 class='modal-title' id='myModalLabel'>"
                    + header_text + "</h3></div><div class='modal-body'><h4>"
                    + msg + "</h4></div><div class='modal-footer'><button type='button' class='btn btn-info close-alert1' data-dismiss='modal'>Close</button></div></div></div></div></div></div></div>";
            $(".container-wrapper").first().after(elem1);

            $(".close-alert1").on("click", function () {
                $('.modal-alert').hide();
                location.href = '/account';
                return false;
            });
        </script>
      <?php } ?>
        <div class="account-top Profile" style="margin-left: 16px;">

          <?php if ($usertype === 1) { ?>

            <div class="row account-table table-responsive">
                <table class="table table-hover table-bordered fixed-table-layout">
                    <thead>
                    <tr class="info">
                        <th>
                            English Name
                        </th>
                        <th>
                            Chinese Name
                        </th>
                        <th>
                            Birthday
                        </th>
                        <th>
                            Gender
                        </th>
                        <th>
                            Race
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($students as $student) { ?>

                    <tr id="ms-<?=$student['student_id']?>">
                        <td>
                          <?= $student['en_name'] ?>
                        </td>
                        <td>
                          <?= $student['cn_name'] ?>
                        </td>
                        <td>
                          <?= $student['birthday'] ?>
                        </td>
                        <td>
                          <?= $student['gender'] ?>
                        </td>
                        <td>
                          <?= $student['race'] ?>
                        </td>
                        <td>
                            <a href="/register_class?stdid=<?= $student['student_id'] ?>"
                               class="btn btn-primary btn-sm">Register</a>
                            <a href="" class="btn btn-info btn-sm edit_student" stdid="<?= $student['student_id'] ?>"
                               sname="<?= $student['en_name'] ?>"
                               cname="<?= $student['cn_name'] ?>" bday="<?= $student['birthday'] ?>"
                               gender="<?= $student['gender'] ?>" race="<?= $student['race'] ?>"
                                    >Edit</a>
                            <a href="" class="btn btn-danger btn-sm remove_student" stdid="<?= $student['student_id']
                              ?>"
                               sname="<?= $student['en_name'] ?>">Remove</a>
                        </td>
                    </tr>

                      <?php } ?>
                    </tbody>
                </table>
            </div>
          <?php } ?>
        </div>
    </div>
    <br><br>

    <div class="row account-table">
        <button class="btn btn-primary btn-lg" id="add_new_student">Add A New Student</button>
    </div>
    <br><br>
</div>

</div>
</div>
</section>


<?php echo view($_SESSION['tm'].'uc/footer.php') ?>