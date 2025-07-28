<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Presensi Bulanan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }
        thead {
            background-color: #f2f2f2;
        }
        h2 {
            text-align: center;
            margin-bottom: 0;
        }
        .subtitle {
            text-align: center;
            font-size: 14px;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <h2>Laporan Presensi Bulanan</h2>
    <p class="subtitle">Bulan: {{ \Carbon\Carbon::parse($bulan->bulan_angka)->translatedFormat('F Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kelas</th>
                <th>S</th>
                <th>I</th>
                <th>A</th>
                <th>Hadir (%)</th>
                <th>Tidak Hadir (%)</th>
                <th>Tepat Waktu (%)</th>
                <th>Terlambat (%)</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($statistikPerKelas as $data)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $data['kelas']->nama_kelas }}</td>
                    <td>{{ $data['presensi']->where('status', 'sakit')->count() }}</td>
                    <td>{{ $data['presensi']->where('status', 'izin')->count() }}</td>
                    <td>{{ $data['presensi']->where('status', 'alpha')->count() }}</td>
                    <td>{{ number_format($data['persentaseHadir'], 1) }}</td>
                    <td>{{ number_format($data['persentaseTidakHadir'], 1) }}</td>
                    <td>{{ number_format($data['persentaseTepatWaktu'], 1) }}</td>
                    <td>{{ number_format($data['persentaseTerlambat'], 1) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
