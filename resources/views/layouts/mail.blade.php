@extends('layouts.app')

@can('employee')
    @section('title', 'employee')
@endcan
@can('admin')
    @section('title', 'admin')
@endcan

@section('css_external_link')

    <link rel="stylesheet" href="/css/mail/layout.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <!-- Sweet alert -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">
@endsection

@section('app-content')

    <div class="container-fluid">
        <div class="row">
            <div style="width: 96.7%; float:left">

                @yield('mail.content')

                @include('mail.composer')

            </div>
            <div style="width: 3.3%; height: 100%; float:right; position: relative">
                <div id="w3-sidebar" class="w3-sidebar w3-collapse w3-light w3-card w3-animate-right w3-large" style="position: relative!important;width:40px;height:1000px">
                    <a id='btn-composer' class="w_1 activate w3-bar-item w3-button">
                        <i style="margin-left: -3px" class="fas fa-pencil-alt"></i>
                        <span style="font-size:12px">&nbsp;&nbsp;Compose</span>
                    </a>

                    <a href="{{route('mail.inbox.index')}}" id='btn-inbox' class="w_2 w3-bar-item w3-button">
                        <i style="margin-left: -5px" class="fal fa-mailbox"></i>
                        <span style="font-size:12px">&nbsp;&nbsp;Inbox</span>
                    </a>

                    <a href="{{route('mail.outbox.index')}}" id='btn-outbox' class="w_3 w3-bar-item w3-button">
                        <i style="margin-left: -5.5px" class="fas fa-box-open"></i>
                        <span style="font-size:12px">&nbsp;&nbsp;Outbox</span>
                    </a>
                    <a href="{{route('mail.trash_bin.index')}}" id='btn-trash' class="w_4 w3-bar-item w3-button">
                        <i style="margin-left: -3.5px" class="fas fa-trash"></i>
                        <span style="font-size:12px">&nbsp;&nbsp;Trash Bin</span>
                    </a>
                    <a href="{{route('mail.favorite.index')}}" id='btn-favorite' class="w_5 w3-bar-item w3-button">
                        <i style="margin-left: -5.5px" class="fas fa-star"></i>
                        <span style="font-size:12px">&nbsp;&nbsp;Favorite</span>
                    </a>
                    <a href="{{route('mail.important.index')}}" id='btn-important' class="w_6 w3-bar-item w3-button">
                        <i style="margin-left: -5.5px" class="fas fa-exclamation-circle"></i>
                        <span style="font-size:12px">&nbsp;&nbsp;Important</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!-- sweet alert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>
    <script>
        // Get the container element
        let btnContainer = document.getElementById("w3-sidebar");

        // Get all buttons (href) with class="btn" inside the container
        let btns = btnContainer.getElementsByClassName("w3-bar-item");
        // Loop through the buttons and add the active class to the current/clicked button
        for (let i = 0; i < btns.length; i++) {
            btns[i].addEventListener("click", function() {
                let current = document.getElementsByClassName("activate");

                // If there's no active class
                if (current.length > 0) {
                    current[0].className = current[0].className.replace(" activate", "");
                }

                // Add the active class to the current/clicked button
                this.className += " activate";
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            /* Activate class*/
            let type = '{{$type}}';
            console.log(type);
            for (let i = 1; i<=6; i++)
                $('a.w_'+i).removeClass('activate');
            if (type === 'inbox') $('a.w_2').addClass('activate');
            if (type === 'outbox') $('a.w_3').addClass('activate');
            if (type === 'trash_bin') $('a.w_4').addClass('activate');
            if (type === 'favorite') $('a.w_5').addClass('activate');
            if (type === 'important') $('a.w_6').addClass('activate');

            /* Composer */
            $('#btn-composer').on('click', function (){
                if (! $('#composebox').is(":visible")){
                    $('#composebox').css("display", "block");
                }
            })

            $('#close-composer').on('click', function (){
                $('#composebox').css("display", "none");
            })

            $('#minimize-composer').on('click', function (){
                $('#composer-body').css('display', 'none');
                $('#thumoi').removeClass('col-lg-3 ml-auto');
                $('#icon').removeClass('col-lg-2 ml-auto');
                $('#composebox').removeClass('col-lg-5 ml-auto');
                $('#composebox').toggleClass('col-lg-2 ml-auto');
                $('#minimize-composer').css('display', 'none');
                $('#maximize-composer').css('display', 'block');
            })

            $('#maximize-composer').on('click', function (){
                $('#composer-body').css('display', 'block');
                $('#icon').addClass('col-lg-2 ml-auto');
                $('#thumoi').addClass('col-lg-3 mr-auto');
                $('#composebox').removeClass('col-lg-2 ml-auto');
                $('#composebox').toggleClass('col-lg-5 ml-auto');
                $('#minimize-composer').css('display', 'block');
                $('#maximize-composer').css('display', 'none');
            })

            $('#cc').on('click', function (){
                if ($('#ccid').is(":visible"))
                    $('#ccid').css('display', 'none');
                else
                    $('#ccid').css('display', 'block');

            })

            $('#submit').on('click', function (){
                let receiver = $("#receiverid").val();
                if (!receiver){
                    swal({
                        title: "Lỗi!",
                        text: "Hãy chỉ định ít nhất một người nhận!",
                        type: "error",
                        confirmButtonText: "Ok"
                    });
                    return false;
                }
            });
        })

    </script>
@endsection

