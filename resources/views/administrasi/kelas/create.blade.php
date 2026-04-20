@extends('layouts.app2')

@section('title')
    <title>Tambah Administrasi Kelas</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-portfolio icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Tambah Administrasi Kelas
                        <div class="page-title-subheading">
                            Mengunggah administrasi kelas untuk diperiksa pimpinan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="loadingOverlay" class="loader-overlay d-none">
            <div class="loader-content">
                <div class="loader"></div>
                <div class="loader-text">Sedang mengunggah file ke Google Drive...<br><small>Mohon jangan tutup halaman
                        ini.</small></div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger shadow-sm">
                <h6 class="font-weight-bold mb-2"><i class="pe-7s-attention mr-1"></i> Gagal Menyimpan!</h6>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="main-card card">
            <div class="card-header">
                Tambah Data Administrasi
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('administrasi-kelas.store') }}" id="createForm"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="kategori_administrasi_id" class="font-weight-bold">Judul Administrasi</label>
                                <select name="kategori_administrasi_id" id="kategori_administrasi_id"
                                    class="form-control @error('kategori_administrasi_id') is-invalid @enderror" required>
                                    <option value="" selected disabled>-- Pilih Judul Administrasi --</option>
                                    @foreach ($kategori as $item)
                                        <option value="{{ $item->id }}" data-semester="{{ $item->semester }}"
                                            {{ old('kategori_administrasi_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('kategori_administrasi_id')
                                    <div class="invalid-feedback" style="font-style: italic; font-size: 0.8rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="position-relative form-group" id="semester-group" style="display: none;">
                                <label for="semester" class="font-weight-bold">Semester</label>
                                <select id="semester" name="semester" class="form-control">
                                    <option value="">-- Pilih Semester --</option>
                                    <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Semester 1
                                    </option>
                                    <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Semester 2
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <div class="position-relative form-group">
                                <label class="font-weight-bold">Berkas <span class="text-muted font-weight-normal">(Maksimal
                                        10MB per file. Format: PDF, DOC, XLS)</span></label>

                                <div id="file-wrapper">
                                    <div class="d-flex mb-2 file-row">
                                        <input name="files[]" type="file" class="form-control" required
                                            accept=".pdf,.doc,.docx,.xls,.xlsx" />
                                        <button type="button" class="btn btn-danger btn-sm ml-2 remove-file" disabled
                                            title="File pertama tidak bisa dihapus">
                                            <i class="pe-7s-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-outline-success btn-sm mt-2 font-weight-bold"
                                    id="add-file">
                                    <i class="pe-7s-plus font-weight-bold"></i> Tambah Berkas Lainnya
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr class="mt-4">
                    <div class="form-group mb-0 text-right">
                        <a href="{{ route('administrasi-kelas.index') }}" class="btn btn-secondary mr-2">Batal</a>
                        <button type="submit" class="btn btn-primary font-weight-bold" id="submitBtn">
                            <i class="pe-7s-cloud-upload mr-1 font-weight-bold"></i> Mulai Unggah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(2, 0, 15, 0.9);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loader-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .loader {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #007bff;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 0.8s linear infinite;
        }

        .loader-text {
            margin-top: 15px;
            font-size: 1.1rem;
            color: #ffffff;
            font-weight: 600;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Trigger check semester saat awal load (berguna jika validasi gagal dan halaman reload)
            function checkSemester() {
                const semesterFlag = $('#kategori_administrasi_id').find(':selected').data('semester');
                if (semesterFlag == 1) {
                    $('#semester-group').fadeIn();
                } else {
                    $('#semester-group').fadeOut();
                    $('#semester').val('');
                }
            }

            $('#kategori_administrasi_id').on('change', checkSemester);
            checkSemester(); // Jalankan sekali

            // Tambah input file dinamis
            $('#add-file').click(function() {
                $('#file-wrapper').append(`
                <div class="d-flex mb-2 file-row">
                    <input name="files[]" type="file" class="form-control" required accept=".pdf,.doc,.docx,.xls,.xlsx" />
                    <button type="button" class="btn btn-danger btn-sm ml-2 remove-file" title="Hapus baris ini">
                        <i class="pe-7s-trash"></i>
                    </button>
                </div>
            `);
            });

            // Hapus input file dinamis
            $(document).on('click', '.remove-file', function() {
                // Cegah penghapusan jika hanya ada 1 input file
                if ($('.file-row').length > 1) {
                    $(this).closest('.file-row').remove();
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("createForm");
            const submitBtn = document.getElementById("submitBtn");
            const overlay = document.getElementById("loadingOverlay");

            form.addEventListener("submit", function() {
                // Validasi manual HTML5 fallback
                const files = document.querySelectorAll('input[type="file"]');
                let hasFile = false;
                files.forEach(input => {
                    if (input.files.length > 0) hasFile = true;
                });

                if (hasFile) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML =
                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengunggah...`;
                    overlay.classList.remove("d-none");
                }
            });

            // Hentikan loading jika user menekan tombol 'Back' di browser
            window.addEventListener('pageshow', function() {
                overlay.classList.add("d-none");
                submitBtn.disabled = false;
                submitBtn.innerHTML =
                    `<i class="pe-7s-cloud-upload mr-1 font-weight-bold"></i> Mulai Unggah`;
            });
        });
    </script>
@endsection
