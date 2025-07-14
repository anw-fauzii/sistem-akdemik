<div class="app-header header-shadow bg-primary header-text-light">
    <div class="app-header__logo">
        <div class="logo-src mb-4"><img src="{{ asset('storage/logo/samping.png') }}" width="150px"></div>
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
            </span>
    </div> 
    <div class="app-header__content">
        <div class="app-header-right">
            <div class="header-btn-lg pr-0">
                <div class="widget-content p-0">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left">                                 
                            <div class="dropdown show">
                                @if(Auth::user()->foto)
                                <img width="42" class="rounded-circle" src="{{asset('storage/'. Auth::user()->foto)}}" alt="">
                                @else
                                <img width="42" class="rounded-circle" src="{{asset('storage/logo/user.png')}}" alt="">
                                @endif
                            </div>
                        </div>
                        <div class="widget-content-right ml-3 header-user-info">
                            <div class="widget-heading">
                                <strong>{{ Auth::user()->name }}</strong>
                            </div>
                            <div class="widget-subheading">
                                {{ Auth::user()->email }}    
                            </div>
                        </div>
                    </div>
                </div>
            </div>      
        </div>
    </div>
</div> 