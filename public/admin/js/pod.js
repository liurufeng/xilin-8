var thisParentId = 0;
var userLevel = 1; //1:admin, 0: parent

var months = new Array("一", "二", "三", "四", "五", "六", "七", "八", "九", "十", "十一", "十二");
var daysInMonth = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
var days = new Array("日 Sunday", "一 Monday", "二 Tuesday", "三 Wednesday", "四 Thursday", "五 Friday", "六 Saturday");
var classTemp;
var today = new getToday();
var year = today.year;
var month = today.month;
var newCal;
var req = getXMLHTTPRequest();
var processFunction;
var u;
var content = "";


function Querystring(qs) {
  this.params = {};

  if (qs == null) qs = location.search.substring(1, location.search.length);
  if (qs.length == 0) return;

  qs = qs.replace(/\+/g, ' ');
  var args = qs.split('&');

  for (var i = 0; i < args.length; i++) {
    var pair = args[i].split('=');
    var name = decodeURIComponent(pair[0]);

    var value = (pair.length==2)
      ? decodeURIComponent(pair[1])
      : name;

    this.params[name] = value;
  }
}

Querystring.prototype.get = function(key, default_) {
  var value = this.params[key];
  return (value != null) ? value : default_;
}

Querystring.prototype.contains = function(key) {
  var value = this.params[key];
  return (value != null);
}

function getDays(month, year) {
  if (1 == month) return ((0 == year % 4) && (0 != (year % 100))) || (0 == year % 400) ? 29 : 28;
  else return daysInMonth[month];
}

function getToday() {
  this.now = new Date();
  this.year = this.now.getFullYear();
  this.month = this.now.getMonth();
  this.day = this.now.getDate();
}

function Calendar() {
  newCal = new Date(year, month, 1);
  today = new getToday();
  var day = -1;
  var startDay = newCal.getDay();
  var endDay = getDays(newCal.getMonth(), newCal.getFullYear());
  var daily = 0;
  if ((today.year == newCal.getFullYear()) && (today.month == newCal.getMonth())) day = today.day;

  var caltable = document.all.caltable.tBodies.calendar;
  var intDaysInMonth = getDays(newCal.getMonth(), newCal.getFullYear());

  for (var intWeek = 0; intWeek < caltable.rows.length; intWeek++)
    for (var intDay = 0; intDay < caltable.rows[intWeek].cells.length; intDay++) {
      var cell = caltable.rows[intWeek].cells[intDay];
      var montemp = (newCal.getMonth() + 1) < 10 ? ("0" + (newCal.getMonth() + 1)) : (newCal.getMonth() + 1);
      if ((intDay == startDay) && (0 == daily)) daily = 1;
      var daytemp = daily < 10 ? ("0" + daily) : (daily);
//				var d = "<"+newCal.getFullYear()+"-"+montemp+"-"+daytemp+">";
      if (day == daily) cell.className = "DayNow";
      else if (intDay == 6) cell.className = "DaySat";
      else if (intDay == 0) cell.className = "DaySun";
      else cell.className = "Day";
      if ((daily > 0) && (daily <= intDaysInMonth)) {
        cell.innerHTML = daily;
        daily++;
      } else {
        cell.className = "CalendarTD";
        cell.innerHTML = "";
      }
    }
  document.all.year.value = year;
  document.all.month.value = month + 1;

  getEvents(year, month);
}
function subMonth() {
  if ((month - 1) < 0) {
    month = 11;
    year = year - 1;
  } else {
    month = month - 1;
  }
  Calendar();
}

function addMonth() {
  if ((month + 1) > 11) {
    month = 0;
    year = year + 1;
  } else {
    month = month + 1;
  }
  Calendar();
}

function setDate() {
  if (document.all.month.value < 1 || document.all.month.value < 12) {
    alert("Invalid month number!");
    return;
  }
  year = Math.ceil(document.all.year.value);
  month = Math.ceil(document.all.month.value - 1);
  Calendar();
}

