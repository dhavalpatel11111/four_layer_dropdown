@extends('admin.index')
@section('title' , "Add New User || Admin")
@section('admin.content')







<!-- Button trigger modal -->
<div class="container mt-3 mb-5 pt-5 pb-3">
    <button type="button" class="btn btn-success float-right" id="popup">
        <i class="bi bi-person-add m-2"></i>Add User
    </button>
</div>


<!-- Modal -->
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add User</h5>

            </div>
            <form id="form" method="post" onsubmit="return false" enctype="multipart/form-data">
                <div class="modal-body">

                    @csrf

                    <div class="mb-3 mt-3">
                        <label for="text">First Name:</label>
                        <input type="hidden" name="hid" id="hid" value="">
                        <input type="text" class="form-control" id="fname" placeholder="First Name" name="fname">
                    </div>
                    <div class="mb-3">
                        <label for="text">Last Name:</label>
                        <input type="text" class="form-control" id="lname" placeholder="Last Name" name="lname">
                    </div>

                    <div class="mb-3">
                        <label for="text">E-mail:</label>
                        <input type="email" class="form-control" id="email" placeholder="E-mail" name="email">
                    </div>

                    <div class="mb-3">
                        <label for="text">Mo. Number</label>
                        <input type="number" class="form-control" id="number" placeholder="Number" name="number">
                    </div>

                    <div class="mb-3">
                        <label for="text">Gender:</label>
                        <input type="radio" name="gender" id="gender" value="Male">Male
                        <input type="radio" name="gender" id="gender" value="Female">Female
                        <input type="radio" name="gender" id="gender" value="Other">Other
                    </div>

                    <select class="form-select mb-3" id="select" name="select">
                        <option selected>Your Skill</option>
                        <option value="frontend developer">Frontend Developer</option>
                        <option value="backend developer">Backend Developer</option>
                        <option value="game developer">Game Developer</option>
                        <option value="App developer">App Developer</option>
                        <option value="Sward Master">Sward Master</option>
                        <option value="Hokage">Hokage</option>
                        <option value="Sanin">Sanin</option>
                        <option value="Ganin">Ganin</option>
                    </select>

                    <div class="mb-3">
                        <label for="text">Image</label>
                        <input type="file" class="form-control" id="img" placeholder="Number" name="img">
                        <img alt="Your img Show Here" id="editimg" height="150px" width="auto" style="margin: 10px;">
                        <p id="oldimg"></p>
                        <img alt="Your img Show Here" id="priv" height="150px" width="auto" style="margin: 10px;">
                        <p id="newimg"></p>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="submit">Submit</button>

                    <button class="btn btn-danger" id="close">Close</button>
                </div>
            </form>

        </div>
    </div>
</div>


<table class="table table-striped" id="table">
    <thead>
        <th>No.</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>E-mail</th>
        <th>Number</th>
        <th>Gender</th>
        <th>Skill</th>
        <th>Img</th>
        <th>Action</th>
    </thead>
    <tbody id="tbody">

    </tbody>
</table>





@endsection
@section('extrajs')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.2/dist/sweetalert2.all.min.js"></script>

<script>







    $(document).ready(function () {

        var headers = {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }


        // show data


        let list = $('#table').dataTable({
            searching: true,
            paging: true,
            pageLength: 10,

            "ajax": {
                url: "/list",
                type: 'GET',
                headers: headers,
                dataType: 'json',
            },
            columns: [
                { data: 'id' },
                { data: 'fname' },
                { data: 'lname' },
                { data: 'email' },
                { data: 'no' },
                { data: 'gender' },
                { data: 'power' },
                { data: 'img' },
                { data: 'action' }
            ],
        });

        if ($('#add').modal('hide')) {
            $("#form")[0].reset();
            $('input:radio[name="gender"]').attr('checked', false);
            $("#editimg").attr("src", " ");
            $("#priv").attr("src", " ");
            $("#oldimg").html(" ");
            $("#newimg").html(" ");
        }





        $('#table').DataTable().ajax.reload();


        $("#popup").on("click", function () {
            $('#add').modal('show');
            $("#form")[0].reset();
            $('input:radio[name="gender"]').attr('checked', false);
            $("#editimg").attr("src", "");
            $("#editimg").attr("alt", " ");
            $("#oldimg").html(" ");
            $("#newimg").html(" ");
        })

        $("#close").on("click", function () {
            $('#add').modal('hide');
            $("#form")[0].reset();
            $('input:radio[name="gender"]').attr('checked', false);
            $("#editimg").attr("src", "");

        })



        // second style to add data 



        // $("#form").submit(function (e) {
        //     e.preventDefault();

        //     let formData = new FormData(this);
        //     console.log('formData:', formData);
        // })





        // add data



        //  $("#submit").on("click", function (e) {

        img.onchange = evt => {
            const [file] = img.files
            if (file) {
                priv.src = URL.createObjectURL(file)
            }
        }

        $("#form").submit(function (e) {
            e.preventDefault();
            $.ajax({

                type: "POST",
                url: "/add",
                headers: headers,
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (responce) {
                    var data = JSON.parse(responce);

                    console.log('responce:', responce);
                    $('#add').modal('hide');
                    $('#table').DataTable().ajax.reload();



                    $('input:radio[name="gender"]').attr('checked', false);
                    $("#submit").html("Submit");
                    $("#form")[0].reset();
                    $("#hid").val("");




                    if (data['status'] = 1) {
                        // toastr.success(data['mesege']);
                        Swal.fire({
                            title: data['mesege'],
                            icon: "success"
                        });
                    } else {
                        // toastr.error(data['mesege']);
                        Swal.fire({
                            title: "Sorry",
                            text: data['mesege'],
                            icon: "error"
                        });
                    }

                }

            })

        });





        $(document).on("click", "#del", function () {
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
                title: "Are you sure You Want to delete this data?",

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
                        url: "{{ url('/delete') }}",
                        headers: headers,
                        data: {
                            id: dataid
                        },
                        success: function (responce) {
                            // console.log('responce:', responce);
                            if (responce['status'] = 1) {
                                // toastr.success(responce['mesege']);

                            } else {
                                // toastr.error(responce['mesege']);
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
                        text: "Your file has been deleted.",
                        icon: "success"
                    });

                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire({
                        title: "Cancelled",

                        icon: "error"
                    });
                }
            });

        });





        $(document).on("click", "#edit", function () {


            var headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            var id = $(this).data("id");

            $.ajax({
                type: "POST",
                cache: false,
                url: "{{ url('/edit') }}",
                headers: headers,
                data: {
                    id: id
                },
                success: function (responce) {
                    console.log('edit responce :', responce);
                    $('#add').modal('show');

                    if (responce.status == 0) {
                        $("#hid").val(responce.data.id);
                        $("#fname").val(responce.data.fname);
                        $("#lname").val(responce.data.lname);
                        $("#email").val(responce.data.email);
                        $("#number").val(responce.data.no);
                        $('input:radio[name="gender"]').filter('[value=' + responce.data.gender + ']').attr('checked', true);
                        $("#select").val(responce.data.power);
                        $("#editimg").attr("src", "http://127.0.0.1:8000/img/" + responce.data.img);
                        $("#submit").html("Update");
                        $("#oldimg").html("Old Image");
                        $("#newimg").html("New Image");




                    } else {
                        // toastr.error(responce.messege);
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