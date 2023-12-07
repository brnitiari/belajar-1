<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel 6 Crud Application using Yajra Datatables and Ajax</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
    </head>
    <body>
        <div class="container">
            <br />
            <h3 align="center">Laravel 6 Crud Application using Yajra Datatables and Ajax</h3>
            <br />
            <div align="right">
                <button type="button" name="create_record" id="create_record" 
                class="btn btn-success btn-sm">buat data baru</button>
            </div>
        </div>
        <br />
        <div class="table-responsive">
            <table id="user_table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="20%">No</th>
                        <th width="20%">Nama Depan</th>
                        <th width="20%">Nama belakang</th>
                        <th width="20%">gambar</th>
                        <th width="20%">Action</th>
                    </tr>
            </thead>
            </table>
        </div>

        <!-- Modal Start -->
        <div id="formModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Masukan Data Baru</h4>
                    </div>
                    <div class="modal-body">
                        <span id="form_result"></span>
                        <!-- <div class="alert" id="message" style="display: none"></div> -->
                        <form method="post" id="sample_form" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label class="control-label" >Nama depan : </label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="first_name" id="first_name" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label class="control-label">Nama belakang : </label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="last_name" id="last_name" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label class="control-label">Select File for Upload</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="file" name="select_file" id="select_file" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row" id="divPreviewImage">
                                <div class="col-md-3">
                                    <label for="" class="control-label">Preview Image</label>
                                </div>
                                <div class="col-md-9">
                                    <img id="previewImage" style="height: 10rem;">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-12 text-right">
                                    <input type="hidden" name="action" id="action" value="Add" />
                                    <input type="hidden" name="hidden_id" id="hidden_id" />
                                    <button class="btn btn-primary btn-icon waves-effect waves-light" name="upload" id="upload" class="btn btn-primary" value="Add" type="submit">Tambah</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> 

        <div id="confirmModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h2 class="modal-title">konfirmasi</h2>
                    </div>
                    <div class="modal-body">
                        <h4 align="center" style="margin:0;">anda yakin ingin menghapus data ini?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">hapus data</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">batal</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal End -->

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function(){
                $('#user_table').DataTable({
                    processing :true,
                    serverSide :true,
                    ajax : {
                        url: "{{ route('sample.index') }}",
                    },
                    columns : [
                        {
                            data : 'id',
                            name : 'id'
                        },
                        {
                            data : 'first_name',
                            name : 'first_name'
                        },
                        {
                            data : 'last_name',
                            name : 'last_name'
                        },
                        {
                            data : 'image', 
                            name : 'image'    
                        },
                        {
                            data : 'action',
                            name : 'action',
                            orderable : false
                        },
                    ]  
                });

                $('#divPreviewImage').hide();
            });

            $('#create_record').click(function(){
                $('.modal-title').text('Add New Record');
                $('#action_button').val('Add');
                $('#action').val('Add');
                $('#form_result').html('');
                $('#formModal').modal('show');
                $('#divPreviewImage').hide();
            });

            $(document).on('click', '.edit', function(){
                var id = $(this).attr('id');
                $('#form_result').html('');
                $('#divPreviewImage').show();
                $.ajax({
                    url :"/sample/"+id+"/edit",
                    dataType:"json",
                    success:function(data)
                    {
                        $('#first_name').val(data.result.first_name);
                        $('#last_name').val(data.result.last_name);
                        $('#previewImage').attr('src', "{{ asset('images/') }}" + '/' + data.result.image);
                        $('#hidden_id').val(id);
                        $('.modal-title').text('Edit Record');
                        $('#action_button').val('edit');
                        $('#action').val('edit');
                        $('#formModal').modal('show');
                    }
                });
            });
            

            $('#sample_form').on('submit', function(event){
                event.preventDefault();
                var action_url = '';

                if($('#action').val() == 'Add')
                {
                    action_url = "{{ route('sample.store') }}";
                }

                if($('#action').val() == 'edit')
                {
                    action_url = "{{ route('sample.update') }}";
                }

                $.ajax({
                    url: action_url,
                    method:"POST",
                    data:new FormData(this),
                    dataType:'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success:function(data)
                    {
                        var html = '';

                        if(data.errors)
                        {
                            html = '<div class="alert alert-danger">';
                            for(var count = 0; count < data.errors.length; count++)
                            {
                                html += '<p>' + data.errors[count] + '</p>';
                            }
                            html += '</div>';
                        }

                        if(data.success)
                        {
                            html = '<div class="alert alert-success">' + data.success + '</div>';
                            $('#sample_form')[0].reset();
                            $('#user_table').DataTable().ajax.reload();
                        }

                        $('#form_result').html(html);
                    }
                });
            });

            var user_id;

            $(document).on('click', '.delete', function(){
                user_id = $(this).attr('id');
                $('#confirmModal').modal('show');
            });

            $('#ok_button').click(function(){
                $.ajax({
                    url:"sample/destroy/"+user_id,
                    beforeSend:function(){
                        $('#ok_button').text('menghapus...');
                    },
                    success:function(data)
                    {
                        setTimeout(function(){
                            $('#confirmModal').modal('hide');
                            $('#user_table').DataTable().ajax.reload();
                            alert('Data Deleted');
                        }, 2000);
                    }
                });
            });
        </script>
    </body>
</html>