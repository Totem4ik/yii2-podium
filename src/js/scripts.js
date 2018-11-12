function createCookie(name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    } else var expires = "";
    document.cookie = name + "=" + value + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

var sCookieName = $(".cookie-box");

function setCookieWarning(active) {
    if(active != true){
        sCookieName.addClass("cookie-box--hide");
    }else{
        sCookieName.removeClass("cookie-box--hide");
    }
}
console.log(setCookieWarning(false));
$('#acceptCookies').on("click", function() {
    console.log("cookie accept");
    createCookie(sCookieName, 1, 365);
    setCookieWarning(false);
});

// cookie warning
if (readCookie(sCookieName) != 1) {
    setCookieWarning(true);
}

$("#js-cookie-close").on("click", function() {
    console.log("cookie close");
    eraseCookie(sCookieName);
    setCookieWarning(false);
});