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
        .table td img {
            max-width: 10px; /* atau sesuai kebutuhan */
            max-height: 10px;
            display: block;
            margin: 0 auto; /* supaya tetap center */
        }
    </style>
</head>
<body>
    <h2>Laporan Presensi Bulanan Kelas {{$kelas->nama_kelas}}</h2>
    <p class="subtitle">Bulan: {{ \Carbon\Carbon::parse($bulan->bulan_angka)->translatedFormat('F Y') }}</p>

    <table class="mb-0 table table-hover table-striped" id="myTable2">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                @foreach ($tanggal_tercatat as $tanggal)
                    <th class="text-center">
                        <strong>{{ \Carbon\Carbon::parse($tanggal)->format('d/m') }}</strong>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @if ($tanggal_tercatat->isEmpty()) 
            <tr>
                <td colspan="2" class="text-center">Belum ada data </td>
            </tr>
            @else
            @php
                $no = 1;
            @endphp
                @foreach ($anggotaKelas as $anggota)
                    <tr>
                        <td>{{$no++}}</td>
                        <td style="text-align: left">{{ $anggota->siswa->nama_lengkap }}</td>
                        @foreach ($tanggal_tercatat as $tanggal)
                            @php
                                $presensiData = $presensi
                                    ->where('anggota_kelas_id', $anggota->id)
                                    ->first(function ($item) use ($tanggal) {
                                        return \Carbon\Carbon::parse($item->tanggal)->toDateString() === $tanggal;
                                    });
                            @endphp
                                @if ($presensiData)
                                    @if ($presensiData->status == 'hadir')
                                    <td class="text-center"><img src="ceklis.png" alt="✓"></td>
                                    @elseif ($presensiData->status == 'sakit')
                                    <td class="text-center" style="background-color: yellow;"> S </td>
                                    @elseif ($presensiData->status == 'izin')
                                    <td class="text-center" style="background-color: green; color:white"> I </td>
                                    @elseif ($presensiData->status == 'alpha')
                                    <td class="text-center" style="background-color: red; color:white"> A </td>
                                    @else
                                        {{ $presensiData->status }} 
                                    @endif
                                @else
                                    <td class="text-center" style="background-color: black;"> -</td>
                                @endif
                        @endforeach
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</body>
</html>
