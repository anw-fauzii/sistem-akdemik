@extends('layouts.app2')

@section('title')
    <title>Agenda</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-rocket icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Update Agenda
                    <div class="page-title-subheading">
                        Memperbarui Agenda yang baru
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
            <form  method="post" action="{{route('agenda.update', $agenda->id)}}" id="editForm">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="kegiatan" class="">Nama Kegiatan</label>
                            <input name="kegiatan" id="kegiatan" placeholder="kegiatan" type="text" class="form-control @error('kegiatan') is-invalid @enderror" value="{{ $agenda->kegiatan ?? old('kegiatan') }}">
                            @error('kegiatan')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="tanggal" class="">Tanggal</label>
                            <input name="tanggal" id="tanggal" placeholder="" type="date" class="form-control @error('tanggal') is-invalid @enderror" value="{{ $agenda->tanggal ??  old('tanggal') }}">
                            @error('tanggal')
                                <div class="invalid-feedback" style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="unit" class="">Unit</label>
                            <select name="unit" id="unit"  class="multiselect-dropdown form-control @error('unit') is-invalid @enderror">
                                <option value="" selected disabled>-- Pilih unit --</option>
                                <option value="SD" {{ old('unit') == '1' || $agenda->unit == 'SD' ? 'selected' : '' }}>SD GIS Prima Insani</option>
                                <option value="PG TK" {{ old('unit') == '1' || $agenda->unit == 'PG TK' ? 'selected' : '' }}>PG TK Islam Plus Prima Insani</option>
                            </select>
                            @error('unit')
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
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("editForm");
        const submitBtn = document.getElementById("submitBtn");

        form.addEventListener("submit", function () {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...`;
        });
    });
</script>

@endsection
