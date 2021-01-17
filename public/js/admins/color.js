$(document).ready(function () {

    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $("#color").change(function(){
        let color = $(this).val();
        $(".color-text").val(color);
        return false;
    });

    setNumberColor();

    function setNumberColor(){
        $.ajax({
            type: "POST",
            url: "/admin/color-product/number",
            dataType: "json",
            success: function (data) {
                $("#sumColor").empty().text(data.data.colorSum);
                $("#activeColor").empty().text(data.data.colorActive);
                $("#inactiveColor").empty().text(data.data.colorInactive);
                // console.log(data.data);
                return false;
            }
        });
    }

    var dataTable = $('#colorTable').DataTable({
        ajax: {
            url: "/admin/color-product/all",
            type: "POST",
            dataType: "json",
            dataSrc: function(jsons){
                return jsons.data.map(obj=>{
                    return {
                        checkbox: '<input type="checkbox" class="child" data-id="'+obj.id+'">',
                        name: obj.name,
                        code: obj.code,
                        color: `<input type="color" disabled value="${obj.code}">`,
                        status: renderToggleStatus(obj.status, obj.id),
                        action: `
                            <a href="#" data-id="${obj.id}" class="editColor" title="edit"><i class="fa fa-edit"></i></a>
                            <a href="#" data-id="${obj.id}" class="deleteColor" title="delete"><i class="fa fa-trash"></i></a>
                        `
                    }
                })
            },
        },
        fnDrawCallback: function() {
            $('.toggle-status').bootstrapToggle();
        },
        columns: [
            { data: 'checkbox' },
            { data: 'name' },
            { data: 'code' },
            { data: 'color' },
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

    function resetFormAddColor(){
        $('#formStoreColor')[0].reset();
        $("input[name=color_id]").val('');
        $("#storeColorModal").find(".modal-title").text('Add Color');
        $("#storeColorModal").find(".store-color-btn").text('Add');
    }

    $(document).on('click', '#addColor', function(){
        $("#storeColorModal").modal('show');
        resetFormAddColor();
    })

    $(document).on('click', '.store-color-btn', function(){
        $.ajax({
            type: "POST",
            url: "/admin/color-product/store",
            data: $("#formStoreColor").serialize(),
            dataType: "json",
            success: function (data) {
                console.log(data);
                messenger(data.data.success);
                dataTable.ajax.reload();
                setNumberColor();
                $("#storeColorModal").modal('hide');
                return false;
            },
            error: function(data){
                $.each(data.responseJSON.errors, function(key, val) {
                    $(`.error-${key}`).text(val);
                });
                return false;
            }
        });
    });

    $(document).on('click', '.editColor', function(){
        let color_id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "/admin/color-product/edit",
            data: {color_id: color_id},
            dataType: "json",
            success: function (response) {
                // console.log(response.data);
                $(".error").empty();
                $("#name").val(response.data.name);
                $("#color").val(response.data.code);
                $(".color-text").val(response.data.code);
                $(`.sta-${response.data.status}`).prop('checked', true);
                $("input[name=color_id]").val(color_id);
                $("#storeColorModal").find(".modal-title").text('Edit Color');
                $("#storeColorModal").find(".store-color-btn").text('Update');
                $("#storeColorModal").modal('show');

                return false;
            }
        });
    });

    $(document).on('click', '.deleteColor', function(){
        let color_id = $(this).data('id');
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
                    url: "/admin/color-product/delete",
                    data: {color_id:color_id},
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        dataTable.ajax.reload();
                        messenger(response.success);
                        setNumberColor();
                        return false;
                    },
                });
            }
        });
    });

    $(document).on('change','.toggle-status', function(){
        let color_id = $(this).data('id');
        let isChecked = $(this).prop('checked');
        $.ajax({
            type: "POST",
            url: "/admin/color-product/update-status",
            data: {isChecked:isChecked, color_id:color_id},
            dataType: "json",
            success: function (response) {
                // console.log(response.data);
                setNumberColor();
                messenger(response.data.success);
                return false;
            }
        });
    });

    $('#parent').on('click', function(){
        var check = $(this).is(':checked');
        $(this).parents('.table').find('.child').prop('checked', check)
    })

    $('#deleteMutipleColor').click(function(){
        let colorIds = [];
        $('input:checkbox[class=child]:checked').each(function(i){
            colorIds.push($(this).data('id'));
        });

        if(colorIds.length === 0){
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
                        url: "/admin/color-product/delete-rows",
                        data: {colorIds:colorIds},
                        dataType: "json",
                        success: function (response) {
                            setNumberColor();
                            dataTable.ajax.reload();
                            messenger(response.data.success);
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
