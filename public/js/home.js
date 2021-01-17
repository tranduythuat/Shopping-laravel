(function ($) {
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    var page = 1;
    var pageFilter = 1;
    var filterObj = {} || filterObj;

    $(document).on('click', "#loadMore", function(){
        let filterHas = $("#loadMore").data('filter');
        if(!filterHas){
            page = page + 1;
            loadProductList(page);
        }else{
            pageFilter = pageFilter + 1;
            loadFilterProduct(pageFilter, filterObj);
        }
    });

    loadProductList(page);

    function loadProductList(page){
        $.ajax({
            type: "POST",
            url: "/product",
            data: {page:page},
            dataType: "json",
            success: function (response) {
                console.log(response);
                let isReadMore = response.data.readMore,
                html = response.data.products;
                if(!isReadMore || html === ''){
                    $('#loadMore').css('display', 'none');
                }
                $('.isotope-grid').append(html).hide().fadeIn('3000');
            },
            error: function(response){
                $('.isotope-grid').empty().html('<p class="m-l-100">These products were not found</p')
                _alert('These products were not found!');
            }
        });
    }

    function loadFilterProduct(page, filter){
        $.ajax({
            type: "POST",
            url: "/product-filter",
            data: {page:page, filter:filter},
            dataType: "json",
            success: function (response) {
                console.log(response);
                let pageFilter = response.data.page,
                readMoreFilter = response.data.readMore,
                htmlFilter = response.data.products;

                if(!readMoreFilter || htmlFilter === ''){
                    $('#loadMore').css('display', 'none');
                }else{
                    $('#loadMore').css('display', 'block');
                }
                if(pageFilter === '1'){
                    $('.isotope-grid').empty().append(htmlFilter).hide().fadeIn('3000');
                }else{
                    $('.isotope-grid').append(htmlFilter).hide().fadeIn('3000');
                }
            },
            error: function(response){
                console.log(response);
                $('.isotope-grid').empty().html('<p class="m-l-100">These products were not found</p')
                _alert('These products were not found!');
            }
        });
    }

    var filterLink = $('.filter-link');

    $(filterLink).each(function(){
        $(this).on('click', function(){
            $("#loadMore").attr('data-filter', 'true');
            $('#loadMoreSearch').css('display', 'none');
            $('input[name=search-product]').val('');

            pageFilter = 1;
            filterObj.category = $('.category').parent().find('.how-active1').data('id');
            filterObj.sortBy = $('.sort-by').parent().parent().find('.filter-link-active').data('sort'),
            filterObj.filterByPriceMin = $('.filter-by-price').parent().parent().find('.filter-link-active').data('min'),
            filterObj.filterByPriceMax = $('.filter-by-price').parent().parent().find('.filter-link-active').data('max'),
            filterObj.filterByColor = $('.filter-by-color').parent().parent().find('.filter-link-active').data('color'),
            filterObj.filterBySize = $('.filter-by-size').parent().parent().find('.filter-link-active').data('size');
            filterObj.filterByTag = $('.filter-by-tag').parent().find('.tag-link-active').data('tag');

            loadFilterProduct(pageFilter, filterObj);
        });
    });

    function loadSearchProduct(page, val_search){
        $('#loadMore').css('display', 'none');
        $(filterLink).each(function(){
            $(this).removeClass('filter-link-active');
            $(this).removeClass('tag-link-active');
        })
        $.ajax({
            type: "POST",
            url: "/product-search",
            data: {page:page, val_search:val_search},
            dataType: "json",
            success: function (response) {
                console.log(response);
                let products = response.data.products,
                page = response.data.page,
                readMore = response.data.readMore;
                if(!readMore || products === ''){
                    $('#loadMoreSearch').css('display', 'none');
                }else{
                    $('#loadMoreSearch').css('display', 'block');
                }
                if(products === ''){
                    $('.isotope-grid').html('<p class="m-l-70">The products were not found!</p>').hide().fadeIn('3000');
                }else{
                    if(page === '1'){
                        $('.isotope-grid').html(products).hide().fadeIn('3000');
                    }else{
                        $('.isotope-grid').append(products).hide().fadeIn('3000');
                    }
                }
            }
        });
    }

    var pageSearch = 1;
    var data_search = '';
    $(document).on('click', "#loadMoreSearch", function(){
        pageSearch = pageSearch + 1;
        loadSearchProduct(pageSearch, data_search);
    });

    $('input[name=search-product]').keyup(function(){
        let val_search = $(this).val(),
        data_search = val_search;
        pageSearch = 1;
        loadSearchProduct(pageSearch, data_search);
    });

    var $topeContainer = $('.isotope-grid');

    $(document).on('load', function () {
        var $grid = $topeContainer.each(function () {
            $(this).isotope({
                itemSelector: '.isotope-item',
                layoutMode: 'fitRows',
                percentPosition: true,
                animationEngine : 'best-available',
                masonry: {
                    columnWidth: '.isotope-item'
                }
            });
        });
    });

    // Modal
    $(document).on('click', '.js-show-modal1',function(e){
        e.preventDefault();
        let product_id = $(this).data('id');
        $('.errors_product').empty();
        $('#quanity_hidden').val("");
        $(".num-product").val(1);
        $.ajax({
            type: "POST",
            url: "/product-overview",
            data: {product_id:product_id},
            dataType: "json",
            success: function (response) {
                // console.log(response.data);
                $("#product_name").text(response.data.name);
                $("#product_price").text('$' + response.data.price);
                $("#product_description").text(response.data.description);
                $("#quanity").text(response.data.quanity);
                let product_colors = response.data.product_colors;
                let sizes = response.data.sizes;

                if(typeof product_colors !== 'undefined' && product_colors.length > 0){
                    if(typeof product_colors[0] == 'object'){
                        loadImages(response.data.product_colors[0].images);
                    }

                    render_product_colors(product_colors);
                }else{
                    render_product_colors(false);
                    loadImages(false);
                }

                if(typeof sizes !== 'underfined'){
                    render_color_sizes(sizes);
                }else{
                    render_color_sizes(false);
                }

                $('.js-modal1').addClass('show-modal1');
            }
        });
    });

    function render_product_colors(data)
    {
        let html = '';
        $.each(data, function(i, v){
            html += `
            <label class="color-btn-tick ${(v.sum == 0)?'btn-color--disabled':''}">
                <input type="radio" name="color" id="${v.id}" class="color-radio" ${(v.sum == 0)?'disabled':''}>
                <span class="checkmark-color">${v.color.name}</span>
            </label>
            `;
        })

        $(".product-color-group").html(html);
    }

    function render_color_sizes(data)
    {
        let html = '';
        $.each(data, function(i, v){
            html += `
            <label class="size-btn-tick ${(v.is_tick === 'false')?'btn-size--disabled':''}">
                <input type="radio" name="size" id="${v.id}" class="size-radio" ${(v.is_tick === 'false')?'disabled':''}>
                <span class="checkmark-size">${v.name}</span>
            </label>
            `;
        })
        $(".product-size-group").html(html);
    }

    function loadImages(data)
    {
        let html = `
            <div class="wrap-slick3 flex-sb flex-w">
                <div class="wrap-slick3-dots"></div>
                <div class="wrap-slick3-arrows flex-sb-m flex-w"></div>

                <div class="slick3 gallery-lb">`;
        if(data == false){
            html += `
                <div class="item-slick3" data-thumb="https://static.thenounproject.com/png/59103-200.png">
                    <div class="wrap-pic-w pos-relative">
                        <img src="https://static.thenounproject.com/png/59103-200.png" alt="IMG-PRODUCT">

                        <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="https://static.thenounproject.com/png/59103-200.png">
                            <i class="fa fa-expand"></i>
                        </a>
                    </div>
                </div>
            `;
        }else{
            $.each(data, function(i,v){
                html += `
                <div class="item-slick3" data-thumb="${v.image_path}">
                    <div class="wrap-pic-w pos-relative">
                        <img src="${v.image_path}" alt="IMG-PRODUCT">

                        <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="${v.image_path}">
                            <i class="fa fa-expand"></i>
                        </a>
                    </div>
                </div>
                `;
            })
        }

        html += `
            </div>
        </div>`
        ;
        $("#imageList").html(html);

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
    }

    $(document).on('change', '#colors', function(){
        let colorSizeId = $(this).val();
        console.log(colorSizeId);
    })

    $(document).on('click', '.js-hide-modal1',function(){
        $('.js-modal1').removeClass('show-modal1');
        delete productQuanity.product_color_id;
        delete productQuanity.color_size_id;
    });

    $(document).on("click",".color-radio", function(){
        $('.color-btn-tick').removeClass('btn-color--checked');
        if ($(this).is(':checked')){
            $(this).parent().addClass('btn-color--checked');
            console.log('product_color_id: ' + $(this).prop('id'));
            let product_color_id =  $(this).prop('id');
            loadQuanity(product_color_id, color_size_id = '');
        }
    })

    $(document).on("click", ".size-radio", function(){
        $('.size-btn-tick').removeClass('btn-size--checked');
        if ($(this).is(':checked')){
            $(this).parent().addClass('btn-size--checked');
            console.log('color_size_id: ' + $(this).prop('id'));
            let color_size_id = $(this).prop('id');
            loadQuanity(product_color_id = '', color_size_id);
        }
    })

    var productQuanity = {} || tem;

    function loadQuanity(product_color_id, color_size_id) {
        if(product_color_id !== ''){
            productQuanity.product_color_id = product_color_id
        }
        if(color_size_id !== ''){
            productQuanity.color_size_id = color_size_id
        }

        if('product_color_id' in productQuanity && 'color_size_id' in productQuanity){
            $.ajax({
                type: "POST",
                url: "/product-quanity",
                data: {productQuanity:productQuanity},
                dataType: "json",
                success: function (response) {
                    console.log(response.data);
                    $("#quanity").text(response.data);
                    $("#quanity_hidden").val(response.data);
                    return false;
                }
            });
        }
    }

    $(".num-product").on("keypress keyup blur",function (event) {
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    $(document).on('click', '.js-addcart-detail', function(){
        let product_detail = {} || product_detail;
        product_detail.product_color = productQuanity.product_color_id ? productQuanity.product_color_id: '';
        product_detail.color_size = productQuanity.color_size_id ? productQuanity.color_size_id : '';
        product_detail.num_product = $(".num-product").val();
        product_detail.quanity = $("#quanity_hidden").val();
        // console.log(product_detail);
        $('.errors_product').empty();
        $("#cartWrapItem").empty();
        $.ajax({
            type: "POST",
            url: "/product-addcart",
            data: {product_detail:product_detail},
            dataType: "json",
            success: function (response) {
                console.log(response.data);
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
    })

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
})(jQuery);

