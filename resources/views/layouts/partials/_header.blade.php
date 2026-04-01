<nav class="main-header !h-[3.75rem]" aria-label="Global">
    <div class="main-header-container ps-[0.725rem] pe-[1rem] ">

        <div class="header-content-left">
            <!-- Start::header-element -->
            <div class="header-element">
                <div class="horizontal-logo">
                    <a href="{{ route('home') }}" class="header-logo">
                        <img src="{{ asset('eofficeadmin/images/brand-logos/logo-eoffice.png') }}" alt="logo"
                            class="desktop-logo">
                        <img src="{{ asset('eofficeadmin/images/brand-logos/toggle-logo-eoffice.png') }}" alt="logo"
                            class="toggle-logo">
                        <img src="{{ asset('eofficeadmin/images/brand-logos/logo-eoffice-dark.png') }}" alt="logo"
                            class="desktop-dark">
                        <img src="{{ asset('eofficeadmin/images/brand-logos/toggle-logo.png') }}" alt="logo"
                            class="toggle-dark">
                        <img src="{{ asset('eofficeadmin/images/brand-logos/logo-eoffice.png') }}" alt="logo"
                            class="desktop-white">
                        <img src="{{ asset('eofficeadmin/images/brand-logos/toggle-logo-eoffice.png') }}" alt="logo"
                            class="toggle-white">
                    </a>
                </div>
            </div>
            <!-- End::header-element -->
            <!-- Start::header-element -->
            <div class="header-element md:px-[0.325rem] !items-center">
                <!-- Start::header-link -->
                <a aria-label="Hide Sidebar"
                    class="sidemenu-toggle animated-arrow  hor-toggle horizontal-navtoggle inline-flex items-center"
                    href="javascript:void(0);"><span></span></a>
                <!-- End::header-link -->
            </div>
            <!-- End::header-element -->
        </div>



        <div class="header-content-right">
            <!-- light and dark theme -->
            <div
                class="header-element header-theme-mode hidden !items-center sm:block !py-[1rem] md:!px-[0.65rem] px-2">
                <a aria-label="anchor"
                    class="hs-dark-mode-active:hidden flex hs-dark-mode group flex-shrink-0 justify-center items-center gap-2  rounded-full font-medium transition-all text-xs dark:bg-bgdark dark:hover:bg-black/20 dark:text-[#8c9097] dark:text-white/50 dark:hover:text-white dark:focus:ring-white/10 dark:focus:ring-offset-white/10"
                    href="javascript:void(0);" data-hs-theme-click-value="dark">
                    <i class="bx bx-moon header-link-icon"></i>
                </a>
                <a aria-label="anchor"
                    class="hs-dark-mode-active:flex hidden hs-dark-mode group flex-shrink-0 justify-center items-center gap-2  rounded-full font-medium text-defaulttextcolor  transition-all text-xs dark:bg-bodybg dark:bg-bgdark dark:hover:bg-black/20 dark:text-[#8c9097] dark:text-white/50 dark:hover:text-white dark:focus:ring-white/10 dark:focus:ring-offset-white/10"
                    href="javascript:void(0);" data-hs-theme-click-value="light">
                    <i class="bx bx-sun header-link-icon"></i>
                </a>
            </div>
            <!-- End light and dark theme -->

            <div
                class="header-element py-[1rem] md:px-[0.65rem] px-2 notifications-dropdown header-notification hs-dropdown ti-dropdown !hidden md:!block [--placement:bottom-left]">
                <button id="dropdown-notification" type="button"
                    class="hs-dropdown-toggle relative ti-dropdown-toggle !p-0 !border-0 flex-shrink-0  !rounded-full !shadow-none align-middle text-xs">
                    <i class="bx bx-bell header-link-icon  text-[1.125rem]"></i>
                    <span class="flex absolute h-5 w-5 -top-[0.25rem] end-0  -me-[0.6rem]">
                        <span
                            class="animate-slow-ping absolute inline-flex -top-[2px] -start-[2px] h-full w-full rounded-full bg-secondary/40 opacity-75"></span>
                        <span
                            class="relative inline-flex justify-center items-center rounded-full  h-[14.7px] w-[14px] bg-secondary text-[0.625rem] text-white"
                            id="notification-icon-badge">{{ '$countNotif' }}</span>
                    </span>
                </button>
                <div class="main-header-dropdown !-mt-3 !p-0 hs-dropdown-menu ti-dropdown-menu bg-white !w-[22rem] border-0 border-defaultborder !m-0 hidden"
                    aria-labelledby="dropdown-notification" style="">

                    <div class="ti-dropdown-header !m-0 !p-4 !bg-transparent flex justify-between items-center">
                        <p
                            class="mb-0 text-[1.0625rem] text-defaulttextcolor font-semibold dark:text-[#8c9097] dark:text-white/50">
                            Notifikasi</p>
                        <span
                            class="text-[0.75em] py-[0.25rem/2] px-[0.45rem] font-[600] rounded-sm bg-secondary/10 text-secondary"
                            id="notifiation-data">{{ '$countNotif' }} Surat Masuk</span>
                    </div>
                    <div class="dropdown-divider"></div>
                    <ul class="list-none !m-0 !p-0 end-0" id="header-notification-scroll" data-simplebar="init">
                        <div class="simplebar-wrapper" style="margin: 0px;">
                            <div class="simplebar-height-auto-observer-wrapper">
                                <div class="simplebar-height-auto-observer"></div>
                            </div>
                            <div class="simplebar-mask">
                                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                    <div class="simplebar-content-wrapper" tabindex="0" role="region"
                                        aria-label="scrollable content" style="height: auto; overflow: hidden;">
                                        <div class="simplebar-content" style="padding: 0px;">
                                            {{-- @foreach ($data as $item) --}}
                                                <li class="ti-dropdown-item dropdown-item">
                                                    <div class="flex items-start">
                                                        <div class="pe-2">
                                                            <span
                                                                class="inline-flex justify-center items-center !w-[2.5rem] !h-[2.5rem] !leading-[2.5rem] !text-[0.8rem]  bg-success/10 rounded-[50%] text-success"><i
                                                                    class="ti  text-[1.125rem]"></i></span>
                                                        </div>
                                                        <div class="grow flex items-center justify-between">
                                                            <div>
                                                                <p
                                                                    class="mb-0 text-defaulttextcolor dark:text-[#8c9097] dark:text-white/50  text-[0.8125rem]  font-semibold">
                                                                    <a href="notifications.html">Surat Baru untuk
                                                                        Disposisi <span
                                                                            class="text-success }}">No
                                                                            Surat:
                                                                            #{{ '$item->no_surat' }}</span></a>
                                                                </p>
                                                                <span
                                                                    class="mb-0 text-defaulttextcolor dark:text-[#8c9097] dark:text-white/50  text-[0.8125rem]">Surat
                                                                    Masuk Dari :{{ '$item->asal_surat' }}</span>
                                                                <div class="row">

                                                                    <span
                                                                        class="text-[#8c9097] dark:text-white/50 font-normal text-[0.75rem] header-notification-text">{{ '$item->perihal' }}</span>
                                                                </div>
                                                                <div class="row">

                                                                    <span
                                                                        class="text-[#8c9097] dark:text-white/50 font-normal text-[0.75rem] header-notification-text">nanti</span>
                                                                        {{-- {{ (new \Carbon\Carbon($item->created_at))->diffForHumans() }}</span> --}}

                                                                </div>
                                                            </div>
                                                            <div>
                                                                <a aria-label="anchor" href="javascript:void(0);"
                                                                    class="min-w-fit text-[#8c9097] dark:text-white/50 me-1 dropdown-item-close1"><i
                                                                        class="ti ti-x text-[1rem]"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            {{-- @endforeach --}}
                                            {{-- <li class="ti-dropdown-item dropdown-item">
                                                <div class="flex items-start">
                                                    <div class="pe-2">
                                                        <span
                                                            class="inline-flex text-success justify-center items-center !w-[2.5rem] !h-[2.5rem] !leading-[2.5rem] !text-[0.8rem]  bg-success/10 rounded-[50%]"><i
                                                                class="ti ti-clock text-[1.125rem]"></i></span>
                                                    </div>
                                                    <div class="grow flex items-center justify-between">
                                                        <div>
                                                            <p
                                                                class="mb-0 text-defaulttextcolor dark:text-[#8c9097] dark:text-white/50  text-[0.8125rem]  font-semibold">
                                                                <a href="notifications.html">Pengingat Surat untuk
                                                                    Disposisi <span class="text-success">ID:
                                                                        7731116</span></a></p>
                                                            <span
                                                                class="text-[#8c9097] dark:text-white/50 font-normal text-[0.75rem] header-notification-text">Mohon
                                                                segera lakukan tindakan.</span>
                                                        </div>
                                                        <div>
                                                            <a aria-label="anchor" href="javascript:void(0);"
                                                                class="min-w-fit text-[#8c9097] dark:text-white/50 me-1 dropdown-item-close1"><i
                                                                    class="ti ti-x text-[1rem]"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="simplebar-placeholder" style="width: 0px; height: 0px;"></div>
                        </div>
                        <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                            <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                        </div>
                        <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                            <div class="simplebar-scrollbar"
                                style="height: 0px; display: none; transform: translate3d(0px, 0px, 0px);"></div>
                        </div>
                    </ul>

                    <div class="p-4 empty-header-item1 border-t mt-2">
                        <div class="grid">
                            <a href="{{ 'route(surat_masuk.index)' }}" class="ti-btn ti-btn-primary-full !m-0 w-full p-2">Tampil
                                Semua</a>
                        </div>
                    </div>
                    <div class="p-[3rem] empty-item1 hidden">
                        <div class="text-center">
                            <span
                                class="!h-[4rem]  !w-[4rem] avatar !leading-[4rem] !rounded-full !bg-secondary/10 !text-secondary">
                                <i class="ri-notification-off-line text-[2rem]  "></i>
                            </span>
                            <h6
                                class="font-semibold mt-3 text-defaulttextcolor dark:text-[#8c9097] dark:text-white/50 text-[1rem]">
                                No New Notifications</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Header Profile -->
            <div
                class="header-element md:!px-[0.65rem] px-2 hs-dropdown !items-center ti-dropdown [--placement:bottom-left]">

          <button id="dropdown-profile" type="button"
            class="hs-dropdown-toggle ti-dropdown-toggle !gap-2 !p-0 flex-shrink-0 sm:me-2 me-0 !rounded-full !shadow-none text-xs align-middle !border-0 !shadow-transparent ">
            {{-- <img class="inline-block rounded-full " src="{{ asset('eofficeadmin/images/authentication/admin.png') }}"  width="32" height="32" alt="Image Description"> --}}
            {{-- get image from session logged user --}}
            {{-- if default in --}}
            <img class="inline-block rounded-full " src="{{ asset(Auth::user()->photo ?? '') }}" width="32" height="32" alt="Image Description">
          </button>
          <div class="md:block hidden dropdown-profile">
            <p class="font-semibold mb-0 leading-none text-[#536485] text-[0.813rem] ">{{ Auth::user()->name ?? '' }}</p>
            <!-- <span class="opacity-[0.7] font-normal text-[#536485] block text-[0.6875rem] ">Web Designer</span> -->
          </div>
          <div
            class="hs-dropdown-menu ti-dropdown-menu !-mt-3 border-0 w-[11rem] !p-0 border-defaultborder hidden main-header-dropdown  pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end"
            aria-labelledby="dropdown-profile">

                    <ul class="text-defaulttextcolor font-medium dark:text-[#8c9097] dark:text-white/50">
                        <li>
                            <a class="w-full ti-dropdown-item !text-[0.8125rem] !gap-x-0  !p-[0.65rem] !inline-flex"
                                href="{{ 'route(profile)' }}">
                                <i class="ti ti-user-circle text-[1.125rem] me-2 opacity-[0.7]"></i>Profile
                            </a>
                        <li>
                                <a class="w-full ti-dropdown-item !text-[0.8125rem] !gap-x-0  !p-[0.65rem] !inline-flex"
                                    href="{{ 'route(gantiPassword)'}}">
                                    <i class="bx bx-lock-alt text-[1.125rem] me-2 opacity-[0.7]"></i>Ganti Password
                                </a>
                            <li>
                                <a class="w-full ti-dropdown-item !text-[0.8125rem] !p-[0.65rem] !gap-x-0 !inline-flex"
                                href="href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logoutform').submit();"><i
                                    class="ti ti-logout text-[1.125rem] me-2 opacity-[0.7]"></i>Log Out</a>
                            <form id="logoutform" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- End Header Profile -->

            <!-- End::header-element -->
        </div>
    </div>
</nav>
