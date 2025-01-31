@extends('layouts.app2')

@section('title')
    <title>Tahun Ajaran</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-plugin icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Tambah Tahun Ajaran
                    <div class="page-title-subheading">
                        Membuat tahun ajaran yang baru
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
            <form  method="post" action="{{route('tahun-ajaran.store')}}" id="createForm">
                @csrf
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="nama_tahun_ajaran" class="">Tahun Ajaran</label>
                            <input name="nama_tahun_ajaran" id="nama_tahun_ajaran" placeholder="Masukkan tahun ajaran" type="text" class="form-control @error('nama_tahun_ajaran') is-invalid @enderror" value="{{ old('nama_tahun_ajaran') }}">
                            @error('nama_tahun_ajaran')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="semester">Semester</label>
                            <select name="semester" id="semester" class="form-control @error('semester') is-invalid @enderror">
                                <option value="" selected disabled>-- Pilih Semester --</option>
                                <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>1 (Ganjil)</option>
                                <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>2 (Genap)</option>
                            </select>
                            @error('semester')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>                        
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary"value="Simpan" id="submitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById("kebutuhanForm").addEventListener("submit", function(event) {
        document.getElementById("submitBtn").disabled = true;
        document.getElementById("submitBtn").innerText = "Menyimpan...";
    });
</script>

@endsection
