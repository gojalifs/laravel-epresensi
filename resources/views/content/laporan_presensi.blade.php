@section('title')
    <h1>Data Presensi</h1>
@stop

@section('user-content')

    <div class="container" id="body">
        <h3 id="month_name"></h3>
        <form action="" method="GET" id="form-presensi">
            <div class="form-group row">
                <label for="tanggal" class="col-md-2 col-form-label text-md-right">Tanggal</label>
                <div class="col-md-3">
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $tanggal }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary" id="submit-btn">Tampilkan</button>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered" id="monthly-presensi">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>NIP</th>
                            <th>Rekapitulasi Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>

                                <td>{{ $user->nama }}</td>
                                <td>{{ $user->nik }}</td>
                                <td>{{ $user->nipns }}</td>
                                <td><button type="button" class="btn btn-success get_report" data-id="{{ $user }}">
                                        Tampilkan sebagai PDF
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

<script>
    $(document).ready(function() {
        $('#form-presensi').on('submit', function(e) {
            e.preventDefault();
            var tanggal = $('#tanggal').val();
            $.ajax({
                url: "/presensi?isGetReport=true",
                type: 'GET',
                data: {
                    tanggal: tanggal
                },
                success: function(data) {
                    console.log(data);
                    $('#body').html(data);
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        var bulans = '<?php echo $bulans; ?>';
        var namaBulan = new Date(Date.UTC(2023, bulans - 1, 1)).toLocaleString('id-ID', {
            month: 'long'
        });
        document.getElementById('month_name').innerHTML = 'Data Presensi Bulan ' + namaBulan;
        $('.get_report').on('click', function() {
            // Mendapatkan nilai dari atribut data-id
            var userData = $(this).data('id');

            // Parsing string JSON menjadi objek JavaScript
            // console.log(userData);
            console.log(userData);
            // var user = JSON.parse(userId);
            // Mendapatkan nilai nama dan nik dari objek user
            var nama = userData.nama;
            var userNik = userData.nik;
            // var userNik = $(this).data('id');
            var tanggal = $('#tanggal').val();

            $.ajax({
                url: '/presensi/report',
                type: 'GET',
                data: {
                    nik: userNik,
                    _token: '{{ csrf_token() }}',
                    tanggal: tanggal
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {
                    var fileName = 'Laporan Presensi ' + nama + ' ' + namaBulan + ' ' +
                        userNik + '.pdf';

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

@yield('user-content')
