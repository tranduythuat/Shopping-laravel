$(document).ready(function () {
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $('.wrap-icon-header ').css('display', 'none');

    $('.btn-num-product-down').on('click', function(){
        let num = $(this).parent().find('.num-product').val();
        let cart_id =  $(this).parent().find('.num-product').data('cart');
        updateNumCartItem(num, cart_id);
        console.log(cart_id + num);
    })

    $('.btn-num-product-up').on('click', function(){
        let num = $(this).parent().find('.num-product').val();
        let cart_id =  $(this).parent().find('.num-product').data('cart');
        updateNumCartItem(num, cart_id)
        console.log(cart_id + num);
    })

    function updateNumCartItem(num, cart_id)
    {
        $.ajax({
            type: "POST",
            url: "/cart/update-cart-item",
            data: {num:num, cart_id:cart_id},
            dataType: "json",
            success: function (response) {
                console.log(response);
                let total_cart_item = response.data.totalCartItem,
                sub_total = response.data.subTotal,
                total_price = response.data.totalPrice;

                $(`#total-item-${cart_id}`).html('$ ' + total_cart_item);
                $('#sub_total').html('$ ' + sub_total);
                $('#totalPrice').html('$ ' + total_price);
            },
            error: function(response){
                console.log(response);
                $.each(response.responseJSON.errors, function(i, v){
                    _alert(v);
                })
                $(`#num-product-${cart_id}`).val(1);
            }
        });
    }

    $(".num-product").on("blur",function (event) {
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        let num = $(this).val();
        let cart_id =  $(this).data('cart');
        updateNumCartItem(num, cart_id)
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    function messenger(_text){
        $.toast({
            heading: 'Success',
            text: _text,
            icon: 'success',
            position: 'bottom-right',
            showHideTransition: 'slide',
            loader: true,
            loaderBg: '#9EC600'
        })
    }

    function _alert(_text){
        $.toast({
            heading: 'Information',
            text: _text,
            icon: 'info',
            position: 'bottom-right',
            showHideTransition: 'slide',
            loader: true,
            loaderBg: '#9EC600'
        })
    }
});
