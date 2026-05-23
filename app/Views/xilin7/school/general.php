<?php echo view($_SESSION['tm'].'uc/header.php') ?>
    <div class="col-md-9">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <?php if (session()->get('error')) { ?>
                    <div class='alert alert-dismissable alert-success'>
                    <?= session()->get('error');?>
                    </div>
                    <?php } ?>
                    <div class="heading4">
                        <h2>CONTACT US</h2>

                    </div>
                    <div class="contact-page-sec">
                        <div class="row">
                            <!--<div class="col-md-6 column">
                                <div class="contact-form">
                                    <form method="POST" id="contactForm" name="contactForm" action="/school/contact">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <i class="fa fa-user"></i>
                                                <input type="text" name="name" placeholder="Name" value="<?/*=session()->flashdata('name');*/?>">
                                            </div>
                                            <div class="col-md-12">
                                                <i class="fa fa-at"></i>
                                                <input type="text" name="email" placeholder="Email" value="<?/*=session()->flashdata('email');*/?>">
                                            </div>
                                            <div class="col-md-12">
                                                <i class="fa fa-pencil"></i>
                                                <textarea name="msg" placeholder="Message"><?/*=session()->flashdata('msg');*/?></textarea>
                                            </div>
                                            <div class="col-md-12">
                                                <button class="flat-btn" type="submit">SEND NOW</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>-->
                            <div class="col-md-12 column">
                                <div class="contact-details">
                                    <div class="contact-infos">
                                        <ul>
                                            <li>
                                                <span><i class="fa fa-map"></i>Class Address</span>
                                                <p>7701 N. Lincoln Avenue
                                                    <br>Skokie, IL 60077
                                                </p>

                                            </li>
                                            <li>
                                                <span><i class="fa fa-envelope"></i> Mailing Address</span>
                                                <p>Xilin NS Chinese School
                                                    <br>
                                                    4957 Oakton St, Suite 292
                                                    <br>Skokie, IL 60077
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="contact-infos">
                                        <ul>
                                            <li>
                                                <span><i class="fa fa-at"></i> Email</span>
                                                <p>ec@xilinnschinese.org</p>
                                                <br><br>
                                            </li>
                                        </ul>
                                    </div>

                                    <!--<ul class="social-btns">
                                        <li><a title="" href="https://www.facebook.com/xilinns/" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                    </ul>-->
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d11856.105312073598!2d-87.7494991!3d42.0211676!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x63ff25c8f8ae3b1e!2sXilin%20Northshore%20Chinese%20school!5e0!3m2!1sen!2sus!4v1595539092247!5m2!1sen!2sus" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    </div>
    </section>

<?php echo view($_SESSION['tm'].'uc/footer.php') ?>