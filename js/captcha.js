$(function(){
    $("#reload-captcha").click(function() {
        $('#img-captcha').attr('src', 'captcha/img.php?id='+Math.random()+'');
    });
});