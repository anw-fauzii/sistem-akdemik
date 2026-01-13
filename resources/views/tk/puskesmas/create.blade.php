@extends('layouts.app2')

@section('title')
    <title>Data Kesehatan</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-smile icon-gradient bg-mean-fruit"></i>
                </div>
                <div>{{ $semuaKosong ? 'Tambah Data' : 'Ubah Data' }} Kelas <strong>{{$kelas->nama_kelas}}</strong>, Bulan {{$bulanTerbaru->nama_bulan}}
                    <div class="page-title-subheading">
                        Merupakan tambah kesehatan siswa
                    </div>
                </div>
            </div>  
        </div> 
    </div>
    <div class="main-card card">
        <div class="card-header">
            {{ $semuaKosong ? 'Tambah Data' : 'Ubah Data' }}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form action="{{ route('data-kesehatan.store') }}" method="POST">
                @csrf
                <input type="hidden" name="bulan_spp_id" value="{{ $bulanTerbaru->id }}">

                <table class="mb-0 table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Fisik</th>
                            <th>Pemeriksaan Lain</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($anggotaKelasList as $anggota)
                        <tr>
                            <td>
                                {{ $anggota->siswa->nama_lengkap }}
                                <input type="hidden" name="anggota_kelas_id[]" value="{{ $anggota->id }}">
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <label class="mb-0" style="width: 50px;">TB:</label>
                                        <input type="text" class="form-control w-auto" name="tb[{{ $anggota->id }}]" placeholder="0 cm" value="{{ old('tb.'.$anggota->id, $anggota->dataKesehatan->tb ?? '') }}">
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <label class="mb-0" style="width: 50px;">BB:</label>
                                        <input type="text" class="form-control w-auto" name="bb[{{ $anggota->id }}]" placeholder="0 kg" value="{{ old('bb.'.$anggota->id, $anggota->dataKesehatan->bb ?? '') }}">
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <label class="mb-0" style="width: 50px;">LILA:</label>
                                        <input type="text" class="form-control w-auto" name="lila[{{ $anggota->id }}]" placeholder="0 cm" value="{{ old('lila.'.$anggota->id, $anggota->dataKesehatan->lila ?? '') }}">
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <label class="mb-0" style="width: 50px;">LIKA:</label>
                                        <input type="text" class="form-control w-auto" name="lika[{{ $anggota->id }}]" placeholder="0 cm" value="{{ old('lika.'.$anggota->id, $anggota->dataKesehatan->lika ?? '') }}">
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <label class="mb-0" style="width: 50px;">LP:</label>
                                        <input type="text" class="form-control w-auto" name="lp[{{ $anggota->id }}]" placeholder="0 cm" value="{{ old('lp.'.$anggota->id, $anggota->dataKesehatan->lp ?? '') }}">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <label class="mb-0" style="width: 70px;">Mata:</label>
                                        <input type="text" class="form-control w-auto" name="mata[{{ $anggota->id }}]" placeholder="-" value="{{ old('mata.'.$anggota->id, $anggota->dataKesehatan->mata ?? '') }}">
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <label class="mb-0" style="width: 70px;">Telinga:</label>
                                        <input type="text" class="form-control w-auto" name="telinga[{{ $anggota->id }}]" placeholder="-" value="{{ old('telinga.'.$anggota->id, $anggota->dataKesehatan->telinga ?? '') }}">
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <label class="mb-0" style="width: 70px;">Gigi:</label>
                                        <input type="text" class="form-control w-auto" name="gigi[{{ $anggota->id }}]" placeholder="-" value="{{ old('gigi.'.$anggota->id, $anggota->dataKesehatan->gigi ?? '') }}">
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <label class="mb-0" style="width: 70px;">Hasil:</label>
                                        <input type="text" class="form-control w-auto" name="hasil[{{ $anggota->id }}]" placeholder="-" value="{{ old('hasil.'.$anggota->id, $anggota->dataKesehatan->hasil ?? '') }}">
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <label class="mb-0" style="width: 70px;">Tensi:</label>
                                        <input type="text" class="form-control w-auto" name="tensi[{{ $anggota->id }}]" placeholder="-" value="{{ old('tensi.'.$anggota->id, $anggota->dataKesehatan->tensi ?? '') }}">
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <br>
                <button type="submit" class="btn btn-sm btn-primary text-center">Simpan Data Kesehatan</button>
            </form>

            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> 
@endsection
