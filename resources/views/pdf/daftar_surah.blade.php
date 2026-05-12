<!DOCTYPE html>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Daftar Surah Al-Quran</title>
    <style>
        @font-face {
            font-family: 'Traditional Arabic';
            font-style: normal;
            font-weight: normal;
            src: url("file://{{ public_path('fonts/trado.ttf') }}") format("truetype");
        }

        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
        }

        .font-arab {
            font-family: 'Traditional Arabic', sans-serif;
            font-size: 25px;
            line-height: 0.5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 6px;
            text-align: center;
            vertical-align: middle;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="title">DAFTAR SURAH AL-QURAN </div>
    <div style="font-size: 20px; text-align: center;">
        Jilid <span class="font-arab">{{ $arab->angka_arab ?? 'N/A' }}</span>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th style="width: 40%;">Nama Surah</th>
                <th style="width: 15%;">Jumlah Ayat</th>
                <th style="width: 40%;">Nama (Arab)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataSurah as $index => $surah)
                <tr>
                    <!-- Karena narik dari DB, kita bisa pakai property panah (->) -->
                    <td>{{ $index + 1 }}</td>
                    <td style="text-align: left; padding-left: 10px;">{{ $surah->nama_surah }}</td>
                    <td>{{ $surah->jumlah_ayat }}</td>

                    <td class="font-arab" style="text-align: right; padding-right: 15px;">
                        {{ $surah->nama_arab_cetak }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
