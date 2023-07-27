@section('title')
    <h1>Daftar Permintaan Izin Keluar</h1>
@stop

@section('user-content')
    <div class="card" id="body">
        <div class="card-header">
            <h3 class="card-title">Permintaan Izin Keluar</h3>
        </div>
        {{-- date selector --}}
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <form action="" method="GET" id="pilih-tanggal">
                        <div class="form-group row mt-3">
                            <label for="tanggal" class="col-md-1 col-form-label text-md-right">Tanggal</label>
                            <div class="col-md-3">
                                <input type="date" name="tanggal" id="tanggal" class="form-control"
                                    value="{{ $tanggal }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary" id="daily">Tampilkan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Jam Keluar</th>
                        <th>Jam Kembali</th>
                        <th>Alasan</th>
                        <th>Status Pengajuan</th>
                        <th>Persetujuan</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($izin_keluars as $keluar)
                        <tr>
                            <td>{{ $keluar->user_nik }}</td>
                            <td>{{ $keluar->name }}</td>
                            <td>{{ $keluar->tanggal }}</td>
                            <td>{{ $keluar->jam_keluar }}</td>
                            <td>{{ $keluar->jam_kembali }}</td>
                            <td>{{ $keluar->alasan }}</td>
                            <td>{{ $keluar->is_approved == 1 ? 'Disetujui' : ($keluar->is_approved == 2 ? 'Ditolak' : 'Belum Disetujui') }}
                            <td>{{ $keluar->approval_name }}</td>
                            <td>
                                <button class="btn btn-success btn-setujui" data-id="{{ $keluar->id }}">Setujui</button>
                                <button class="btn btn-danger btn-tolak" data-id="{{ $keluar->id }}">Tolak</button>
                                <button class="btn btn-maroon btn-hapus" data-id="{{ $keluar->id }}">Hapus</button>
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
    $('#pilih-tanggal').on('submit', function(e) {
        e.preventDefault();
        var tanggal = $('#tanggal').val();

        $.ajax({
            url: "/keluar",
            type: 'GET',
            data: {
                date: tanggal
            },
            success: function(data) {
                $('#body').empty();
                $('#body').html(data);
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.btn-setujui').on('click', function(e) {
            e.preventDefault();

            var tanggal = $('#tanggal').val();
            var id = $(this).data('id');
            $.ajax({
                url: '/izinkeluar/' + id,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_approved: 'approved',
                    date: tanggal,
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
            var tanggal = $('#tanggal').val();
            var id = $(this).data('id');
            $.ajax({
                url: '/izinkeluar/' + id,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_approved: 'rejected',
                    date: tanggal,
                },
                success: function(data) {
                    $('#body').html(data);
                }
            });
        });

        $('.btn-hapus').on('click', function() {
            var tanggal = $('#tanggal').val();
            var id = $(this).data('id');
            if (confirm("Apakah Anda yakin ingin menghapus izin keluar ini?")) {
                $.ajax({
                    url: '/izinkeluar/' + id,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}',
                        date: tanggal,
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
