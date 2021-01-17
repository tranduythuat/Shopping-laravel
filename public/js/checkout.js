$(document).ready(function () {
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $("header").css('display', 'none');

    $("#checkout").click(function(){
        $('#user-checkout-info').submit();
    })
});
