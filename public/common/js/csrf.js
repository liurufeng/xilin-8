/*
 * Sends the CSRF token (cookie csrf_cookie_name, checked by the csrf filter in app/Config/Filters.php) with every
 * form post and every non-GET AJAX call of the page, so the views need no hidden field. Added to each page by
 * app/Filters/CsrfScript.php. Plain ES5: the admin pages still run old scripts.
 */
(function () {
  if (window.xilinCsrf) {
    return;
  }

  function token() {
    var m = document.cookie.match(/(?:^|;\s*)csrf_cookie_name=([^;]*)/);
    return m ? decodeURIComponent(m[1]) : '';
  }

  function sameSite(url) {
    var a = document.createElement('a');
    a.href = url || location.href;
    return !a.host || a.host === location.host;
  }

  function addField(form) {
    if (!form || String(form.method).toLowerCase() !== 'post' || !sameSite(form.action)) {
      return;
    }
    var field = form.querySelector('input[name="csrf_test_name"]');
    if (!field) {
      field = document.createElement('input');
      field.type = 'hidden';
      field.name = 'csrf_test_name';
      form.appendChild(field);
    }
    field.value = token();
  }

  window.xilinCsrf = { token: token, addField: addField };

  // forms the visitor submits (capture: before the page's own submit handlers) ...
  document.addEventListener('submit', function (e) { addField(e.target); }, true);

  // ... and forms a script submits with form.submit(), which fires no submit event
  var submit = HTMLFormElement.prototype.submit;
  HTMLFormElement.prototype.submit = function () {
    addField(this);
    return submit.apply(this, arguments);
  };

  // AJAX (jQuery and the admin's own XMLHttpRequest code)
  var open = XMLHttpRequest.prototype.open;
  var send = XMLHttpRequest.prototype.send;
  XMLHttpRequest.prototype.open = function (method, url) {
    this.xilinCsrf = String(method).toUpperCase() !== 'GET' && sameSite(url);
    return open.apply(this, arguments);
  };
  XMLHttpRequest.prototype.send = function () {
    if (this.xilinCsrf) {
      this.setRequestHeader('X-CSRF-TOKEN', token());
    }
    return send.apply(this, arguments);
  };
})();
