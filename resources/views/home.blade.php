@extends('layouts.adminlte')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Dashboard</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div style="background-color: lightgreen; padding: 10px;">
            <span style="font-weight: bold; color: green;">
                {{ __('Haloo. Selamat datang di aplikasi admin presensi') }}
            </span>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-4">
                <!-- Jumlah Guru PNS -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ $user }}</h3>
                        <p>Jumlah Total Guru/Admin</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <!-- Jumlah Guru PNS -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{ $jumlahGuruPNS }}</h3>
                        <p>Jumlah Guru PNS</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <!-- Jumlah Guru Honorer -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{ $jumlahGuruHonorer }}</h3>
                        <p>Jumlah Guru Honorer</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <!-- Jumlah Guru Sudah Absen Hari Ini -->
                <div class="small-box bg-blue">
                    <div class="inner">
                        <h3>{{ $jumlahSudahAbsen }}</h3>
                        <p>Jumlah Guru Sudah Absen Hari Ini</p>
                    </div>
                    <div class="icon">
                        <i class="far fa-calendar-check"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <!-- Jumlah Guru Belum Absen Hari Ini -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{ $jumlahBelumAbsen }}</h3>
                        <p>Jumlah Guru Belum Absen Hari Ini</p>
                    </div>
                    <div class="icon">
                        <i class="far fa-calendar-times"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <!-- Persentase Absensi Hari Ini -->
                <div class="small-box bg-orange">
                    <div class="inner">
                        <h3>{{ $persentaseAbsensi }}%</h3>
                        <p>Persentase Absensi Hari Ini</p>
                    </div>
                    <div class="icon">
                        <i class="far fa-calendar-times"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- /.content -->
@endsection
