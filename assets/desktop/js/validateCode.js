/**
 * Created by 1040170 on 2019/3/21.
 */

$(window).load(function () {
    validateCode();
});


function validateCode() {
    $('#imgTryCode').attr('src', '/asp/agTryValidateCodeForIndex.php');
    $('#imgTryCode1').attr('src', '/asp/emailValidateCodeForIndex.php');
    $('#imgTryCode3').attr('src', '/asp/agTryValidateCodeForIndex.php');
    $('#imgTryCode2').attr('src', '/asp/emailValidateCodeForIndex.php');
    $('#imgTryCode4').attr('src', '/asp/agTryValidateCodeForIndex.php');
    $('#imgTryCode5').attr('src', '/asp/agTryValidateCodeForIndex.php');
}

$(document).on('click', '#imgTryCode', function () {
    $('#imgTryCode').attr('src', '/asp/agTryValidateCodeForIndex.php?r=' + Math.random());
});

$(document).on('click', '#imgTryCode1', function () {
    $('#imgTryCode1').attr('src', '/asp/emailValidateCodeForIndex.php?r=' + Math.random());
});

$(document).on('click', '#imgTryCode2', function () {
    $('#imgTryCode2').attr('src', '/asp/emailValidateCodeForIndex.php?r=' + Math.random());
});

$(document).on('click', '#imgTryCode3', function () {
    $('#imgTryCode3').attr('src', '/asp/agTryValidateCodeForIndex.php?r=' + Math.random());
});

$(document).on('click', '#imgTryCode4', function () {
    $('#imgTryCode4').attr('src', '/asp/agTryValidateCodeForIndex.php?r=' + Math.random());
});
$(document).on('click', '#imgTryCode5', function () {
    $('#imgTryCode5').attr('src', '/asp/agTryValidateCodeForIndex.php?r=' + Math.random());
});

function refreshValidateCode() {
    $('#imgTryCode').attr('src', '/asp/agTryValidateCodeForIndex.php?r=' + Math.random());
    $('#imgTryCode3').attr('src', '/asp/agTryValidateCodeForIndex.php?r=' + Math.random());
    $('#imgTryCode1').attr('src', '/asp/emailValidateCodeForIndex.php?r=' + Math.random());
    $('#imgTryCode2').attr('src', '/asp/emailValidateCodeForIndex.php?r=' + Math.random());
    $('#imgTryCode4').attr('src', '/asp/agTryValidateCodeForIndex.php?r=' + Math.random());
    $('#imgTryCode5').attr('src', '/asp/agTryValidateCodeForIndex.php?r=' + Math.random());
}