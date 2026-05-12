@extends('layouts.app2')

@section('title')
    <title>Input Yaumiyah Tahfiz</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon shadow-sm bg-white">
                        <i class="pe-7s-study text-success"></i>
                    </div>
                    <div>Input Yaumiyah Tahfiz - Tingkat {{ request()->route('tingkat') }}
                        <div class="page-title-subheading">
                            Mencatat setoran hafalan Surah, Ayat, dan nilai harian siswa.
                        </div>
                    </div>
                </div>

                <div class="page-title-actions">
                    <div class="d-inline-block">
                        <label for="filter_tanggal" class="font-weight-bold mr-2 mb-0 align-middle">Tanggal:</label>
                        <input type="date" id="filter_tanggal" class="form-control d-inline-block w-auto shadow-sm"
                            value="{{ $tanggal }}" onchange="changeDate(this.value)">
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card card">
            <div class="card-header">
                Form Input Hafalan - {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('yaumiyah-tahfiz.store') }}" id="createForm">
                    @csrf

                    <div class="table-responsive">
                        <table class="mb-0 table table-hover table-striped align-middle">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th>Nama Siswa</th>
                                    <th width="30%">Surah Al-Quran</th>
                                    <th width="20%">Ayat</th>
                                    <th width="15%" class="text-center">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($siswa as $index => $item)
                                    @php
                                        // Ambil record database
                                        $dbRecord = $yaumiyah->get($item->id);

                                        // Anti Data-Loss: Prioritaskan inputan 'old()' jika error validasi
                                        $oldSurah = old(
                                            "records.{$index}.surah_alquran_id",
                                            $dbRecord->surah_alquran_id ?? '',
                                        );
                                        $oldAngka = old(
                                            "records.{$index}.angka_arab_id",
                                            $dbRecord->angka_arab_id ?? '',
                                        );
                                        $oldNilai = old("records.{$index}.nilai", $dbRecord->nilai ?? '');
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="font-weight-bold text-dark">
                                                {{ $item->anggotaKelas->siswa->nama_lengkap ?? '-' }}</div>
                                            <div class="small text-muted">Kelas
                                                {{ $item->anggotaKelas->kelas->nama_kelas ?? '-' }}</div>

                                            <input type="hidden" name="records[{{ $index }}][anggota_t2q_id]"
                                                value="{{ $item->id }}">
                                            <input type="hidden" name="records[{{ $index }}][tanggal]"
                                                value="{{ $tanggal }}">
                                        </td>

                                        <td>
                                            <select name="records[{{ $index }}][surah_alquran_id]"
                                                class="multiselect-dropdown form-control">
                                                <option value="">-- Pilih Surah --</option>
                                                @foreach ($surah as $s)
                                                    <option value="{{ $s->id }}"
                                                        {{ $oldSurah == $s->id ? 'selected' : '' }}>
                                                        {{ $s->nama_surah ?? $s->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td>
                                            <select name="records[{{ $index }}][angka_arab_id]"
                                                class="multiselect-dropdown form-control">
                                                <option value="">-- Ayat --</option>
                                                @foreach ($angka as $a)
                                                    <option value="{{ $a->id }}"
                                                        {{ $oldAngka == $a->id ? 'selected' : '' }}>
                                                        Ayat {{ $a->id ?? $a->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td>
                                            <input name="records[{{ $index }}][nilai]" type="number"
                                                class="form-control text-center font-weight-bold" placeholder="0-100"
                                                min="0" max="100" value="{{ $oldNilai }}"
                                                oninput="if(this.value > 100) this.value = 100; if(this.value < 0) this.value = 0;">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="pe-7s-info d-block mb-2" style="font-size: 2rem;"></i>
                                            Belum ada data siswa di tingkat ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($siswa->isNotEmpty())
                        <div class="form-group mt-4 text-right">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm" id="submitBtn">
                                <i class="pe-7s-diskette mr-1"></i> Simpan Hafalan
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <script>
        // Konfigurasi Toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "timeOut": "4000",
        };

        // Fungsi Cegat Hari Sabtu/Minggu
        function changeDate(dateValue) {
            if (!dateValue) return;

            const parts = dateValue.split('-');
            const selectedDate = new Date(parts[0], parts[1] - 1, parts[2]);
            const day = selectedDate.getDay();

            if (day === 0 || day === 6) {
                toastr.warning('Hari Sabtu dan Minggu tidak bisa dipilih untuk penilaian.', 'Peringatan Hari Libur');
                document.getElementById('filter_tanggal').value = "{{ $tanggalPilih ?? $tanggal }}";
                return;
            }

            const url = new URL(window.location.href);
            url.searchParams.set('tanggal', dateValue);
            window.location.href = url.toString();
        }

        // Animasi Loading Tombol Submit
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("createForm");
            const submitBtn = document.getElementById("submitBtn");

            if (form && submitBtn) {
                form.addEventListener("submit", function() {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML =
                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...`;
                });
            }
        });
    </script>
@endsection
