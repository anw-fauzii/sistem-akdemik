<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
            <div class="header__pane ml-auto">
                <div>
                    <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>    
    <div class="scrollbar-sidebar" style="overflow-y:scroll;">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">Menu Utama</li>
                <li>
                    <a href="{{route('dashboard.index')}}" class="{{(request()->is('dashboard')) ? 'mm-active' : ''}}">
                        <i class="metismenu-icon pe-7s-home"></i>
                            Beranda
                    </a>
                </li>
                @role('admin')
                    <li>
                        <a href="#" class="{{(request()->is('kategori-kebutuhan*','pekerjaan*','penghasilan*')) ? 'mm-active' : ''}}">
                            <i class="metismenu-icon pe-7s-plugin"></i>
                            Data Pelengkap
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul class="{{(request()->is('kategori-kebutuhan*','pekerjaan*','penghasilan*','transportasi*','jenjang-pendidikan*')) ? 'mm-show' : ''}}">
                            <li>
                                <a href="{{route('kategori-kebutuhan.index')}}" class="{{(request()->is('kategori-kebutuhan*')) ? 'mm-active' : ''}}">
                                    Berkebutuhan Khusus
                                </a>
                            </li>
                            <li>
                                <a href="{{route('pekerjaan.index')}}" class="{{(request()->is('pekerjaan*')) ? 'mm-active' : ''}}">
                                    Pekerjaan
                                </a>
                            </li>
                            <li>
                                <a href="{{route('penghasilan.index')}}" class="{{(request()->is('penghasilan*')) ? 'mm-active' : ''}}">
                                    Penghasilan
                                </a>
                            </li>
                            <li>
                                <a href="{{route('transportasi.index')}}" class="{{(request()->is('transportasi*')) ? 'mm-active' : ''}}">
                                    Transportasi
                                </a>
                            </li>
                            <li>
                                <a href="{{route('jenjang-pendidikan.index')}}" class="{{(request()->is('jenjang-pendidikan*')) ? 'mm-active' : ''}}">
                                    Jenjang Pendidikan
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="{{(request()->is('agenda*','pengumuman*')) ? 'mm-active' : ''}}">
                            <i class="metismenu-icon pe-7s-info"></i>
                            Informasi
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul class="{{(request()->is('agenda*','pengumuman*')) ? 'mm-show' : ''}}">
                            <li>
                                <a href="{{route('agenda.index')}}" class="{{(request()->is('agenda*')) ? 'mm-active' : ''}}">
                                    Agenda
                                </a>
                            </li>
                            
                            <li>
                                <a href="{{route('pengumuman.index')}}" class="{{(request()->is('pengumuman*')) ? 'mm-active' : ''}}">
                                    Pengumuman
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="{{(request()->is('tahun-ajaran*','guru*','siswa*','kelas*','ekstrakurikuler*','anggota-t2q*','jemputan*')) ? 'mm-active' : ''}}">
                            <i class="metismenu-icon pe-7s-rocket"></i>
                            Data Master
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul class="{{(request()->is('tahun-ajaran*','guru*','siswa*','kelas*','ekstrakurikuler*','anggota-t2q*','jemputan*')) ? 'mm-show' : ''}}">
                            <li>
                                <a href="{{route('tahun-ajaran.index')}}" class="{{(request()->is('tahun-ajaran*')) ? 'mm-active' : ''}}">
                                    Tahun Ajaran
                                </a>
                            </li>
                            <li>
                                <a href="{{route('kelas.index')}}" class="{{(request()->is('kelas*')) ? 'mm-active' : ''}}">
                                    Kelas
                                </a>
                            </li>
                            <li>
                                <a href="{{route('guru.index')}}" class="{{(request()->is('guru*')) ? 'mm-active' : ''}}">
                                    Guru
                                </a>
                            </li>
                            <li>
                                <a href="{{route('siswa.index')}}" class="{{(request()->is('siswa*')) ? 'mm-active' : ''}}">
                                    Siswa
                                </a>
                            </li>
                            <li>
                                <a href="{{route('ekstrakurikuler.index')}}" class="{{(request()->is('ekstrakurikuler*')) ? 'mm-active' : ''}}">
                                    Ekstrakurikuler
                                </a>
                            </li>
                            <li>
                                <a href="{{route('anggota-t2q.index')}}" class="{{(request()->is('anggota-t2q*')) ? 'mm-active' : ''}}">
                                    Kelempok T2Q
                                </a>
                            </li>
                            <li>
                                <a href="{{route('jemputan.index')}}" class="{{(request()->is('jemputan*')) ? 'mm-active' : ''}}">
                                    Jemputan
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="app-sidebar__heading">Keuangan</li>
                    <li>
                        <a href="#" class="{{(request()->is('tarif-spp*', 'bulan-spp*','tagihan-tahunan*')) ? 'mm-active' : ''}}">
                            <i class="metismenu-icon pe-7s-cash"></i>
                            Data Keuangan
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul class="{{(request()->is('tarif-spp*', 'bulan-spp*','tagihan-tahunan*')) ? 'mm-show' : ''}}">
                            <li>
                                <a href="{{route('bulan-spp.index')}}" class="{{(request()->is('bulan-spp*')) ? 'mm-active' : ''}}">
                                    Bulan SPP
                                </a>
                            </li>
                            <li>
                                <a href="{{route('tarif-spp.index')}}" class="{{(request()->is('tarif-spp*')) ? 'mm-active' : ''}}">
                                    Tarif SPP
                                </a>
                            </li>
                            <li>
                                <a href="{{route('tagihan-tahunan.index')}}" class="{{(request()->is('tagihan-tahunan*')) ? 'mm-active' : ''}}">
                                    Biaya Tahunan
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="{{(request()->is('pembayaran-spp*','pembayaran-tagihan-tahunan*','pembayaran-jemputan*')) ? 'mm-active' : ''}}">
                            <i class="metismenu-icon pe-7s-safe"></i>
                            Pembayaran
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul class="{{(request()->is('pembayaran-spp*','pembayaran-tagihan-tahunan*','pembayaran-jemputan*')) ? 'mm-show' : ''}}">
                            <li>
                                <a href="{{route('pembayaran-spp.index')}}" class="{{(request()->is('pembayaran-spp*')) ? 'mm-active' : ''}}">
                                    SPP
                                </a>
                            </li>
                            <li>
                                <a href="{{route('pembayaran-tagihan-tahunan.index')}}" class="{{(request()->is('pembayaran-tagihan-tahunan*')) ? 'mm-active' : ''}}">
                                    Tahunan
                                </a>
                            </li>
                            <li>
                                <a href="{{route('pembayaran-jemputan.index')}}" class="{{(request()->is('pembayaran-jemputan*')) ? 'mm-active' : ''}}">
                                    Jemputan
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="{{(request()->is('laporan-tagihan-tahunan*','laporan-tagihan-spp*')) ? 'mm-active' : ''}}">
                            <i class="metismenu-icon pe-7s-display1"></i>
                            Laporan
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul class="{{(request()->is('laporan-tagihan-tahunan*','laporan-tagihan-spp*')) ? 'mm-show' : ''}}">
                            <li>
                                <a href="{{route('laporan-tagihan-tahunan.index')}}" class="{{(request()->is('laporan-tagihan-tahunan*')) ? 'mm-active' : ''}}">
                                    Biaya Tahunan
                                </a>
                            </li>
                            <li>
                                <a href="{{route('laporan-tagihan-spp.index')}}" class="{{(request()->is('laporan-tagihan-spp*')) ? 'mm-active' : ''}}">
                                    SPP
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                    <a href="{{route('laporan.presensi.index')}}" class="{{(request()->is('laporan/presensi')) ? 'mm-active' : ''}}">
                        <i class="metismenu-icon pe-7s-home"></i>
                            Laporan Presensi
                    </a>
                </li>
                @endrole
                @role('siswa')
                    <li>
                        <a href="#" class="{{(request()->is('profil-siswa*','update-password*')) ? 'mm-active' : ''}}">
                            <i class="metismenu-icon pe-7s-user"></i>
                            Data Siswa
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul class="{{(request()->is('profil-siswa*','update-password*')) ? 'mm-show' : ''}}">
                            <li>
                                <a href="{{route('profil-siswa')}}" class="{{(request()->is('profil-siswa*')) ? 'mm-active' : ''}}">
                                    Profil
                                </a>
                            </li>
                            <li>
                                <a href="{{route('update-password')}}" class="{{(request()->is('update-password*')) ? 'mm-active' : ''}}">
                                    Update Password
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{route('presensi.index')}}" class="{{(request()->is('presensi*')) ? 'mm-active' : ''}}">
                            <i class="metismenu-icon pe-7s-clock"></i>
                                Presensi
                        </a>
                    </li>
                    <li>
                        <a href="#" class="{{(request()->is('keuangan-spp*','keuangan-tahunan*')) ? 'mm-active' : ''}}">
                            <i class="metismenu-icon pe-7s-display1"></i>
                            Keuangan
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul class="{{(request()->is('keuangan-spp*','keuangan-tahunan*')) ? 'mm-show' : ''}}">
                            <li>
                                <a href="{{route('keuangan-tahunan.index')}}" class="{{(request()->is('keuangan-tahunan*')) ? 'mm-active' : ''}}">
                                    Biaya Tahunan
                                </a>
                            </li>
                            <li>
                                <a href="{{route('keuangan-spp.index')}}" class="{{(request()->is('keuangan-spp*')) ? 'mm-active' : ''}}">
                                    SPP
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{route('kesehatan-siswa.index')}}" class="{{(request()->is('kesehatan-siswa*')) ? 'mm-active' : ''}}">
                            <i class="metismenu-icon pe-7s-smile"></i>
                                Data Kesehatan
                        </a>
                    </li>
                    <li>
                        <a href="{{route('qr-code.index')}}" class="{{(request()->is('QR-Code*')) ? 'mm-active' : ''}}">
                            <i class="metismenu-icon pe-7s-keypad"></i>
                                QR Code
                        </a>
                    </li>
                @endrole
                @role(['guru_tk'])
                    <li>
                        <a href="{{route('anggota-kelas.index')}}" class="{{(request()->is('anggota-kelas*')) ? 'mm-active' : ''}}">
                            <i class="metismenu-icon pe-7s-users"></i>
                                Peserta Didik
                        </a>
                    </li>
                    <li>
                        <a href="{{route('data-kesehatan.index')}}" class="{{(request()->is('data-kesehatan*')) ? 'mm-active' : ''}}">
                            <i class="metismenu-icon pe-7s-smile"></i>
                                Data Kesehatan
                        </a>
                    </li>
                    <li>
                        <a href="{{route('kelas.pgtk.index.kelas')}}" class="{{(request()->is('kelas-pg-tk*')) ? 'mm-active' : ''}}">
                            <i class="metismenu-icon pe-7s-smile"></i>
                                Kelas
                        </a>
                    </li>
                @endrole
                @role('guru_sd')
                    <li>
                        <a href="{{route('anggota-kelas.index')}}" class="{{(request()->is('anggota-kelas*')) ? 'mm-active' : ''}}">
                            <i class="metismenu-icon pe-7s-users"></i>
                                Peserta Didik
                        </a>
                    </li>
                    <li>
                        <a href="#" class="{{(request()->is('presensi-kelas*','presensi-ekstrakurikuler*')) ? 'mm-active' : ''}}">
                            <i class="metismenu-icon pe-7s-smile"></i>
                            Presensi
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul class="{{(request()->is('presensi-kelas*','presensi-ekstrakurikuler*')) ? 'mm-show' : ''}}">
                            <li>
                                <a href="{{route('presensi-kelas.index')}}" class="{{(request()->is('presensi-kelas*')) ? 'mm-active' : ''}}">
                                    Kelas
                                </a>
                            </li>
                            <li>
                                <a href="{{route('presensi-ekstrakurikuler.index')}}" class="{{(request()->is('presensi-ekstrakurikuler*')) ? 'mm-active' : ''}}">
                                    Ekstrakurikuler
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="{{(request()->is('laporan/presensi-pekanan*','laporan/presensi-bulanan*')) ? 'mm-active' : ''}}">
                            <i class="metismenu-icon pe-7s-graph2"></i>
                            Laporan 
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul class="{{(request()->is('laporan/presensi-pekanan*','laporan/presensi-bulanan*')) ? 'mm-show' : ''}}">
                            <li>
                                <a href="{{route('laporan.presensi.pekanan')}}" class="{{(request()->is('laporan/presensi-pekanan*')) ? 'mm-active' : ''}}">
                                    Pekanan
                                </a>
                            </li>
                            <li>
                                <a href="{{route('laporan.presensi.bulanan')}}" class="{{(request()->is('laporan/presensi-bulanan*')) ? 'mm-active' : ''}}">
                                    Bulanan
                                </a>
                            </li>
                        </ul>
                    </li>
                @endrole
                <li>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"><i class="metismenu-icon pe-7s-power"></i>Keluar</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                </li>                 
            </ul>
        </div>
    </div>
</div> 