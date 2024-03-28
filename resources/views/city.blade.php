@extends('admin.index')
@section('title' , "City || Admin")
@section('admin.content')


<div class="container  mt-5 pt-5 pb-5">
    <button type="button" class="btn btn-outline-primary" id="add_city_btn">
        Add City
    </button>
</div>

<!-- Modal -->
<!-- <div class="modal fade" id="cityModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add City</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="city_form" method="post" onsubmit="return false">
                    <div class="modal-body">
                        <input type="hidden" name="hid" id="hid" value="">
                        @csrf
                        <div class="mb-3">
                            <label for="country_id">Country Name:</label>
                            <select class="form-select" aria-label="Default select example" id="country_id" name="country_id">
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="state_id">State Name:</label>
                            <select class="form-select" aria-label="Default select example" id="state_id" name="state_id">
                                <option selected disabled value="defult">Select State</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="state_name">City Name:</label>
                            <input type="text" class="form-control" id="city_name" placeholder="City Name" name="city_name">
                        </div>
                        <div class="mb-3 ">
                            <label for="state_name">City Status:</label>

                            <select class="form-select" aria-label="Default select example" id="city_status" name="city_status">
                                <option selected disabled>Select City Status</option>
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

@extends('form_modal.City_modal')

<table class="table table-striped mt-5" id="table">
    <thead>
        <th>No.</th>
        <th>Country Name</th>
        <th>State Name</th>
        <th>City Name</th>
        <th>City Status</th>
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

        var form = $("#city_form");
        var validator = form.validate({

            rules: {
                country_id: "required",
                state_id: "required",
                city_name: {
                    required: true,
                    maxlength: 15,
                },
                city_status: {
                    required: true,
                }
            },
   

        });


        $("#cityModal").on("hidden.bs.modal", function() {
            $("#city_form")[0].reset();
            $("#hid").val("");
            $("#state_id").html('<option selected disabled value="defult">Select State</option>');
            validator.resetForm();
        })

        $(document).on("click", "#add_city_btn", function() {
            $('#cityModal').modal('show');
        })
        var headers = {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }


        function get_Country() {
            $.ajax({
                url: "/get_Country",
                type: "GET",
                headers: headers,
                success: function(responce) {
                    var html = '<option selected disabled value="defult">Select Country</option>';
                    for (let i = 0; i < responce.length; i++) {
                        html += "<option value='" + responce[i].id + "'>" + responce[i].country + "</option>";
                    }
                    $("#country_id").html(html);
                }
            })
        }
        get_Country();

        $("#Submit_city").on("click", function() {
            if (form.valid()) {
                $("#city_form").submit(function(e) {
                    e.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: "/add_city",
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
                                $("#city_form")[0].reset();
                                $('#cityModal').modal('hide');

                            } else {

                            }
                        }
                    })
                });
            } else {

            }
        })



        let list = $('#table').dataTable({
            processing: true,
            serverSide: true,
            "ajax": {
                url: "/city_list",
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
                    data: 'city'
                },
                {
                    data: 'city_status'
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
                title: "Are you sure You Want to delete this City?",
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
                        url: "/delete_city",
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
                        text: "City has been deleted.",
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
                url: "/edit_city",
                headers: headers,
                data: {
                    id: id
                },
                success: function(responce) {
                    if (responce.status == 0) {
                        $('#cityModal').modal('show');
                        $("#hid").val(responce.data[0].id);
                        $("#country_id option[value=" + responce.data[0].country_id + "]").attr("selected", true).change();
                        setTimeout(function() {
                            $("#state_id option[value=" + responce.data[0].state_id + "]").attr("selected", true);
                        }, 900);
                        $("#city_name").val(responce.data[0].city);
                        $("#city_status").val(responce.data[0].city_status);
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


        $("#country_id").on("change", function() {
            $.ajax({
                url: "/get_State",
                type: "POST",
                data: {
                    country_id: $(this).val()
                },
                headers: headers,
                success: function(responce) {
                    var html = '<option selected disabled>Select State </option> ';
                    for (let i = 0; i < responce.length; i++) {
                        html += "<option value='" + responce[i].id + "'>" + responce[i].state + "</option>";
                    }
                    $("#state_id").html(html);
                }
            })
        })

    })
</script>

@endsection