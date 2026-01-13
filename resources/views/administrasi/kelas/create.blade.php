@extends('layouts.app2')

@section('title')
<title>Kelas</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-portfolio icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Tambah Administrasi 
                    <div class="page-title-subheading">
                        Membuat administrasi kelas untuk diperikasa pimpinan
                    </div>
                </div>
            </div>  
        </div> 
    </div>

    <div class="main-card card">
        <div class="card-header">
            Tambah Data
        </div>
        <div class="card-body">
            <form method="POST" 
                  action="{{ route('administrasi-kelas.store') }}" 
                  id="createForm" 
                  enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="judul">Judul Administrasi</label>
                            <select name="judul" id="judul"
                                class="form-control @error('judul') is-invalid @enderror">
                                <option value="">-- Pilih Judul Administrasi --</option>
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->id }}" data-semester="{{ $item->semester }}">
                                        {{ $item->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>

                            @error('judul')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
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
                            <label>Berkas</label>

                            <div id="file-wrapper">
                                <div class="d-flex mb-2 file-row">
                                    <input name="link[]" type="file" class="form-control" />
                                    <button type="button" class="btn btn-danger btn-sm ml-2 remove-file">
                                        Hapus
                                    </button>
                                </div>
                            </div>

                            <button type="button" class="btn btn-success btn-sm mt-2" id="add-file">
                                + Tambah Berkas
                            </button>
                        </div>

                    </div>
                </div>

                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        Simpan
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
        background: rgba(2, 0, 15, 0.8);
        z-index: 9999;
        display: flex;
        align-items: center; 
        justify-content: center; 
    }

    .loader-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
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
        margin-top: 12px;
        font-size: 1rem;
        color: #ffffff;
        font-weight: 600;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#judul').on('change', function() {
        const semesterFlag = $(this).find(':selected').data('semester');

        if (semesterFlag == 1) {
            $('#semester-group').show();
        } else {
            $('#semester-group').hide();
            $('#semester').val('');
        }
    });
</script>
<script>
    $(document).ready(function () {

        // Tambah input file
        $('#add-file').click(function () {
            $('#file-wrapper').append(`
                <div class="d-flex mb-2 file-row">
                    <input name="link[]" type="file" class="form-control" />
                    <button type="button" class="btn btn-danger btn-sm ml-2 remove-file">
                        Hapus
                    </button>
                </div>
            `);
        });

        // Hapus salah satu input
        $(document).on('click', '.remove-file', function () {
            $(this).closest('.file-row').remove();
        });

    });
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("createForm");
        const submitBtn = document.getElementById("submitBtn");
        const overlay = document.getElementById("loadingOverlay");

        form.addEventListener("submit", function () {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...`;
            overlay.classList.remove("d-none");
        });
        window.addEventListener('pageshow', function() {
            overlay.classList.add("d-none");
            submitBtn.disabled = false;
            submitBtn.innerHTML = "Simpan";
        });
    });
</script>
@endsection
