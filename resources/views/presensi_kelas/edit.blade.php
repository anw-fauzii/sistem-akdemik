@extends('layouts.app2')

@section('title')
    <title>Presensi</title>
@endsection

@section('content')
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-smile icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Perbarui Presensi
                        <div class="page-title-subheading">
                            Merupakan Presensi yang Berada di sekolah
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card card">
            <div class="card-header">
                Perbarui Presensi
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <form id="formSimpan" action="{{ route('presensi-kelas.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal"
                                value="{{ \Carbon\Carbon::parse($tanggal)->format('Y-m-d') }}" readonly>
                        </div>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Status</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($siswaList as $siswa)
                                    <tr>
                                        <td>{{ $siswa->siswa->nama_lengkap }}</td>
                                        @php
                                            $presensiData = $presensi
                                                ->where('anggota_kelas_id', $siswa->id)
                                                ->first(function ($item) use ($tanggal) {
                                                    return \Carbon\Carbon::parse($item->tanggal)->toDateString() ===
                                                        $tanggal;
                                                });
                                        @endphp
                                        @if ($presensiData)
                                            <td>
                                                <select name="presensi[{{ $siswa->id }}]" class="form-control">
                                                    <option value="" disabled selected>-- Pilih Keterangan --</option>
                                                    <option value="Hadir"
                                                        {{ $presensiData->status == 'hadir' ? 'selected' : '' }}>Hadir
                                                    </option>
                                                    <option value="Izin"
                                                        {{ $presensiData->status == 'izin' ? 'selected' : '' }}>Izin
                                                    </option>
                                                    <option value="Sakit"
                                                        {{ $presensiData->status == 'sakit' ? 'selected' : '' }}>Sakit
                                                    </option>
                                                    <option value="Alpha"
                                                        {{ $presensiData->status == 'alpha' ? 'selected' : '' }}>Alpha
                                                    </option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control waktu"
                                                    value="{{ \Carbon\Carbon::parse($presensiData->tanggal)->format('H:i') }}"
                                                    name="waktu[{{ $siswa->id }}]">
                                            </td>
                                        @else
                                            <td>
                                                <select name="presensi[{{ $siswa->id }}]" class="form-control">
                                                    <option value="" disabled selected>-- Pilih Keterangan --</option>
                                                    <option value="Hadir">Hadir</option>
                                                    <option value="Izin">Izin</option>
                                                    <option value="Sakit">Sakit</option>
                                                    <option value="Alpha">Alpha</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control waktu" value="7:15"
                                                    name="waktu[{{ $siswa->id }}]">
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </form>
                    <div class="d-flex justify-content-between mt-4">

                        <button type="submit" form="formSimpan" class="btn btn-primary font-weight-bold">
                            <i class="pe-7s-diskette"></i> Simpan Presensi
                        </button>

                        <form action="{{ route('presensi-kelas.destroy-massal') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                            @foreach ($siswaList as $siswa)
                                <input type="hidden" name="siswa_ids[]" value="{{ $siswa->id }}">
                            @endforeach

                            <button type="submit" class=" delete-button btn btn-danger font-weight-bold">
                                <i class="pe-7s-trash"></i> Kosongkan Presensi Hari Ini
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        flatpickr(".waktu", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
        });
    </script>
    <script>
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah yakin akan dihapus?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn-swal-confirm',
                        cancelButton: 'btn-swal-cancel'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.closest('form').submit();
                    }
                });
            });
        });
    </script>
@endsection
