
<!---- Permission 1: Quản lý employee --->

<a href="#employeeSubmenu" data-toggle="collapse" aria-expanded="false">
    <i style="color: yellow; padding-left: 0.4em !important;padding-right: -6px !important;" class="far fa-users"></i>
    <span class="menu-collapsed">&nbsp;Employees</span>
    <span class="dropdown-toggle ml-auto"></span>
</a>

<ul class="collapse list-unstyled" id="employeeSubmenu">
    <li>
        <a href="{{route('admin.employee.list_employee')}}">
            <span class="menu-collapsed">List employee</span>
        </a>
    </li>
    <li>
        <a href="{{route('admin.employee.add_employee')}}">
            <span class="menu-collapsed">Add employee</span>
        </a>
    </li>
</ul>

<!---- Permission 2: Quản lý lương - attendance --->

<a href="#salarySubmenu" data-toggle="collapse" aria-expanded="false">
    <i style="color: yellow; padding-left: 0.65em !important;" class="far fa-file-invoice-dollar"></i>
    <span class="menu-collapsed">&nbsp;Salary - Attendance</span>
    <span class="dropdown-toggle ml-auto"></span>
</a>

<ul class="collapse list-unstyled" id="salarySubmenu">
    <li>
        <a href="{{route('admin.salary.employee_attendance')}}">
            <span class="menu-collapsed">Employees Attendance</span>
        </a>
    </li>
    <li>
        <a href="{{route('admin.salary.employee_salary')}}">
            <span class="menu-collapsed">Employees Salary</span>
        </a>
    </li>
</ul>

<!---- Permission 3: Quản lý leave --->

<a href="{{route('admin.leave.list_leaves')}}" aria-expanded="false">
    <i style="color: yellow; padding-left: 0.5em !important;" class="far fa-users-medical"></i>
    <span class="menu-collapsed">&nbsp;Leave Management</span>
    <span class="dropdown-toggle ml-auto"></span>
</a>

