$(document).ready(function () {

    $.ajaxSetup({
        headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    setNumberSize();

    function setNumberSize(){
        $.ajax({
            type: "POST",
            url: "/admin/size-product/number",
            dataType: "json",
            success: function (response) {
                $("#sumSize").empty().text(response.data.sizeSum);
                $("#activeSize").empty().text(response.data.sizeActive);
                $("#inactiveSize").empty().text(response.data.sizeInactive);
                // console.log(data.data);
                return false;
            }
        });
    }

    var dataTable = $('#sizeTable').DataTable({
        ajax: {
            url: "/admin/size-product/all",
            type: "POST",
            dataType: "json",
            dataSrc: function(jsons){
                var sizesArray = $.map(jsons.data, function(obj) {
                    return {
                        checkbox: '<input type="checkbox" class="child" data-id="'+obj.id+'">',
                        name: obj.name,
                        status: renderToggleStatus(obj.status, obj.id),
                        action: `
                            <a href="#" data-id="${obj.id}" class="editSize" title="edit"><i class="fa fa-edit"></i></a>
                            <a href="#" data-id="${obj.id}" class="deleteSize" title="delete"><i class="fa fa-trash"></i></a>
                        `
                    }
                });

                return sizesArray;
            },
        },
        fnDrawCallback: function() {
            $('.toggle-status').bootstrapToggle();
        },
        columns: [
            { data: 'checkbox' },
            { data: 'name' },
            { data: 'status' },
            { data: 'action' }
        ],
    });

    function renderToggleStatus(status, id){
        var html = '<input type="checkbox" data-id="'+id+'" class="toggle-status" data-size="small"'
                    + ' data-onstyle="success" data-offstyle="danger"';
        if(status === 'active'){
            html += ' checked';
        }else{
            html += '';
        }
        html += ' data-toggle="toggle">';

        return html;
    }

    function resetFormAddSize(){
        $('#formStoreSize')[0].reset();
        $("input[name=size_id]").val('');
        $("#storeSizeModal").find(".modal-title").text('Add Size');
        $("#storeSizeModal").find(".store-size-btn").text('Add');
    }

    $(document).on('click', '#addSize', function(){
        $("#storeSizeModal").modal('show');
        resetFormAddSize();
    })

    $(document).on('click', '.store-size-btn', function(){
        $.ajax({
            type: "POST",
            url: "/admin/size-product/store",
            data: $("#formStoreSize").serialize(),
            dataType: "json",
            success: function (response) {
                console.log(response);
                messenger(response.data.success);
                dataTable.ajax.reload();
                setNumberSize();
                $("#storeSizeModal").modal('hide');
                return false;
            },
            error: function(response){
                $.each(response.responseJSON.errors, function(key, val) {
                    $(`.error-${key}`).text(val);
                });
                return false;
            }
        });
    });

    $(document).on('click', '.editSize', function(){
        let size_id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "/admin/size-product/edit",
            data: {size_id: size_id},
            dataType: "json",
            success: function (response) {
                // console.log(response.data);
                $(".error").empty();
                $("#title").val(response.data.title);
                $("#description").val(response.data.description);
                $(`.sta-${response.data.status}`).prop('checked', true);
                $("input[name=size_id]").val(size_id);
                $("#storeSizeModal").find(".modal-title").text('Edit Size');
                $("#storeSizeModal").find(".store-size-btn").text('Update');
                $("#storeSizeModal").modal('show');
                setNumberSize();
                return false;
            }
        });
    });

    $(document).on('click', '.deleteSize', function(){
        let size_id = $(this).data('id');
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
                    url: "/admin/size-product/delete",
                    data: {size_id:size_id},
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        dataTable.ajax.reload();
                        messenger(response.success);
                        setNumberSize();
                        return false;
                    },
                });
            }
        });
    });

    $(document).on('change','.toggle-status', function(){
        let size_id = $(this).data('id');
        let isChecked = $(this).prop('checked');
        $.ajax({
            type: "POST",
            url: "/admin/size-product/update-status",
            data: {isChecked:isChecked, size_id:size_id},
            dataType: "json",
            success: function (response) {
                // console.log(response.data);
                setNumberSize();
                messenger(response.data.success);
                return false;
            }
        });
    });

    $('#parent').on('click', function(){
        var check = $(this).is(':checked');
        $(this).parents('.table').find('.child').prop('checked', check)
    })

    $('#deleteMutipleSize').click(function(){
        let sizeIds = [];
        $('input:checkbox[class=child]:checked').each(function(i){
            sizeIds.push($(this).data('id'));
        });

        if(sizeIds.length === 0){
            reminder('Please select atleast one checkbox!')
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
                        url: "/admin/size-product/delete-rows",
                        data: {sizeIds:sizeIds},
                        dataType: "json",
                        success: function (response) {
                            setNumberSize();
                            dataTable.ajax.reload();
                            messenger(response.data.success);
                            return false;
                        }
                    });
                }
            })
        }
    });

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
