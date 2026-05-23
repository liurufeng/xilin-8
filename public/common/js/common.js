var domain = 'http://' + window.location.hostname;

function ajax_error(msg, header_text, redirect_to) {
  if (!header_text) header_text = 'Alert!';
  //flash notice
  /*  var elem = "<div class='container-fluid'><div class='row'><div class='col-md-12'><div class='alert alert-dismissable alert-warning'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'> × </button>"
   + msg + "</div></div></div></div>";*/

  //modal dialog notice
  var elem1 = "<div class='container-fluid modal-alert'><div class='row'><div class='col-md-12'><div class='modal fade in' id='modal-container' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' style='display: block; padding-right: 17px;'><div class='modal-dialog'><div class='modal-content'><div class='modal-header'><button type='button' class='close close-alert' data-dismiss='modal' aria-hidden='true'> × </button><h3 class='modal-title' id='myModalLabel'>"
    + header_text + "</h3></div><div class='modal-body'><h4>"
    + msg + "</h4></div><div class='modal-footer'><button type='button' class='btn btn-info close-alert' data-dismiss='modal'>Close</button></div></div></div></div></div></div></div>";
  $(".container-wrapper").first().after(elem1);
  //click close-alert

  $(".close-alert").on("click", function () {
    if (redirect_to) {
      if (redirect_to == 'return') {
        $('.modal-alert').hide();
        return false;
      } else {
        location.href = redirect_to;
        return false;
      }
    } else {
      location.href = '/';
    }
  });
}

function ajax_confirm(msg, header_text, function_name, id, target, extra, yes_str, no_str) {
  if (!header_text) header_text = 'Confirm!';

  var elem1 = '<div class="container-fluid"> 	' +
    '<div class="row"> 		' +
    '<div class="col-md-12"> 			 ' +
    '<a id="modal-301706" href="#modal-container-301706" role="button" class="btn" data-toggle="modal" style="visibility: hidden; display: none;"></a> 			' +
    '<div class="modal fade" id="modal-container-301706" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> 				' +
    '<div class="modal-dialog"> 					' +
    '<div class="modal-content"> 						' +
    '<div class="modal-header"> 							' +
    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true"> × </button>' +
    '<h3 class="modal-title" id="myModalLabel"> ' + header_text + '	</h3> 						' +
    '</div> 						' +
    '<div class="modal-body"><h4> ' + msg + '</h4></div> 						' +
    '<div class="modal-footer"> 							<button type="button" class="btn btn-info no-button" data-dismiss="modal">' + no_str + '</button>' +
    '<button type="button" class="btn btn-success confirm-alert"> ' + yes_str + '</button>' +
    '</div> 					' +
    '</div> 				' +
    '</div> 			' +
    '</div> 		' +
    '</div> 	' +
    '</div> ' +
    '</div>';

  $(".container-wrapper").first().after(elem1);
  $("#modal-301706").click();
  //click close-alert
  $(".close-alert").on("click", function () {
    $(".modal.fade.in").fadeOut();
  });

  $(".confirm-alert").on("click", function () {
    $(".modal.fade").fadeOut();
    window[ function_name ](id, target, extra);
    $(".modal.fade.in").fadeOut();
    return false;
  });
}

