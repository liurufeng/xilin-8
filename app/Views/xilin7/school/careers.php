<?php echo view($_SESSION['tm'].'uc/header.php') ?>
<?php if (session()->getFlashdata('success') || session()->getFlashdata('error')) { ?>
    <script>
        $(function () {
            $('#careerMessageModal').modal('show');
        });
    </script>
<?php } ?>

    <div class="container" style="margin-top:20px;">

        <div class="row">

            <!-- LEFT MAIN CONTENT -->
            <div class="col-md-8">

                <!-- PAGE HEADER -->
                <div class="panel panel-default">

                    <div class="panel-heading" style="background-color: #7d1a1ae6;">
                        <h3 class="panel-title" style="color: white">
                            <span class="glyphicon glyphicon-education" style="color: white"></span>
                            Careers - Join Our Teaching Team
                        </h3>
                    </div>

                    <div class="panel-body">

                        <p style="font-size:14px; line-height:1.8;">
                            Xilin Northshore Chinese School is looking for passionate teachers
                            in Chinese language, Mathematics, Science, and enrichment programs.
                            We welcome educators who enjoy working with children and contributing
                            to a strong academic and cultural community.
                        </p>

                    </div>

                </div>

                <!-- ALERTS -->
              <?php if(session()->getFlashdata('success')): ?>
                  <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                  </div>
              <?php endif; ?>

              <?php if(session()->getFlashdata('error')): ?>
                  <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                  </div>
              <?php endif; ?>

                <!-- FORM -->
                <form method="post"
                      action="/careers/applyjob"
                      enctype="multipart/form-data">

                    <div class="panel panel-default">

                        <div class="panel-heading" style="background-color: #7d1a1ae6;">
                            <h4 class="panel-title" style="color: white">
                                <span class="glyphicon glyphicon-list-alt" style="color: white"></span>
                                Application Form
                            </h4>
                        </div>

                        <div class="panel-body">

                            <div class="row">

                                <div class="col-md-6">
                                    <label>First Name *</label>
                                    <input type="text" name="first_name" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label>Last Name *</label>
                                    <input type="text" name="last_name" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label>Email *</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label>Phone</label>
                                    <input type="text" name="phone" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label>City</label>
                                    <input type="text" name="city" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label>State</label>
                                    <input type="text" name="state" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label>Education</label>
                                    <select name="education" class="form-control">
                                        <option>Bachelor's</option>
                                        <option>Master's</option>
                                        <option>PhD</option>
                                        <option>Other</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label>Experience</label>
                                    <select name="experience" class="form-control">
                                        <option>None</option>
                                        <option>1–3 years</option>
                                        <option>3–5 years</option>
                                        <option>5–10 years</option>
                                        <option>10+ years</option>
                                    </select>
                                </div>

                                <!-- SUBJECTS -->
                                <div class="col-md-12" style="margin-top:15px;">
                                    <label>
                                        <span class="glyphicon glyphicon-book"></span>
                                        Subjects You Can Teach
                                    </label>

                                    <div class="row" style="margin-top:10px;">

                                        <div class="col-md-4">
                                            <label><input type="checkbox" name="subjects[]" value="Chinese"> Chinese</label><br>
                                            <label><input type="checkbox" name="subjects[]" value="Math"> Math</label><br>
                                            <label><input type="checkbox" name="subjects[]" value="Science"> Science</label><br>
                                            <label><input type="checkbox" name="subjects[]" value="English"> English</label>
                                        </div>

                                        <div class="col-md-4">
                                            <label><input type="checkbox" name="subjects[]" value="Art"> Art</label><br>
                                            <label><input type="checkbox" name="subjects[]" value="Music"> Music</label><br>
                                            <label><input type="checkbox" name="subjects[]" value="Programming"> Programming</label><br>
                                            <label><input type="checkbox" name="subjects[]" value="Robotics"> Robotics</label>
                                        </div>

                                        <div class="col-md-4">
                                            <label><input type="checkbox" name="subjects[]" value="Public Speaking"> Public Speaking</label><br>
                                            <label><input type="checkbox" name="subjects[]" value="Chess"> Chess</label><br>
                                            <label><input type="checkbox" name="subjects[]" value="Other"> Other</label>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-6" style="margin-top:15px;">
                                    <label>Available Start Date</label>
                                    <input type="date" name="available_date" class="form-control">
                                </div>

                                <div class="col-md-6" style="margin-top:15px;">
                                    <label>Resume Upload</label>
                                    <input type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx" required>
                                </div>

                                <div class="col-md-12" style="margin-top:15px;">
                                    <label>About You</label>
                                    <textarea name="message" rows="5" class="form-control"
                                              placeholder="Tell us about your teaching experience and interest in joining Xilin."></textarea>
                                </div>

                                <div class="col-md-12 text-center" style="margin-top:20px;margin-bottom:20px;">
                                    <button type="submit" class="btn btn-primary btn-lg" style="background-color: #7d1a1ae6;>
                                        <span class="glyphicon glyphicon-send"></span>
                                        Submit Application
                                    </button>
                                </div>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

            <!-- RIGHT SIDEBAR -->
            <div class="col-md-6 col-md-offset-3">

                <!-- WHY TEACH -->
                <div class="panel panel-default">

                    <div class="panel-heading" style="background-color: #7d1a1ae6;">
                        <h4 class="panel-title" style="color: white">
                            <span class="glyphicon glyphicon-star" style="color: white"></span>
                            Why Teach at Xilin?
                        </h4>
                    </div>

                    <div class="panel-body">

                        <ul class="list-unstyled" style="line-height:2;">
                            <li>✔ Weekend schedule</li>
                            <li>✔ Small class sizes</li>
                            <li>✔ Friendly community</li>
                            <li>✔ Competitive compensation</li>
                            <li>✔ Curriculum support</li>
                            <li>✔ Enrichment opportunities</li>
                            <li>✔ Make a meaningful impact</li>
                        </ul>

                    </div>

                </div>

                <!-- CONTACT -->
                <div class="panel panel-default">

                    <div class="panel-heading" style="background-color: #7d1a1ae6;">
                        <h4 class="panel-title" style="color: white">
                            <span class="glyphicon glyphicon-envelope" style="color: white"></span>
                            Contact Us
                        </h4>
                    </div>

                    <div class="panel-body">

                        <p style="margin-bottom:8px;">
                            <strong>Email:</strong><br>
                            ec@xilinnschinese.org
                        </p>

                        <p style="margin-bottom:8px;">
                            <strong>Response Time:</strong><br>
                            2–3 business days
                        </p>

                        <p style="margin-bottom:0;">
                            <strong>Location:</strong><br>
                            Northshore Area, IL
                        </p>

                    </div>

                </div>

            </div>

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

    <!-- Success / Error Modal -->
    <div class="modal fade" id="careerMessageModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">
                      <?php if (session()->getFlashdata('success')) { ?>
                          <span class="text-success">
                            <span class="glyphicon glyphicon-ok-circle"></span>
                            Application Submitted
                        </span>
                      <?php } else { ?>
                          <span class="text-danger">
                            <span class="glyphicon glyphicon-remove-circle"></span>
                            Submission Failed: <?= session()->getFlashdata('error'); ?>
                        </span>
                      <?php } ?>
                    </h4>
                </div>

                <div class="modal-body">

                  <?php if (session()->getFlashdata('success')) { ?>
                      <p><?= session()->getFlashdata('success'); ?></p>
                  <?php } ?>

                  <?php if (session()->getFlashdata('error')) { ?>
                      <p><?= session()->getFlashdata('error'); ?></p>
                  <?php } ?>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal">
                        OK
                    </button>
                </div>

            </div>
        </div>
    </div>

<?php echo view($_SESSION['tm'].'uc/footer.php') ?>