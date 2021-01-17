$(document).ready(function () {

    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    var dataTable = $('#transactionTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        order: [],
        ajax: {
            type: 'POST',
            url: '/admin/dashboard',
            dataType: 'json'
        },
        columns: [
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'created_at', name: 'created_at'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ]
    });

    $('#parent').on('click', function(){
        var check = $(this).is(':checked');
        $(this).parents('.table').find('.child').prop('checked', check)
    })

    $(document).on('click', '.transactionInfo', function(){
        let trans_id = $(this).data('id');
        $.ajax({
            type: "GET",
            url: "/admin/transaction-pending-info/" + trans_id,
            dataType: "json",
            success: function (response) {
                let transaction = response.data.transaction;
                let product = response.data.product;
                let html = '';

                html += `
                    <div class="user-info bg-light p-3">
                        <h5>USER INFOMATION</h5>
                        <div class="pl-2">
                            <strong>Name</strong>
                            <p>${transaction.name}</p>
                        </div>
                        <div class="pl-2">
                            <strong>Email</strong>
                            <p>${transaction.email}</p>
                        </div>
                        <div class="pl-2">
                            <strong>Phone</strong>
                            <p>${transaction.phone}</p>
                        </div>
                        <div class="pl-2">
                            <strong>Address</strong>
                            <p>${transaction.address}</p>
                        </div>
                        <div class="pl-2">
                            <strong>Payment</strong>
                            <p>Payment after arrival of goods</p>
                        </div>
                        <div class="pl-2">
                            <strong>Status</strong>`;
                            if(transaction.status===1 || transaction.status ===2){
                                html += `
                                    <p>${transaction.status===1?"<span class='badge badge-primary'>Completed</span>":"<span class='badge badge-primary'>Canceled</span>"}</p>
                                `;
                            }else{
                                html += `
                                <div class="input-group">
                                    <select class="custom-select" id="statusTransaction">
                                        <option ${transaction.status===0?"selected":""} value="0">Pending</option>
                                        <option ${transaction.status===1?"selected":""} value="1">Completed</option>
                                        <option ${transaction.status===2?"selected":""} value="2">Canceled</option>
                                    </select>
                                    <div class="input-group-append">
                                        <button data-transaction_id="${trans_id}"
                                        class="btn ${transaction.status===0?"btn-outline-primary":(transaction.status===1?"btn-outline-success":"btn-outline-danger")} btn-status-trans"
                                        type="button">Update</button>
                                    </div>
                                </div>`;
                            }
                            html += `
                        </div>
                    </div>
                    <div class="order-detail bg-light p-3 table-responsive-md">
                        <h5 class="mb-4">LIST PRODUST</h5>

                        <table class="table table-striped table-dark table-sm mt-2">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Product</th>
                                    <th scope="col">Photo</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quanity</th>
                                    <th scope="col">Sub Total Price</th>
                                </tr>
                            </thead>
                            <tbody>`;
                            $.each(product, function(i, v){
                                html += `
                                <tr>
                                    <th scope="row">${i+1}</th>
                                    <td>
                                        <p>${v.product}</p>
                                        <div>
                                            <i>Color: ${v.color}</i><br>
                                            <i>Size: ${v.size}</i>
                                        </div>
                                    </td>
                                    <td><img src="${v.image}" class="img-thumbnail" width="70"></td>
                                    <td>$ ${v.price}</td>
                                    <td>${v.qty}</td>
                                    <td>$ ${v.sub_total}</td>
                                </tr>`;
                            });
                            html += `
                            </tbody>
                        </table>
                    </div>
                    <div class="transaction-value bg-light p-3">
                        <hr style="margin:5px 0">
                        <div class="ship-fee d-flex justify-content-between">
                            <strong>Fee Ship</strong>
                            <p class="text-info">$ 1.50</p>
                        </div>
                        <hr style="margin:5px 0">
                        <div class="amount d-flex justify-content-between">
                            <strong>Total</strong>
                            <p class="text-info">$ ${transaction.amount}</p>
                        </div>
                    </div>
                    `;

                $('#transactionModal').find('.modal-body').html(html);
                $('#transactionModal').modal({
                    backdrop: false
                });
            }
        });
    })

    $(document).on('click', '.btn-status-trans', function(){
        let status = $('#statusTransaction').val();
        let transaction_id = $(this).data('transaction_id');

        $.ajax({
            type: "GET",
            url: "/admin/transaction-update-status/" + transaction_id + "/" + status,
            dataType: "json",
            success: function (response) {
                console.log(response);
                messenger(response.data);
                dataTable.ajax.reload();
                $('#transactionModal').modal('hide');
            }
        });
    });

    $(document).on('click', '#open_modal', function(){
        $('#modal').modal('show');
    })

    $(document).on('click', '.transactionDelete', function(){
        let transaction_id = $(this).data('id');
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
                    type: "GET",
                    url: "/admin/transaction-delete/"+ transaction_id,
                    dataType: "json",
                    success: function (response) {
                        dataTable.ajax.reload();
                        messenger(response.data);
                        dataTable.ajax.reload();
                        return false;
                    },
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
