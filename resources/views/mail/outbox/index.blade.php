@extends('layouts.mail')

@section('css_external_link')
    @parent
    <link rel="stylesheet" href="/css/mail/both_ibob.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
@endsection

@section('mail.content')
    <div class="card-header">
        <h2>Outbox</h2>
    </div>
    <br>
    <table id="inbox" class="table table-borderless table table-hover row-border display nowrap">
        <thead>
        <tr>
            <th style="width: 5%; text-align: center; background-color: blue"></th>
            <th style="width: 19%; text-align: center; background-color: purple"></th>
            <th style="width: 66%; text-align: center; background-color: green"></th>
            <th style="width: 10%; text-align: left; background-color: red"></th>
            <th style="width: 10%;  display: none"></th>
        </tr>
        </thead>

        <tbody>
            @foreach($outboxes as $i=>$outbox)
                <tr id='{{$i+1}}' class="rowDataTable">
                    <td style="text-align: center;"></td>
                    <td style="text-align: left;">{{\App\Models\User::where('email', $outbox->sender)->first()->name}}</td>
                    <td style="text-align: left;">
                        {{$outbox->subject??'(Không có chủ đề)'}}&nbsp;-&nbsp;
                        <span style="color:darkgrey">{{$outbox->content}}</span>
                    </td>
                    <td id='date{{$i+1}}' style="text-align: right;"><small><strong>{{$date[$i]}}</strong></small></td>
                    <td id='icon{{$i+1}}' style="text-align: right; display: none">
                        <form method="post" action="{{route('mail.outbox.detail', $outbox->id)}}">
                            @csrf
                            <button type="submit" style="border: 0">
                                <i class="fas fa-info-circle fa-lg"></i>
                            </button>

                            <button type="submit" style="border: 0">
                                <i style="width: 1.2em" class="fas fa-trash fa-lg"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('js')
    @parent
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            let table = $('#inbox').DataTable({
                "bPaginate": false,
                "bLengthChange": false,
                "bInfo": false,
                "bAutoWidth": false,
                "bSort" : false,
                columnDefs:[{
                    targets:2,
                    // className:"truncate"
                    render: function ( data, type, row ) {
                        let cutoff = 180;
                        return data.length > cutoff ?
                            data.substr( 0, cutoff ) +'…' :
                            data;
                    },
                }],
                // createdRow: function(row){
                //     var td = $(row).find(".truncate");
                //     td.attr("title", td.html());
                // }

            });

            $('#inbox tbody tr').mouseenter(function () {
                let row = table.row( this );
                let index = $(this).attr('id');
                $('#date'+index).css('display', 'none');
                $('#icon'+index).css('display', 'block');
            } );

            $('#inbox tbody tr').mouseleave(function () {
                let row = table.row( this );
                let index = $(this).attr('id');
                $('#date'+index).css('display', 'block');
                $('#icon'+index).css('display', 'none');
            } );
        })

    </script>
    <script>
        // Get the container element
        var btnContainer = document.getElementById("w3-sidebar");

        // Get all buttons (href) with class="btn" inside the container
        var btns = btnContainer.getElementsByClassName("w3-bar-item");
        // Loop through the buttons and add the active class to the current/clicked button
        for (var i = 0; i < btns.length; i++) {
            btns[i].addEventListener("click", function() {
                var current = document.getElementsByClassName("activate");

                // If there's no active class
                if (current.length > 0) {
                    current[0].className = current[0].className.replace(" activate", "");
                }

                // Add the active class to the current/clicked button
                this.className += " activate";
            });
        }
    </script>

@endsection

