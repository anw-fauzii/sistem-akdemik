@extends('layouts.app2')

@section('title')
    <title>Berkebutuhan Khusus</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-plugin icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Tambah Kebutuhan Khusus
                    <div class="page-title-subheading">
                        Membuat kebutuhan khusus yang baru
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
            <form  method="post" action="{{route('kategori-kebutuhan.store')}}" id="createForm">
                @csrf
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="nama_berkebutuhan_khusus" class="">Kebutuhan Khusus</label>
                            <input name="nama_berkebutuhan_khusus" id="nama_berkebutuhan_khusus" placeholder="Masukkan Kebutuhan Khusus" type="text" class="form-control @error('nama_berkebutuhan_khusus') is-invalid @enderror" value="{{ old('nama_berkebutuhan_khusus') }}">
                            @error('nama_berkebutuhan_khusus')
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