function getEvents(y, m) {
  u = "getEvents";

  var p = "y=" + y + "&m=" + m + "&pid=" + thisParentId;
  processFunction = showEvents;
  sendRequest(u, p);
}
function showEvents(data) {
  //data: 0:EventId^1:day^2:startTime^3:EndTime^4:RegisteredHelpers^5:HelpersNeeded^6:UnregLimit^7: ThisParentRegistered^8:
  //					80:Helper1: 0:HelperId!1:ParentId!2:PrimaryEName!3:DetailedInfo!4:signin!5:signout;81:Helper2...;82:Helper3...
  //data = "1^2^12:00^13:00^3^5^7^0";
  //console.log(data);
  if (data.length > 1) {
    var events = data.split("|");
    var caltable = document.all.caltable.tBodies.calendar;
    //console.log(caltable);
    for (var i = 0; i < events.length; i++) {
      var thisEvent = events[i].split("^");
      //console.log(thisEvent);
      var newCal = new Date(year, month, thisEvent[1]);
      var today = new getToday();
      var day = new Date(year, month, 1);
      var icol = (parseInt(thisEvent[1]) + day.getDay() - 1) % 7;
      var irow = (parseInt(thisEvent[1]) + day.getDay() - icol - 1) / 7;
      //console.log(irow);
      //console.log(caltable.rows);
      var cell = caltable.rows[irow].cells[icol];
      if (newCal <= today)  cell.innerHTML = cell.innerHTML + "<br/>" + thisEvent[2] + " ~ " + thisEvent[3] + ", " + thisEvent[4] + "/" + thisEvent[5];
      else {
        if (userLevel == 1) cell.innerHTML = cell.innerHTML + "<br/><a href=\"javascript:editEvent(" + thisEvent[0] + "," + thisEvent[1] + ",'" + thisEvent[2] + "','" + thisEvent[3] + "'," + thisEvent[5] + "," + thisEvent[6] + ");\">" + thisEvent[2] + " ~ " + thisEvent[3] + ", " + thisEvent[4] + "/" + thisEvent[5] + "</a>";
        else if (thisEvent[7] == 0 &&  Number(thisEvent[4]) < Number(thisEvent[5])) {
          cell.innerHTML = cell.innerHTML + "<br/><a href=\"javascript:regEvent(" + thisEvent[0] + "," + thisParentId + ");\"><b>" + thisEvent[2] + " ~ " + thisEvent[3] + ", " + thisEvent[4] + "/" + thisEvent[5] + "</b></a>";
        } else {
          cell.innerHTML = cell.innerHTML + "<br/>" + thisEvent[2] + " ~ " + thisEvent[3] + ", " + thisEvent[4] + "/" + thisEvent[5];
        }
      }

      if (thisEvent.length > 8) {
        var helpers = thisEvent[8].split("%");
        for (var ih = 0; ih < helpers.length; ih++) {
          var helperInfo = helpers[ih].split("!");
          if (newCal <= today.now) {
            if (userLevel == 1) {
              cell.innerHTML = cell.innerHTML + ": <a href=\"javascript:updatehelper(&quot;" + helperInfo[3] + "&quot;," + helperInfo[0] + ",&quot;" + helperInfo[4] + "&quot;,&quot;" + helperInfo[5] + "&quot;);\">" + helperInfo[2] + "</a>";
            } else {
              cell.innerHTML = cell.innerHTML + ": " + helperInfo[2];
            }
          } else {
            if (userLevel == 1) {
              cell.innerHTML = cell.innerHTML + ": <a href=\"javascript:updatehelper(&quot;" + helperInfo[3] + "&quot;," + helperInfo[0] + ",&quot;" + helperInfo[4] + "&quot;,&quot;" + helperInfo[5] + "&quot;);\">" + helperInfo[2] + "</a>";
            } else {
              if (thisParentId == helperInfo[1] && (newCal.getTime() - (new Date().getTime())) / (24 * 60 * 60 * 1000) > thisEvent[6]) cell.innerHTML = cell.innerHTML + ": <a href=\"javascript:unregEvent(" + helperInfo[0] + ");\">" + helperInfo[2] + "</a>";
              else  cell.innerHTML = cell.innerHTML + ": " + helperInfo[2];
            }
          }
        }
      }
    }
  }
  if (userLevel == 1) addEventLink();
}
function updatehelper(info, hid, signin, signout) {
  var width = 400;
  var height = 300;
  var title = "Helper Information";
  content = "<p class=\"Ptitle\">Helper Information</p>";
  content += "<p>" + info + "</p>";
  content += "<a href=\"#\" onclick=\"signinout(" + hid + ",1);\">Sign In</a><span id=\"signintime\">";
  if (signin) content += ": " + signin.substring(5, 16) + "</span>&nbsp; &nbsp;";
  else content += "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; </span>&nbsp; &nbsp; "
  ;
  content += "<a href=\"#\" onclick=\"signinout(" + hid + ",2);\">Sign Out</a><span id=\"signouttime\">";
  if (signout) content += ": " + signout.substring(5, 16) + "</span>&nbsp; &nbsp;";
  else content += "</a>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; </span>&nbsp; &nbsp; ";
  content += "<a href=\"#\" onclick=\"unregEvent(" + hid + ");\">Delete Helper";
  content += "</a>&nbsp; &nbsp;  ";
  content += "<p>&nbsp;</p>";
  content += "<input type=\"button\" name=\"Cancel\" value=\"Close Window\" onclick=\"javascript:closePopupWindow();\"/></p>";
  newPopupWindow(title, content, width, height);
}
function signinout(hid, act) {
  u = "processSigninout";
  var p = "hid=" + hid + "&act=" + act;
  processFunction = updatesigninout;
  sendRequest(u, p);
}
function updatesigninout(data) {
  var info = data.split("|");
  if (info[0] == 1) document.getElementById("signintime").innerHTML = info[1].substring(5, 16);
  else   document.getElementById("signouttime").innerHTML = info[1].substring(5, 16);
}
function addEventLink() {
  newCal = new Date(year, month, 1);
  today = new getToday();
  var day = -1;
  var startDay = newCal.getDay();
  var endDay = getDays(newCal.getMonth(), newCal.getFullYear());
  var daily = 0;
  if ((today.year == newCal.getFullYear()) && (today.month == newCal.getMonth())) day = today.day;
  if (today.year > newCal.getFullYear()) day = 32;
  if ((today.year == newCal.getFullYear()) && (today.month > newCal.getMonth())) day = 32;

  var caltable = document.all.caltable.tBodies.calendar;
  var intDaysInMonth = getDays(newCal.getMonth(), newCal.getFullYear());

  for (var intWeek = 0; intWeek < caltable.rows.length; intWeek++)
    for (var intDay = 0; intDay < caltable.rows[intWeek].cells.length; intDay++) {
      var cell = caltable.rows[intWeek].cells[intDay];
      var montemp = (newCal.getMonth() + 1) < 10 ? ("0" + (newCal.getMonth() + 1)) : (newCal.getMonth() + 1);
      if ((intDay == startDay) && (0 == daily)) daily = 1;
      var daytemp = daily < 10 ? ("0" + daily) : (daily);
      if ((daily > 0) && (daily <= intDaysInMonth)) {
        /*if (day <= daily && intDay == 0
          && !(year == 2015 && month + 1 == 3 && daily == 29)
          && !(year == 2015 && month + 1 == 1 && daily == 4)
          && !(year == 2015 && month + 1 == 5 && daily == 31)
          && !(year == 2015 && month + 1 == 8 && daily == 2)
          && !(year == 2015 && month + 1 == 8 && daily == 9)
          && !(year == 2015 && month + 1 == 8 && daily == 16)
          && !(year == 2015 && month + 1 == 9 && daily == 6)
          && !(year == 2015 && month + 1 == 11 && daily == 29)
          && !(year == 2015 && month + 1 == 12 && daily == 20)
          && !(year == 2015 && month + 1 == 12 && daily == 27)
          && year == 2015 && month <= 11
          && month != 5 && month != 6
          )*/
        var test = year + '-'+month+'-'+daily;
        if($.inArray( test, event_dates ) >= 0)
        {
          cell.innerHTML = cell.innerHTML + "<br/> <a href=\"javascript:addEvent(" + daily + ");\">Add Event</a>";
        }
        daily++;
      }
    }
}
function addEvent(d) {
  var width = 400;
  var height = 300;
  var title = "";
  var semesterSelect = document.getElementById("semester_id");
  var selectedText = semesterSelect.options[semesterSelect.selectedIndex].text;
  content = "<p class=\"Ptitle\">Add event on date " + (month + 1) + "/" + d + "/" + year + "</p>";
  content += "<form onsubmit=\"javascript:processAddEvent();return false;\">";
  content += "<input type=\"hidden\" name=\"d\" id=\"d\" value=\"" + d + "\"/>";
  content += "Start time: <input type=\"text\" name=\"st\" id=\"st\" size=\"6\" value=\"" + document.getElementById("dst").value + "\" /><br/>";
  content += "Start time: <input type=\"text\" name=\"et\" id=\"et\" size=\"6\" value=\"" + document.getElementById("det").value + "\" /><br/>";
  content += "Number of helpers needed: <input type=\"text\" name=\"hn\" id=\"hn\" size=\"2\" value=\"" + document.getElementById("dhn").value + "\" /><br/>";
  content += "Number of days to allow cancel a sign-up: <input type=\"text\" name=\"cd\" id=\"cd\" size=\"2\" value=\"" + document.getElementById("dcd").value + "\" /><br/>";
  //content += "<input type=\"hidden\" name=\"semester_id\" id=\"semester_id\" value=\"" + document.getElementById("semester_id").value + "\" /><br/>";
  content += "Semester: " + selectedText + "<br/>";
  content += "<p align=\"center\"><input type=\"submit\" name=\"submit\" value=\"Submit\"/>";
  content += "&nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  <input type=\"button\" name=\"Cancel\" value=\"Cancel\" onclick=\"javascript:closePopupWindow();\"/></p>";
  newPopupWindow(title, content, width, height);
}
function processAddEvent() {
  u = "processAddEvent";
  var p = "y=" + year + "&m=" + month + "&d=" + document.getElementById("d").value + "&s=" + document.getElementById("st").value + "&e=" + document.getElementById("et").value + "&h="
    + document.getElementById("hn").value + "&c=" + document.getElementById("cd").value + "&semester_id=" + document.getElementById("semester_id").value;
  processFunction = refreshPage;
  closePopupWindow();
  sendRequest(u, p);
}
function downloadHelperInfo() {
  today = new getToday();
  window.location = "processDownloadHelperInfo?y=" + today.year + "&m=" + today.month + "&d=" + today.day;
}
function editEvent(eid, d, starttime, endtime, helpersNeeded, unregLimit) {
  var width = 400;
  var height = 300;
  var title = "";
  var semesterSelect = document.getElementById("semester_id");
  var selectedText = semesterSelect.options[semesterSelect.selectedIndex].text;
  content = "<p class=\"Ptitle\">Edit event on date " + (month + 1) + "/" + d + "/" + year + "</p>";
  content += "<form onsubmit=\"javascript:processEditEvent();return false;\">";
  content += "<input type=\"hidden\" name=\"eid\" id=\"eid\" value=\"" + eid + "\"/>";
  content += "Start time: <input type=\"text\" name=\"st\" id=\"st\" size=\"6\" value=\"" + starttime + "\" /><br/>";
  content += "End time:  &nbsp;<input type=\"text\" name=\"et\" id=\"et\" size=\"6\" value=\"" + endtime + "\" /><br/>";
  content += "Number of helpers needed: <input type=\"text\" name=\"hn\" id=\"hn\" size=\"2\" value=\"" + helpersNeeded + "\" /><br/>";
  content += "Number of days to allow cancel a sign-up: <input type=\"text\" name=\"cd\" id=\"cd\" size=\"2\" value=\"" + unregLimit + "\" /><br/>";
  //content += "<input type=\"hidden\" name=\"semester_id\" id=\"semester_id\" value=\"" + document.getElementById("semester_id").value + "\" /><br/>";
  content += "Semester: " + selectedText + "<br/>";
  content += "<p align=\"center\"><input type=\"submit\" name=\"submit\" value=\"Update\"/>";
  content += "&nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  <input type=\"button\" name=\"submit\" value=\"Delete\" onclick=\"javascript:processDeleteEvent();\"/>";
  content += "&nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  <input type=\"button\" name=\"Cancel\" value=\"Cancel\" onclick=\"javascript:closePopupWindow();\"/></p>";
  newPopupWindow(title, content, width, height);
}
function processEditEvent() {
  u = "processEditEvent";
  var p = "eid=" + document.getElementById("eid").value + "&s=" + document.getElementById("st").value + "&e=" + document.getElementById("et").value
    + "&h=" + document.getElementById("hn").value + "&c=" + document.getElementById("cd").value + "&semester_id=" + document.getElementById("semester_id").value;
  processFunction = refreshPage;
  closePopupWindow();
  sendRequest(u, p);
}
function processDeleteEvent() {
  u = "processDeleteEvent";
  var p = "eid=" + document.getElementById("eid").value;
  processFunction = refreshPage;
  closePopupWindow();
  sendRequest(u, p);
}
function regEvent(eid, pid) {
  var width = 300;
  var height = 50;
  var title = "";

  content = "Do you want to volunteer in this event?<br/><br/>"
  content += "<p align=\"center\"><input style='height: 30px;' type=\"button\" name=\"yes\" value=\"Yes\" onclick=\"javascript:processRegEvent(" + eid + "," + pid + ");\"/>";
  content += "&nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  <input style='height: 30px;' type=\"button\" name=\"no\" value=\"No\" onclick=\"javascript:closePopupWindow();\"/></p>";

  newPopupWindow(title, content, width, height);
}
function processRegEvent(eid, pid) {
  u = "processRegEvent";
  var p = "eid=" + eid + "&pid=" + pid;
  processFunction = refreshPage;
  closePopupWindow();
  sendRequest(u, p);
}
function unregEvent(hid) {
  var width = 300;
  var height = 50;
  var title = "";

  content = "Do you want to cancel this registration?<br/><br/>"
  content += "<p align=\"center\"><input style='height: 30px;' type=\"button\" name=\"yes\" value=\"Yes\" onclick=\"javascript:processUnregEvent(" + hid + ");\"/>";
  content += "&nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  <input style='height: 30px;' type=\"button\" name=\"no\" value=\"No\" onclick=\"javascript:closePopupWindow();\"/></p>";

  newPopupWindow(title, content, width, height);
}
function processUnregEvent(hid) {
  u = "processUnregEvent";
  var p = "hid=" + hid;
  processFunction = refreshPage;
  closePopupWindow();
  sendRequest(u, p);
}
function checkShow(hid, showed) {
  u = "processCheckShow.php";
  var p = "hid=" + hid + "&showed=" + showed;
  processFunction = doNothing;
  sendRequest(u, p);
}
function doNothing(data) {
}
function refreshPage(data) {
  Calendar();
}
function newPopupWindow(title, content, width, height) {
  var dialogPanel = document.getElementById("dialogPanel")
  dialogPanelBg.style.visibility = 'visible';
  dialogPanel.style.visibility = 'visible';
  dialogPanel.style.width = width;
  dialogPanel.style.height = height;
  dialogPanel.innerHTML = '<table id="popWindow" width="' + width + '" cellspacing="0" cellpadding="5" border="1">' +
    '<tr><td id="popContent" valign="top">' +
    content +
    '</td></tr>' +
    '</table>';
}
//drag to move window

