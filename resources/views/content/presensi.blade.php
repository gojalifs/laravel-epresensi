@section('title')
    <h1>Data Presensi</h1>
@stop

@section('user-content')




    <div class="container" id="body">
        {{-- <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mapModalLabel">Map</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="mapContainer" style="height: 500px;"></div>
                    </div>
                </div>
            </div>
        </div> --}}

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
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Keterangan</th>
                            <th>NIP</th>
                            <th>Status</th>
                            <th>Jam</th>
                            <th>Lokasi Maps</th>
                            <th>Foto Wajah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($presensis as $presensi)
                            <tr
                                @if ($presensi->jam == '' || !$presensi->jam) class="bg-danger text-white" @else class="bg-green text-white" @endif>
                                <td>{{ $presensi->nik }}</td>
                                <td>{{ $presensi->nama }}</td>
                                <td>{{ $presensi->jenis }}</td>
                                <td>{{ $presensi->nipns }}</td>
                                @if ($presensi->nipns == '' || !$presensi->nipns)
                                    <td>Honorer</td>
                                @else
                                    <td>PNS</td>
                                    </button>
                                @endif
                                <td>{{ $presensi->jam }}</td>
                                <td>
                                    @if ($presensi->jam == '' || !$presensi->jam)
                                    @else
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                            data-long="{{ $presensi->longitude }}" data-lat="{{ $presensi->latitude }}"
                                            data-target="#myModal{{ $presensi->id }}" data-mapid="map{{ $presensi->id }}">
                                            Lihat Map
                                        </button>
                                    @endif
                                </td>
                                <td>
                                    @if ($presensi->jam == '' || !$presensi->jam)
                                    @else
                                        <button type="button" class="btn btn-primary lihat-btn"
                                            data-img="{{ asset('storage') . '/' . $presensi->img_path }}"
                                            data-target="#imageModal{{ $presensi->id }}">
                                            Lihat Foto
                                        </button>
                                    @endif
                                </td>
                            </tr>

                            <!-- Map Modal -->
                            <div class="modal fade" id="myModal{{ $presensi->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel" data-target="#myModal{{ $presensi->id }}">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel">Map {{ $presensi->nama }}</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- map container -->
                                            <div id="map{{ $presensi->id }}" style="height: 400px"></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Modal -->
                            <div class="modal fade" id="imageModal{{ $presensi->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="imageModalLabel{{ $presensi->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="imageModalLabel{{ $presensi->id }}">Gambar Presensi
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body-img">
                                            <img src="" class="img-fluid" width="400rem" height="400rem">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

<script>
    $(document).ready(function() {
        var map = null; // Variable to hold the map instance

        $('.btn-sm').click(function() {
            var targetModal = $(this).data('target');
            var long = $(this).data('long');
            var lat = $(this).data('lat');
            var mapid = $(this).data('mapid');
            // Remove the previous map instance if it exists
            if (map) {
                map.remove();
                map = null;
            }


            map = L.map(mapid).setView([lat, long], 16);

            // L.marker([lat, long]).addTo(map);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Reverse geocode the location to obtain the address
            var geocodeUrl = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + lat +
                '&lon=' +
                long;
            fetch(geocodeUrl)
                .then(response => response.json())
                .then(data => {
                    // Add a marker to the map at a specific location
                    var marker = L.marker([lat, long]).addTo(map);

                    // Add a popup to the marker with the street name and location name
                    marker.bindPopup("<b>" + data.display_name).openPopup();
                });

            // Comment out the below code to see the difference.
            $(targetModal).on('shown.bs.modal', function() {
                map.invalidateSize();
            });
        });
    });
</script>


<script>
    $(document).ready(function() {
        $('.lihat-btn').click(function() {
            var imgSrc = $(this).data('img');
            var targetModal = $(this).data('target');
            $(targetModal + ' .modal-body-img img').attr('src', imgSrc);
            $(targetModal).modal('show');
        });
    });
</script>

{{-- <script>
    $('.btn-sm').on('show.bs.modal', function(event) {
        map.invalidateSize();
        var long = 107.1895377;
        var lat = -6.1981671;
        // Create a Leaflet map centered on a specific location
        var mymap = L.map('mapid').setView([lat, long], 16);

        // Add a tile layer from OpenStreetMap
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: "Map data &copy; <a href='https://www.openstreetmap.org/'>OpenStreetMap</a> contributors",
            maxZoom: 18,
        }).addTo(mymap);

        // Reverse geocode the location to obtain the address
        var geocodeUrl = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + lat + '&lon=' +
            long;
        fetch(geocodeUrl)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                // Extract the street name and location name from the response
                var address = data.address;
                var street = address.residential || address.pedestrian || '';
                var location =
                    address.neighbourhood || address.suburb || address.city || address.town || address
                    .village || '';

                // Add a marker to the map at a specific location
                var marker = L.marker([lat, long]).addTo(mymap);

                // Add a popup to the marker with the street name and location name
                marker.bindPopup("<b>" + data.display_name + "</b><br>" + location).openPopup();
            });
    });
</script> --}}

{{-- Modal Untuk selfie Presensi --}}


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


{{-- 
@section('user-content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.css"
            integrity="sha384-JYwKdGdCZOvmPcN2gY7i/njZtTJmHkAdx1aJRmaGFWO0VTd9XttcI+K/fQWNW0Yx" crossorigin="anonymous">
        <!-- Leaflet JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"
            integrity="sha384-KI/zl9yfMdE8eW/uP4ynvF3dndqAxyRJJUA2rVnfyk0Nl3BZWtJWn/HU6v/Dus4l" crossorigin="anonymous">
        </script>
        <title>Map</title>
    </head>

    <body>
        <div id="mapid"></div>

    </body>

    </html>

    <script>
        // Initialize the map
        var mymap = L.map('mapid').setView([51.505, -0.09], 13);

        // Add a tile layer to the map
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
            maxZoom: 18,
        }).addTo(mymap);

        // Add a marker to the map
        var marker = L.marker([51.5, -0.09]).addTo(mymap);
    </script>
@endsection
 --}}
{{-- @yield('title') --}}
@yield('user-content')
