@extends('layouts.app2')

@section('title')
    <title>Pekerjaan</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-plugin icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Update Pekerjaan
                    <div class="page-title-subheading">
                        Update Pekerjaan yang lama
                    </div>
                </div>
            </div>  
        </div> 
    </div>

    <div class="main-card card">
        <div class="card-header">
            Update Data
        </div>
        <div class="card-body">
            <form  method="post" action="{{route('pekerjaan.update', $pekerjaan->id)}}" id="editForm">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="nama_pekerjaan" class="">Pekerjaan</label>
                            <input name="nama_pekerjaan" id="nama_pekerjaan" placeholder="Masukkan Pekerjaan" type="text" class="form-control @error('nama_pekerjaan') is-invalid @enderror" value="{{ $pekerjaan->nama_pekerjaan ?? old('nama_pekerjaan') }}">
                            @error('nama_pekerjaan')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" name="signup" value="Simpan" id="submitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById("editForm").addEventListener("submit", function(event) {
        document.getElementById("submitBtn").disabled = true;
        document.getElementById("submitBtn").innerText = "Menyimpan...";
    });
</script>

@endsection
