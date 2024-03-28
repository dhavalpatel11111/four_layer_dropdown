@extends('admin.index')
@section('title' , "Country || Admin")
@section('admin.content')

<div class="container  mt-5 pt-5 pb-5">
    <button type="button" class="btn btn-outline-primary" id="add_country_btn">
        Add Country
    </button>
</div>
<!-- Modal -->



@extends('form_modal.Country_modal')

<table class="table table-striped mt-5" id="table">
    <thead>
        <th>No.</th>
        <th>Country Name</th>
        <th>Country Status</th>
        <th>Action</th>
    </thead>
    <tbody id="tbody">

    </tbody>
</table>

@endsection
@section('extrajs')

<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.2/dist/sweetalert2.all.min.js"></script>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/validate-js/2.0.1/validate.min.js" integrity="sha512-8GLIg5ayTvD6F9ML/cSRMD19nHqaLPWxISikfc5hsMJyX7Pm+IIbHlhBDY2slGisYLBqiVNVll+71CYDD5RBqA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>





<script>
    $(document).ready(function() {

        jQuery.validator.setDefaults({
            debug: true,
            success: "valid"
        });

        var form = $("#country_form");
        var validator = form.validate({
            // debug: true,
            rules: {
                country_name: {
                    required: true,
                    maxlength: 15,
                },
                country_status: {
                    required: true,
                }
            },


        });


        $("#submit_country").on("click", function() {
            if (form.valid()) {
                $("#country_form").submit(function(e) {
                    e.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: "/add_country",
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
                                $("#country_form")[0].reset();
                                $('#countryModal').modal('hide');

                            } else {
                                Swal.fire({
                                    title: responce.msg,
                                    icon: "error"
                                });
                            }
                        }
                    })
                });
            } else {}
        })


        $(document).on("click", "#add_country_btn", function() {
            $('#countryModal').modal('show');
        })

        $("#countryModal").on("hidden.bs.modal", function() {
            validator.resetForm();
            $("#country_form")[0].reset();
            $("#hid").val("");
        })

        var headers = {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }

        let list = $('#table').dataTable({
            processing: true,
            serverSide: true,
            "ajax": {
                url: "/country_list",
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
                    data: 'country_status'
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
                title: "Are you sure You Want to delete this country?",
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
                        url: "/delete_country",
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
                        text: "Country has been deleted.",
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
                url: "/edit_country",
                headers: headers,
                data: {
                    id: id
                },
                success: function(responce) {
                    console.log('responce:', responce.data)
                    if (responce.status == 0) {
                        $("#hid").val(responce.data.id);
                        $("#country_name").val(responce.data.country);
                        $("#country_status").val(responce.data.country_status);
                        $('#countryModal').modal('show');
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