function add_student(stdid, sname, cname, bday, gender, race) {
  var fgender_checked= '';
  var mgender_checked='';
  var post_url = '';
  if(stdid) {
    post_url = '/account/update_student';
    header_text = 'Edit Student Info';
    yes_str = 'Update';
    if(gender == 'Male') {mgender_checked='selected';}
    else if(gender == 'Female') {fgender_checked='selected';}
  } else {
    post_url = '/account/add_student';
    header_text = 'Add A New Student';
    yes_str = 'Add';
    sname = cname = bday = gender = race = '';
  }
  no_str = 'Cancel';

  var elem1 = '<div class="container-fluid"> 	' +
    '<div class="row"> 		' +
    '<div class="col-md-12"> 			 ' +
    '<a id="modal-301706" href="#modal-container-301706" role="button" class="btn" data-toggle="modal" style="visibility: hidden; display: none;"></a> 			' +
    '<div class="modal fade" id="modal-container-301706" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> 				' +
    '<div class="modal-dialog">' +
    '<div class="modal-content">' +
    '<div class="modal-header">' +
    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true"> × </button>' +
    '<h3 class="modal-title" id="myModalLabel"> ' + header_text + '	</h3>' +
    '</div> 						' +
    '<div class="modal-body">' +
    '<table id="student">' +
    '<tr><td style="text-align:right;"><span style="color:red;">*</span>English Name:</td>' +
    '<td style="padding:3px;"><input type="text" value="'+sname+'" name="en_name" id="en_name" style="width:300px; margin-left:20px;"/></td></tr>' +
    '<tr><td style="text-align:right;">Chinese Name:</td>' +
    '<td style="padding:3px;"><input type="text" value="'+cname+'" name="cn_name" id="cn_name" style="width:300px; margin-left:20px;"/></td></tr>' +
    '<tr><td style="text-align:right;"><span style="color:red;">*</span>Birthday:</td>' +
    '<td style="padding:3px;" id="sandbox-container" ><input type="text" value="'+bday+'" name="birthday" id="birthday" style="width:300px; margin-left:20px;" class="form-control" placeholder="yyyy-mm-dd" required/></td></tr>' +
    '<tr><td style="text-align:right;">Gender:</td>' +
    '<td style="padding:3px;"><select id="gender" style="width:300px; margin-left:20px; height: 30px;"><option value="">Select</option><option value="Female" '+fgender_checked+'>Female</option><option value="Male" '+mgender_checked+'>Male</option></select></td></tr>' +
    '<tr><td style="text-align:right;">Race:</td>' +
    '<td style="padding:3px;"><input type="text" value="'+race+'" name="race" id="race" style="width:300px; margin-left:20px;"/></td></tr>' +
    '</table>' +
    '</div>' +
    '<div class="modal-footer"> <button type="button" class="btn btn-danger no-button" data-dismiss="modal">' + no_str + '</button>' +
    '<button type="button" class="btn btn-success confirm-alert"> ' + yes_str + ' </button>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>';

  $(".container-wrapper").first().after(elem1);
  $("#modal-301706").click();

  $('#sandbox-container input').datepicker({
    format: "yyyy-mm-dd",
    clearBtn: true,
    autoclose: true
  });
  //click close-alert
  $(".close-alert").on("click", function () {
    $(".modal.fade.in").fadeOut();
  });

  $(".confirm-alert").on("click", function () {
    if($("#birthday").val().trim() == '' || $("#birthday").val().trim() == '0000-00-00' ) {
      alert('Please provide a valid birthday in the format of yyyy-mm-dd!');
      return;
    }
    $.post(post_url,
      {
        stdid: stdid,
        en_name: $("#en_name").val(),
        cn_name: $("#cn_name").val(),
        birthday: $("#birthday").val(),
        gender: $("#gender").val(),
        race: $("#race").val(),
        'csrf_test_name': $.cookie('csrf_cookie_name')
      },
      function (result) {
        result = JSON.parse(result);
        if (result.success) {
          $(".modal.fade.in").fadeOut();
          location.href = domain + '/account/students';
        } else {
          msg = "Failed to add student, click <a href='/' class='alert-link'>here</a> to login and try again!";
          ajax_error(msg);
        }
      });
  });
}