var dragObj = new Object();
function AttachEvent(obj, eventName, eventHandler) {
  if (obj) {
    if (eventName.substring(0, 2) == "on") {
      eventName = eventName.substring(2, eventName.length);
    }
    if (obj.addEventListener) {
      obj.addEventListener(eventName, eventHandler, false);
    } else if (obj.attachEvent) {
      obj.attachEvent('on' + eventName, eventHandler);
    }
  }
}
function DetachEvent(obj, eventName, eventHandler) {
  if (obj) {
    if (eventName.substring(0, 2) == "on") {
      eventName = eventName.substring(2, eventName.length);
    }
    if (obj.removeEventListener) {
      obj.removeEventListener(eventName, eventHandler, false);
    } else if (obj.detachEvent) {
      obj.detachEvent('on' + eventName, eventHandler);
    }
  }
}

function dragStart(event, id) {
  var el;
  var x, y;
  dragObj.elNode = document.getElementById(id);

  // Get cursor position with respect to the page.
  if (window.event) {
    //IE
    x = window.event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
    y = window.event.clientY + document.documentElement.scrollTop + document.body.scrollTop;
  } else {
    //FF
    x = event.pageX + document.documentElement.scrollLeft + document.body.scrollLeft;
    y = event.pageY + document.documentElement.scrollTop + document.body.scrollTop;
  }
  // Save starting positions of cursor and element.

  dragObj.cursorStartX = x;
  dragObj.cursorStartY = y;
  dragObj.elStartLeft = parseInt(dragObj.elNode.style.left, 10);
  dragObj.elStartTop = parseInt(dragObj.elNode.style.top, 10);

  if (isNaN(dragObj.elStartLeft)) dragObj.elStartLeft = 0;
  if (isNaN(dragObj.elStartTop))  dragObj.elStartTop = 0;
  // Capture mousemove and mouseup events on the page.
  AttachEvent(document, "onmousemove", dragGo);
  AttachEvent(document, "onmouseup", dragStop);
  event.cancelBubble = true;
  event.returnValue = false;
}

