@extends('layouts.app2')

@section('title')
    <title>Tambah Administrasi Guru</title>
@endsection

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" type="text/css" />

    <style>
        /* Perbaikan Kotak Utama Dropzone */
        .dropzone-area {
            border: 2px dashed #007bff !important;
            border-radius: 0.5rem;
            background-color: #f8fafc;
            min-height: 180px;
            cursor: pointer;
            transition: all 0.2s ease;
            padding: 20px !important;
        }

        .dropzone-area:hover {
            background-color: #f1f5f9;
            border-color: #0056b3 !important;
        }

        .dropzone .dz-message {
            margin: 1.5em 0;
            font-weight: 500;
            color: #64748b;
            text-align: center;
        }

        /* FIX TAMPILAN PREVIEW BIAR TIDAK BURAM & BERTUMPUK */
        .dropzone .dz-preview {
            margin: 10px !important;
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            padding: 6px !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .dropzone .dz-preview .dz-image {
            border-radius: 6px !important;
            background: #f1f5f9 !important;
        }

        /* Memaksa text ukuran file & nama file agar terlihat jelas */
        .dropzone .dz-preview .dz-details .dz-size span {
            background-color: rgba(15, 23, 42, 0.7) !important;
            border-radius: 4px;
            padding: 2px 6px;
            color: #fff !important;
        }

        .dropzone .dz-preview .dz-details .dz-filename span {
            background-color: transparent !important;
            color: #334155 !important;
            font-size: 0.75rem;
        }

        /* Merapikan Tombol Batal / Remove bawaan */
        .dropzone .dz-preview .dz-remove {
            margin-top: 8px !important;
            color: #dc3545 !important;
            font-size: 12px !important;
            font-weight: bold;
            text-decoration: none !important;
            display: block;
            border: 1px solid #dc3545;
            border-radius: 4px;
            padding: 2px 4px;
            background: #fff;
            transition: all 0.2s;
        }

        .dropzone .dz-preview .dz-remove:hover {
            background: #dc3545;
            color: #fff !important;
        }
    </style>

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-portfolio icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Tambah Administrasi
                        <div class="page-title-subheading">
                            Mengunggah administrasi guru untuk diperiksa pimpinan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="errorAlert" class="alert alert-danger d-none">
            <h6 class="font-weight-bold">Gagal Menyimpan!</h6>
            <ul class="mb-0" id="errorList"></ul>
        </div>

        <div class="main-card card">
            <div class="card-header">
                Tambah Data via Drag & Drop
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('administrasi-guru.store') }}" id="dropzoneForm"
                    class="dropzone p-0 border-0" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="kategori_administrasi_id">Judul Administrasi</label>
                                <select name="kategori_administrasi_id" id="kategori_administrasi_id" class="form-control"
                                    required>
                                    <option value="" selected disabled>-- Pilih Judul Administrasi --</option>
                                    @foreach ($kategori as $item)
                                        <option value="{{ $item->id }}" data-semester="{{ $item->semester }}">
                                            {{ $item->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="position-relative form-group" id="semester-group" style="display: none;">
                                <label for="semester">Semester</label>
                                <select id="semester" name="semester" class="form-control">
                                    <option value="">-- Pilih Semester --</option>
                                    <option value="1">Semester 1</option>
                                    <option value="2">Semester 2</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label>Berkas Administrasi (Maksimal 10MB per file, Format: PDF, DOC, XLS)</label>

                                <div class="dropzone-area" id="fileUploadDropzone">
                                    <div class="dz-message" data-dz-message>
                                        <i class="pe-7s-cloud-upload text-primary mb-2"
                                            style="font-size: 2.5rem; display: block;"></i>
                                        <span class="text-lg block font-weight-bold">Tarik & Lepaskan berkas-berkas di
                                            sini</span>
                                        <span class="text-xs text-muted block">atau klik untuk memilih file dari
                                            komputer</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="form-group mb-0 text-right">
                        <a href="{{ route('administrasi-guru.index') }}" class="btn btn-secondary mr-2">Batal</a>
                        <button type="button" class="btn btn-primary font-weight-bold" id="submitBtn" disabled>
                            <i class="pe-7s-cloud-upload mr-1"></i> Mulai Unggah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>

    <script>
        Dropzone.autoDiscover = false;

        $(document).ready(function() {
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

            var myDropzone = new Dropzone("#fileUploadDropzone", {
                url: "{{ route('administrasi-guru.store') }}",
                paramName: "file", // Diubah menjadi tunggal 'file'
                autoProcessQueue: false,
                uploadMultiple: false,
                parallelUploads: 2,
                maxFilesize: 10,
                acceptedFiles: ".pdf,.doc,.docx,.xls,.xlsx",
                addRemoveLinks: true,
                dictRemoveFile: "Batal",
                dictFileTooBig: "File terlalu besar (@{{ filesize }}MB). Maksimal 10MB.",

                sending: function(file, xhr, formData) {
                    formData.append("_token", "{{ csrf_token() }}");
                    formData.append("kategori_administrasi_id", $('#kategori_administrasi_id').val());
                    if ($('#semester').val()) {
                        formData.append("semester", $('#semester').val());
                    }
                },

                init: function() {
                    var submitButton = document.getElementById("submitBtn");
                    var wrapper = this;

                    wrapper.on("addedfile", function() {
                        submitButton.disabled = false;
                    });

                    wrapper.on("removedfile", function() {
                        if (wrapper.files.length === 0) {
                            submitButton.disabled = true;
                        }
                    });

                    submitButton.addEventListener("click", function(e) {
                        e.preventDefault();

                        if (!$('#kategori_administrasi_id').val()) {
                            alert('Silakan pilih Judul Administrasi terlebih dahulu!');
                            return;
                        }
                        if ($('#semester-group').is(':visible') && !$('#semester').val()) {
                            alert('Silakan pilih Semester terlebih dahulu!');
                            return;
                        }

                        submitButton.disabled = true;
                        submitButton.innerHTML =
                            `<span class="spinner-border spinner-border-sm" role="status"></span> Mengunggah...`;

                        wrapper.processQueue();
                    });

                    wrapper.on("success", function(file, response) {
                        if (wrapper.getQueuedFiles().length > 0) {
                            wrapper.processQueue();
                        } else {
                            window.location.href = "{{ route('administrasi-guru.index') }}";
                        }
                    });

                    wrapper.on("error", function(file, response) {
                        submitButton.disabled = false;
                        submitButton.innerHTML =
                            `<i class="pe-7s-cloud-upload mr-1"></i> Mulai Unggah`;

                        $('#errorAlert').removeClass('d-none');
                        let errorMsg = typeof response === 'object' ? response.message :
                            response;
                        $('#errorList').html(`<li>${errorMsg}</li>`);
                    });
                }
            });
        });
    </script>
@endsection
