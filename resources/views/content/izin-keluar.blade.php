@section('title')
    <h1>Daftar Permintaan Izin Keluar</h1>
@stop

@section('user-content')
    <div class="card" id="body">
        <div class="card-header">
            <h3 class="card-title">Permintaan Izin Keluar</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Tanggal</th>
                        <th>Jam Keluar</th>
                        <th>Jam Kembali</th>
                        <th>Alasan</th>
                        <th>Status Pengajuan</th>
                        <th>Approval</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($izin_keluars as $keluar)
                        <tr>
                            <td>{{ $keluar->user_nik }}</td>
                            <td>{{ $keluar->tanggal }}</td>
                            <td>{{ $keluar->jam_keluar }}</td>
                            <td>{{ $keluar->jam_kembali }}</td>
                            <td>{{ $keluar->alasan }}</td>
                            <td>{{ $keluar->is_approved == 1 ? 'Disetujui' : ($keluar->is_approved == 2 ? 'Ditolak' : 'Belum Disetujui') }}
                            <td>{{ $keluar->approval_name }}</td>
                            <td>
                                <button class="btn btn-success btn-setujui" data-id="{{ $keluar->id }}">Setujui</button>
                                <button class="btn btn-danger btn-tolak" data-id="{{ $keluar->id }}">Tolak</button>
                                <button class="btn btn-maroon btn-hapus" data-id="{{ $keluar->id }}">Delete</button>
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
                url: '/izinkeluar/' + id,
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
                url: '/izinkeluar/' + id,
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
            if (confirm("Apakah Anda yakin ingin menghapus izin keluar ini?")) {
                $.ajax({
                    url: '/izinkeluar/' + id,
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
