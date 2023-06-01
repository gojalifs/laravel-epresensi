@section('title')
    <h1>Data User</h1>
@stop

<script>
    // jQuery function to hide the modal
    $(document).on("click", ".btn-secondary", function(e) {
        e.preventDefault();
        $('#addUserModal').modal('hide');
        // $('#user-list').load('users-list');
    });
</script>

<script>
    $(document).ready(function() {
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            var userNik = $(this).data('nik');
            var deleteUrl = '/users/delete';
            if (confirm('Are you sure you want to delete this user?')) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    data: {
                        nik: userNik
                    },
                    success: function(response) {
                        alert('Data berhasil dihapus');
                        $('#body').load('users-list');
                    },
                    error: function(response) {
                        console.log(response.message);
                    }
                });
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#search').on('keyup', function() {
            $value = $(this).val().toLowerCase();
            $('#user-table tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf($value) > -1)
            });
        });
    });
</script>

<script>
    // Fungsi untuk mereset input search dan menampilkan semua data user
    $('#reset-btn').on('click', function() {
        $('#search').val('');
        $('#user-table tr').show();
    });
</script>

<script>
    $(document).ready(function() {
        $('#add-user').on('click', function(e) {
            e.preventDefault();
            $('#addUserModal').modal('show');
            addUser();
            $('#addUserModal').modal('hide');
        });

    })
</script>

<script>
    function addUser() {

        // Mengirimkan data form ke server menggunakan AJAX
        $(document).ready(function() {
            $('form').submit(function(e) {
                e.preventDefault();
                var url = 'users/add';
                var data = $(this).serialize();
                console.log(data);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function(response) {
                        if (response.status == 'success') {
                            alert('Data Sukses Ditambahkan');
                            $('#addUserModal').modal('hide');
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');

                        } else {
                            alert(response.message);
                            // Jika terdapat error, tampilkan pesan error
                            $('#addUserModal').modal('hide');
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                            $('#form-add-user .error-message').text(response.message)
                                .show();
                        }
                        var urllist = 'users-list';
                        $('#body').load(urllist);
                    },
                    error: function(response) {

                        // Handle error response, display error messages in the modal
                        var message = '';
                        var errorMessage = response.responseJSON;
                        var jsonMessage = JSON.stringify(errorMessage);
                        console.log(jsonMessage);
                        var errors = '';
                        if (jsonMessage.includes('email')) {
                            message = 'Email Sudah Dipakai';
                        } else if (jsonMessage.includes('telp')) {
                            message = 'Telp Sudah Dipakai';
                        } else {
                            message = 'NIP Sudah Dipakai';
                        }

                        alert(message);
                        var errorList = '';

                        // $.each(errors, function(field, messages) {
                        //     errorList += '<li>' + messages[0] + '</li>';
                        // });

                        $('#errorMessages').html(errorList);
                        $('#errorModal').modal('show');
                    }
                });
            });
        })
    }
</script>

<script>
    // function for edit the user
    function editUser(user) {
        var addButton = $('#add-button');
        addButton.text('Konfirmasi Edit')
        // Show the modal
        $('#addUserModal').modal('show');

        // Populate the form with the selected user's data

        $('#nik').val(user.nik);
        $('#name').val(user.nama);
        $('#nipns').val(user.nipns);
        $('#email').val(user.email);
        $('#telp').val(user.telp);
        $('#password').val(user.password);

        if (user.gender === 'L') {
            $('#L').prop('checked', true);
        } else {
            $('#P').prop('checked', true);
        }

        $(document).ready(function() {
            $('form').submit(function(e) {
                e.preventDefault();
                var url = 'users/update';
                var data = $(this).serialize();
                console.log(data);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function(response) {
                        console.log(response);
                        if (response.success == true) {
                            alert('Data Sukses Diubah');
                            $('#addUserModal').modal('hide');
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                        } else {
                            alert(response.message);
                            $('#addUserModal').modal('hide');
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                        }
                        var urllist = 'users-list';
                        $('#body').load(urllist);
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });
        })
    }
</script>

<script>
    $(document).ready(function() {
        // ...

        $(document).on('click', '.btn-del', function(event) {
            event.preventDefault();
            event.stopPropagation();

            var nik = $(this).data('nik');

            if (!$(this).hasClass('loading')) {
                $(this).addClass('loading');
                $('#loadingModal').modal('show');
                updateUserAdminStatus(0, nik);
            }
        });

        $(document).on('click', '.btn-set', function(event) {
            event.preventDefault();
            event.stopPropagation();

            var nik = $(this).data('nik');

            if (!$(this).hasClass('loading')) {
                $(this).addClass('loading');
                $('#loadingModal').modal('show');
                updateUserAdminStatus(1, nik);
            }
        });

        function updateUserAdminStatus(isAdmin, nik) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/users/update/role',
                type: 'POST',
                data: {
                    is_admin: isAdmin,
                    nik: nik
                },
                success: function(response) {
                    var urllist = 'users-list';
                    $('#body').load(urllist);
                    $('#loadingModal').modal('hide');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                },
                error: function(xhr, status, error) {
                    $('#loadingModal').modal('hide');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    var errorMessage = xhr.responseJSON;
                    console.log('errorMessage ' + error);
                },
                complete: function() {
                    $('.btn-del, .btn-set').removeClass('loading');
                }
            });
        }

        // ...
    });
