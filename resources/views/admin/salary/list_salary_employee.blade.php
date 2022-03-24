@extends('layouts.app')

@section('title', 'admin')

@section('css_external_link')
    <!-- DataTables -->
    <link rel="stylesheet" href="/css/admin/update_fee.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css"/>

{{--    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">--}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">



    <!-- Date range picker -->
@endsection

@section('app-content')
    <!--- Content header --->
    @if ($title == 'all')
        @include('include.app.content_header', ['name' => 'Employees Salary', 'pre'=>'Admin', 'cur'=>'List salary'])
    @else
        @include('include.app.content_header', ['name' => 'Employees Salary in '.$title, 'pre'=>'Admin', 'cur'=>'List salary'])
    @endif

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
                                <form action="{{route('admin.salary.employee_salary_select')}}" method="post">
                                    @csrf
                                    <button name="all" type="submit" class="btn btn-info" value="all">Get all month</button>
                                </form>
                            </div>
                            <br>
                            <br>

                            <form id="month-form" style="{{count($errors) > 0 ? 'display: block' : 'display:none'}}" action="{{route('admin.salary.employee_salary_select')}}" method="post">
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

                            {{--                        <form id="all-month-form" style="display: none" action="{{route('admin.salary.employee_salary_select')}}" method="post">--}}
                            {{--                            {{csrf_field()}}--}}
                            {{--                            <div class="form-group row">--}}
                            {{--                                <div class="col-sm-4">--}}
                            {{--                                    <input name="all" type="submit" class="btn btn-dark" value="Get all">--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            {{--                        </form>--}}

                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <br>
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <table class="table table-bordered table-hover" id="list_salaries">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 5%; text-align: center;">#</th>
                                    <th style="width: 20%; text-align: center;">Month</th>
                                    <th style="width: 15%; text-align: center;">Name</th>
                                    <th style="width: 0; text-align: center;">Department</th>
                                    <th style="width: 0; text-align: center;">Position</th>
                                    <th style="width: 0; text-align: center;">Subsidy</th>
                                    <th style="width: 0; text-align: center;">Allowance</th>
                                    <th style="width: 0; text-align: center;">Insurance</th>
                                    <th style="width: 20%; text-align: center;">Salary</th>
                                    <th style="width: 0; text-align: center;">Action</th>

                                    <!--- Detail --->
                                    <th style="display: none">Email</th>
                                    <th style="display: none">Base Salary</th>
                                    <th style="display: none">Number of dayoff</th>
                                    <th style="display: none">Number of dayon</th>
                                    <th style="display: none">Number of dayleave</th>
                                    <th style="display: none">Number of overtimehours</th>
                                    <th style="display: none">Number of undertimehours</th>
                                    <th style="display: none">Number of max leaves</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < count($list_salary); $i++)
                                    <tr>
                                        <td style='text-align: center; vertical-align: middle'> {{$i + 1}} </td>
                                        <td style='text-align: center; vertical-align: middle'> {{$list_salary[$i]->month}} </td>
                                        <td style='vertical-align: middle'> {{$list_salary[$i]->name}} </td>
                                        <td style='vertical-align: middle'> {{$list_employee[$i]->department}} </td>
                                        <td style='vertical-align: middle'> {{$list_employee[$i]->position}} </td>
                                        <td style='text-align: center; vertical-align: middle'> {{$list_salary[$i]->subsidy??'null'}} </td>
                                        <td style='text-align: center; vertical-align: middle'> {{$list_salary[$i]->allowance??'null'}} </td>
                                        <td style='text-align: center; vertical-align: middle'> {{$list_salary[$i]->insurance??'null'}} </td>
                                        <td id="total_salary_{{$i+1}}" style='text-align: center; vertical-align: middle'> {{$list_salary[$i]->take_home_pay??'null'}} </td>
                                        <td style='text-align: center; vertical-align: middle'>
                                                                                        <!-- Dropdown menu action -->
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                    <i style="color: red" class="fas fa-bars"></i> &nbsp;Menu
                                                </button>
                                                <div style="width: 100px" class="dropdown-menu">
                                                    <input id="info-detail-{{$i+1}}" type="button" style="width: 100px" class="btn btn-outline-info" value="Detail">

                                                    <div style="margin-top:1px; margin-bottom: 1px !important;" class="dropdown-divider"></div>
{{--                                                    <a  style="width: 100px" class="btn btn-success" href="{{route('admin.salary.update_fee', [$list_salary[$i]->id, $title])}}">Update</a>--}}

                                                        <a style="width: 100px" class="btn btn-outline-danger" href="#update-fees-{{$i+1}}" data-toggle="modal">
                                                            Update
                                                        </a>


                                                    <div style="margin-top:1px; margin-bottom: 1px !important;" class="dropdown-divider"></div>
                                                    <a style="width: 100px" class="btn btn-outline-secondary" href="{{route('admin.salary.calculate_salary', [$list_salary[$i]->id, $title])}}">Calculate</a>
                                                    <div style="margin-top:1px; margin-bottom: 1px !important;" class="dropdown-divider"></div>
{{--                                                    <a style="width: 100px" class="btn btn-danger" href="#">Delete</a>--}}
{{--                                                    <div style="margin-top:1px; margin-bottom: 1px !important;" class="dropdown-divider"></div>--}}
                                                </div>

                                            <!-- Modal -->
                                            <div class="modal fade" id="update-fees-{{$i+1}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">Update fees for {{$list_employee[$i]->user->name}} in {{$title}}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <form method="post" id="form-update-fees-{{$i+1}}" action="{{route('admin.salary.store_fee', [$list_salary[$i]->id, $title])}}" enctype="multipart/form-data">
                                                                {{ csrf_field() }}
                                                                <div style="margin-left: 5%;" class="card-body">
                                                                    <div class="form-group row">
                                                                        <label for="monthid" class="col-sm-3 col-form-label"><strong>Month</strong> </label>
                                                                        <div class="col-sm-8">
                                                                            <input name="" type="text" class="form-control" id="monthid" value="{{$list_salary[$i]->month}}" disabled>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="subsidyid" class="col-sm-3 col-form-label"><strong>Subsidy</strong></label>
                                                                        <div class="col-sm-8">
                                                                            <input name="subsidy" type="text" class="form-control" id="subsidyid"
                                                                                   value="{{old('subsidy')}}">
                                                                            @error('subsidy')
                                                                            <div class="text-danger">
                                                                                {{$message}}
                                                                            </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="allowanceid" class="col-sm-3 col-form-label"><strong>Allowance</strong></label>
                                                                        <div class="col-sm-8">
                                                                            <input name="allowance" type="text" class="form-control" id="allowanceid"
                                                                                   value="{{old('allowance')}}">
                                                                            @error('allowance')
                                                                            <div class="text-danger">
                                                                                {{$message}}
                                                                            </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label for="insuranceid" class="col-sm-3 col-form-label"><strong>Insurance</strong> </label>
                                                                        <div class="col-sm-8">
                                                                            <input name="insurance" type="text" class="form-control" id="insuranceid"
                                                                                   value="{{old('insurance')}}">
                                                                            @error('insurance')
                                                                            <div class="text-danger">
                                                                                {{$message}}
                                                                            </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @if(session('message'))
                                                                    <div class="text-success">
                                                                        <strong>{{session()->get('message')}}</strong>
                                                                    </div>
                                                                @endif
                                                            </form>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary" form="form-update-fees-{{$i+1}}">Save changes</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </td>
                                        <!-- Detail -->
                                        <td>{{$list_employee[$i]->user()->first()->email}}</td>
                                        <td>{{$list_employee[$i]->base_salary}}</td>
                                        <td>{{$list_info_detail[$i]->number_dayoff}}</td>
                                        <td>{{$list_info_detail[$i]->number_dayon}}</td>
                                        <td>{{$list_info_detail[$i]->number_dayleft}}</td>
                                        <td>{{$list_info_detail[$i]->overtime_workings}}</td>
                                        <td>{{$list_info_detail[$i]->undertime_workings}}</td>
                                        <td>{{$list_employee[$i]->max_leaves}}</td>

                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-lg-12 d-flex">
                        <a class="btn btn-danger ml-auto" href="{{route('admin.salary.calculate_all_salary', $title)}}">Calculate all</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

        {{--    source: https://datatables.net/examples/api/row_details.html --}}
        <script>

            function mysubmit(){

            }

            $('#specific-month').on('click', function(){

                if ($('#month-form').is(":visible")) {
                    $('#month-form').css("display", "none");
                } else {
                    $('#month-form').css("display", "block");
                }
           });
        </script>
        <!-- DataTables -->
        <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

        <script>
            /* Formatting function for row details - modify as you need */
            $('.dropdown-menu').click(function(e) {
                e.stopPropagation();
                if ($(e.target).is('[data-toggle=modal]')) {
                    $($(e.target).data('target')).modal()
                }
            });

            function format(d ) {
                // `d` is the original data object for the row
                // let overtime = parseFloat(d.overtime);

                let this_format = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
                    '<tr>' +
                    '<td><strong>Email</strong></td>' +
                    '<td>' + d.email + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td><strong>Number of day on</strong></td>' +
                    '<td>' + d.dayon + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td><strong>Number of day off</strong></td>' +
                    '<td>' + d.dayoff + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td><strong>Overtime hours</strong> </td>' +
                    '<td>' + d.overtime + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td><strong>Undertime hours </strong></td>' +
                    '<td>'+ d.undertime + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td><strong>Number of leaves</strong> </td>' +
                    '<td>' + d.dayleave + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td><strong> Allowance</strong> </td>' +
                    '<td>' + d.allowance + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td><strong> Subsidy</strong> </td>' +
                    '<td>' + d.subsidy + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td><strong> Insurance</strong> </td>' +
                    '<td>' + d.insurance + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td><strong>Base salary</strong> </td>' +
                    '<td>' + d.base + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td><strong>Number of maximum leaves</strong> </td>' +
                    '<td>' + d.maxleaves + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td><strong>Total salary</strong> </td>' +
                    '<td>' + d.salary + '</td>' +
                    '</tr>'
                this_format += '</table>';
                return this_format;
            }

            $(document).ready(function() {

                let status_calculate = '{{$status_calculate??'null'}}';
                let name_fail_cal = '{{$name??'null'}}';
                if (status_calculate == 'true'){
                    window.alert('Calculate salary success');
                } else if (status_calculate == 'false') {
                    window.alert('There is a null field at employee '+name_fail_cal);
                }

                let table = $('#list_salaries').DataTable({
                    "responsive": false,
                    "bAutoWidth": true,

                    "bPaginate": true,
                    "bLengthChange": true,
                    "bInfo": true,
                    "lengthMenu": [10, 20, 50, 100, 200, 500],
                    "columns": [

                        {"data": "#"},
                        {"data": "month"},
                        {"data": "name"},
                        {"data": "department"},
                        {"data": "position"},
                        {"data": "subsidy"},
                        {"data": "allowance"},
                        {"data": "insurance"},
                        {"data": "salary"},
                        {
                            "className": 'details-control',
                            "orderable": false,
                            "data": 'action',
                            "defaultContent": ''
                        },
                        {"data": "email",  "visible": false},
                        {"data": "base",  "visible": false},
                        {"data": "dayoff",  "visible": false},
                        {"data": "dayon",  "visible": false},
                        {"data": "dayleave",  "visible": false},
                        {"data": "overtime",  "visible": false},
                        {"data": "undertime",  "visible": false},
                        {"data": "maxleaves",  "visible": false},
                    ],
                    "order": [[1, 'asc']]
                });

                // Add event listener for opening and closing details
                $('input').on('click', function (e) {
                    let idClicked = '#'+e.target.id;
                    let tr = $(idClicked).parent().parent().parent();
                    let row = table.row(tr);
                    let index = idClicked.slice(-1);
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
@endsection

