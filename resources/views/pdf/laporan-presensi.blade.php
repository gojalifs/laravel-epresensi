<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Presensi</title>
</head>

<body>
    <h1>Laporan Presensi</h1>

    <table>
        <thead>
            <tr>
                <th>ID Presensi</th>
                <th>NIK</th>
                <th>Nama</th>
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
                    <td>{{ $item->nik }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->jenis }}</td>
                    <td>{{ $item->jam }}</td>
                    <td>{{ $item->longitude }}</td>
                    <td>{{ $item->latitude }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