</script>



@section('user-content')
    <div class="card" id="body">
        <div class="card-header">
            <h3 class="card-title">Daftar User</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="form-group">
                <label for="search">Search by Name or NIK:</label>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" id="search" name="search">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <button class="btn btn-secondary" id="reset-btn">Reset</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <button type="button" id="add-user" class="btn btn-primary " data-toggle="modal"
                            data-target="#addUserModal">Tambah Guru PNS/Honorer</button>
                    </div>
                </div>
            </div>
            <table id="user-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Status</th>
                        <th>Email</th>
                        <th>Jenis Kelamin</th>
                        <th>Telp</th>
                        <th>Peran</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->nik }}</td>
                            <td>{{ $user->nama }}</td>
                            <td>{{ $user->nipns }}</td>
                            @if ($user->nipns == '' || !$user->nipns)
                                <td>Honorer</td>
                            @else
                                <td>PNS</td>
                                </button>
                            @endif
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->gender }}</td>
                            <td>{{ $user->telp }}</td>
                            <td>{{ $user->is_admin == 1 ? 'Admin' : '-' }}</td>
                            <td>
                                <a href="#" class="btn btn-primary" onclick="editUser({{ $user }})">Edit</a>
                                @if (auth()->user()->nik != $user->nik)
                                    @if ($user->is_admin == 1)
                                        <button href="#" class="btn btn-success btn-del"
                                            data-nik="{{ $user->nik }}">
                                            Hapus Peran Admin
                                        </button>
                                    @else
                                        <button href="#" class="btn btn-success btn-set"
                                            data-nik="{{ $user->nik }}">
                                            Jadikan Admin
                                        </button>
                                    @endif
                                @endif
                                @if (auth()->user()->nik != $user->nik)
                                    <a href="#" class="btn btn-danger delete-btn"
                                        data-nik="{{ $user->nik }}">Hapus</a>
                                @else
                                    <button class="btn btn-danger" disabled>Hapus</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

    <!-- Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document" id="myModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Tambah Data Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="form-add-user" class="myForm">
                        <div class="form-group">
                            <input type="hidden" class="form-control" id="nik" name="nik">
                        </div>
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="nipns">NIP</label>
                            <input type="text" class="form-control" id="nipns" name="nipns">
                        </div>
                        <div class="form-group">
                            <label>Gender</label><br>
                            <div>
                                <input type="radio" id="L" name="gender" value="L">
                                <label for="L">L</label><br>
                                <input type="radio" id="P" name="gender" value="P">
                                <label for="P">P</label><br>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="telp">Telp</label>
                            <input type="text" class="form-control" id="telp" name="telp" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="text" class="form-control" id="password" name="password" minlength="8">
                        </div>
                        {{-- <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="is_admin" name="is_admin"> Jadikan Sebagai Admin
                                </label>
                            </div>
                        </div> --}}

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="add-button">Tambah</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    {{-- Spinner Modal --}}
    <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin fa-3x"></i>
                        <h4 class="mt-2">Loading...</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@yield('user-content')
