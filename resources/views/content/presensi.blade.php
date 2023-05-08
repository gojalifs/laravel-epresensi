@section('title')
    <h1>Data Presensi</h1>
@stop

@section('user-content')
    <div class="container" id="body">
        <div class="row">
            <div class="col-md-12">
                <h3 id="month_name">Data Presensi</h3>
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
        var tanggal = $('#tanggal').val();
        console.log(tanggal);
        var options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            timeZone: 'Asia/Jakarta'
        };
        var date = new Date(tanggal + 'T00:00:00+07:00'); // timezone offset untuk WIB adalah +07:00
        var tanggalIndonesia = date.toLocaleDateString('id-ID', options);
        console.log(tanggalIndonesia); // Senin, 8 Mei 2023
        document.getElementById('month_name').innerHTML = 'Data Presensi ' + tanggalIndonesia;

        $('#form-presensi').on('submit', function(e) {
            e.preventDefault();
            var tanggal = $('#tanggal').val();
            console.log(tanggal);
            var options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                timeZone: 'Asia/Jakarta'
            };
            var date = new Date(tanggal + 'T00:00:00+07:00'); // timezone offset untuk WIB adalah +07:00
            var tanggalIndonesia = date.toLocaleDateString('id-ID', options);
            console.log(tanggalIndonesia); // Senin, 8 Mei 2023
            document.getElementById('month_name').innerHTML = 'Data Presensi ' + tanggalIndonesia;
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



<script>
    $(document).ready(function() {
        $('.get_report').on('click', function() {
            // Mendapatkan nilai dari atribut data-id
            var userData = $(this).data('id');

            // Parsing string JSON menjadi objek JavaScript
            // console.log(userData);
            console.log(userData.nik);
            // var user = JSON.parse(userId);
            // Mendapatkan nilai nama dan nik dari objek user
            var nama = userData.nama;
            var userNik = userData.nik;
            // var userNik = $(this).data('id');
            $.ajax({
                url: '/presensi/report',
                type: 'GET',
                data: {
                    nik: userNik,
                    _token: '{{ csrf_token() }}',
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {
                    var fileName = 'Laporan Presensi ' + nama + ' ' + userNik + ' .pdf';

                    console.log(fileName);

                    // Membuat blob URL dari response dan membuat link download
                    var blob = new Blob([response], {
                        type: 'application/pdf'
                    });
                    var url = URL.createObjectURL(blob);
                    var a = document.createElement('a');

                    a.href = url;
                    a.download = fileName;
                    a.click();
                },
                error: function(response) {
                    console.log(userNik);
                    console.log(response.message);
                }
            });
        })
    })
</script>




{{-- @yield('title') --}}
@yield('user-content')
