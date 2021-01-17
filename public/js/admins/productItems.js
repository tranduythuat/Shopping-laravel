$(document).ready(function () {

    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    var product_id = $('#productId').val();

    $("#colors").select2({
        tags: true
    });

    $("#sizes").select2({
        tags: true
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    setNumberProductItem();

    function setNumberProductItem(){
        $.ajax({
            type: "POST",
            url: "/admin/product/item/"+ product_id +"/number",
            dataType: "json",
            success: function (data) {
                $("#sumProductItem").empty().text(data.data);
                return false;
            }
        });
    }

    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    renderProductItems();

    function renderProductItems()
    {
        $.ajax({
            url : "/admin/product/item/" + product_id,
            method : "POST",
            dataType : "json",
            success : function(data){
                $('#tbProductItem').empty();
                var i = 1;
                $.each(data.data, function(key, val){
                    // console.log(val[0]);
                    $('#tbProductItem').append(
                        "<tr>"+
                            "<td>"+ (i++) +"</td>"+
                            "<td>" + val.color.name + "</td>"+
                            "<td>"+
                                "<a href='/admin/product/item/"+product_id+"/product-color/"+key+"/list-image'><i class='fa fa-upload' aria-hidden='true'></i></a></br>" +
                                renderImages(val['images'])+
                            "</td>"+
                            "<td>"+ renderSizes(val[0], key, val.color.id ) +"</td>"+
                            "<td>"+
                                "<a href='javascript:;' data-product-color-id='"+key+"' class='deleteProductColor'><i class='fa fa-trash'></i></a>" +
                            "</td>"+
                        "</tr>"
                    );
                });
            }
        });
    }

    function renderSizes(data, productColorId, colorId)
    {
        size = '';
        $.each(data, function(i, v){
            // console.log(v);
            size += `
            <table>
                <tr>
                    <td style="width:100%"><p>Size: ${v[0].name} (${v.quanity})</p></td>
                    <td>
                        <a href="javascript:;" title="edit product item" data-color-id="${colorId}" data-size-id="${v[0].id}" class="editProductColor"><i class="fa fa-edit"></i></a>
                        <a href="javascript:;" title="delete product itemt" data-color-id="${colorId}" data-size-id="${v[0].id}" class="deleteColorSize"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            </table>`
        });
        return size;
    }

    function renderImages(data)
    {
        image = '';
        $.each(data[0], function(key, val){
            $.each(val, function(i, v){
                image += `<img src="${v.image_path}" class="img-thumbnail" width="75" height="100">`;
            })
        });

        return image;
    }

    function resetFormAddProductItem(){
        $('#formStoreProductItem')[0].reset();
        $("input[name=product_color_id]").val('');
        $("#storeProductItemModal").find(".modal-title").text('Add Product Item');
        $("#storeProductItemModal").find(".store-product-item-btn").text('Add');
    }

    $(document).on('click', '#addProductItem', function(){
        $("#storeProductItemModal").modal('show');
        resetFormAddProductItem();
        getListColors();
        getListSizes();
    })

    function getListColors(color_id = null)
    {
        $.ajax({
            type: "post",
            url: "/admin/color-product/all",
            dataType: "json",
            success: function (response) {
                let html = '';
                html += `<option value="">Choose</option>`;
                $.each(response.data, function(i, v){
                    html += `<option value="${v.id}" data-code=${v.code} ${(color_id==v.id)?"selected":""}>${v.name}</option>`
                });

                $("#colors").html(html);
            }
        });
    }

    function getListSizes(sizeId = null)
    {
        $.ajax({
            type: "POST",
            url: "/admin/size-product/all",
            dataType: "json",
            success: function (response) {
                let html = '';
                html += '<option value="">Choose</option>';
                $.each(response.data, function(i, v){
                    html += `<option value="${v.id}" ${(sizeId == v.id)?"selected":""}>${v.name}</option>`
                })

                $("#sizes").html(html);
            }
        });
    }

    $(".store-product-item-btn").on('click', function(){
        $.ajax({
            type: "POST",
            url: "/admin/product/item/" + product_id + "/add",
            data: $("#formStoreProductItem").serialize(),
            dataType: "json",
            success: function (data) {
                console.log(data);
                messenger(data.data.success);
                renderProductItems();
                setNumberProductItem();
                $("#storeProductItemModal").modal('hide');
                return false;
            },
            error: function(data){
                $.each(data.responseJSON.errors, function(key, val) {
                    $(`.error-${key}`).text(val);
                });
                return false;
            }
        });
    })

    $(document).on('click', '.editProductColor', function(){
        let colorId = $(this).data('color-id');
        let sizeId = $(this).data('size-id');
        $.ajax({
            type: "POST",
            url: "/admin/product/item/" + product_id +"/color/"+colorId+"/size/" + sizeId,
            dataType: "json",
            success: function (response) {
                console.log(response);
                console.log(response.data['productColorByColor'][0].color_id);
                let color_id = response.data['productColorByColor'][0].color_id;
                let size_id = response.data['size'][0].size_id;
                let quanity = response.data['size'][0].quanity;
                $("input[name=product_color_id]").val(response.data['productColorId']);
                $("input[name=size_id]").val(size_id);
                $("#storeProductItemModal").find(".modal-title").text('Edit Product Item');
                $("#storeProductItemModal").find(".store-product-item-btn").text('Update');
                getListColors(color_id);
                getListSizes(size_id);
                $("#quanity").val(quanity);
                $("#storeProductItemModal").modal('show');
            }
        });
    });

    $(document).on('click', '.deleteColorSize', function(){
        let colorId = $(this).data('color-id');
        let sizeId = $(this).data('size-id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "/admin/product/item/"+ product_id +"/color/" + colorId + "/size/" + sizeId + "/delete",
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        renderProductItems();
                        setNumberProductItem();
                        messenger(response.success);
                        return false;
                    }
                });
            }
        });

    })

    $(document).on('click', '.deleteProductColor', function(){
        let productColorId = $(this).data('product-color-id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "/admin/product/item/"+ product_id +"/product-color/" + productColorId + "/delete",
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        renderProductItems();
                        setNumberProductItem();
                        messenger(response.success);
                        return false;
                    }
                });
            }
        });

    })

    function reminder(_text){
        $.toast({
            text: _text,
            position: 'bottom-right',
            showHideTransition: 'slide',
            loader: true,
            loaderBg: '#9EC600'
        })
    }

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
});
