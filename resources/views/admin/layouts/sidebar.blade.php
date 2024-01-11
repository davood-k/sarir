<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/login" class="brand-link">
        <img src="" alt="" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light mr-5">ورود</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar" style="direction: ltr">
        <div style="direction: rtl">
            <!-- Sidebar user panel (optional) -->
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                      with font-awesome or any other icon font library -->

                    @can('add-user')
                        <li class="nav-item has-treeview {{ isActive(['importexcel', 'insert'], 'menu-open') }}">
                            <a href="" class="nav-link {{ isActive(['importexcel', 'insert']) }}">
                                <i class="nav-icon fa fa-dashboard"></i>
                                <p>
                                    افزودن نیرو
                                    <i class="fa fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                {{-- <li class="nav-item">
                                    <a href="{{ route('importexcel') }}" class="nav-link {{ isActive('importexcel') }}">
                                        <i class="fa fa-circle-o nav-icon"></i>
                                        <p>افزودن نیرو از اکسل</p>
                                    </a>
                                </li> --}}
                                <li class="nav-item">
                                    <a href="{{ route('insert') }}" class="nav-link {{ isActive('insert') }}">
                                        <i class="fa fa-circle-o nav-icon"></i>
                                        <p>افزودن نیرو فردی</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                    @can('show-page')
                        <li
                            class="nav-item has-treeview {{ isActive(['amaken', 'tablighat', 'basij', 'hamkar'], 'menu-open') }}">
                            <a href="" class="nav-link {{ isActive(['amaken', 'tablighat', 'basij', 'hamkar']) }}">
                                <i class="nav-icon fa fa-tree"></i>
                                <p>
                                    لیست کلی
                                    <i class="fa fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('amaken') }}" class="nav-link {{ isActive('amaken') }}">
                                        <i class="fa fa-circle-o nav-icon"></i>
                                        <p>اماکن</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('tablighat') }}" class="nav-link {{ isActive('tablighat') }}">
                                        <i class="fa fa-circle-o nav-icon"></i>
                                        <p>تبلیغات</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('basij') }}" class="nav-link {{ isActive('basij') }}">
                                        <i class="fa fa-circle-o nav-icon"></i>
                                        <p>بسیج</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('hamkar') }}" class="nav-link {{ isActive('hamkar') }}">
                                        <i class="fa fa-circle-o nav-icon"></i>
                                        <p>همکاران</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li
                            class="nav-item has-treeview {{ isActive(['azmoon', 'taeedeazmoon', 'printazmoon', 'infolderpr'], 'menu-open') }}">
                            <a href="#"
                                class="nav-link {{ isActive(['azmoon', 'taeedeazmoon', 'printazmoon', 'infolderpr']) }}">
                                <i class="nav-icon fa fa-edit"></i>
                                <p>
                                    آزمون
                                    <i class="fa fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="{{ route('azmoon') }}" class="nav-link {{ isActive('azmoon') }}">
                                        <i class="fa fa-circle-o nav-icon"></i>
                                        <p>در مرحله آزمون</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('taeedeazmoon') }}" class="nav-link {{ isActive('taeedeazmoon') }}">
                                        <i class="fa fa-circle-o nav-icon"></i>
                                        <p>
                                            آماده دعوت
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('infolderpr') }}" class="nav-link {{ isActive('infolderpr') }}">
                                        <i class="fa fa-circle-o nav-icon"></i>
                                        <p>پرینت در پرونده</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('printazmoon') }}" class="nav-link {{ isActive('printazmoon') }}">
                                        <i class="fa fa-circle-o nav-icon"></i>
                                        <p>پرینت مدعوین</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li class="nav-item has-treeview {{ isActive(['comision', 'bayegani'], 'menu-open') }}">
                            <a href="" class="nav-link {{ isActive(['comision', 'bayegani']) }}">
                                <i class="nav-icon fa fa-table"></i>
                                <p>
                                    کمیسیون
                                    <i class="fa fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('comision') }}" class="nav-link {{ isActive('comision') }}">
                                        <i class="fa fa-circle-o nav-icon"></i>
                                        <p>در مرحله کمیسون</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('bayegani') }}" class="nav-link {{ isActive('bayegani') }}">
                                        <i class="fa fa-circle-o nav-icon"></i>
                                        <p>
                                            بایگانی
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview {{ isActive(['khorooj', 'pagestring', 'duty.index'], 'menu-open') }}">
                            <a href="" class="nav-link {{ isActive(['khorooj', 'pagestring', 'duty.index']) }}">
                                <i class="nav-icon fa fa-edit"></i>
                                <p>
                                    پنل 1
                                    <i class="fa fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('pagestring') }}" class="nav-link {{ isActive('pagestring') }}">
                                        <i class="fa fa-circle-o nav-icon"></i>
                                        <p>
                                            توضیحات
                                        </p>
                                    </a>
                                </li>
                                @can('show-page')
                                    <li class="nav-item">
                                        <a href="{{ route('duty.index') }}" class="nav-link {{ isActive('duty.index') }}">
                                            <i class="fa fa-circle-o nav-icon"></i>
                                            <p>
                                                وظایف
                                            </p>
                                        </a>
                                    </li>
                                @endcan

                                <li class="nav-item">
                                    <a href="{{ route('khorooj') }}" class="nav-link {{ isActive('khorooj') }}">
                                        <i class="fa fa-circle-o nav-icon"></i>
                                        <p>
                                            خروجی اکسل
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                    <li class="nav-item has-treeview {{ isActive(['information', '/', 'informationOffice.index'], 'menu-open') }}">
                        <a href="" class="nav-link {{ isActive(['information', '/', 'informationOffice.index']) }}">
                            <i class="nav-icon fa fa-table"></i>
                            <p>
                                اطلاع رسانی خدام
                                <i class="fa fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('information') }}" class="nav-link {{ isActive('information') }}">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>قوانین و مقررات</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('/') }}" class="nav-link {{ isActive('/') }}">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>نامه های معرفی</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('informationOffice.index') }}" class="nav-link {{ isActive('informationOffice.index') }}">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>
                                        محل های خدمتی
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
    </div>
    <!-- /.sidebar -->
</aside>
