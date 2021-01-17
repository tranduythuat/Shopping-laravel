$(document).ready(function () {

    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    setNumberSupplier();

    function setNumberSupplier(){
        $.ajax({
            type: "POST",
            url: "/admin/supplier/number",
            dataType: "json",
            success: function (data) {
                $("#sumSupplier").empty().text(data.data.supplierSum);
                // console.log(data.data);
                return false;
            }
        });
    }

    var dataTable = $('#supplierTable').DataTable({
        ajax: {
            url: "/admin/supplier/all",
            type: "POST",
            dataType: "json",
            dataSrc: function(jsons){
                return jsons.data.map(obj=>{
                    return {
                        name: obj.name,
                        email: obj.email,
                        phone: obj.phone,
                        website: obj.website,
                        action: `
                            <a href="#" data-id="${obj.id}" class="editSupplier" title="edit"><i class="fa fa-edit"></i></a>
                            <a href="#" data-id="${obj.id}" class="deleteSupplier" title="delete"><i class="fa fa-trash"></i></a>
                        `
                    }
                })
            },
        },
        columns: [
            { data: 'name' },
            { data: 'email' },
            { data: 'phone' },
            { data: 'website' },
            { data: 'action' }
        ],
    });

    function resetFormAddSupplier(){
        $('#formStoreSupplier')[0].reset();
        $('.error').empty();
        $('#imageShow').attr('src', '/images/img01.png');
        $("input[name=supplier_id]").val('');
        $("#storeSupplierModal").find(".modal-title").text('Add Supplier');
        $("#storeSupplierModal").find(".store-supplier-btn").text('Add');
    }

    $(document).on('click', '#addSupplier', function(){
        resetFormAddSupplier();
        $("#storeSupplierModal").modal('show');
    })

    $(document).on('click', '.store-supplier-btn', function(){
        $.ajax({
            type: "POST",
            url: "/admin/supplier/store",
            data: $("#formStoreSupplier").serialize(),
            dataType: "json",
            success: function (data) {
                // console.log(data);
                messenger(data.data.success);
                dataTable.ajax.reload();
                setNumberSupplier();
                $("#storeSupplierModal").modal('hide');
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

    $(document).on('click', '.editSupplier', function(){
        let supplier_id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "/admin/supplier/edit",
            data: {supplier_id: supplier_id},
            dataType: "json",
            success: function (response) {
                // console.log(response.data);
                $(".error").empty();
                $("input[name=supplier_id]").val(response.data.id);
                $("#name").val(response.data.name);
                $("#email").val(response.data.email);
                $("#phone").val(response.data.phone);
                $("#website").val(response.data.website);
                $("#storeSupplierModal").find(".modal-title").text('Edit Supplier');
                $("#storeSupplierModal").find(".store-supplier-btn").text('Update');
                $("#storeSupplierModal").modal('show');

                return false;
            }
        });
    });

    $(document).on('click', '.deleteSupplier', function(){
        let supplier_id = $(this).data('id');
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
                    url: "/admin/supplier/delete",
                    data: {supplier_id:supplier_id},
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        dataTable.ajax.reload();
                        messenger(response.success);
                        setNumberSupplier();
                        return false;
                    },
                });
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
