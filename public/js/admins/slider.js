$(document).ready(function () {

    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    setNumberSlider();

    function setNumberSlider(){
        $.ajax({
            type: "POST",
            url: "/admin/slider/number",
            dataType: "json",
            success: function (data) {
                $("#sumSlider").empty().text(data.data.sliderSum);
                // console.log(data.data);
                return false;
            }
        });
    }

    var dataTable = $('#sliderTable').DataTable({
        ajax: {
            url: "/admin/slider/all",
            type: "POST",
            dataType: "json",
            dataSrc: function(jsons){
                return jsons.data.map(obj=>{
                    return {
                        title_01: obj.title_01,
                        title_02: obj.title_02,
                        link: obj.link,
                        image_path: `<img src="${ obj.image_path}" class="img-thumbnail" width="100">`,
                        action: `
                            <a href="#" data-id="${obj.id}" class="editSlider" title="edit"><i class="fa fa-edit"></i></a>
                            <a href="#" data-id="${obj.id}" class="deleteSlider" title="delete"><i class="fa fa-trash"></i></a>
                        `
                    }
                })
            },
        },
        columns: [
            { data: 'title_01' },
            { data: 'title_02' },
            { data: 'link' },
            { data: 'image_path', orderable: false, searchable: false},
            { data: 'action', orderable: false, searchable: false}
        ],
    });

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

    function resetFormAddSlider(){
        $('#formStoreSlider')[0].reset();
        $('.error').empty();
        $('#imageShow').attr('src', '/images/img01.png');
        $("input[name=slider_id]").val('');
        $("input[name=hidden_image_path]").val('');
        $("input[name=hidden_publicId]").val('');
        $("#storeSliderModal").find(".modal-title").text('Add Slider');
        $("#storeSliderModal").find(".store-slider-btn").text('Add');
    }

    $(document).on('click', '#addSlider', function(){
        $("#storeSliderModal").modal('show');
        resetFormAddSlider();
    })

    $(document).on('submit', '#formStoreSlider', function(e){
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "/admin/slider/store",
            data: formData,
            dataType: "json",
            cache:false,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data);
                messenger(data.data.success);
                dataTable.ajax.reload();
                $("#storeSliderModal").modal('hide');
                return false;
            },
            error: function(data){
                console.log(data);
                $.each(data.responseJSON, function(key, val) {
                    $(`.error-${key}`).text(val);
                });
                return false;
            }
        });
    });

    $(document).on('click', '.editSlider', function(){
        let slider_id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "/admin/slider/edit",
            data: {slider_id: slider_id},
            dataType: "json",
            success: function (response) {
                $(".error").empty();
                $("input[name=slider_id]").val(response.data.id);
                $("input[name=hidden_publicId]").val(response.data.publicId);
                $("#hidden_image_path").val(response.data.image_path);
                $('#imageShow').attr('src', response.data.image_path);

                $("#name").val(response.data.name);
                $("#title_01").val(response.data.title_01);
                $("#title_02").val(response.data.title_02);
                $("#link").val(response.data.link);

                $("#storeSliderModal").find(".modal-title").text('Edit Slider');
                $("#storeSliderModal").find(".store-slider-btn").text('Update');
                $("#storeSliderModal").modal('show');

                return false;
            }
        });
    });

    $(document).on('click', '.deleteSlider', function(){
        let slider_id = $(this).data('id');
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
                    url: "/admin/slider/delete",
                    data: {slider_id:slider_id},
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        dataTable.ajax.reload();
                        messenger(response.success);
                        setNumberSlider();
                        return false;
                    },
                });
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
});
