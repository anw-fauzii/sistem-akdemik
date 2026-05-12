<!DOCTYPE html>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Cetak Rekap Tahsin - Tingkat {{ $tingkat }}</title>

    <style>
        /* MENGGUNAKAN FONT CUSTOM ARAB */
        @font-face {
            font-family: 'Traditional Arabic';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/trado.ttf') }}") format("truetype");
        }

        @page {
            margin: 1cm;
        }

        body {
            font-family: 'Helvetica', sans-serif;
            color: #000;
            font-size: 9pt;
        }

        /* KOP SURAT */
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
            table-layout: fixed;
            /* KUNCI UTAMA AGAR KOLOM PRESISI & TIDAK MELAR */
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
            font-size: 8pt;
            word-wrap: break-word;
            /* Mencegah teks tumpah keluar kotak */
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
            line-height: 0.5;
            /* Diubah agar teks Arab yang panjang (turun baris) tidak saling tumpuk */
        }

        /* TANDA TANGAN */
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

        .img-ttd {
            height: 60px;
            /* Sesuaikan tinggi TTD agar pas dengan spasi */
            margin-top: 5px;
            margin-bottom: 5px;
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

        $pathTtd = public_path('ttd.png');

        $base64Ttd = '';
        if (file_exists($pathTtd)) {
            $typeTtd = pathinfo($pathTtd, PATHINFO_EXTENSION);
            $dataTtd = file_get_contents($pathTtd);
            $base64Ttd = 'data:image/' . $typeTtd . ';base64,' . base64_encode($dataTtd);
        }
        $pathTtd1 = public_path('ttd-1.png');

        $base64Ttd1 = '';
        if (file_exists($pathTtd1)) {
            $typeTtd1 = pathinfo($pathTtd1, PATHINFO_EXTENSION);
            $dataTtd1 = file_get_contents($pathTtd1);
            $base64Ttd1 = 'data:image/' . $typeTtd1 . ';base64,' . base64_encode($dataTtd1);
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
        REKAP PERKEMBANGAN CAPAIAN TAHSIN QURAN<br>
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
                    <th width="8%">Jilid</th>
                    <th width="5%">Hal/Surah</th>
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

                        <td class="font-arab">{{ $record ? $record->surah_jilid_arab : '-' }}</td>

                        <td class="font-arab">
                            @if ($record && $record->surah_alquran_id)
                                {{ $record->surah_cetak_arab ?? '-' }}
                            @elseif($record && $record->angka_arab_id)
                                {{ $record->angkaArab->angka_arab ?? '-' }}
                            @else
                                -
                            @endif
                        </td>

                        <td class="font-arab">
                            {{ $record && $record->nilaiArab ? $record->nilaiArab->angka_arab : '-' }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 2 + count($hariPekanIni) * 3 }}">Belum ada data siswa.</td>
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
                @if ($base64Ttd1)
                    <div><img src="{{ $base64Ttd1 }}" class="img-ttd" alt="TTD Kepsek"></div>
                @else
                    <div class="signature-space"></div>
                @endif
                <strong style="text-decoration: underline;">Puji Fauziah, S.Pd.SD</strong><br>
                NRKS. 190231.1040211212119042
            </td>
            <td style="width: 50%; text-align: center; padding-right: 40px;">
                Garut, ....................................<br><br>
                Guru T2Q<br>
                <br>
                @if ($base64Ttd)
                    <div><img src="{{ $base64Ttd }}" class="img-ttd" alt="TTD Kepsek"></div>
                @else
                    <div class="signature-space"></div>
                @endif
                <strong
                    style="text-decoration: underline;">{{ Auth::user()->name ?? 'Fajrin Munifah, S.Sos' }}</strong><br>
                NIPY. {{ Auth::user()->email ?? '-' }}
            </td>
        </tr>
    </table>

</body>

</html>
