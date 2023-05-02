{{-- @extends('layouts/adminlte') --}}

@section('title')
    <h1>Daftar Permintaan Revisi Absen</h1>
@stop

@section('user-content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Permintaan Revisi Absen</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Yang Direvisi</th>
                        <th>Alasan</th>
                        <th>Disetujui?</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($revisian as $revisi)
                        <tr>
                            <td>{{ $revisi->user_nik }}</td>
                            <td>{{ $revisi->tanggal }}</td>
                            <td>{{ $revisi->jam }}</td>
                            <td>{{ $revisi->direvisi }}</td>
                            <td>{{ $revisi->alasan }}</td>
                            <td>{{ $revisi->is_approved == 1 ? 'Disetujui' : 'Belum Disetujui' }}</td>
                            <td>
                                <a href="#" class="btn btn-primary">Setujui</a>
                                <a href="#" class="btn btn-primary">Tolak</a>
                                <a href="#" class="btn btn-danger">Delete</a>
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
@yield('user-content')