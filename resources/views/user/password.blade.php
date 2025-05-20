@extends('layouts.app2')

@section('title')
    <title>Update Password</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-user icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Update Password
                    <div class="page-title-subheading">
                       Memperbarui password untuk menjaga keamanan data
                    </div>
                </div>
            </div>  
        </div> 
    </div>

    <div class="main-card card">
        <div class="card-header">
            Update Password
        </div>
        <div class="card-body">
            <form action="{{ route('update-password.update') }}" method="post" enctype="multipart/form-data"> 
                @csrf
                @method('put')
                <div class="position-relative row form-group"><label class="col-sm-3 col-form-label" for="nama">Password Lama</label>
                    <div class="col-sm-9">
                        <input placeholder="Masukan Password Lama" type="password" name="old_password" class="form-control @error('old_password') is-invalid @enderror">
                        @error('old_password')
                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                {{ strtolower($message) }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="position-relative row form-group"><label class="col-sm-3 col-form-label" for="nama">Password Baru</label>
                    <div class="col-sm-9">
                        <input placeholder="Masukan Password Baru" type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
                        @error('new_password')
                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                {{ strtolower($message) }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="position-relative row form-group"><label class="col-sm-3 col-form-label" for="nama">Konfirmasi Password Baru</label>
                    <div class="col-sm-9">
                        <input placeholder="Masukan Konfirmasi Password Baru" type="password" name="confirm_password" class="form-control @error('confirm_password') is-invalid @enderror">
                        @error('confirm_password')
                            <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                {{ strtolower($message) }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="text-center">
                    <button class="btn btn-primary btn-sm">
                        <i class="pe-7s-paper-plane"></i> Simpan
                    </button>
                </div> 
            </form>
        </div>
    </div>
</div>
@endsection
