@section('title')
    <h1>Daftar Permintaan ketidakhadiran</h1>
@stop

@section('user-content')
    <div class="card" id="body">
        <div class="card-header">
            <h3 class="card-title">Permintaan ketidakhadiran</h3>
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
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Alasan</th>
                        <!-- <th>Potong Cuti</th> -->
                        <th>Jenis Cuti</th>
                        <th>Status</th>
                        <th>Persetujuan</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ketidakhadiran as $ketidakhadiran)
                        <tr>
                            <td>{{ $ketidakhadiran->nik }}</td>
                            <td>{{ $ketidakhadiran->name }}</td>
                            <td>{{ $ketidakhadiran->tanggal }}</td>
                            <td>{{ $ketidakhadiran->tanggal_selesai }}</td>
                            <td>{{ $ketidakhadiran->alasan }}</td>
                            <!-- <td>{{ $ketidakhadiran->potong_cuti }}</td> -->
                            <td>{{ $ketidakhadiran->jenis_cuti }}</td>
                            <td>{{ $ketidakhadiran->status == 1 ? 'Disetujui' : ($ketidakhadiran->status == 2 ? 'Ditolak' : 'Belum Disetujui') }}
                            </td>
                            <td>{{ $ketidakhadiran->approval_name ? $ketidakhadiran->approval_name : '' }}</td>
                            <td>
                                <button class="btn btn-success btn-setujui"
                                    data-id="{{ $ketidakhadiran->id }}">Setujui</button>
                                <button class="btn btn-danger btn-tolak" data-id="{{ $ketidakhadiran->id }}">Tolak</button>
                                <button class="btn btn-maroon btn-hapus" data-id="{{ $ketidakhadiran->id }}">Hapus</button>
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
            url: "/ketidakhadiran",
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
                url: '/ketidakhadiran/' + id,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: 'approved',
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
                url: '/ketidakhadiran/' + id,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: 'rejected',
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
            if (confirm("Apakah Anda yakin ingin menghapus ketidakhadiran ini?")) {
                $.ajax({
                    url: '/ketidakhadiran/' + id,
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
