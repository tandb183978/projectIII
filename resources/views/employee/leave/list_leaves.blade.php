@extends('layouts.app')

@section('title', 'employee')

@section('css_external_link')
    <!-- DataTables -->
    {{--    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css"/>--}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css"/>
    <!-- Date range picker -->
@endsection

@section('app-content')
    <!--- Content header --->
    @include('include.app.content_header', ['name' => 'Leave in '.$title, 'pre'=>'Employee', 'cur'=>'List leaves'])
    <!--- Main content --->
    <br>

    <div class="container-fluid">
        <div class="card">
            <div style="padding-top: 20px" class="card-header bg-outline-secondary">
                <div class="card-title">
                    <div class="row">
                        <div class="col-lg-12 mx-auto">
                            <!-- Option to select all or specific month -->
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                                <h4><i style="color: yellow" class="fas fa-bars">???</i><strong>&nbsp;&nbsp;Option filter</strong></h4>
                            </button>

                            <div class="dropdown-menu">
                                <button id="specific-month" type="button" class="btn btn-info">Get a specific month</button>
                                <div style="margin-top:1px; margin-bottom: 1px !important;" class="dropdown-divider"></div>
                                <form action="{{route('employee.leave.list_leaves_select')}}" method="post">
                                    @csrf
                                    <button name="all" type="submit" class="btn btn-info" value="all">Get all month</button>
                                </form>
                            </div>
                            <br>
                            <br>
                            {{--                        @if ($errors->has('month'))--}}
                            {{--                        <form id="month-form" style="display: block" action="{{route('admin.salary.employee_salary_select')}}" method="post">--}}
                            {{--                        @else--}}
                            {{--                        <form id="month-form" style="display: none" action="{{route('admin.salary.employee_salary_select')}}" method="post">--}}
                            {{--                        @endif--}}
                            <form id="month-form" style="{{count($errors) > 0 ? 'display: block' : 'display:none'}}" action="{{route('employee.leave.list_leaves_select')}}" method="post">
                                {{csrf_field()}}
                                <div class="form-group row">
                                    <div class="col-sm-1">
                                        <label style="margin-top: 5px" for="monthid"><strong>Month </strong> </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <input name="month" type="month" class="form-control" id="monthid" placeholder="{{old('month')}}">
                                        @error('month')
                                        <div class="text-danger">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                    <div style="padding-bottom: -20px" class="col-sm-4">
                                        <input type="submit" name="" class="btn btn-dark" value="Get a specific month">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                    <!-- Nav pills -->
                <div>
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="pill" href="#not_seen">No response</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#declined">Declined</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#accepted">Accepted</a>
                        </li>
                    </ul>
                </div>
                <br>
                <div class="tab-content">
                    <div class="tab-pane container active" id="not_seen">
                        <div class="row">
                            <div class="col-lg-12 mx-auto">
                                <table class="table table-bordered table-hover" id="list_leaves1">
                                    <thead class="table-info">
                                    <tr>
                                        <th style="width: 5%; text-align: center;">#</th>
                                        <th style="width: 20%; text-align: center;">Month</th>
                                        <th style="width: 15%; text-align: center;">Multiple Day?</th>
                                        <th style="width: 0; text-align: center;">From date</th>
                                        <th style="width: 0; text-align: center;">To date</th>
                                        <th style="width: 0; text-align: center;">Date</th>
                                        <th style="width: 0; text-align: center;">Status</th>
                                        <th style="width: 15%; text-align: center;">Detail</th>

                                        <!--- Detail --->
                                        <th style="display: none">Format</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @for ($i = 0; $i < count($not_seen); $i++)
                                        <tr>
                                            <td style='text-align: center; vertical-align: middle'> {{$i + 1}} </td>
                                            <td style='text-align: center; vertical-align: middle'> {{$not_seen[$i]->month}} </td>
                                            <td style='text-align: center; vertical-align: middle'> {{$not_seen[$i]->multidays}} </td>

                                            @if ($not_seen[$i]->multidays == 'Yes')
                                                <td style='text-align: center; vertical-align: middle'> {{\Carbon\Carbon::parse($not_seen[$i]->start_leave_day)->format("d-m")}} </td>
                                                <td style='text-align: center; vertical-align: middle'> {{\Carbon\Carbon::parse($not_seen[$i]->end_leave_day)->format("d-m")}} </td>
                                                <td style='text-align: center; vertical-align: middle; background-color: silver'></td>
                                            @else
                                                <td style='text-align: center; vertical-align: middle; background-color: silver'></td>
                                                <td style='text-align: center; vertical-align: middle; background-color: silver'></td>
                                                <td style='text-align: center; vertical-align: middle'> {{\Carbon\Carbon::parse($not_seen[$i]->leave_day)->format("d-m")}} </td>
                                            @endif

                                            <td style='text-align: center; vertical-align: middle'>
                                                <h5><span class="badge badge-pill badge-info"> Not seen </span> </h5>
                                            </td>

                                            <td style='text-align: center; vertical-align: middle'>
                                                <button id="{{$i}}" class="btn btn-outline-info">
                                                    <i class="fas fa-info-circle"></i> Detail
                                                </button>
                                            </td>

                                            <!--- Detail --->
                                            <td style="display: none">{{$not_seen[$i]->format_leave()->first()->format}}</td>
                                        </tr>
                                    @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <div class="tab-pane container fade" id="declined">
                        <div class="row">
                            <div class="col-lg-12 ml-auto">
                                <table class="table table-bordered table-hover" id="list_leaves2">
                                    <thead class="table-danger">
                                    <tr>
                                        <th style="width: 5%; text-align: center;">#</th>
                                        <th style="width: 20%; text-align: center;">Month</th>
                                        <th style="width: 15%; text-align: center;">Multiple Day?</th>
                                        <th style="width: 0; text-align: center;">From date</th>
                                        <th style="width: 0; text-align: center;">To date</th>
                                        <th style="width: 0; text-align: center;">Date</th>
                                        <th style="width: 0; text-align: center;">Status</th>
                                        <th style="width: 15%; text-align: center;">Detail</th>

                                        <!--- Detail --->
                                        <th style="display: none">Format</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @for ($i = 0; $i < count($declined); $i++)
                                        <tr>
                                            <td style='text-align: center; vertical-align: middle'> {{$i + 1}} </td>
                                            <td style='text-align: center; vertical-align: middle'> {{$declined[$i]->month}} </td>
                                            <td style='text-align: center; vertical-align: middle'> {{$declined[$i]->multidays}} </td>

                                            @if ($declined[$i]->multidays == 'Yes')
                                                <td style='text-align: center; vertical-align: middle'> {{\Carbon\Carbon::parse($declined[$i]->start_leave_day)->format("d-m")}} </td>
                                                <td style='text-align: center; vertical-align: middle'> {{\Carbon\Carbon::parse($declined[$i]->end_leave_day)->format("d-m")}} </td>
                                                <td style='text-align: center; vertical-align: middle; background-color: silver'></td>
                                            @else
                                                <td style='text-align: center; vertical-align: middle; background-color: silver'></td>
                                                <td style='text-align: center; vertical-align: middle; background-color: silver'></td>
                                                <td style='text-align: center; vertical-align: middle'> {{\Carbon\Carbon::parse($declined[$i]->leave_day)->format("d-m")}} </td>
                                            @endif

                                            <td style='text-align: center; vertical-align: middle'>
                                                <h5><span class="badge badge-pill badge-danger"> Declined </span> </h5>
                                            </td>

                                            <td style='text-align: center; vertical-align: middle'>
                                                <button id="{{$i+1000}}" class="btn btn-outline-danger">
                                                    <i class="fas fa-info-circle"></i> Detail
                                                </button>
                                            </td>

                                            <!--- Detail --->
                                            <td style="display: none">{{$declined[$i]->format_leave()->first()->format}}</td>
                                        </tr>
                                    @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane container fade" id="accepted">
                        <div class="row">
                            <div class="col-lg-12 mx-auto">
                                <table class="table table-bordered table-hover" id="list_leaves3">
                                    <thead class="table-success">
                                    <tr>
                                        <th style="width: 5%; text-align: center;">#</th>
                                        <th style="width: 20%; text-align: center;">Month</th>
                                        <th style="width: 15%; text-align: center;">Multiple Day?</th>
                                        <th style="width: 0; text-align: center;">From date</th>
                                        <th style="width: 0; text-align: center;">To date</th>
                                        <th style="width: 0; text-align: center;">Date</th>
                                        <th style="width: 0; text-align: center;">Status</th>
                                        <th style="width: 15%; text-align: center;">Detail</th>

                                        <!--- Detail --->
                                        <th style="display: none">Format</th>
{{--                                        <th style="display: none">Reason</th>--}}
{{--                                        <th style="display: none">Description</th>--}}
{{--                                        <th style="display: none">Name</th>--}}
{{--                                        <th style="display: none">Id</th>--}}
{{--                                        <th style="display: none">Department</th>--}}
{{--                                        <th style="display: none">Position</th>--}}
{{--                                        <th style="display: none">Number_leaveday</th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @for ($i = 0; $i < count($accepted); $i++)
                                        <tr>
                                            <td style='text-align: center; vertical-align: middle'> {{$i + 1}} </td>
                                            <td style='text-align: center; vertical-align: middle'> {{$accepted[$i]->month}} </td>
                                            <td style='text-align: center; vertical-align: middle'> {{$accepted[$i]->multidays}} </td>

                                            @if ($accepted[$i]->multidays == 'Yes')
                                                <td style='text-align: center; vertical-align: middle'> {{\Carbon\Carbon::parse($accepted[$i]->start_leave_day)->format("d-m")}} </td>
                                                <td style='text-align: center; vertical-align: middle'> {{\Carbon\Carbon::parse($accepted[$i]->end_leave_day)->format("d-m")}} </td>
                                                <td style='text-align: center; vertical-align: middle; background-color: silver'></td>
                                            @else
                                                <td style='text-align: center; vertical-align: middle; background-color: silver'></td>
                                                <td style='text-align: center; vertical-align: middle; background-color: silver'></td>
                                                <td style='text-align: center; vertical-align: middle'> {{\Carbon\Carbon::parse($accepted[$i]->leave_day)->format("d-m")}} </td>
                                            @endif

                                            <td style='text-align: center; vertical-align: middle'>
                                                <h5><span class="badge badge-pill badge-success"> Accepted </span> </h5>
                                            </td>

                                            <td style='text-align: center; vertical-align: middle'>
                                                <button id="{{$i+10000}}" class="btn btn-outline-success">
                                                    <i class="fas fa-info-circle"></i> Detail
                                                </button>
                                            </td>

                                            <!--- Detail --->
                                            <td style="display: none">{{$accepted[$i]->format_leave()->first()->format}}</td>
                                        </tr>
                                    @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

    {{--    source: https://datatables.net/examples/api/row_details.html --}}
    <script>
        $('#specific-month').on('click', function(){

            if ($('#month-form').is(":visible")) {
                $('#month-form').css("display", "none");
            } else {
                $('#month-form').css("display", "block");
            }
        });
    </script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

    <script>
        /* Formatting function for row details - modify as you need */
        function format(d ) {
            let f = d.format.replace(/&gt;/g, '>').replace(/&lt;/g, '<');
            let my_format =
                '<div style="-moz-box-shadow:    inset 0 0 10px #000000;-webkit-box-shadow: inset 0 0 10px #000000;box-shadow: inset 0 0 10px #000000;" class="card">'
                + f
                +'</div>';
            return my_format;
        }

        $(document).ready(function() {

             let table = $('#list_leaves2, #list_leaves1, #list_leaves3').DataTable({
                "responsive": false,
                "bAutoWidth": false,

                "bPaginate": true,
                "bLengthChange": true,
                "bInfo": true,
                "lengthMenu": [10, 20, 50, 100, 200, 500],
                "columns": [

                    {"data": "#"},
                    {"data": "month"},
                    {"data": "multipledays"},
                    {"data": "from"},
                    {"data": "to"},
                    {"data": "date"},
                    {"data": "status"},
                    {
                        "className": 'details-control',
                        "orderable": false,
                        "data": 'detail',
                        "defaultContent": ''
                    },
                    {"data": "format",  "visible": false},
                    // {"data": "reason",  "visible": false},
                    // {"data": "description",  "visible": false},
                    // {"data": "name",  "visible": false},
                    // {"data": "id",  "visible": false},
                    // {"data": "department",  "visible": false},
                    // {"data": "position",  "visible": false},
                    // {"data": "number",  "visible": false},
                ],
                "order": [[1, 'asc']]
            });

            // Add event listener for opening and closing details
            $('tbody button').on('click', function (e) {
                let idClicked = '#'+e.target.id;
                console.log(idClicked);
                let tr = $(idClicked).parent().parent();
                let row = table.row(tr);
                if (row.child.isShown()) {
                    //$(this).find('#button-'+index).replaceWith(`<span id="button-${index}"><i class="fas fa-plus-circle " style="padding-top:6px;color:blue"></i></span>`);
                    // $('#button').replaceWith('<span id="button"><i class="fas fa-plus-circle " style="color:green"></i></span>');
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    //$(this).find('#button-'+index).replaceWith(`<span id="button-${index}"><i class="fas fa-minus-circle " style="padding-top:6px;color:red"></i></span>`);
                    // $('#button').replaceWith('<span id="button"><i class="fas fa-minus-circle " style="color:red"></i></span>');
                    row.child(format(row.data())).show();
                    tr.addClass('shown');
                }
            });
        });




    </script>

    <!---- Format for form ---->
    <script>
        //         '<div style="padding-top: 20px" class="card-header bg-outline-secondary">' +
        //             '<div class="card-title"' +
        //                 '<h1 style="font-size: 20px"> Dear <span style="color: orangered; font-weight: bold;">' + d.toadmin +',</span></h1>' +
        //             '</div>' +
        //         '</div>' +
        //         '<div class="card-body text-dark">' +
        //             '<p style="color:black">' +
        //                 'My name is <span style="font-weight: bold;"> ' + d.name + '</span> , has employee id:<strong> ' + d.id + '</strong> , is a <span style="font-weight: bold;"> '+d.position+'</span> of department <span style="font-weight: bold;">' + d.department + '</span>.' +
        //             '</p>' +
        //             '<p style="color:black">';
        // let random1 = Math.random();
        // if (random1 < 1/4)
        //     my_format += 'I  would like to ask permission for ';
        // else if (random1 < 2/4)
        //     my_format += 'I am writing to request your approval for ';
        // else if (random1 < 3/4)
        //     my_format += 'This letter concerns my request for ';
        // else
        //     my_format += 'I am writing this letter to let you know about my requirement for ';
        //
        // if (parseInt(d.number) <= 7) {
        //     my_format += digit_to_char[d.number];
        // } else {
        //     my_format = my_format + d.number + 'days';
        // }
        // my_format += ' off from work ';
        // if (d.multipledays === 'Yes') {
        //     my_format += 'from <span style="font-weight: bold;">' + d.from + ' </span> to <span style="font-weight: bold;"> ' + d.to + '</span> because ' + d.reason;
        // } else {
        //     my_format += 'on <span style="font-weight: bold;">' + d.date + '</span> because ' + d.reason;
        // }
        // my_format+= '</p>' +
        //             '<p style="color:black">' +
        //                 'In description, ' + d.description +
        //             '</p>' +
        //         '</div>' +
        //         '<div class="card-footer">';
        //
        // let random2 = Math.random();
        // if (random2 < 1/3) {
        //     my_format += '<p style="color:black">' +
        //         'Thank you very much for your consideration.' +
        //         '</p>' +
        //         '<p style="color:black">' +
        //         'With kind regards,' +
        //         '</p>';
        // } else if (random2 < 2/3) {
        //     my_format += '<p style="color:black">' +
        //         'Hoping for your kind consideration.' +
        //         '</p>' +
        //         '<p style="color:black">' +
        //         'Sincerely Yours,' +
        //         '</p>';
        // } else {
        //     my_format += '<p style="color:black">' +
        //         'Thank you and regards,' +
        //         '</p>';
        // }
        // my_format += '<p style="color:black">' +
        //                 '<span style="font-weight: bold;">' + d.name + '</span' +
        //             '</p>' +
        //         '</div>' +
        //     '</div>';
    </script>
@endsection



