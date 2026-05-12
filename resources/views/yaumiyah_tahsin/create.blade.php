@extends('layouts.app2')

@section('title')
    <title>Input Yaumiyah Tahsin</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-notebook icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Input Yaumiyah Tahsin - Tingkat {{ request()->route('tingkat') }}
                        <div class="page-title-subheading">
                            Mencatat penilaian capaian Jilid/Surah dan nilai harian siswa.
                        </div>
                    </div>
                </div>

                <!-- Filter Tanggal Penilaian di Header Kanan -->
                <div class="page-title-actions">
                    <div class="d-inline-block">
                        <label for="filter_tanggal" class="font-weight-bold mr-2 mb-0 align-middle">Tanggal:</label>
                        <input type="date" id="filter_tanggal" class="form-control d-inline-block w-auto"
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
                <!-- Pastikan route 'yaumiyah-tahsin.store' sudah Anda daftarkan di web.php -->
                <form method="post" action="{{ route('yaumiyah-tahsin.store') }}" id="createForm">
                    @csrf

                    <div class="table-responsive">
                        <table class="mb-0 table table-hover table-striped" id="tableYaumiyah">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th>Nama Siswa</th>
                                    <th width="25%">Jilid (Talaqqi)</th>
                                    <th width="25%">Halaman / Surah</th>
                                    <th width="15%" class="text-center">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @forelse ($siswa as $index => $item)
                                    @php
                                        // Cari apakah siswa ini sudah punya nilai di tanggal tersebut
                                        $record = $yaumiyah->get($item->id);
                                        // Cek jenis jilid existing untuk mengatur tampilan dropdown Surah/Halaman
                                        $jenisExist =
                                            $record && $record->daftarJilid ? $record->daftarJilid->jenis : '';
                                    @endphp
                                    <tr>
                                        <td class="text-center align-middle">{{ $no++ }}</td>
                                        <td class="align-middle">
                                            <div class="font-weight-bold text-dark">
                                                {{ $item->anggotaKelas->siswa->nama_lengkap ?? '-' }}</div>
                                            <div class="small text-muted">Kelas
                                                {{ $item->anggotaKelas->kelas->nama_kelas ?? '-' }}</div>

                                            <input type="hidden" name="records[{{ $index }}][anggota_t2q_id]"
                                                value="{{ $item->id }}">
                                            <input type="hidden" name="records[{{ $index }}][tanggal]"
                                                value="{{ $tanggal }}">
                                        </td>

                                        <!-- Select Jilid -->
                                        <td>
                                            <select name="records[{{ $index }}][daftar_jilid_id]"
                                                class="multiselect-dropdown form-control"
                                                onchange="toggleHalamanSurah(this, {{ $index }})">
                                                <option value="" data-jenis="">-- Pilih Jilid --</option>
                                                @foreach ($jilid as $j)
                                                    <!-- Tambahkan logika 'selected' jika ID jilid sama dengan record lama -->
                                                    <option value="{{ $j->id }}" data-jenis="{{ $j->jenis }}"
                                                        {{ $record && $record->daftar_jilid_id == $j->id ? 'selected' : '' }}>
                                                        {{ $j->jilid_latin }} ({{ $j->jilid_arab }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <!-- Select Halaman / Surah -->
                                        <td>
                                            <!-- Tampilkan/Sembunyikan via PHP Style berdasarkan 'jenisExist' -->
                                            <div id="container_angka_{{ $index }}"
                                                style="display: {{ $jenisExist == 'halaman' ? 'block' : 'none' }};">
                                                <select name="records[{{ $index }}][angka_arab_id]"
                                                    id="select_angka_{{ $index }}"
                                                    class="multiselect-dropdown form-control"
                                                    {{ $jenisExist == 'halaman' ? 'required' : '' }}>
                                                    <option value="">-- Pilih Halaman --</option>
                                                    @foreach ($angka as $a)
                                                        <option value="{{ $a->id }}"
                                                            {{ $record && $record->angka_arab_id == $a->id ? 'selected' : '' }}>
                                                            {{ $a->id ?? $a->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Container Surah -->
                                            <div id="container_surah_{{ $index }}"
                                                style="display: {{ $jenisExist == 'quran' ? 'block' : 'none' }};">
                                                <select name="records[{{ $index }}][surah_alquran_id]"
                                                    id="select_surah_{{ $index }}"
                                                    class="multiselect-dropdown form-control"
                                                    {{ $jenisExist == 'quran' ? 'required' : '' }}>
                                                    <option value="">-- Pilih Surah --</option>
                                                    @foreach ($surah as $s)
                                                        <option value="{{ $s->id }}"
                                                            {{ $record && $record->surah_alquran_id == $s->id ? 'selected' : '' }}>
                                                            {{ $s->nama_surah ?? $s->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Teks Default -->
                                            <div id="container_default_{{ $index }}"
                                                class="text-center text-muted mt-2"
                                                style="font-size: 0.8rem; font-style: italic; display: {{ $jenisExist == '' ? 'block' : 'none' }};">
                                                Pilih Jilid dahulu
                                            </div>
                                        </td>

                                        <!-- Input Nilai (Isikan dengan record lama jika ada) -->
                                        <td>
                                            <input name="records[{{ $index }}][nilai]" type="number"
                                                class="form-control text-center" placeholder="0-100" min="0"
                                                max="100" value="{{ $record ? $record->nilai : '' }}"
                                                oninput="if(this.value > 100) this.value = 100; if(this.value < 0) this.value = 0;">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center font-italic text-muted">Belum ada data siswa
                                            di tingkat ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Tampilkan tombol simpan hanya jika ada data siswa -->
                    @if ($siswa->isNotEmpty())
                        <div class="form-group mt-4 text-right">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="pe-7s-diskette mr-1"></i> Simpan Penilaian
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <script>
        // 1. Konfigurasi Toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right", // Muncul di pojok kanan atas
            "preventDuplicates": true,
            "timeOut": "4000", // Hilang otomatis dalam 4 detik
        };

        // 2. FUNGSI CHANGE DATE (Cukup satu saja, jangan ada duplikat!)
        function changeDate(dateValue) {
            if (!dateValue) return;

            // Memecah string 'YYYY-MM-DD' untuk mencegah isu Timezone
            const parts = dateValue.split('-');
            const selectedDate = new Date(parts[0], parts[1] - 1, parts[2]);
            const day = selectedDate.getDay(); // 0 = Minggu, 6 = Sabtu

            // LOGIKA BLOKIR WEEKEND
            if (day === 0 || day === 6) {
                // Memanggil Toastr Warning
                toastr.warning('Hari Sabtu dan Minggu tidak bisa dipilih untuk penilaian.', 'Peringatan Hari Libur');

                // Kembalikan kotak input tanggal ke nilai sebelumnya
                document.getElementById('filter_tanggal').value = "{{ $tanggalPilih ?? $tanggal }}";
                return; // Hentikan proses di sini, jadi halaman tidak akan ter-reload!
            }

            // Jika lolos (Senin - Jumat), lanjutkan proses reload halaman
            const url = new URL(window.location.href);
            url.searchParams.set('tanggal', dateValue);
            window.location.href = url.toString();
        }

        // 3. Script Loading Spinner saat disubmit
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

        // 4. Menampilkan Halaman/Surah berdasarkan Jenis Jilid
        function toggleHalamanSurah(selectElement, index) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const jenis = selectedOption.getAttribute('data-jenis');

            const containerAngka = document.getElementById(`container_angka_${index}`);
            const containerSurah = document.getElementById(`container_surah_${index}`);
            const containerDefault = document.getElementById(`container_default_${index}`);

            const selectAngka = document.getElementById(`select_angka_${index}`);
            const selectSurah = document.getElementById(`select_surah_${index}`);

            // Tampilkan dropdown yang sesuai dengan jenis jilid
            if (jenis === 'halaman') {
                containerAngka.style.display = 'block';
                containerSurah.style.display = 'none';
                containerDefault.style.display = 'none';
                selectSurah.value = ""; // Reset surah
            } else if (jenis === 'quran') {
                containerAngka.style.display = 'none';
                containerSurah.style.display = 'block';
                containerDefault.style.display = 'none';
                selectAngka.value = ""; // Reset angka
            } else {
                containerAngka.style.display = 'none';
                containerSurah.style.display = 'none';
                containerDefault.style.display = 'block';
                selectAngka.value = "";
                selectSurah.value = "";
            }
        }
    </script>
@endsection
