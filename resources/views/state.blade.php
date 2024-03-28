@extends('admin.index')
@section('title' , "State || Admin")
@section('admin.content')

<div class="container  mt-5 pt-5 pb-5">
    <button type="button" class="btn btn-outline-primary" id="add_state_btn">
        Add State
    </button>
</div>

<!-- Modal -->
<!-- <div class="modal fade" id="stateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add State</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="state_form" method="post" onsubmit="return false">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="country_id">Country Name:</label>
                            <select class="form-select" aria-label="Default select example" id="country_id" name="country_id">
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="state_name">State Name:</label>
                            <input type="hidden" name="hid" id="hid" value="">
                            <input type="text" class="form-control" id="state_name" placeholder="State Name" name="state_name">
                        </div>
                        <div class="mb-3 ">
                            <label for="state_name">State Status:</label>

                            <select class="form-select" aria-label="Default select example" id="state_status" name="state_status">
                                <option selected disabled>Select State Status</option>
                                <option value="0">Active</option>
                                <option value="1">In-Active</option>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> -->

@extends('form_modal.State_modal')

<table class="table table-striped mt-5" id="table">
    <thead>
        <th>No.</th>
        <th>Country Name</th>
        <th>State Name</th>
        <th>State Status</th>
        <th>Action</th>
    </thead>
    <tbody id="tbody">

    </tbody>
</table>



@endsection
@section('extrajs')


<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.2/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

<script>
    $(document).ready(function() {

        jQuery.validator.setDefaults({
            debug: true,
            success: "valid"
        });

        var form = $("#state_form");


        var validator = form.validate({
            rules: {
                country_id: "required",
                state_name: {
                    required: true,
                    maxlength: 15,
                },
                state_status: {
                    required: true,
                }
            },
  
        });


        function get_Country() {
            $.ajax({
                url: "/get_Country",
                type: "GET",
                headers: headers,
                success: function(responce) {
                    var html = '<option selected disabled value="defult">Select Country</option>';
                    for (let i = 0; i < responce.length; i++) {
                        console.log("responce.id", responce[i].id);
                        html += "<option value='" + responce[i].id + "'>" + responce[i].country + "</option>";
                    }
                    $("#country_id").html(html);
                }
            })
        }

        get_Country();

        var headers = {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }

        $(document).on("click", "#add_state_btn", function() {
            $('#stateModal').modal('show');
        })

        $("#stateModal").on("hidden.bs.modal", function() {
            validator.resetForm();
            $("#state_form")[0].reset();
            $("#hid").val("");
        })


        $("#Submit_state").on("click", function() {
            if (form.valid()) {
                $("#state_form").submit(function(e) {
                    e.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: "/add_state",
                        headers: headers,
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        success: function(responce) {
                            $('#table').DataTable().ajax.reload();
                            $("#hid").val("");
                            if (responce.status == 1) {
                                Swal.fire({
                                    title: responce.msg,
                                    icon: "success"
                                });
                                validator.resetForm();
                                $("#state_form")[0].reset();
                                $('#stateModal').modal('hide');
                            } else {

                            }
                        }
                    })
                });
            } else {}

        })



        let list = $('#table').dataTable({
            processing: true,
            serverSide: true,
            "ajax": {
                url: "/state_list",
                type: 'GET',
                headers: headers,
                dataType: 'json',
            },
            columns: [{
                    data: 'id',
                },
                {
                    data: 'country'
                },
                {
                    data: 'state'
                },
                {
                    data: 'state_status'
                },
                {
                    data: 'action',
                    orderable: false
                }
            ],
        });

        $(document).on("click", "#del", function() {
            var headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            var dataid = $(this).data("id");
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: "Are you sure You Want to delete this State?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        cache: false,
                        url: "/delete_state",
                        headers: headers,
                        data: {
                            id: dataid
                        },
                        success: function(responce) {
                            if (responce['status'] = 1) {} else {
                                Swal.fire({
                                    title: "Sorry",
                                    text: responce['mesege'],
                                    icon: "error"
                                });
                            }
                            $('#table').DataTable().ajax.reload();
                        }
                    })
                    swalWithBootstrapButtons.fire({
                        title: "Deleted!",
                        text: "State has been deleted.",
                        icon: "success"
                    });

                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire({
                        title: "Cancelled",
                        icon: "error"
                    });
                }
            });
        });

        $(document).on("click", "#edit", function() {
            var headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            var id = $(this).data("id");
            $.ajax({
                type: "POST",
                cache: false,
                url: "/edit_state",
                headers: headers,
                data: {
                    id: id
                },
                success: function(responce) {
                    if (responce.status == 0) {
                        $("#hid").val(responce.data[0].id);
                        $("#country_id").val(responce.data[0].country_id);
                        $("#state_name").val(responce.data[0].state);
                        $("#state_status").val(responce.data[0].state_status);
                        $('#stateModal').modal('show');
                    } else {
                        Swal.fire({
                            title: responce.messege,
                            text: responce.messege,
                            icon: "success"
                        });
                    }
                }
            })
        });

    })
</script>

@endsection