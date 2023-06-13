

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Dashboard</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- Jumlah Guru PNS -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ $jumlahGuruPNS }}</h3>
                        <p>Jumlah Guru PNS</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <!-- Jumlah Guru Honorer -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{ $jumlahGuruHonorer }}</h3>
                        <p>Jumlah Guru Honorer</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <!-- Absensi Hari Ini -->
                <div class="box">
                    
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Jumlah Absen -->
                                <div class="info-box">
                                    <span class="info-box-icon bg-blue"><i class="fa fa-calendar-check-o"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Sudah Absen</span>
                                        <span class="info-box-number">{{ $jumlahSudahAbsen }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Jumlah Belum Absen -->
                                <div class="info-box">
                                    <span class="info-box-icon bg-red"><i class="fa fa-calendar-times-o"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Belum Absen</span>
                                        <span class="info-box-number">{{ $jumlahBelumAbsen }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <!-- Persentase Absensi -->
                            <h4>Persentase Absensi Hari Ini</h4>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $persentaseAbsensi }}%"></div>
                            </div>
                            <span>{{ $persentaseAbsensi }}% Absensi Hari Ini</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <!-- Grafik Absensi Harian -->
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Grafik Absensi Harian</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="absensiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@yield('content')

@push('scripts')
    <script>
        $(function () {
            // Data Grafik
            var absensiData = {
                labels: {!! json_encode($grafikLabel) !!},
                datasets: [
                    {
                        label: 'Absensi Masuk',
                        data: {!! json_encode($grafikAbsensiMasuk) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Absensi Pulang',
                        data: {!! json_encode($grafikAbsensiPulang) !!},
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            };

            // Konfigurasi Grafik
            var absensiOptions = {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: {!! json_encode($grafikMax) !!}
                    }
                }
            };

            // Render Grafik
            var absensiChart = new Chart(document.getElementById('absensiChart'), {
                type: 'line',
                data: absensiData,
                options: absensiOptions
            });
        });
    </script>
@endpush
