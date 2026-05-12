@extends('layouts.app2')

@section('title')
    <title>Rekap Mingguan Yaumiyah</title>
@endsection

@section('content')
    <style>
        /* CSS Modern Soft-Card (Fixed Size) */
        .table-rekap {
            table-layout: fixed;
            width: 100%;
        }

        .table-rekap th {
            background-color: transparent !important;
            border-bottom: 2px solid #eaeaea !important;
            border-top: none !important;
            text-transform: uppercase;
            padding: 1rem 0.5rem !important;
            color: #6c757d;
        }

        .table-rekap td {
            vertical-align: middle !important;
            padding: 0.5rem !important;
            border-top: 1px solid #f5f5f5 !important;
            overflow: hidden;
        }

        /* Desain Kartu Nilai (Ukurannya Dikunci) */
        .cell-card {
            border-radius: 8px;
            padding: 6px;
            text-align: center;
            border: 1px solid transparent;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 85px;
            width: 100%;
            transition: all 0.2s ease;
        }

        .cell-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.04);
        }

        /* Warna Pastel Lembut */
        .card-success {
            background-color: #f0fdf4;
            border-color: #dcfce7;
        }

        .card-warning {
            background-color: #fefce8;
            border-color: #fef08a;
        }

        .card-danger {
            background-color: #fef2f2;
            border-color: #fee2e2;
        }

        /* Warna Teks Angka yang Menyesuaikan */
        .score-txt {
            font-size: 1.3rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 4px;
        }

        .text-success-dark {
            color: #16a34a;
        }

        .text-warning-dark {
            color: #ca8a04;
        }

        .text-danger-dark {
            color: #dc2626;
        }

        /* Teks Detail agar kalau kepanjangan otomatis terpotong */
        .jilid-txt {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .hal-txt {
            font-size: 0.7rem;
            color: #9ca3af;
            margin-top: 1px;
            width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* --- CSS Date Picker KOTAK (Seragam dengan Tema) --- */
        .date-picker-wrapper {
            background: #ffffff;
            border: 1px solid #ced4da;
            /* Warna border standar Bootstrap */
            border-radius: 0.25rem;
            /* Sudut kotak standar */
            padding: 0.375rem 0.75rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .date-picker-wrapper:hover,
        .date-picker-wrapper:focus-within {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .date-picker-input {
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
            color: #495057;
            cursor: pointer;
            padding: 0;
            height: auto;
            width: 120px;
        }

        .date-picker-input:focus {
            outline: none;
            box-shadow: none;
        }
    </style>

    <div class="app-main__inner">
        <!-- HEADER HALAMAN -->
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon shadow-sm" style="background: white;">
                        <i class="pe-7s-notebook icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Rekap Yaumiyah Mingguan - Tingkat {{ $tingkat }}
                        <div class="page-title-subheading">
                            Capaian siswa periode {{ $startOfWeek->translatedFormat('d M Y') }} s/d
                            {{ $endOfWeek->translatedFormat('d M Y') }}.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN CARD -->
        <div class="main-card card">
            <div class="card-header d-flex justify-content-between align-items-center py-4">

                <!-- Date Picker Widget Kotak -->
                <div class="d-flex align-items-center">
                    <label for="filter_tanggal" class="mb-0 text-muted mr-3"
                        style="font-size: 0.8rem; font-weight: 700;">PILIH PEKAN:</label>
                    <div class="date-picker-wrapper d-flex align-items-center">
                        <i class="pe-7s-date text-secondary mr-2" style="font-size: 1.2rem;"></i>
                        <input type="date" id="filter_tanggal" class="form-control form-control-sm date-picker-input"
                            value="{{ $tanggalPilih }}" onchange="changeDate(this.value)">
                    </div>
                    <a href="{{ route('yaumiyah-tahfiz.print', ['tingkat' => $tingkat, 'tanggal' => $tanggalPilih]) }}"
                        target="_blank" class="btn btn-primary btn-sm shadow-sm mr-2">
                        <i class="pe-7s-print mr-1"></i> CETAK PDF
                    </a>
                </div>

                <!-- Tombol Kembali Kotak Standar -->
                <a href="{{ route('yaumiyah-tahsin.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                    <i class="pe-7s-back mr-1"></i> KEMBALI
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table id="myTable2" class="align-middle mb-0 table table-hover table-rekap" style="min-width: 900px;">
                        <thead>
                            <tr>
                                <th class="text-center align-middle" style="width: 5%;">No</th>
                                <th style="width: 25%;" class="align-middle">Nama Siswa</th>

                                @foreach ($hariPekanIni as $hari)
                                    <th class="text-center" style="width: 14%;">
                                        <div style="font-size: 0.7rem; letter-spacing: 1px; font-weight: 700;">
                                            {{ \Carbon\Carbon::parse($hari)->translatedFormat('l') }}
                                        </div>
                                        <div class="text-dark mt-1"
                                            style="text-transform: none; font-size: 0.85rem; font-weight: 500;">
                                            {{ \Carbon\Carbon::parse($hari)->translatedFormat('d M') }}
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @forelse ($siswa as $item)
                                <tr>
                                    <td class="text-center text-muted font-weight-bold" style="font-size: 0.9rem;">
                                        {{ $no++ }}
                                    </td>
                                    <td>
                                        <div class="font-weight-bold text-dark text-truncate"
                                            style="font-size: 0.95rem; max-width: 200px;"
                                            title="{{ $item->anggotaKelas->siswa->nama_lengkap ?? 'Nama Tidak Ditemukan' }}">
                                            {{ $item->anggotaKelas->siswa->nama_lengkap ?? 'Nama Tidak Ditemukan' }}
                                        </div>
                                        <div class="text-muted mt-1" style="font-size: 0.75rem;">
                                            Kelas {{ $item->anggotaKelas->kelas->nama_kelas ?? '-' }}
                                        </div>
                                    </td>

                                    @foreach ($hariPekanIni as $hari)
                                        @php
                                            $record = $yaumiyah[$item->id][$hari] ?? null;

                                            $cardClass = '';
                                            $textClass = '';

                                            if ($record) {
                                                $nilai = $record->nilai ?? 0;
                                                if ($nilai >= 85) {
                                                    $cardClass = 'card-success';
                                                    $textClass = 'text-success-dark';
                                                } elseif ($nilai >= 70) {
                                                    $cardClass = 'card-warning';
                                                    $textClass = 'text-warning-dark';
                                                } else {
                                                    $cardClass = 'card-danger';
                                                    $textClass = 'text-danger-dark';
                                                }
                                            }
                                        @endphp

                                        <td class="text-center" style="padding: 0.4rem !important;">
                                            @if ($record)
                                                <div class="cell-card {{ $cardClass }}">

                                                    <div
                                                        title="{{ $record->surah_alquran_id ? $record->surahAlquran->nama_surah ?? '-' : 'Hal. ' . ($record->angkaArab->angka ?? '-') }}">
                                                        @if ($record->surah_alquran_id)
                                                            <div class="jilid-txt">
                                                                {{ $record->surahAlquran->nama_surah ?? '-' }}
                                                            </div>
                                                            <div class="hal-txt">
                                                                Ayat. {{ $record->angkaArab->id ?? '-' }}
                                                            </div>
                                                        @else
                                                            -
                                                        @endif
                                                    </div>

                                                    <div class="score-txt {{ $textClass }}">
                                                        {{ $record->nilai ?? 0 }}
                                                    </div>
                                                </div>
                                            @else
                                                <div class="cell-card"
                                                    style="background: transparent; border: 1px dashed #e5e7eb;">
                                                    <span
                                                        style="color: #d1d5db; font-size: 1.5rem; line-height: 0;">-</span>
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 2 + count($hariPekanIni) }}" class="text-center py-5">
                                        <div class="text-muted opacity-5 mb-3">
                                            <i class="pe-7s-notebook" style="font-size: 3rem;"></i>
                                        </div>
                                        <span class="text-muted font-italic">Belum ada data siswa di tingkat ini.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function changeDate(dateValue) {
            const url = new URL(window.location.href);
            url.searchParams.set('tanggal', dateValue);
            window.location.href = url.toString();
        }
    </script>
@endsection
