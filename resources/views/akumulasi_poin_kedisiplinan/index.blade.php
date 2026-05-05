@extends('layouts.app2')

@section('title')
    <title>Akumulasi Poin Kedisiplinan</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-target icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Akumulasi Poin Kedisiplinan
                        <div class="page-title-subheading">
                            Laporan total poin pelanggaran (-) dan prestasi (+) siswa per kelas.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Filter Kelas -->
        <div class="main-card card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('akumulasi-poin-kedisiplinan.index') }}" class="form-inline">
                    <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                        <label for="kelas_id" class="mr-sm-2">Pilih Kelas:</label>
                        <select name="kelas_id" id="kelas_id" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ $kelasTerpilih == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Tampilkan Akumulasi</button>

                    @if ($kelasTerpilih)
                        <a href="{{ route('akumulasi-poin-kedisiplinan.index') }}" class="btn btn-secondary ml-2">Reset</a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Tabel Hasil Akumulasi (Muncul Jika Kelas Dipilih) -->
        @if ($kelasTerpilih)
            <div class="main-card card">
                <div class="card-header">
                    Hasil Akumulasi Poin
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="mb-0 table table-hover table-striped" id="myTable2">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th>Nama Siswa</th>
                                    <th class="text-center">Total Pelanggaran</th>
                                    <th class="text-center">Total Prestasi</th>
                                    <th class="text-center">Akumulasi Poin</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($akumulasiSiswa as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->siswa->nama_lengkap ?? 'Tanpa Nama' }}</td>
                                        <td class="text-center text-danger">
                                            -{{ $item->poin_pelanggaran }}
                                        </td>
                                        <td class="text-center text-success">
                                            +{{ $item->poin_prestasi }}
                                        </td>
                                        <td class="text-center">
                                            @if ($item->total_poin < 0)
                                                <span class="badge badge-danger">{{ $item->total_poin }}</span>
                                            @elseif($item->total_poin > 0)
                                                <span class="badge badge-success">+{{ $item->total_poin }}</span>
                                            @else
                                                <span class="badge badge-secondary">{{ $item->total_poin }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item->total_poin < -50)
                                                <span class="text-danger font-weight-bold">Peringatan Keras!</span>
                                            @elseif($item->total_poin < 0)
                                                <span class="text-warning font-weight-bold">Perlu Perhatian</span>
                                            @else
                                                <span class="text-success font-weight-bold">Aman</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data siswa di kelas ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
