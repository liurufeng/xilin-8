<!DOCTYPE html>
<html lang="en">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xilin Northshore Chinese School</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <link rel="stylesheet" href="<?php echo base_url();?>/<?=$_SESSION['tm']?>fonts/font-awesome/css/font-awesome.min.css" type="text/css" /><!-- Icons -->
    <link rel="stylesheet" href="<?php echo base_url();?>/<?=$_SESSION['tm']?>fonts/themify-icons/themify-icons.css" type="text/css" /><!-- Icons -->


    <link rel="stylesheet" href="<?php echo base_url();?>/<?=$_SESSION['tm']?>css/style.css?10" type="text/css" /><!-- Style -->
    <link rel="stylesheet" href="<?php echo base_url();?>/<?=$_SESSION['tm']?>css/responsive.css?1" type="text/css" /><!-- Responsive -->
    <link rel="stylesheet" href="<?php echo base_url();?>/<?=$_SESSION['tm']?>css/colors.css" type="text/css" /><!-- color -->

    <!-- REVOLUTION STYLE SHEETS -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/<?=$_SESSION['tm']?>js/rs-plugin/css/settings.css">

    <link rel="shortcut icon" href="<?php echo base_url();?>/<?=$_SESSION['tm']?>img/logo.png">
    <style type="text/css">
        body{margin-top:50px;}
        .glyphicon { margin-right:10px; }
        .panel-body { padding:0px; }
        .panel-body table tr td { padding-left: 15px }
        .panel-body .table {margin-bottom: 0px; }    </style>


    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>

    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="787488937189-p0prt8vpmf7vrg3c51681u1d7q6kchv2.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js?onload=onLoadCallback" async defer></script>

    <link rel="shortcut icon" href="<?php echo base_url();?>/<?=$_SESSION['tm']?>img/logo.png">

    <script src="<?php echo base_url();?>/common/js/common.js"></script>
    <!--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Ma+Shan+Zheng">-->
</head>

<body style="background-color: #31f5080d;">

