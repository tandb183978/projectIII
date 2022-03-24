<!doctype html>
<html lang="en-us">
<head>
    <title> HomePage </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style>
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            height: 40px;
            background-color: darkslategrey;
            color: red;
            text-align: right;
            padding: 7px 10px;
            transition: background-color 2s ease-in-out;
        }
        .footer:hover{
            background-color: #718096;
        }

        body{
            margin-bottom: 50px;
        }

        .carousel-item {
            transition: transform 2s ease-in-out, -webkit-transform 2s ease-in-out;
        }

        .carousel-item {
            width: 1540px !important;
            height: 670px !important;
        }

        .home_content.fixed{
            display: block;
            position: fixed;
            bottom: 0;
            right: 0;
            width: 100%;
            height: 93%;
        }

        @yield('css')

    </style>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</head>
<body>
    <!--- Navigation bar --->
    <div>
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark sticky-top">        <!-- Thêm sticky để cho nó position:relative luôn top -->
            <ul class="navbar-nav">
                <!-- Brand -->
                <a class="navbar-brand" href="#">
                    <img src="https://i.pinimg.com/originals/3f/3d/d9/3f3dd9219f7bb1c9617cf4f154b70383.jpg" alt="Logo" style="width:40px;">
                </a>
                <!-- Links -->
                <li class="nav-item active">
                    <a class="nav-link" href="{{route('login')}}">Login</a>
                </li>

                <!-- Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                        Company
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">Public Information</a>
                        <a class="dropdown-item" href="#">Organizational Structure</a>
                        <a class="dropdown-item" href="#">Employees</a>
                    </div>
                </li>

                <!-- Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                       Recruitment
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">News</a>
                        <a class="dropdown-item" href="#">Application</a>
                    </div>
                </li>


{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="#">Link</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="#">Link</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link disabled" href="#">Disabled</a>--}}
{{--                </li>--}}

                <!-- Navbar text: Bởi vì những đường trên là link nên nếu thêm text thì không thẳng hàng !-->
{{--                <span class="navbar-text">--}}
{{--                        Navbar text--}}
{{--                    </span>--}}
            </ul>

            <!-- Navbar and button !-->
            <ul class="navbar-nav ml-auto">
                <form class="form-inline"  action="">
                    <input class="form-control mr-sm-2" type="text" placeholder="Search">
                    <button class="btn btn-success" type="submit">Search</button>
                </form>
            </ul>
        </nav>
    </div>

    @yield('home-content')

    <footer class="footer">
        <span style="color:red" >Duong Ba Tan - 20183978</span>
    </footer>

    @yield('js')


</body>
</html>