function register_class(stdid, stname, cid, ctime, cname, bookfee, act, withbook) {
  if(act == '0') {
    header_text = 'Register the following class';
    yes_str = 'Register';
  } else if (act == '1') {
    header_text = 'Un-Register the following class';
    yes_str = 'Un-Register';
  } else if (act == '2') {
    header_text = 'Change the following class registration';
    yes_str = 'Change';
  }

  no_str = 'Cancel';
  bookfee = parseFloat(bookfee);

  var elem1 = '<div class="container-fluid"> 	' +
    '<div class="row"> 		' +
    '<div class="col-md-12"> 			 ' +
    '<a id="modal-301706" href="#modal-container-301706" role="button" class="btn" data-toggle="modal" style="visibility: hidden; display: none;"></a> 			' +
    '<div class="modal fade" id="modal-container-301706" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> 				' +
    '<div class="modal-dialog">' +
    '<div class="modal-content">' +
    '<div class="modal-header">' +
    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true"> × </button>' +
    '<h3 class="modal-title" id="myModalLabel"> ' + header_text + '	</h3>' +
    '</div> 						' +
    '<div class="modal-body">' +
    '<table id="register-class">' +
    '<tr><td >Student Name:</td>' +
    '<td style="padding:3px;">' + stname + '</td></tr>' +
    '<tr><td >Class Name:</td>' +
    '<td style="padding:3px;">' + cname + '</td></tr>' +
    '<tr><td >Class Time:</td>' +
    '<td style="padding:3px;">' + ctime + '</td></tr>';
  if (bookfee > 0) {
    if (act == '1') {
      elem1 = elem1 +
      '<tr><td ><b>This class needs textbook:</b></td>' +
        '<td style="padding:3px;"><b>If you have requested book, the book fee $' + bookfee + ' is not refundable!</b></td></tr>';
    } else {
    elem1 = elem1 +
      '<tr><td ><b>This class needs textbook:</b></td>' +
      '<td style="padding:3px;"><b>$' + bookfee + '</b></td></tr>' +
      '<tr><td ><input type="radio" id="withbook" name="withbook" checked="checked" value="1"> Yes, add textbook to my order </td>' +
      '<td> </td></tr>' +
      '<tr><td > <input type="radio" id="withbook" name="withbook" value="0"> No, I do NOT want textbook </td>' +
      '<td> </td></tr>';
    }
  }

    elem1 = elem1 +
      '</table>' +
      '</div>' +
      '<div class="modal-footer"> <button type="button" class="btn btn-danger no-button" data-dismiss="modal">' + no_str + '</button>' +
      '<button type="button" class="btn btn-success confirm-alert">' + yes_str + ' </button>' +
      '</div>' +
      '</div>' +
      '</div>' +
      '</div>' +
      '</div>' +
      '</div>' +
      '</div>';

  $(".container-wrapper").first().after(elem1);
  $("#modal-301706").click();

  if( bookfee > 0 && (act == '1' || act == '2')) {
    if( withbook == '0') {
      $('input:radio[name="withbook"]').filter('[value="0"]').attr('checked', true);
    } else {
      $('input:radio[name="withbook"]').filter('[value="1"]').attr('checked', true);
    }
  }
  //click close-alert
  $(".close-alert").on("click", function () {
    $(".modal.fade.in").fadeOut();
  });

  $(".confirm-alert").on("click", function () {
    if(undefined == $("input[name=withbook]:checked").val() ) {
      $buy_book = 0;
    } else {
      $buy_book = $("input[name=withbook]:checked").val();
    }
    // $(".overlay").show();
    $.post("/register_class/do_register?stdid="+stdid,
      { student_id: stdid,
        class_id: cid,
        buy_book: $buy_book,
        act: act,
        'csrf_test_name': $.cookie('csrf_cookie_name')
      },
      function (result) {
        // $(".overlay").hide();
        $(".fade.in").fadeOut();
        result = JSON.parse(result);
        if (result.success) {
          //location.href = '/register_class?stdid='+stdid;
          // now popup dialog with Complete and Continue Register
          var elem1 = '<div class="container-fluid"> 	' +
              '<div class="row"> 		' +
              '<div class="col-md-12"> 			 ' +
              '<a id="modal-301708" href="#modal-container-301708" role="button" class="btn" data-toggle="modal" style="visibility: hidden; display: none;"></a> 			' +
              '<div class="modal fade" id="modal-container-301708" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> 				' +
              '<div class="modal-dialog">' +
              '<div class="modal-content">' +
              '<div class="modal-header">' +
              '<button type="button" class="close" data-dismiss="modal" aria-hidden="true"> × </button>' +
              '<h3 class="modal-title" id="myModalLabel"> Success </h3>' +
              '</div> 						' +
              '<div class="modal-body">' +
              'Class registered/updated successfully' +
              '</div>' +
              '<div class="modal-footer"> <button type="button" class="btn btn-danger continue-btn" data-dismiss="modal">Register another class</button>' +
              '<button type="button" class="btn btn-success confirm-alert"> Complete </button>' +
              '</div>' +
              '</div>' +
              '</div>' +
              '</div>' +
              '</div>' +
              '</div>' +
              '</div>';

          $(".container-wrapper").first().after(elem1);
          $("#modal-301708").click();

          // click close-alert, go to the register_class page
          $(".close-alert").on("click", function () {
            $(".overlay").show();
            location.href = '/register_class?stdid='+stdid;
          });
          $(".continue-btn").on("click", function () {
            $(".overlay").show();
            location.href = '/register_class?stdid='+stdid;
          });

          // clicked Complete, go to payment instruction page
          $(".confirm-alert").on("click", function () {
            //location.href = '/account/invoice/online';
              window.open("/account/invoice/PaymentInstruction", "_blank",
                  "directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,top=200,left=200,width=990,height=660");

            $(".overlay").show();
            //location.href = '/register_class?stdid='+stdid; /pod/index
            location.href = '/pod/index';
          });

        } else if (result.msg){
          ajax_error(result.msg, 'Class Registration failed!' , 'return');
        } else {
          msg = "Failed to register class, click <a href='/' class='alert-link'>here</a> to login and try again!";
          ajax_error(msg);
        }
      });
  });
}


