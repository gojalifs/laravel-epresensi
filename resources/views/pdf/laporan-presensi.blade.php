<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Presensi</title>

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1,
        h2,
        h3 {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .divider {
            margin-top: 20px;
            margin-bottom: 20px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <h1>Laporan Bulan {{ $month }}</h1>

    <p>Nama : {{ $users->nama }}</p>
    <p>NIK : {{ $users->nik }}</p>
    <p>NIP : {{ $users->nipns }}</p>
    <p>Email : {{ $users->email }}</p>
    <p>Telp : {{ $users->telp }}</p>

    <div class="divider"></div>

    <h3>Laporan Presensi</h3>
    <table>
        <thead>
            <tr>
                <th>ID Presensi</th>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Jam</th>
                <th>Longitude</th>
                <th>Latitude</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($report as $item)
                <tr>
                    <td>{{ $item->id_presensi }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->jenis }}</td>
                    <td>{{ $item->jam }}</td>
                    <td>{{ $item->longitude }}</td>
                    <td>{{ $item->latitude }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Laporan Cuti</h3>
    <h5>Total Cuti : {{ $leaves->count() }}</h5>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Alasan</th>
                <th>Potong Cuti</th>
                <th>Jenis Cuti</th>
                <th>Status</th>
                <th>Persetujuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaves as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->tanggal_selesai }}</td>
                    <td>{{ $item->alasan }}</td>
                    <td>{{ $item->potong_cuti }}</td>
                    <td>{{ $item->jenis_cuti }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->approval }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Laporan Revisi</h3>
    <h5>Total Revisi : {{ $revisions->count() }}</h5>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal Mulai</th>
                <th>Jam</th>
                <th>Yang Direvisi</th>
                <th>Alasan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($revisions as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->jam }}</td>
                    <td>{{ $item->yang_direvisi }}</td>
                    <td>{{ $item->alasan }}</td>
                    <td>{{ $item->is_approved }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Laporan Izin Keluar</h3>
    <h5>Total Izin Keluar : {{ $exits->count() }}</h5>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Alasan</th>
                <th>Tanggal</th>
                <th>Jam Keluar</th>
                <th>Jam Kembali</th>
                <th>Status</th>
                <th>Persetujuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($exits as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->alasan }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->jam_keluar }}</td>
                    <td>{{ $item->jam_kembali }}</td>
                    <td>{{ $item->is_approved }}</td>
                    <td>{{ $item->approval }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
