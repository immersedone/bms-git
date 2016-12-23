$(document).ready(function() {
    var csrf_token= Cookies.get('csrf_cookie');

    $.ajaxSetup({
        data: {
            'csrf_token' : csrf_token
        }
    });	
});