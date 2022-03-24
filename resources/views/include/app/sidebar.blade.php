
    <!-- Sidebar  -->
    <nav id="sidebar">
        <div class="sidebar-header bg-secondary">
            <h3>Employee Management</h3>
            <strong>EM</strong>
        </div>

        <ul class="list-unstyled components">
                    <!---- Admin ----->
            <li>
                @can('admin-access')
                    <a href="{{route('admin.index')}}">
                        <i style="color: yellow; padding-left: 0.5em !important;" class="far fa-home"></i>
                        <span class="menu-collapsed" style="margin-left: 3px">  Admin content </span>
                    </a>
                @include('admin.sidebar_admin_permission')
                @endcan
            </li>


                    <!--- Employee ---->
            <li>
                @can('employee-access')
                    <a href="{{route('employee.index')}}">
                        <i style="color: yellow; padding-left: 0.5em !important;" class="far fa-home"></i>
                        <span class="menu-collapsed" style="margin-left: 3px">  Employee content </span>
                    </a>
                @include('employee.sidebar_employee_permission')
            @endcan
            </li>


            <li>
                <a href="{{route('mail.inbox.index')}}" aria-expanded="false">
                    <i style="color: yellow; padding-left: 0.5em !important;" class="far fa-paper-plane"></i>
                    <span class="menu-collapsed">&nbsp;Mail</span>
                    <span class="dropdown-toggle ml-auto"></span>
                </a>
            </li>


        </ul>

        <ul class="list-unstyled CTAs">
            <li>
                <a href="https://bootstrapious.com/tutorial/files/sidebar.zip" class="download">Download source</a>
            </li>
            <li>
                <a href="https://bootstrapious.com/p/bootstrap-sidebar" class="article">Back to article</a>
            </li>
        </ul>
    </nav>


