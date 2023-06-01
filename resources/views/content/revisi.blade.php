@section('title')
    <h1>Daftar Permintaan Revisi Absen</h1>
@stop

@section('user-content')
    <div class="card" id="body">
        <div class="card-header">
            <h3 class="card-title">Permintaan Revisi Absen</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Yang Direvisi</th>
                        <th>Alasan</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Persetujuan</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($revisian as $revisi)
                        <tr>
                            <td>{{ $revisi->user_nik }}</td>
                            <td>{{ $revisi->name }}</td>
                            <td>{{ $revisi->tanggal }}</td>
                            <td>{{ $revisi->jam }}</td>
                            <td>{{ $revisi->yang_direvisi }}</td>
                            <td>{{ $revisi->alasan }}</td>
                            <td>
                                <button type="button" class="btn btn-primary btn-bukti" data-toggle='modal'
                                    data-img="{{ asset('storage') . '/' . $revisi->bukti_path }}"
                                    data-target="#imageModal{{ $revisi->id }}">
                                    Lihat Bukti
                                </button>
                            </td>
                            <td>{{ $revisi->is_approved == 1 ? 'Disetujui' : ($revisi->is_approved == 2 ? 'Ditolak' : 'Belum Disetujui') }}
                            </td>
                            <td>{{ $revisi->approval_name }}</td>
                            <td>
                                <button class="btn btn-success btn-setujui" data-id="{{ $revisi->id }}">Setujui</button>
                                <button class="btn btn-danger btn-tolak" data-id="{{ $revisi->id }}">Tolak</button>
                                <button class="btn btn-maroon btn-hapus" data-id="{{ $revisi->id }}">Hapus</button>
                            </td>
                        </tr>
                        
                        <!-- Modal -->
                        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog"
                            aria-labelledby="imageModalLabel{{ $revisi->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="imageModalLabel{{ $revisi->id }}">Gambar Presensi
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body-img">
                                        <img id="image" src="" class="img-fluid" width="400rem" height="400rem">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->


    </div>
    <!-- /.card -->
@stop

<script>
    $(document).ready(function() {
        $('.btn-bukti').click(function() {
            console.log('log');
            var imgSrc = $(this).data('img');
            console.log(imgSrc);
            var targetModal = $(this).data('target');
            console.log(targetModal);
            $(targetModal + ' .modal-body-img img').attr('src', imgSrc);
            document.getElementById('image').src = imgSrc;
            $('#imageModal').modal('show');

        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.btn-setujui').on('click', function(e) {
            e.preventDefault();

            var id = $(this).data('id');
            $.ajax({
                url: '/revisi/' + id,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_approved: 'approved'
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
            var id = $(this).data('id');
            $.ajax({
                url: '/revisi/' + id,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_approved: 'rejected'
                },
                success: function(data) {
                    $('#body').html(data);
                }
            });
        });

        $('.btn-hapus').on('click', function() {
            var id = $(this).data('id');
            if (confirm("Apakah Anda yakin ingin menghapus revisi ini?")) {
                $.ajax({
                    url: '/revisi/' + id,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
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
