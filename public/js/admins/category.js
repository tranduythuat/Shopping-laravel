$(document).ready(function () {

    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    setNumberCategory();

    function setNumberCategory(){
        $.ajax({
            type: "POST",
            url: "/admin/category/number",
            dataType: "json",
            success: function (data) {
                $("#sumCategory").empty().text(data.data.categorySum);
                $("#activeCategory").empty().text(data.data.categoryActive);
                $("#inactiveCategory").empty().text(data.data.categoryInactive);
                // console.log(data.data);
                return false;
            }
        });
    }

    var dataTable = $('#categoryTable').DataTable({
        ajax: {
            url: "/admin/category/all",
            type: "POST",
            dataType: "json",
            dataSrc: function(jsons){
                return jsons.data.map(obj=>{
                    return {
                        name: obj.name,
                        image_path: `<img src="${ obj.image_path}" class="img-thumbnail" width="100">`,
                        status: renderToggleStatus(obj.status, obj.id),
                        action: `
                            <a href="#" data-id="${obj.id}" class="editCategory" title="edit"><i class="fa fa-edit"></i></a>
                            <a href="#" data-id="${obj.id}" class="deleteCategory" title="delete"><i class="fa fa-trash"></i></a>
                        `
                    }
                })
            },
        },
        fnDrawCallback: function() {
            $('.toggle-status').bootstrapToggle();
        },
        columns: [
            { data: 'name' },
            { data: 'image_path' },
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

    function resetFormAddCategory(){
        $('#formStoreCategory')[0].reset();
        $('.error').empty();
        $('#imageShow').attr('src', '/images/img01.png');
        $("input[name=category_id]").val('');
        $("input[name=hidden_image_path]").val('');
        $("input[name=hidden_publicId]").val('');
        $("#storeCategoryModal").find(".modal-title").text('Add Category');
        $("#storeCategoryModal").find(".store-category-btn").text('Add');
    }

    $(document).on('click', '#addCategory', function(){
        $("#storeCategoryModal").modal('show');
        resetFormAddCategory();
    })

    $(document).on('submit', '#formStoreCategory', function(e){
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "/admin/category/store",
            data: formData,
            dataType: "json",
            cache:false,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data);
                messenger(data.data.success);
                dataTable.ajax.reload();
                setNumberCategory();
                $("#storeCategoryModal").modal('hide');
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

    $(document).on('click', '.editCategory', function(){
        let category_id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "/admin/category/edit",
            data: {category_id: category_id},
            dataType: "json",
            success: function (response) {
                // console.log(response.data);
                $(".error").empty();
                $("input[name=category_id]").val(response.data.id);
                $("input[name=hidden_publicId]").val(response.data.publicId);
                $("#name").val(response.data.name);
                $(`.sta-${response.data.status}`).prop('checked', true);
                $("#storeCategoryModal").find(".modal-title").text('Edit Category');
                $("#hidden_image_path").val(response.data.image_path);
                $('#imageShow').attr('src', response.data.image_path);
                $("#storeCategoryModal").find(".store-category-btn").text('Update');
                $("#storeCategoryModal").modal('show');

                return false;
            }
        });
    });

    $(document).on('click', '.deleteCategory', function(){
        let category_id = $(this).data('id');
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
                    url: "/admin/category/delete",
                    data: {category_id:category_id},
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        dataTable.ajax.reload();
                        messenger(response.success);
                        setNumberCategory();
                        return false;
                    },
                });
            }
        });
    });

    $(document).on('change','.toggle-status', function(){
        let category_id = $(this).data('id');
        let isChecked = $(this).prop('checked');
        $.ajax({
            type: "POST",
            url: "/admin/category/update-status",
            data: {isChecked:isChecked, category_id:category_id},
            dataType: "json",
            success: function (response) {
                // console.log(response.data);
                setNumberCategory();
                messenger(response.data.success);
                return false;
            }
        });
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
