@extends('layouts.app2')

@section('title')
    <title>Barang</title>
@endsection

@section('content')
    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">

                    <div class="page-title-icon">
                        <i class="pe-7s-box2 icon-gradient bg-mean-fruit"></i>
                    </div>

                    <div>
                        Edit Barang
                        <div class="page-title-subheading">
                            Mengubah data barang
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="main-card card">
            <div class="card-header">
                Edit Data
            </div>

            <div class="card-body">
                <form method="post" action="{{ route('barang.update', $barang->id) }}" id="editForm">
                    @csrf
                    @method('PUT')

                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="id">
                                    ID Barang
                                </label>
                                <input name="id"
                                    id="id"
                                    placeholder="Contoh: ELK-001"
                                    type="text"
                                    class="form-control @error('id') is-invalid @enderror"
                                    value="{{ old('id', $barang->id) }}"
                                    readonly>
                                @error('id')
                                    <div class="invalid-feedback"
                                        style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="position-relative form-group">
                                <label for="nama_barang">
                                    Nama Barang
                                </label>
                                <input name="nama_barang"
                                    id="nama_barang"
                                    placeholder="Contoh: Laptop"
                                    type="text"
                                    class="form-control @error('nama_barang') is-invalid @enderror"
                                    value="{{ old('nama_barang', $barang->nama_barang) }}">
                                @error('nama_barang')
                                    <div class="invalid-feedback"
                                        style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="kategori_barang_id">
                                    Kategori Barang
                                </label>
                                <select name="kategori_barang_id"
                                    id="kategori_barang_id"
                                    class="form-control @error('kategori_barang_id') is-invalid @enderror multiselect-dropdown">
                                    <option value="">
                                        -- Pilih Kategori --
                                    </option>

                                    @foreach ($kategori as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('kategori_barang_id', $barang->kategori_barang_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama_kategori_barang }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('kategori_barang_id')
                                    <div class="invalid-feedback"
                                        style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="lokasi_barang_id">
                                    Lokasi Barang
                                </label>
                                <select name="lokasi_barang_id"
                                    id="lokasi_barang_id"
                                    class="form-control @error('lokasi_barang_id') is-invalid @enderror multiselect-dropdown">
                                    <option value="">
                                        -- Pilih Lokasi --
                                    </option>

                                    @foreach ($lokasi as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('lokasi_barang_id', $barang->lokasi_barang_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama_lokasi_barang }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('lokasi_barang_id')
                                    <div class="invalid-feedback"
                                        style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="merk">
                                    Merk
                                </label>
                                <input name="merk"
                                    id="merk"
                                    placeholder="Contoh: Lenovo"
                                    type="text"
                                    class="form-control @error('merk') is-invalid @enderror"
                                    value="{{ old('merk', $barang->merk) }}">
                                @error('merk')
                                    <div class="invalid-feedback"
                                        style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="tipe">
                                    Tipe
                                </label>
                                <input name="tipe"
                                    id="tipe"
                                    placeholder="Contoh: ThinkPad E14"
                                    type="text"
                                    class="form-control @error('tipe') is-invalid @enderror"
                                    value="{{ old('tipe', $barang->tipe) }}">
                                @error('tipe')
                                    <div class="invalid-feedback"
                                        style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="nomor_seri">
                                    Nomor Seri
                                </label>
                                <input name="nomor_seri"
                                    id="nomor_seri"
                                    placeholder="Contoh: PF123456"
                                    type="text"
                                    class="form-control @error('nomor_seri') is-invalid @enderror"
                                    value="{{ old('nomor_seri', $barang->nomor_seri) }}">
                                @error('nomor_seri')
                                    <div class="invalid-feedback"
                                        style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="tahun_perolehan">
                                    Tahun Perolehan
                                </label>
                                <input name="tahun_perolehan"
                                    id="tahun_perolehan"
                                    placeholder="Contoh: 2026"
                                    type="number"
                                    min="1900"
                                    max="{{ date('Y') }}"
                                    class="form-control @error('tahun_perolehan') is-invalid @enderror"
                                    value="{{ old('tahun_perolehan', $barang->tahun_perolehan) }}">
                                @error('tahun_perolehan')
                                    <div class="invalid-feedback"
                                        style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="kondisi">
                                    Kondisi
                                </label>
                                <select name="kondisi"
                                    id="kondisi"
                                    class="form-control @error('kondisi') is-invalid @enderror">

                                    <option value="">
                                        -- Pilih Kondisi --
                                    </option>

                                    <option value="baik"
                                        {{ old('kondisi', $barang->kondisi) == 'baik' ? 'selected' : '' }}>
                                        Baik
                                    </option>

                                    <option value="rusak_ringan"
                                        {{ old('kondisi', $barang->kondisi) == 'rusak_ringan' ? 'selected' : '' }}>
                                        Rusak Ringan
                                    </option>

                                    <option value="rusak_berat"
                                        {{ old('kondisi', $barang->kondisi) == 'rusak_berat' ? 'selected' : '' }}>
                                        Rusak Berat
                                    </option>

                                </select>

                                @error('kondisi')
                                    <div class="invalid-feedback"
                                        style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="status">
                                    Status
                                </label>
                                <select name="status"
                                    id="status"
                                    class="form-control @error('status') is-invalid @enderror">

                                    <option value="">
                                        -- Pilih Status --
                                    </option>

                                    <option value="aktif"
                                        {{ old('status', $barang->status) == 'aktif' ? 'selected' : '' }}>
                                        Aktif
                                    </option>

                                    <option value="dipinjam"
                                        {{ old('status', $barang->status) == 'dipinjam' ? 'selected' : '' }}>
                                        Dipinjam
                                    </option>

                                    <option value="hilang"
                                        {{ old('status', $barang->status) == 'hilang' ? 'selected' : '' }}>
                                        Hilang
                                    </option>

                                    <option value="dihapus"
                                        {{ old('status', $barang->status) == 'dihapus' ? 'selected' : '' }}>
                                        Dihapus
                                    </option>

                                </select>

                                @error('status')
                                    <div class="invalid-feedback"
                                        style="font-style: italic; font-size: 0.7rem;">
                                        {{ strtolower($message) }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-12">
                            <label for="keterangan">
                                Keterangan
                            </label>

                            <textarea name="keterangan"
                                id="keterangan"
                                rows="4"
                                placeholder="Tambahkan keterangan jika diperlukan..."
                                class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $barang->keterangan) }}</textarea>

                            @error('keterangan')
                                <div class="invalid-feedback"
                                    style="font-style: italic; font-size: 0.7rem;">
                                    {{ strtolower($message) }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit"
                            class="btn btn-primary"
                            value="Simpan"
                            id="submitBtn">
                            Simpan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("editForm");
            const submitBtn = document.getElementById("submitBtn");

            form.addEventListener("submit", function() {
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    `<span class="spinner-border spinner-border-sm"
                        role="status"
                        aria-hidden="true"></span> Menyimpan...`;
            });
        });
    </script>

@endsection