function findpass() {
  var header_text = 'Find Password';
  var post_url = '/account/findpass';
  yes_str = 'Email me my password';
  no_str = 'Cancel';

  var elem1 = '<div class="container-fluid"> 	' +
    '<div class="row"> 		' +
    '<div class="col-md-12"> 			 ' +
    '<a id="modal-301706" href="#modal-container-301706" role="button" class="btn" data-toggle="modal" style="visibility: hidden; display: none;"></a> 			' +
    '<div class="modal fade" id="modal-container-301706" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> 				' +
    '<div class="modal-dialog">' +
    '<div class="modal-content">' +
    '<div class="modal-header">' +
    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true"> × </button>' +
    '<h3 class="modal-title" id="myModalLabel"> ' + header_text + '	</h3>' +
    '</div> 						' +
    '<div class="modal-body">' +
    '<table id="findpass">' +
    '<tr><td style="text-align:right;"><span style="color:red;">*</span>Email:</td>' +
    '<td style="padding:3px;"><input type="text" value="" name="email" id="email" style="width:300px; margin-left:20px;"/></td></tr>' +
    '</table>' +
    '</div>' +
    '<div class="modal-footer"> <button type="button" class="btn btn-danger no-button" data-dismiss="modal">' + no_str + '</button>' +
    '<button type="button" class="btn btn-success confirm-alert"> ' + yes_str + ' </button>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>';

  $(".container-wrapper").first().after(elem1);
  $("#modal-301706").click();

  //click close-alert
  $(".close-alert").on("click", function () {
    $(".modal.fade.in").fadeOut();
  });

  $(".confirm-alert").on("click", function () {
    $(".fade.in").fadeOut();
    $.post(post_url,
      {
        email: $("#email").val(),
        'csrf_test_name': $.cookie('csrf_cookie_name')
      },
      function (result) {
        $(".modal.fade.in").fadeOut();
        result = JSON.parse(result);
        if (result.success) {
          msg = result.msg;
          ajax_error(msg, 'We found your password', 'return');
          return;
        } else {
          msg = result.msg;
          ajax_error(msg, 'Failed to find you password', 'return');
          return;
        }
      });
  });
}

// your own JS code to be here
$(document).ajaxError(function (event, request, settings) {
  //alert('Your login session has expired and you will be directed to the login page!');
  //location.href = '/';
  msg = "Your login session has expired, click <a href='/' class='alert-link'>here</a> to login again!";
  ajax_error(msg);
});