function dragGo(event) {

  var x, y;

  // Get cursor position with respect to the page.
  if (window.event) {
    //IE
    x = window.event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
    y = window.event.clientY + document.documentElement.scrollTop + document.body.scrollTop;
  } else {
    //FF
    x = event.pageX + document.documentElement.scrollLeft + document.body.scrollLeft;
    y = event.pageY + document.documentElement.scrollTop + document.body.scrollTop;
  }
  // Move drag element by the same amount the cursor has moved.

  dragObj.elNode.style.left = (dragObj.elStartLeft + x - dragObj.cursorStartX) + "px";
  dragObj.elNode.style.top = (dragObj.elStartTop + y - dragObj.cursorStartY) + "px";

  event.cancelBubble = true;
  event.returnValue = false;
}

function dragStop(event) {

  // Stop capturing mousemove and mouseup events.

  DetachEvent(document, "onmousemove", dragGo);
  DetachEvent(document, "onmouseup", dragStop);
}
//end draw to move window.
function setEnd(text) {
  if (text.createTextRange) {
    var FieldRange = text.createTextRange();
    FieldRange.moveStart('character', text.value.length);
    FieldRange.collapse();
    FieldRange.select();
  }
}
function closePopupWindow() {
  document.getElementById("dialogPanel").innerHTML = "";
  document.getElementById("dialogPanel").style.visibility = 'hidden';
  document.getElementById("dialogPanelBg").style.visibility = 'hidden';
  return false;
}
function getXMLHTTPRequest() {
  var xRequest = null;
  try {
    xRequest = new XMLHttpRequest();
  } catch (trymicrosoft) {
    try {
      xRequest = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (othermicrosoft) {
      try {
        xRequest = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (failed) {
        alert(strAjaxObjError);
      }
    }
  }
  return xRequest;
}
function sendRequest(url, params) {
  var HttpMethod = 'POST';
  //req=getXMLHTTPRequest();
  if (req) {
    req.open(HttpMethod, url, true);
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.onreadystatechange = onReadyStateChange;
    req.send(params);
  }
}
function onReadyStateChange() {
  if (req.readyState == 4) {
    try {
      if (req.status == 200) {
        processFunction(req.responseText);
      } else {
        alert("Error Loading " + u + ': ' + req.status);
      }
    } catch (e) {
      alert(e);
    }
  }
}