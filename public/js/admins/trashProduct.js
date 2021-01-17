$(document).ready(function () {

    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    setNumberTrash();

    function setNumberTrash(){
        $.ajax({
            type: "POST",
            url: "/admin/product/trash-number-product",
            dataType: "json",
            success: function (data) {
                $("#sumProductTrash").empty().text(data.data.trashProductSum);
                return false;
            }
        });
    }

    var dataTable = $('#trashProductTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            type: 'POST',
            url: '/admin/product/trash',
            dataType: 'json'
        },
        columns: [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'image_path', name: 'image_path', orderable: false, searchable: false},
            {data: 'price', name: 'price'},
            {data: 'quanity', name: 'quanity'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ]
    });

    $(document).on('click', '.destroy-trash', function(){
        let product_id = $(this).data('id');
        let publicId = $(this).data('public_id');
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
                    url: "/admin/product/destroy-trash",
                    data: {product_id:product_id, publicId: publicId},
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        dataTable.ajax.reload();
                        messenger(response.success);
                        setNumberTrash();
                        return false;
                    },
                });
            }
        });
    });

    $(document).on('click', '.restore-trash', function(){
        let product_id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "/admin/product/restore-trash",
            data: {product_id:product_id},
            dataType: "json",
            success: function (response) {
                console.log(response);
                dataTable.ajax.reload();
                messenger(response.success);
                setNumberTrash();
                return false;
            }
        });
    })

    $('#parent').on('click', function(){
        var check = $(this).is(':checked');
        $(this).parents('.table').find('.child').prop('checked', check)
    })

    $('#destroyAllProduct').click(function(){
        let productIds = [];
        let publicIds = [];
        $('input:checkbox[class=child]:checked').each(function(i){
            productIds.push($(this).data('id'));
            publicIds.push($(this).data('public_id'));
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
                        url: "/admin/product/destroy-rows",
                        data: {productIds:productIds, publicIds:publicIds},
                        dataType: "json",
                        success: function (response) {
                            setNumberTrash();
                            dataTable.ajax.reload();
                            messenger(response.data.number_product + ' Rows '+ response.data.success);
                            return false;
                        }
                    });
                }
            })
        }
    })

    $('#restoreAllProduct').click(function(){
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
                confirmButtonText: 'Yes, restore it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "/admin/product/restore-rows",
                        data: {productIds:productIds},
                        dataType: "json",
                        success: function (response) {
                            setNumberTrash();
                            dataTable.ajax.reload();
                            messenger(response.data.number_product + ' Rows '+ response.data.success);
                            return false;
                        }
                    });
                }
            })
        }
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
