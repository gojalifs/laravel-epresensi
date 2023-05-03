@section('title')
    <h1>Daftar Permintaan Revisi Absen</h1>
@stop

@section('user-content')
    <div class="card" id="body">
        <div class="card-header">
            <h3 class="card-title">Permintaan Revisi Absen</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Yang Direvisi</th>
                        <th>Alasan</th>
                        <th>Disetujui?</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($revisian as $revisi)
                        <tr>
                            <td>{{ $revisi->user_nik }}</td>
                            <td>{{ $revisi->tanggal }}</td>
                            <td>{{ $revisi->jam }}</td>
                            <td>{{ $revisi->direvisi }}</td>
                            <td>{{ $revisi->alasan }}</td>
                            <td>{{ $revisi->is_approved == 1 ? 'Disetujui' : ($revisi->is_approved == 2 ? 'Ditolak' : 'Belum Disetujui') }}
                            </td>
                            <td>
                                <button class="btn btn-success btn-setujui" data-id="{{ $revisi->id }}">Setujui</button>
                                <button class="btn btn-danger btn-tolak" data-id="{{ $revisi->id }}">Tolak</button>
                                <button class="btn btn-maroon btn-hapus" data-id="{{ $revisi->id }}">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
@stop

<script>
    $(document).ready(function() {
        $('.btn-setujui').on('click', function(e) {
            e.preventDefault();

            var id = $(this).data('id');
            $.ajax({
                url: '/revisi/' + id,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_approved: 'approved'
                },
                success: function(data) {
                    $('#body').html(data);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {

        $('.btn-tolak').on('click ', function() {
            var id = $(this).data('id');
            $.ajax({
                url: '/revisi/' + id,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_approved: 'rejected'
                },
                success: function(data) {
                    $('#body').html(data);
                }
            });
        });

        $('.btn-hapus').on('click', function() {
            var id = $(this).data('id');
            if (confirm("Apakah Anda yakin ingin menghapus revisi ini?")) {
                $.ajax({
                    url: '/revisi/' + id,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        $('#body').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            }
        });
    });
</script>

@yield('user-content')
