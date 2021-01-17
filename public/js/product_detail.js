$(document).ready(function () {
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $('.wrap-slick3').each(function(){
        $(this).find('.slick3').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            fade: true,
            infinite: true,
            autoplay: false,
            autoplaySpeed: 6000,

            arrows: true,
            appendArrows: $(this).find('.wrap-slick3-arrows'),
            prevArrow:'<button class="arrow-slick3 prev-slick3"><i class="fa fa-angle-left" aria-hidden="true"></i></button>',
            nextArrow:'<button class="arrow-slick3 next-slick3"><i class="fa fa-angle-right" aria-hidden="true"></i></button>',

            dots: true,
            appendDots: $(this).find('.wrap-slick3-dots'),
            dotsClass:'slick3-dots',
            customPaging: function(slick, index) {
                var portrait = $(slick.$slides[index]).data('thumb');

                return '<img src="' + portrait + '"/><div class="slick3-dot-overlay"></div>';
            },
        });
    });

    var productItem = {} || productItem;

    $('.js-addcart-detail').on('click', function(){
        if('product_color' in productItem && 'color_size' in productItem){
            let num_product = $('.num-product').val();
            $.ajax({
                type: "POST",
                url: "/product-add-to-cart",
                data: {num_product:num_product, productItem:productItem},
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    let product_cart_item = response.data.cart,
                    product_name = response.data.product,
                    totalPrice = response.data.totalPrice,
                    totalQuanity = response.data.totalQuanity;
                    // console.log(product_cart_item);
                    $("#cartWrapItem").html(product_cart_item);
                    $("#cartTotalPrice").html('Total: $' + totalPrice);

                    $(".js-show-cart").attr('data-notify', totalQuanity);
                    // console.log(c);
                    messenger(product_name + " is added to cart !");
                },
                error: function(data){
                    console.log(data);
                    $.each(data.responseJSON.errors, function(i, v){
                        $(".errors_product").append(
                            `<p class="alert alert-danger">
                                ${v}
                            </p>`
                        )
                    })

                    if('too_much' in data.responseJSON.errors){
                        $(".num-product").val(1);
                    }
                }
            });
        }else{
            _alert('Please select the product parameters above!');
            return false;
        }
    })

    function loadNumProductItem(productItem){
        if('product_color' in productItem && 'color_size' in productItem){
            $.ajax({
                type: "POST",
                url: "/num-product-item",
                data: {productItem:productItem},
                dataType: "json",
                success: function (response) {
                    $('#product-quanity').css('color', '#666');
                    $('.js-addcart-detail').attr('disabled', false);
                    $('.js-addcart-detail').removeClass('js-addcart-disabled');
                    $('#product-quanity').html('Quanity: ' + response.data)
                },
                error: function(response){
                    $.each(response.responseJSON.errors, function(i, v){
                        $('.js-addcart-detail').attr('disabled', true);
                        $('.js-addcart-detail').addClass('js-addcart-disabled');
                        $('#product-quanity').css('color', 'red');
                        $('#product-quanity').text(v);
                    })
                }
            });
        }else{
            return false;
        }
    }

    $('input[name=btn-color-item]').change(function(){
        if($(this).is(':checked')){
            let color = $(this).val();
            productItem.product_color = color;
            loadNumProductItem(productItem);
        }
    })

    $('input[name=btn-size-item]').change(function(){
        if($(this).is(':checked')){
            let size = $(this).val();
            productItem.color_size = size;
            loadNumProductItem(productItem);
        }
    });

    $(".num-product").on("keypress keyup blur",function (event) {
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    }); 

    $(document).on('click', ".header-cart-item-img", function(){
        let cart_id = $(this).data('id');
        $.ajax({
            type: "GET",
            url: "/delete-cart-item/" + cart_id,
            dataType: "json",
            success: function (response) {
                let totalPrice = response.data.totalPrice,
                totalQuanity = response.data.totalQuanity,
                product_name = response.data.product_name;

                $(`#cart-item-${cart_id}`).remove();
                $("#cartTotalPrice").html('Total: $' + totalPrice);
                $(".js-show-cart").attr('data-notify', totalQuanity);
                messenger(product_name + " is added to cart !");
            }
        });
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
