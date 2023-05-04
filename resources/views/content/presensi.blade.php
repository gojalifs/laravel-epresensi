@section('title')
    <h1>Data Presensi</h1>
@stop

@section('user-content')
    <div class="container" id="body">
        <div class="row">
            <div class="col-md-12">
                <h3>Data Presensi</h3>
                <form action="" method="GET" id="form-presensi">
                    <div class="form-group row">
                        <label for="tanggal" class="col-md-2 col-form-label text-md-right">Tanggal</label>
                        <div class="col-md-3">
                            <input type="date" name="tanggal" id="tanggal" class="form-control"
                                value="{{ $tanggal }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary" id="daily">Tampilkan</button>
                        </div>
                        <a class="btn btn-success" id="monthly">Tampilkan Per Bulan</a>
                    </div>

                </form>
                <table class="table table-bordered" id="table-presensi">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Jenis</th>
                            <th>Jam</th>
                            <th>Longitude</th>
                            <th>Latitude</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($presensis as $presensi)
                            <tr
                                @if ($presensi->jam == '' || !$presensi->jam) class="bg-danger text-white" @else class="bg-green text-white" @endif>
                                <td>{{ $presensi->nama }}</td>
                                <td>{{ $presensi->nik }}</td>
                                <td>{{ $presensi->jenis }}</td>
                                <td>{{ $presensi->jam }}</td>
                                <td>{{ $presensi->longitude }}</td>
                                <td>{{ $presensi->latitude }}</td>
                                <td>
                                    @if ($presensi->jam == '' || !$presensi->jam)
                                    @else
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#imageModal{{ $presensi->nik }}">
                                            Lihat
                                        </button>
                                    @endif
                                    <div class="modal fade" id="imageModal{{ $presensi->nik }}" tabindex="-1"
                                        role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="imageModalLabel">Gambar Presensi</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body-img">
                                                    <img src="{{ asset('storage') . '/' . $presensi->img_path }}"
                                                        class="img-fluid" width="400rem" height="400rem">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <table class="table table-bordered" id="monthly-presensi" style="display:none;">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Jumlah Kehadiran</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>

                                <td>{{ $user->nama }}</td>
                                <td>{{ $user->nik }}</td>
                                <td>{{ $user->count }}</td>
                                <td><button type="button" class="btn btn-primary"> Tampilkan sebagai PDF </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script>
        $(document).ready(function() {
            // set tanggal awal saat pertama kali load halaman
            let today = new Date().toISOString().substr(0, 10);
            $('#tanggal').val(today);
        });
    </script>
@stop

<script>
    $(document).ready(function() {
        $('#form-presensi').on('submit', function(e) {
            e.preventDefault();
            var tanggal = $('#tanggal').val();
            $.ajax({
                url: "/presensi",
                type: 'GET',
                data: {
                    tanggal: tanggal
                },
                success: function(data) {
                    $('#body').html(data);
                    $('#monthly-presensi').hide();
                    $('#table-presensi').show();
                }
            });
        });
    });
</script>

{{-- <script>
    $(document).ready(function() {
        console.log('executed');
        $('#monthly-presensi').hide();
    });
</script>
 --}}
<script>
    $(document).ready(function() {
        $('#monthly').on('click', function() {
            console.log('jalan');
            $('#table-presensi').hide();
            $('#monthly-presensi').show();
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#daily').on('click',
            function() {
                $('#monthly-presensi').hide();
                $('#table-presensi').show();
            })
    });
</script>


{{-- @yield('title') --}}
@yield('user-content')