$(document).ready(function () {
  //find password
  $("#findpass").on("click", function () {
    findpass();
    return false;
  });
  $("#findpass1").on("click", function () {
    findpass();
    return false;
  });
  //for date picker
  $('#sandbox-container input').datepicker({
    format: "yyyy-mm-dd",
    clearBtn: true,
    autoclose: true
  });
  //add new student button click
  $("#add_new_student").on("click", function () {
    add_student();
    return false;
  });

  //edit student button click
  $(".edit_student").on("click", function () {
    stdid = $(this).attr('stdid');
    sname = $(this).attr('sname');
    cname = $(this).attr('cname');
    bday = $(this).attr('bday');
    gender = $(this).attr('gender');
    race = $(this).attr('race');
    add_student(stdid, sname, cname, bday, gender, race);
    return false;
  });

  //remove student button click
  $(".remove_student").on("click", function () {
    stdid = $(this).attr('stdid');
    sname = $(this).attr('sname');
    msg = "Are you sure you want to remove <i>" + sname + "</i> from your account?" ;
    confirm = 'Confirm Removing Student';
    ajax_confirm(msg, confirm, 'remove_student', stdid, '', '', 'YES', 'NO');
    return false;
  });

  //remove homework button click
  $(".remove_homework").on("click", function () {
    wid = $(this).attr('wid');
    wtitle = $(this).attr('wtitle');
    msg = "Are you sure you want to delete <i>" + wtitle + "</i> homework?" ;
    confirm = 'Confirm Deleteing Homework';
    ajax_confirm(msg, confirm, 'remove_homework', wid, '', '', 'YES', 'NO');
    return false;
  });

  //register class button click
  $(".register_class").on("click", function () {
    if(! $("#agreed").is(':checked')) {
      ajax_error('You need to AGREE TO THE XILIN NS TERMS & AGREEMENT before register a class.', 'AGREE TO THE XILIN NS TERMS & AGREEMENT!' , 'return');
      $('#agreed').goTo();
      return false;
    }

    var theElement = $(this);
    var stdid = $("#stdid").val();
    var stname = $("#stname").val();
    var cid = $(this).attr('cid');
    var ctime = $(this).attr('ctime');
    var cname = $(this).attr('cname');
    var bookfee = $(this).attr('bookfee');

    register_class(stdid, stname, cid, ctime, cname, bookfee, '0', '0');
    return false;
  });

    $('#agreed').on('click', function(){

        terms =
        '<div style="position: relative;height: 200px;overflow: auto;">' +
        '<p class="text-center">TERMS AND AGREEMENT</p>' +
        '<div style="font-size: 13px;padding: 10px;"> ' +
        'By registering class/classes for my child/children or myself with Xilin North Shore Chinese School, ' +
            'I hereby agree to - <br/><br/>'+
        '(1) Grant permission for him/her/them to participate in all school activities in ' +
            'Xilin North Shore Chinese School during this school year. ' +
            'The date, time and location of the activities are included in school notices in writtenor oral formats. ' +
            'I hereby waive and release all claims against Xilin North Shore Chinese School and/or Oakton Community College, ' +
            'its governing committee, its members, teacher(s)/leader(s), parents from any injury, including death, loss, ' +
            'damage, accident, medical care, delay, or expense incurred during participation in these activities.<br><br>'+
            '((2) Grant permission for the school to take photographs/videos of children and use the photos/videos ' +
            'in the school’s website and printed publications.<br/><br/>'+
            '(3) Serve as a parent-on-duty for at least one 2-hour and 20-minute time slot for each student ' +
            'enrolled during each school semester. Full parent-on-duty rules are listed at ' +
            '<a href="https://xilinnschinese.org/public/uploadfiles/web_documents/XilinNS_POD_rules.pdf" target="_blank">https://xilinnschinese.org/public/uploadfiles/web_documents/XilinNS_POD_rules.pdf</a><br/><br/>'+
            '</div>';

        var t = '<div style="font-size: 13px;padding: 10px;"><b>For Students:</b></ul>'+
            '<li><b>General</b>: Students must follow school rules and regulations.</li>'+
            '<li><b>Safety</b>: Activities, which may be harmful to people or cause damage to property, are forbidden. These activities include, but not limited to, chasing each other, standing on table, pointing a sharp object to other people.</li>'+
            '<li><b>Respect</b>: Students should respect and greet their teachers. Students should respect and greet their fellow classmates. ZERO tolerance on bully matters. Students will be forbidden to attend and register with school if they perform any bully behavior. It is forbidden to use any irritative or disrespectful language and words.</li>'+
            '<li><b>Responsibility</b>: Students or their parents will take full responsibility for any damage or harm to human body (including student themselves) and property.</li>'+
            '<li><b>Class</b>: Attend class on time. Follow teacher\'s instruction. Raise hand before speaking. Do not talk without teacher\'s permission. Complete and submit homework in time according to teacher\'s requirements.</li>'+
            '<li style="color: red;"><b>No food in classrooms & hallways.</b>  Eating is only allowed in cafeteria and lounge areas.  Repeat offenders will be fined.</li>'+
            '<li><b>Report</b>: report any concerning issues to class teacher.</li>'+
            '</ul></div>' +
            '</div>';

        ajax_error(terms + t, 'Please read and AGREE TO THE XILIN NS TERMS & AGREEMENT!' , 'return');

    });

  //unregister class button click
  $(".unregister_class").on("click", function () {
    var theElement = $(this);
    var stdid = $("#stdid").val();
    var stname = $("#stname").val();
    var cid = $(this).attr('cid');
    var ctime = $(this).attr('ctime');
    var cname = $(this).attr('cname');
    var bookfee = $(this).attr('bookfee');
    var withbook = $(this).attr('withbook');

    register_class(stdid, stname, cid, ctime, cname, bookfee, '1', withbook);
    return false;
  });

  //change class button click
  $(".change_class").on("click", function () {
    var theElement = $(this);
    var stdid = $("#stdid").val();
    var stname = $("#stname").val();
    var cid = $(this).attr('cid');
    var ctime = $(this).attr('ctime');
    var cname = $(this).attr('cname');
    var bookfee = $(this).attr('bookfee');
    var withbook = $(this).attr('withbook');

    register_class(stdid, stname, cid, ctime, cname, bookfee, '2', withbook);
    return false;
  });

  //search click
  $(".search").on("click", function () {
    if ($("#search").val().trim() == '') {
      alert('Search field is empty!');
      return false;
    }
  });

  //search click
  $(".goback").on("click", function () {
    parent.history.back();
    return false;
  });

  //click close-alert
  $(".close-alert").on("click", function () {
    location.href = '/';
  });

  $(".scroll a").on("click", function () {
    $(".scroll").removeClass('active');
    $(this).parent().addClass('active');
  });

  //teacher send_class_email
  $(".send_class_email").on("click", function () {
    cid = $(this).attr('cid');
    msg = "Are you sure you want to send the message?" ;
    confirm = 'Confirm Sending Message';
    ajax_confirm(msg, confirm, 'send_class_email', cid, '', '', 'YES', 'NO');
    return false;
  });

  //consent online payment
  $("#consent").on("change", function () {
    if(this.checked) {
      $("#paypay_btn").attr('disabled', false);
    } else {
      $("#paypay_btn").attr('disabled', true);
    }
  });
});