<!-- /.preloader -->
<div id="preloader"></div>
<div class="theme-layout">

    <div class="account-popup-sec">

        <div class="account-popup-area">
            <div class="account-popup">
                <div class="row">
                    <div class="col-md-6">
                        <div class="account-user">
                            <div class="logo">
                                <a href="#" title="">
                                    <span>希林芝北中文学校</span><br>
                                    <strong>Xilin Northshore Chinese School</strong>
                                </a>
                            </div><!-- LOGO -->

                            <form method="POST" id="loginForm" name="loginForm" action="/login/check_login">
                                <h1>PARENT Login</h1>
                                -- <a href="https://docs.google.com/document/d/1xaEzueAX_4lzrsJsU89kFI3XImAQAz-ZeAMjcVRsSmQ/edit" target="_blank" style="color: #7d1a1ae6;">Registration Guide</a> --
                                <div class="field">
                                    <input type="text" id="email_login" name="email" placeholder="Username" />
                                </div>
                                <div class="field">
                                    <input type="password" name="password" placeholder="Password" />
                                </div>
                                <div class="field">
                                    <input type="submit" value="Login" class="flat-btn" />
                                    <button id="findpass" class="btn btn-md btn-warning" style="height: 51px;">Find password</button>
                                </div>
                            </form>
                            <i>OR</i>
                            <span>TEACHER LOGIN WITH</span>
                            <ul class="social-btns">
                                <li><a href="/signin/index/teacher" title=""><i class="fa fa-google-plus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="registration-sec">
                            <h1>SIGNUP Form</h1>
                            <form method="POST" name="signupForm"  action="/register/registerinfo?REGISTERTYPE=Parents" id="form1">
                                <div class="field">
                                    <input type="email" value="" name="email" placeholder="*Primary Contact Email" />
                                </div>
                                <div class="field">
                                    <input type="email" value="" name="re_email" placeholder="*Re-type Email" />
                                </div>
                                <div class="field">
                                    <input type="password" name="passwd" placeholder="*Type Password" maxlength="20"/>
                                </div>
                                <div class="field-half">
                                    <input type="text" value="" name="primary_en_name" placeholder="*English Name" />
                                </div>
                                <div class="field-half">
                                    <input type="text" value="" name="primary_cn_name" placeholder="Chinese Name" />
                                </div>
                                <div class="field-half">
                                    <input type="text" id="prim-phone" name="primary_phone" placeholder="*Phone" />
                                </div>
                                <div class="field-half">
                                    <input type="text" value="" name="primary_relationship" placeholder="*Relationship" />
                                </div>
                                <div class="field">
                                    <input type="text" value="" name="alter_contact_email" placeholder="Alternative Contact Email" />
                                </div>
                                <div class="field-half">
                                    <input type="text" value="" name="alter_en_name" placeholder="English Name" />
                                </div>
                                <div class="field-half">
                                    <input type="text" value="" name="alter_cn_name" placeholder="Chinese Name" />
                                </div>
                                <div class="field-half">
                                    <input type="text" id="alt-phone" name="alter_phone" placeholder="Phone" />
                                </div>
                                <div class="field-half">
                                    <input type="text" value="" name="alter_relationship" placeholder="Relationship" />
                                </div>
                                <div class="field-half">
                                    <input type="text" value="" name="address" placeholder="*Street Address" />
                                </div>
                                <div class="field-half">
                                    <input type="text" value="" name="city" placeholder="*City" />
                                </div>
                                <div class="field-half">
                                    <select name="state">
                                        <option value="Alabama">Alabama</option>
                                        <option value="Alaska">Alaska</option>
                                        <option value="Arizona">Arizona</option>
                                        <option value="Arkansas">Arkansas</option>
                                        <option value="California">California</option>
                                        <option value="Colorado">Colorado</option>
                                        <option value="Connecticut">Connecticut</option>
                                        <option value="Delaware">Delaware</option>
                                        <option value="District of Columbia">District of Columbia</option>
                                        <option value="Florida">Florida</option>
                                        <option value="Georgia">Georgia</option>
                                        <option value="Hawaii">Hawaii</option>
                                        <option value="Idaho">Idaho</option>
                                        <option value="Illinois" selected="">Illinois</option>
                                        <option value="Indiana">Indiana</option>
                                        <option value="Iowa">Iowa</option>
                                        <option value="Kansas">Kansas</option>
                                        <option value="Kentucky">Kentucky</option>
                                        <option value="Louisiana">Louisiana</option>
                                        <option value="Maine">Maine</option>
                                        <option value="Maryland">Maryland</option>
                                        <option value="Massachusetts">Massachusetts</option>
                                        <option value="Michigan">Michigan</option>
                                        <option value="Minnesota">Minnesota</option>
                                        <option value="Mississippi">Mississippi</option>
                                        <option value="Missouri">Missouri</option>
                                        <option value="Montana">Montana</option>
                                        <option value="Nebraska">Nebraska</option>
                                        <option value="Nevada">Nevada</option>
                                        <option value="New Hampshire">New Hampshire</option>
                                        <option value="New Jersey">New Jersey</option>
                                        <option value="New Mexico">New Mexico</option>
                                        <option value="New York">New York</option>
                                        <option value="North Carolina">North Carolina</option>
                                        <option value="North Dakota">North Dakota</option>
                                        <option value="Ohio">Ohio</option>
                                        <option value="Oklahoma">Oklahoma</option>
                                        <option value="Oregon">Oregon</option>
                                        <option value="Pennsylvania">Pennsylvania</option>
                                        <option value="Rhode Island">Rhode Island</option>
                                        <option value="South Carolina">South Carolina</option>
                                        <option value="South Dakota">South Dakota</option>
                                        <option value="Tennessee">Tennessee</option>
                                        <option value="Texas">Texas</option>
                                        <option value="Utah">Utah</option>
                                        <option value="Vermont">Vermont</option>
                                        <option value="Virginia">Virginia</option>
                                        <option value="Washington">Washington</option>
                                        <option value="West Virginia">West Virginia</option>
                                        <option value="Wisconsin">Wisconsin</option>
                                        <option value="Wyoming">Wyoming</option>
                                    </select>
                                </div>
                                <div class="field-half">
                                    <input type="text" value="" name="zip" placeholder="*Zip Code" />
                                </div>
                                <div class="field">
                                    <input type="text" value="" name="heard_from" placeholder="Where did you hear us?" />
                                </div>
                                <div class="field">
                                    <input type="number" value="" name="referrer_id" placeholder="Referrer ID Number" onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
                                </div>

                                <input type="submit" value="Sign Up" class="flat-btn" />
                            </form>
                        </div><!-- Registration sec -->
                    </div>
                </div>
                <span class="close-popup"><i class="fa fa-close"></i></span>
            </div>
        </div>
    </div><!-- Account Popup Sec -->

    <header class="simple-header for-sticky container-wrapper">

        <div class="menu">
            <div class="container">
                <div class="logo">
                    <a href="/" title="">
                       <img id="logo-img" src="<?php echo base_url();?>/<?=$_SESSION['tm']?>img/logo.png" class="responsive">
                    </a>
                    <span id="logo-txt" style="" class="text-center">
                            <!--希林芝北中文学校-->
                        <img src="<?php echo base_url();?>/<?=$_SESSION['tm']?>img/title.PNG" class="responsive" style="margin-left: -70%; width: 75%;" />
                    <br>
                        <strong id="logo-txt-en" style="margin-left:  -70%;" >Xilin Northshore Chinese School</strong>
                    </span>
                </div><!-- LOGO -->


                <span class="menu-toggle"><i class="fa fa-bars"></i></span>
                <nav>
                    <h1 class="nocontent outline">--- Main Navigation ---</h1>



                  <?php if(session()->get('userresult') && (isset(session()->get('userresult')[0]['parent_id']) || isset(session()->get('userresult')[0]['teacher_id']))){?>
                    <div class="popup-client">
                        <span><i class="fa fa-user"></i><a href="/login/logout">Logout</a></span>
                    </div>
                  <?php } else { ?>
                    <div class="popup-client login-popup">
                        <span style="line-height: 18px;"><i class="fa fa-user"></i>  Login</span>
                    </div>
                  <?php } ?>


                    <ul>
                        <li><a href="/school/contactus" style="line-height: 39px;">CONTACT</a></li>
                        <li><a href="https://www.facebook.com/xilinns/" target="_blank"><i class="fa fa-facebook" style="background: #7d1a1ae6 none repeat scroll 0 0;height: 39px;line-height: 39px;text-align: center; width: 39px;"></i></a></li>
                      <?php if(session()->get('userresult') && (isset(session()->get('userresult')[0]['parent_id']) || isset(session()->get('userresult')[0]['teacher_id']))){?>
                          <li><a href="/account" style="line-height: 39px;">MY ACCOUNT</a></li>
                      <?php } ?>
                    </ul>
                </nav>

            </div>
        </div>
    </header>

    <section class="box-slider-search">
        <div class="container">
            <h1 class="nocontent outline">--- Search form  ---</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="panel-group" id="accordion">
                        <div class="panel panel-default">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <span class="glyphicon glyphicon-home">
                            </span>Our School
                                </h4>
                            </div>
                            </a>
                            <div id="collapseOne" class="">
                                <div class="panel-body">
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <span class="glyphicon glyphicon-certificate text-primary"></span><a href="/school/intro/">About Us</a>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td>
                                                <span class="glyphicon glyphicon-info-sign text-success"></span><a href="/school/rules">Policies</a>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="glyphicon glyphicon-question-sign text-success"></span><a href="/school/faq">FAQ</a>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="glyphicon glyphicon-file text-info"></span><a
                                                        href="/school/newsletters">Newsletters</a>
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <span class="glyphicon glyphicon-book">
                            </span>Academics
                                </h4>
                            </div>
                            </a>
                            <div id="collapseTwo" class="">
                                <div class="panel-body">
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <span class="glyphicon glyphicon-calendar text-success"></span><a href="/calendar">Calendar</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="glyphicon glyphicon-education text-success"></span><a href="/classinfo">Classes</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="glyphicon glyphicon-book text-success"></span>
                                              <?php if(session()->get('userresult') && (isset(session()->get('userresult')[0]['parent_id']) || isset(session()->get('userresult')[0]['teacher_id']))){?>
                                                  <a href="/account/students">Registration</a>
                                              <?php } else { ?>
                                                  <a class="login-popup" style="cursor: pointer">
                                                      <span>Registration</span>
                                                  </a>
                                                <?php } ?>
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <span class="glyphicon glyphicon-file">
                            </span>General Info
                                </h4>
                            </div>
                            </a>
                            <div id="collapseFour" class="">
                                <div class="panel-body">
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <span class="glyphicon glyphicon-tree-deciduous"></span><a href="/school/pvsa">Community Service</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="glyphicon glyphicon-usd"></span><a href="/school/scholarship">Scholarship</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="glyphicon glyphicon-heart"></span><a href="/school/fundraising">Support Us</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="glyphicon glyphicon-gift"></span><a href="/school/sponsor">Our Sponsors</a>
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



