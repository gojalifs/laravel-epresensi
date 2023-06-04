@extends('layouts.adminlte')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('Haloo. Selamat datang di Aplikasi Admin presensi!') }}
                        <br>
                        {{ __('SMP Negeri 1 Karang bahagia') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('user-content')
    <div class="card">
        
    </div>
    <!-- /.card -->
@endsection
