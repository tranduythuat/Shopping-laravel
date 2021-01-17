$(document).ready(function () {

    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    setNumberProduct();

    function setNumberProduct(){
        $.ajax({
            type: "POST",
            url: "/admin/product/number",
            dataType: "json",
            success: function (data) {
                $("#sumProduct").empty().text(data.data.productSum);
                $("#activeProduct").empty().text(data.data.productActive);
                $("#inactiveProduct").empty().text(data.data.productInactive);
                return false;
            }
        });
    }

    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    var dataTable = $('#productTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        // order: [],
        fnDrawCallback: function() {
            $('.toggle-status').bootstrapToggle();
        },
        ajax: {
            type: 'POST',
            url: '/admin/product/all',
            dataType: 'json'
        },
        columns: [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'image_path', name: 'image_path', orderable: false, searchable: false},
            {data: 'price', name: 'price'},
            {data: 'quanity', name: 'quanity'},
            {data: 'category', name: 'category'},
            {data: 'tag', name: 'tag'},
            {data: 'product_item', name: 'product_item', orderable: false, searchable: false},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ]
    });

    function resetFormAddProduct(){
        $('#formStoreProduct')[0].reset();
        $("input[name=product_id]").val('');
        $("#storeProductModal").find(".modal-title").text('Add Product');
        $("#storeProductModal").find(".store-color-btn").text('Add');
    }

    $(document).on('click', '#addProduct', function(){
        $("#storeProductModal").modal({
            focus: false,
            keyboard: false,
            backdrop: false
        });
        resetFormAddProduct();
    })

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#imageShow').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#image_path").change(function(){
        readURL(this);
    });

    $(document).on('click', '.productInfo', function(){
        let productId = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "/admin/product/show/" + productId,
            dataType: "json",
            success: function (response) {
                console.log(response.data);
                rederDetailProduct(response.data);
                $('#infoRroductModal').modal('show');
            }
        });
    });

    function rederDetailProduct(data) {
        let html = '';
        $.each(data.product, function(i,v){
            html += `
            <div class="row">
                <div class="col-md-5">
                    <img src="${v.image_path}" class="img-thumbnail" alt="" style="width: 100%">
                </div>
                <div class="col-md-7 py-3 px-5">
                    <table>
                        <tr>
                            <td class="pr-4 pb-2"><strong>Name</strong></td>
                            <td class="pb-2">${v.name}</td>
                        </tr>
                        <tr>
                            <td class="pr-4 pb-2"><strong>Price</strong></td>
                            <td class="pb-2">${ number_format(v.price) }</td>
                        </tr>
                        <tr>
                            <td class="pr-4 pb-2"><strong>Sale</strong></td>
                            <td class="pb-2">${v.sale}</td>
                        </tr>
                        <tr>
                            <td class="pr-4 pb-2"><strong>Hot</strong></td>
                            <td class="pb-2">${v.hot}</td>
                        </tr>
                        <tr>
                            <td class="pr-4 pb-2"><strong>Quanity</strong></td>
                            <td class="pb-2">${v.qunaity}</td>
                        </tr>
                        <tr>
                            <td class="pr-4 pb-2"><strong>View</strong></td>
                            <td class="pb-2">${v.view}</td>
                        </tr>
                        <tr>
                            <td class="pr-4 pb-2"><strong>Status</strong></td>
                            <td class="pb-2 ${(v.status == 'active')?"text-primary":"text-danger"}">${v.status}</td>
                        </tr>
                        <tr>
                            <td class="pr-4 pb-2"><strong>Created at</strong></td>
                            <td class="pb-2">${v.created_at}</td>
                        </tr>
                        <tr>
                            <td class="pr-4 pb-2"><strong>Suppliser</strong></td>
                            <td class="pb-2">${v.supplier.name}</td>
                        </tr>
                        <tr>
                            <td lass="pr-4 pb-2"><strong>Category</strong></td>
                            <td class="pb-2">${v.category.name}</td>
                        </tr>
                        <tr>
                            <td lass="pr-4 pb-2"><strong>Tag</strong></td>
                            <td class="pb-2">${renderTagProduct(v.tags)}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-12 my-3">
                    <strong>Items:</strong>
                    ${renderProductColor(data.color)}
                </div>
                <div class="col-12 my-3">
                    <strong>Description: </strong>
                    <div>${v.description}</div>
                </div>
                <div class="col-12 ">
                    <strong>Content: </strong>
                    <div class="content">${v.content}</div>
                </div>
            </div>
            `;
        })

        $("#infoRroductModal").find(".modal-body").html(html);
    }

    function number_format(number)
    {
        return new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'USD' }).format(number)
    }

    function renderTagProduct(tags)
    {
        let tag = '';
        $.each(tags, function(i, v){
            tag += `
                <span class="badge badge-dark mr-2">${v.name}</span>
            `;
        })

        return tag;
    }

    function renderProductColor(product_color)
    {
        let html = '';
        $.each(product_color, function(i, v){
            html += `
                <div class="card m-2">
                    <div class="card-header" style="background-color:${v[0].code}">
                        <div class="shadow-sm p-2 rounded" style="background-color:white !important; max-width: 300px">${v[0].name}</div>
                    </div>
                    <div class="card-body">
                        ${renderColorSizes(v['size'])}
                    </div>
                    <div class="card-footer text-muted">
                        ${renderImageByColor(v['image'])}
                    </div>
                </div>
           `;
        });

        return html;
    }

    function renderColorSizes(sizes)
    {
        let sizeHtml = '';
        $.each(sizes, function(i, v){
            $.each(v, function(i, v){
                sizeHtml += `
                    <span class="badge badge-pill badge-primary p-2">Size: ${v.name} (${v.pivot.quanity})</span>
                `;
            })
        })

        return sizeHtml;
    }

    function renderImageByColor(images)
    {
        let imageHtml = '';
        $.each(images, function(i, v){
            $.each(v, function(i, v){
                imageHtml += `
                    <img class="img-thumbnail" src="${v.image_path}" alt="" style="max-width:120px">
                `;
            })
        })

        return imageHtml;
    }
    $(document).on('click', '.deleteProduct', function(){
        let product_id = $(this).data('id');
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
                    url: "/admin/product/delete",
                    data: {product_id:product_id},
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        dataTable.ajax.reload();
                        messenger(response.success);
                        setNumberProduct();
                        return false;
                    },
                });
            }
        });
    });

    $('#parent').on('click', function(){
        var check = $(this).is(':checked');
        $(this).parents('.table').find('.child').prop('checked', check)
    })

    $('#deleteAllProduct').click(function(){
        let productIds = [];
        $('input:checkbox[class=child]:checked').each(function(i){
            productIds.push($(this).data('id'));
        });

        if(productIds.length === 0){
            reminder('Please Select atleast one checkbox')
        }else{
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
                        url: "/admin/product/delete-rows",
                        data: {productIds:productIds},
                        dataType: "json",
                        success: function (response) {
                            setNumberProduct();
                            dataTable.ajax.reload();
                            messenger(response.data.number_product + ' Rows '+ response.data.success);
                            return false;
                        }
                    });
                }
            })
        }
    })

    $(document).on('change','.toggle-status', function(){
        let product_id = $(this).data('id');
        let isChecked = $(this).prop('checked');
        $.ajax({
            type: "POST",
            url: "/admin/product/update-status",
            data: {isChecked:isChecked, product_id:product_id},
            dataType: "json",
            success: function (response) {
                // console.log(response.data);
                setNumberProduct();
                messenger(response.data.success);
                return false;
            },
            error: function (response) {
                console.log(response);
                $(this).attr('checked', false);
                reminder(response.responseJSON.errors)
            }
        });
    });

    function reminder(_text){
        $.toast({
            text: _text,
            position: 'bottom-right',
            showHideTransition: 'slide',
            loader: true,
            loaderBg: '	#f11b04'
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
