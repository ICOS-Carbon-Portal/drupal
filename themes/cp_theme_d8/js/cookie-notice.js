'use strict';

var checkedCpUserConsent = false;
checkCpUserConsent();

function getCookieByName(cn) {
  var n = cn + "=";
  var ca = document.cookie.split(";");

  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == " ") c = c.substring(1);
    if (c.indexOf(n) == 0) {
      return c.substring(n.length, c.length);
    }
  }

  return "";
}

function checkCpUserConsent() {
  if (checkedCpUserConsent) {
    return;
  }

  var hasConsent = getCookieByName("cpuserconsent");

  if (hasConsent === "") {
    var info = document.createElement("p");
    info.className = "info";
    var infot = document.createTextNode("We use cookies to analyze site use. See our privacy policy for more information.");
    info.appendChild(infot);
    var agree = document.createElement("p");
    agree.className = "agree";
    var agreet = document.createTextNode("Okay!");
    var check = document.createElement("input");
    check.type = "checkbox";
    check.name = "iagree";
    check.addEventListener("click", function() {
      var d = new Date();
      d.setTime(d.getTime() + (10 * 12 * 30 * 24 * 60 * 60 * 1000));
      var expires = "expires=" + d.toUTCString();
      document.cookie = "cpuserconsent=true;" + expires;
      var div = document.getElementById("cpuserconsent");
      div.parentNode.removeChild(div);
    });
    agree.appendChild(agreet);
    agree.appendChild(check);
    var div = document.createElement("div");
    div.id = "cpuserconsent";
    div.className = "eu-cookie-notice";
    div.appendChild(info);
    div.appendChild(agree);
    var page = document.getElementById("page");
    page.insertBefore(div, page.firstChild);
  }

  checkedCpUserConsent = true;
}