function validateEmail(email) {
  var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
  return re.test(email);
}

function remove_student(stdid) {
  $.post("/account/remove_student",
    { stdid: stdid,
      'csrf_test_name': $.cookie('csrf_cookie_name')
    },
    function (result) {
    result = JSON.parse(result);
      if (result.success) {
        var element = document.getElementById('ms-'+stdid);
        element.parentNode.removeChild(element);
        msg = 'The student has been removed successfully!';
        header_text = "Student Removed!";
        ajax_error(msg, header_text, domain + '/account/students');
      } else if (result.msg){
        ajax_error(result.msg, '', 'return');
      } else {
        msg = result.error + ", click <a href='/' class='alert-link'>here</a> to login again!";
        ajax_error(msg);
      }

    });
}

function remove_homework(wid) {
  $.post("/homework/remove_homework",
    { wid: wid,
      'csrf_test_name': $.cookie('csrf_cookie_name')
    },
    function (result) {
      result = JSON.parse(result);
      if (result.success) {
        msg = 'The homework has been deleted successfully!';
        header_text = "Homework Deleted!";
        ajax_error(msg, header_text, '/homework');
      } else if (result.msg){
        ajax_error(result.msg, '', '/homework');
      } else {
        msg = result.error + ", click <a href='/' class='alert-link'>here</a> to login again!";
        ajax_error(msg);
      }

    });
}

function scrollToId(theId) {
  $('html, body').animate({
    scrollTop: $('#'+theId).offset().top -100
  }, 500);
}

function send_class_email(cid) {
  var emails = $("#emails_"+cid).val();
  var subject = $("#subject_"+cid).val();
  var message = $("#message_"+cid).val();

  $.post("/message/send_class_email",
    { cid: cid,
      emails: emails,
      subject: subject,
      message: message,
      'csrf_test_name': $.cookie('csrf_cookie_name')
    },
    function (result) {
      result = JSON.parse(result);
      if (result.success) {
        msg = 'The message has been sent successfully!';
        header_text = "Message Sent!";
        ajax_error(msg, header_text, '/account');
      } else if (result.msg){
        ajax_error(result.msg, '', '/account');
      } else {
        msg = result.error + ", click <a href='/' class='alert-link'>here</a> to login again!";
        ajax_error(msg);
      }

    });
}

function precheck() {
  var sig = ($("#sig").val()).trim();
  if(sig == '') {
    msg = 'You need to sign and agree the TERMS AND AGREEMENT before making an online payment!';
    header_text = "Signature needed!";
    ajax_error(msg, header_text, 'return');
    return false;
  }

  var pdate = ($("#pdate").val()).trim();
  if(pdate == '') {
    msg = 'You need to date and agree the TERMS AND AGREEMENT before making an online payment!';
    header_text = "Date needed!";
    ajax_error(msg, header_text, 'return');
    return false;
  }

  if(!$("#consent").attr('checked')){
    msg = 'You need to check the consent checkbox to the TERMS AND AGREEMENT before making an online payment!';
    header_text = "Agreement needed!";
    ajax_error(msg, header_text, 'return');
    return false;
  }

  $return_url = $("#return").val();
  $("#return").val($return_url + '/' + sig + '/' + pdate.replace(/\//g, "-"));
    //return false;
  $("#paypal").submit();

}

$.fn.goTo = function() {
  $('html, body').animate({
    scrollTop: ($(this).offset().top - 230) + 'px'
  }, 'fast');
  return this; // for chaining...
}
