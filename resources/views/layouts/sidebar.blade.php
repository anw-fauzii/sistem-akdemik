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
                    <a href="{{route('dashboard')}}" class="{{(request()->is('dashboard')) ? 'mm-active' : ''}}">
                        <i class="metismenu-icon pe-7s-home"></i>
                            Beranda
                    </a>
                </li>
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
                    <a href="#" class="{{(request()->is('tahun-ajaran*','guru*','siswa*','kelas*','ekstrakurikuler*')) ? 'mm-active' : ''}}">
                        <i class="metismenu-icon pe-7s-rocket"></i>
                           Data Master
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="{{(request()->is('tahun-ajaran*','guru*','siswa*','kelas*','ekstrakurikuler*')) ? 'mm-show' : ''}}">
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
                    </ul>
                </li>
                <li>
                    <a href="#" class="{{(request()->is('bulan-spp*')) ? 'mm-active' : ''}}">
                        <i class="metismenu-icon pe-7s-cash"></i>
                           Data Keuangan
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="{{(request()->is('bulan-spp*','pembayaran-spp*')) ? 'mm-show' : ''}}">
                        <li>
                            <a href="{{route('bulan-spp.index')}}" class="{{(request()->is('bulan-spp*')) ? 'mm-active' : ''}}">
                                Bulan SPP
                            </a>
                        </li>
                        <li>
                            <a href="{{route('pembayaran-spp.index')}}" class="{{(request()->is('pembayaran-spp*')) ? 'mm-active' : ''}}">
                                Pembayaran SPP
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{route('presensi.index')}}" class="{{(request()->is('presensi*')) ? 'mm-active' : ''}}">
                        <i class="metismenu-icon pe-7s-smile"></i>
                            Presensi
                    </a>
                </li>
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