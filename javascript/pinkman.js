function setCookie(name, value)
{
        var d = new Date();
        var day = 365;
        d.setTime(d.getTime() + (day * 24 * 60 * 60 * 1000));
        var cookieExpireDate = "expires=" + d.toString();
        document.cookie = name + "=" + value + "; " + cookieExpireDate + "; path=/";
}

function reloadPage()
{
  location.reload(true);
}

$(document).ready(function(){
    $(".lngSelector").hover(function(){
        $(this).stop(true, false).animate({ scale:1.15});
        }, function() {
        $(this).stop(true, false).animate({ scale:1});
        });
});
