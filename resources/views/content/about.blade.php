<div class="container">
    @section('title')
        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    <h1>Tentang Aplikasi</h1>
                </div>
            </div>
        </div>
    @endsection

    @section('content')
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Fitur Utama Aplikasi</h3>
                        </div>
                        <div class="card-body">
                            <ul class="feature-list">
                                <li><strong>Data Guru:</strong> Aplikasi ini memungkinkan Anda untuk mengelola data guru,
                                    termasuk informasi pribadi dan riwayat absensi.</li>
                                <li><strong>Data Presensi Harian:</strong> Anda dapat mencatat kehadiran guru secara harian
                                    melalui aplikasi ini.</li>
                                <li><strong>Rekapitulasi Presensi Harian dan Bulanan:</strong> Aplikasi ini menyediakan
                                    rekapitulasi kehadiran guru baik dalam bentuk harian maupun bulanan.</li>
                                <li><strong>Data Revisi Absensi:</strong> Guru atau admin dapat merevisi data absensi jika
                                    terdapat kesalahan pencatatan.</li>
                                <li><strong>Data Izin Keluar:</strong> Guru dapat mengajukan izin keluar melalui aplikasi
                                    ini,
                                    dan admin dapat menyetujui atau menolaknya.</li>
                                <li><strong>Data Izin Tidak Masuk dan Cuti:</strong> Guru dapat mengajukan izin tidak masuk
                                    atau
                                    cuti, dan admin dapat mengelola permohonan ini melalui aplikasi.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Pengembang Aplikasi</h3>
                        </div>
                        <div class="card-body">
                            <p>
                                Nama: Rudi Mulyadi
                            </p>
                            <p>
                                Jenis Kelamin: Laki-laki
                            </p>
                            <p>
                                Kewarganegaraan: Indonesia
                            </p>
                            <p>Email: rudimulyadi011@gmail.com</p>
                            <p>Telepon: 082119453701</p>
                        </div>
                    </div>

                    <div class="card card-info" style="margin-top: 20px;">
                        <div class="card-header">
                            <h3 class="card-title">Pendidikan</h3>
                        </div>
                        <div class="card-body">
                            <img src="/logo-upb.png" alt="Logo UPB" style="max-width: 100px; margin-bottom: 10px;">
                            <p>
                                Pendidikan: S1 Teknik Informatika - <a href="https://pelitabangsa.ac.id"
                                    target="_blank">Universitas
                                    Pelita Bangsa <i class="fas fa-external-link-alt"></i></a>
                            </p>
                            <p>
                                Email: mail@pelitabangsa.ac.id
                            </p>
                            <p>
                                Alamat: Jl. Inspeksi Kalimalang Tegal Danas, Cikarang Pusat
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@yield('title')
@yield('content')
