<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title> @yield('title')</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <link rel="stylesheet" href="/css/sidebar_ui.css">
    <!-- Font Awesome JS -->

    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"/>


    <!-- CSS in section -->
    @yield('css_external_link')
    @yield('js_head_link')
    <style>
        @import url('https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css');

        @yield('css-internal-link')

        body{
            font-family: "Source Sans Pro",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
        }

        i.far {
            display: inline-block;
            box-shadow: 0 0 2px #888;

            width: 30px;
            height: 30px;
            margin-left: 3px;
        }

        @-webkit-keyframes text {
            0% {
                -webkit-transform: translate(0, 0);
                color: #ef4444;
            }

            50% {
                -webkit-transform: translate(-50%, 0);
                color: yellowgreen;
            }
            100% {
                -webkit-transform: translate(-100%, 0);
                color: blue;
            }
        }

        @-webkit-keyframes background {
            0% {
                background-color: #00ff00;
                padding-top: 0;
                opacity: 1;
            }
            25% {
                background-color: rebeccapurple;
                padding-top: 5px;
                opacity: 0.75;
            }
            50% {
                background-color: #2d3748;
                padding-top: 10px;
                opacity: 0.5;


            }
            75% {
                background-color: rebeccapurple;
                padding-top: 5px;
            }
            100% {
                background-color: #00E466;
                padding-top: 0;
            }
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .marquee {
            display:block;
            width:100%;
            white-space:nowrap;
            overflow:hidden;
            /*background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);*/
            /*-webkit-animation: gradient 15s ease infinite;*/
            -webkit-animation: background 10s infinite ease-in-out;
        }
        .marquee strong {
            display:inline-block;
            padding-left:100%;
            -webkit-animation: text 10s infinite linear;
        }

        .shadow {
            -moz-box-shadow:    inset 0 0 10px #000000;
            -webkit-box-shadow: inset 0 0 10px #000000;
            box-shadow:         inset 0 0 10px #000000;
        }
        .modal-open {
            overflow: inherit;
        }
        .bg-faded{background-color:#f3f3f3;max-height:500px}
        .btn-round{border-radius:500px}
        .btn-round,.btn-round:hover,.btn-round:active{border-color:transparent}
        .modal.animate {opacity:0}
        .modal.animate.show {opacity:1}
        .modal.animate .modal-dialog{-webkit-transform:translate(0,0);-ms-transform: translate(0,0);transform:translate(0,0)}
        .modal.animate .a-roll{-webkit-animation:rollOut .5s;animation: rollOut .5s}
        .modal.animate.show .a-roll{-webkit-animation:rollIn .5s;animation:rollIn .5s}

        /*Profile*/


    </style>
</head>

<body>

<div class="wrapper">

    @include('include.app.sidebar')

    <div id="content">
        @include('include.app.navbar')

        <!-- Aditional Styles -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">

        <div style="height: 30px; width: 100%!important;  font-size: 14px; position: relative; margin-top: -60px;" class="marquee">
            <strong>Welcome to employee management application</strong>
        </div>

        @yield('app-content')

    </div>
</div>
<!-- Fontawesame -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/js/fontawesome.min.js"></script>
    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    @yield('js')

    <script type="text/javascript">
        $(".modal-trigger").click(function(e){
            e.preventDefault();
            dataModal = $(this).attr("data-modal");
            $("#" + dataModal).css({"display":"block"});
            // $("body").css({"overflow-y": "hidden"}); //Prevent double scrollbar.
        });

        $(".close-modal, .modal-sandbox").click(function(){
            $(".modal").css({"display":"none"});
            // $("body").css({"overflow-y": "auto"}); //Prevent double scrollbar.
        });

        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
        });

        // Collapse click
        $('[data-toggle=sidebar-collapse]').click(function() {
            SidebarCollapse();
        });

        function SidebarCollapse () {
            $('.menu-collapsed').toggleClass('d-none');
            $('.dropdown-toggle').toggleClass('d-none');

                // $('#sidebar-container').toggleClass('sidebar-expanded sidebar-collapsed');
            // Collapse/Expand icon
            $('#sidebarCollapse').toggleClass('fa-angle-double-left fa-angle-double-right');
        }
    </script>

</body>
</html>
