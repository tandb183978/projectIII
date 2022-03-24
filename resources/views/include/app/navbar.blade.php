<nav  class="navbar navbar-expand-lg navbar-light bg-warning">
    <div class="container-fluid">
        <button type="button" id="sidebarCollapse" class="btn btn-info" data-toggle="sidebar-collapse">
            <i class="fas fa-align-left"></i>
            <span>Toggle Sidebar</span>
        </button>
        <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-align-justify"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item dropdown user user-menu">
                    <a href="#" class="nav-link" data-toggle="dropdown">
                        @can('employee-access')
                            <img style="width:40px !important; height: 40px !important;" src="{{\Illuminate\Support\Facades\Storage::url(Auth::user()->employee()->first()->image_profile)}}" class="user-image rounded-circle elevation-2" alt="user_image">
                        @endcan
                        @can('admin-access')
                            <img style="width:40px !important; height: 40px !important;" src="/img/theme_addemployee.jpg" class="user-image rounded-circle elevation-2" alt="user_image">
                        @endcan
                        <span class="hidden-xs">{{ Auth::user()->name }}</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <!-- User image -->
                        <li style="width: 275px; height: 175px; text-align: center;vertical-align: middle;align-items: center" class="user-header bg-primary">
                            <div style="margin: 0; top: 50%; -ms-transform: translateY(-50%); position: relative; transform: translateY(-50%);">
                                @can('employee-access')
                                    <img style="width:100px !important; height: 100px !important;" src="{{\Illuminate\Support\Facades\Storage::url(Auth::user()->employee()->first()->image_profile)}}" class="img rounded-circle elevation-2" alt="user_image">
                                @endcan
                                @can('admin-access')
                                    <img style="width:100px !important; height: 100px !important;" src="/img/theme_addemployee.jpg" class="img rounded-circle elevation-2" alt="user_image">
                                @endcan
                                <p style="color: white"> {{Auth::user()->name}} </p>
                            </div>
                        </li>

                        <hr>
                        @can('employee-access')
                            <li style="margin-top: -8px; margin-left: 10px;">
                                <button class="btn btn-info btn-flat" style="width: 135px;" data-toggle="modal" data-target=".animate" data-ui-class="a-roll">
                                    <h6 style="margin-left: -20px; margin-top: 5px"><strong>&nbsp;&nbsp;Profile</strong></h6>
                                </button>
                            </li>

                        @endcan
                        <li class="dropdown-divider"></li>

                        @can('admin-access')
                            {{-- <li><a href="{{Route('admin.reset-password')}}" class="btn btn-default btn-flat"> Change Password </a></li> --}}
                        @endcan

                        <li style="margin-top: -8px; margin-left: 10px;"> <a href="{{ route('logout') }}"
                                class="btn btn-info btn-flat" style="width: 135px; "
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <h6 style="margin-left: -20px; margin-top: 5px"><strong>Sign out</strong></h6>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>

