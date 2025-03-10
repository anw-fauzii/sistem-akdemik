@extends('layouts.app2')

@section('title')
    <title>Presensi</title>
@endsection

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-smile icon-gradient bg-mean-fruit"></i>
                </div>
                <div>Presensi
                    <div class="page-title-subheading">
                        Merupakan Presensi yang Berada di sekolah
                    </div>
                </div>
            </div>  
        </div> 
    </div>

    <div class="main-card card">
        <div class="card-header">
            Tambah Presensi
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form action="{{ route('presensi.store') }}" method="POST">
                    @csrf
            
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                    </div>
            
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswaList as $siswa)
                                <tr>
                                    <td>{{ $siswa->siswa->nama_lengkap }}</td>
                                    <td>
                                        <select name="presensi[{{ $siswa->id }}]" class="form-control">
                                            <option value="Hadir">Hadir</option>
                                            <option value="Izin">Izin</option>
                                            <option value="Sakit">Sakit</option>
                                            <option value="Alpha">Alpha</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            
                    <button type="submit" class="btn btn-primary">Simpan Presensi</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
