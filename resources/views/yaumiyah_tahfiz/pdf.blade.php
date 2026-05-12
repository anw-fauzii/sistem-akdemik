<!DOCTYPE html>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Cetak Rekap Tahfiz - Tingkat {{ $tingkat }}</title>

    <style>
        /* MENGGUNAKAN FONT CUSTOM ARAB (Sesuai Referensi Anda) */
        @font-face {
            font-family: 'Traditional Arabic';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/trado.ttf') }}") format("truetype");
        }

        @page {
            margin: 1cm;
        }

        /* Margin diperkecil agar isi tabel muat */
        body {
            font-family: 'Helvetica', sans-serif;
            color: #000;
            font-size: 9pt;
        }

        /* ... (CSS Kop Surat & Judul sama seperti sebelumnya) ... */
        .kop-surat {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .kop-surat h4,
        .kop-surat h3 {
            margin: 0;
            font-weight: bold;
        }

        .kop-surat p {
            margin: 2px 0;
            font-size: 9pt;
        }

        .judul-laporan {
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        /* TABEL DATA */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
            font-size: 8pt;
        }

        th {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        .text-left {
            text-align: left;
            padding-left: 8px;
        }

        /* KELAS KHUSUS FONT ARAB */
        .font-arab {
            font-family: 'Traditional Arabic', sans-serif;
            font-size: 14pt;
            /* Sesuaikan ukurannya */
            line-height: 0.4;
            /* Diubah jadi 1 agar tidak terlalu mepet ke atas */
            /* Direction RTL dihilangkan karena ArPHP sudah me-reverse susunan hurufnya */
        }

        .signature-table {
            width: 100%;
            margin-top: 30px;
            border: none;
        }

        .signature-table td {
            border: none;
            text-align: center;
            vertical-align: bottom;
            font-size: 9pt;
        }

        .signature-space {
            height: 60px;
        }
    </style>
</head>

<body>

    @php
        $path = public_path('storage/logo/kop_sd.png');
        if (!file_exists($path)) {
            $path = storage_path('app/public/logo/kop_sd.png');
        }
        if (!file_exists($path)) {
            $path = storage_path('logo/kop_sd.png');
        }

        $base64Kop = '';
        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64Kop = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
    @endphp

    <div class="kop-surat" style="text-align: center; border-bottom: none; margin-bottom: 0px; padding-bottom: 0;">
        @if ($base64Kop)
            <img src="{{ $base64Kop }}" style="width: 40%; max-width: 650px; height: auto; margin-top: -30px;"
                alt="Kop Surat SD GIS Prima Insani">
        @else
            <h4 style="color: red;">[GAMBAR KOP SURAT TIDAK DITEMUKAN]</h4>
            <p>Pastikan file berada di: storage/logo/kop_sd.png</p>
        @endif
    </div>

    <hr style="border: none; border-top: 2px solid black; margin-top: 10px; margin-bottom: 15px; width: 100%;">
    <div class="judul-laporan">
        REKAP PERKEMBANGAN CAPAIAN TAHFIZ QURAN<br>
        TAHUN AJARAN {{ $tahunAjaran->nama_tahun_ajaran ?? '2024-2025' }}
    </div>

    <div style="margin-bottom: 5px;">
        <strong>Periode :</strong> {{ \Carbon\Carbon::parse($startOfWeek)->translatedFormat('d M') }} -
        {{ \Carbon\Carbon::parse($endOfWeek)->translatedFormat('d M Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" width="3%">No.</th>
                <th rowspan="2" width="17%">Nama</th>

                @foreach ($hariPekanIni as $hari)
                    <th colspan="3">{{ \Carbon\Carbon::parse($hari)->translatedFormat('l, d M Y') }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach ($hariPekanIni as $hari)
                    <th width="6%">Surah</th>
                    <th width="4%">Ayat</th>
                    <th width="3%">Nilai</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($siswa as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-left">{{ $item->anggotaKelas->kelas->nama_kelas ?? '-' }} |
                        {{ $item->anggotaKelas->siswa->nama_lengkap ?? '-' }}</td>

                    @foreach ($hariPekanIni as $hari)
                        @php $record = $yaumiyah[$item->id][$hari] ?? null; @endphp

                        <td class="font-arab">{{ $record ? $record->surah_cetak_arab : '-' }}</td>

                        <td class="font-arab">
                            {{ $record && $record->angkaArab ? $record->angkaArab->angka_arab : '-' }}
                        </td>

                        <td class="font-arab">{{ $record ? $record->nilaiArab->angka_arab : '-' }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 3 + count($hariPekanIni) * 3 }}">Belum ada data siswa.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td style="width: 50%; text-align: center; padding-left: 40px;">
                Mengetahui<br>
                Kepala Sekolah SD Garut Islamic School<br>
                Prima Insani
                <div class="signature-space"></div>
                <strong style="text-decoration: underline;">Puji Fauziah, S.Pd.SD</strong><br>
                NRKS. 190231.1040211212119042
            </td>
            <td style="width: 50%; text-align: center; padding-right: 40px;">
                Garut, ....................................<br><br>
                Guru T2Q<br>
                <br>
                <div class="signature-space"></div>
                <strong
                    style="text-decoration: underline;">{{ Auth::user()->name ?? 'Fajrin Munifah, S.Sos' }}</strong><br>
                NIPY. {{ Auth::user()->email ?? '-' }}
            </td>
        </tr>
    </table>

</body>

</html>
