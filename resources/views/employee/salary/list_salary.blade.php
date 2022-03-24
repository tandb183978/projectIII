@extends('layouts.app')

@section('title', 'employee')

@section('css_external_link')
    <!-- DataTables -->
    <link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.css"/>
    {{--    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css"/>--}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css"/>

@endsection

@section('app-content')
    <!--- Content header --->
    @include('include.app.content_header', ['name' => 'Salary History', 'pre'=>'Employee', 'cur'=>'salary'])
    <!--- Main content --->
    <br>

    <div class="container-fluid">
        <div class="card">
            <div style="padding-top: 20px" class="card-header bg-info">
                <div class="card-title">
                    <h5>
                        <strong> Salary History
                            @if ($get_all)
                            {{-- --}}
                            @else
                                @if ($start_month == $end_month)
                                    @if ($start_month == \Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format("m-Y"))
                                        in this month
                                    @else
                                        in {{$start_month}}
                                    @endif
                                @else
                                    from {{$start_month}} to {{$end_month}}
                                @endif
                            @endif
                        </strong>
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <form action="{{route('employee.salary.get_range')}}" method="post">
                            {{csrf_field()}}
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <label style="margin-top: 5px" for="start_monthid"><strong>From month: </strong> </label>
                                </div>
                                <div class="col-sm-4">
                                    <input name="start_month" type="month" class="form-control" id="start_monthid" placeholder="{{old('start_month')}}">
                                    @error('start_month')
                                    <div class="text-danger">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div style="padding-bottom: -20px" class="col-sm-4">
                                    <input type="submit" name="" class="btn btn-dark" value="Submit">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="end_monthid" class="col-sm-2 col-form-label"><strong>To month: </strong> </label>
                                <div class="col-sm-4">
                                    <input name="end_month" type="month" class="form-control" id="end_monthid" placeholder="{{old('end_month')}}">
                                    @error('end_month')
                                    <div class="text-danger">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 mx-auto">
                        <table class="table table-bordered table-hover" id="list_salaries">
                            <thead class="table-dark">
                            <tr>
                                <th style="width: 5%; text-align: center;">#</th>
                                <th style="width: 20%; text-align: center;">Month</th>
                                <th style="width: 20%; text-align: center;">Allowance</th>
                                <th style="width: 15%; text-align: center;">Subsidy</th>
                                <th style="width: 15%; text-align: center;">Insurance</th>
                                <th style="width: 15%; text-align: center;">Salary</th>

                                <th style="width: 0; text-align: center;"></th>

                                <!--- Detail --->
                                <th style="display: none">Number of dayon</th>
                                <th style="display: none">Number of dayoff</th>
                                <th style="display: none">Number of overtimehours</th>
                                <th style="display: none">Number of undertimehours</th>
                                <th style="display: none">Number days of leave</th>
                                <th style="display: none">Base salary</th>
                                <th style="display: none">Number of max leaves</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($salaries as $salary)
                                <tr>
                                    <td style='text-align: center; vertical-align: middle'> {{$loop->index + 1}} </td>
                                    <td style='text-align: center; vertical-align: middle'> {{\Carbon\Carbon::parse($salary->month)->format('m - Y')}} </td>
                                    <td style='text-align: center; vertical-align: middle'> {{$salary->allowance}} </td>
                                    <td style='text-align: center; vertical-align: middle'> {{$salary->subsidy}} </td>
                                    <td style='text-align: center; vertical-align: middle'> {{$salary->insurance}} </td>
                                    <td style='text-align: center; vertical-align: middle'> {{$salary->take_home_pay}} </td>
                                    <td style='text-align: center; vertical-align: middle'>
                                        <input id="detail-{{$loop->index+1}}" type="button" style="width: 100px" class="btn btn-info" value="Detail">
                                    </td>

                                    <td> {{$salary->info_detail->number_dayon}} </td>
                                    <td> {{$salary->info_detail->number_dayoff}} </td>
                                    <td> {{round($salary->info_detail->overtime_workings, 2)}} </td>
                                    <td> {{round($salary->info_detail->undertime_workings, 2)}} </td>
                                    <td> {{$salary->info_detail->number_dayleft}} </td>
                                    <td> {{$salary->employee->base_salary}} </td>
                                    <td> {{$salary->employee->max_leaves}} </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

    <script>
        /* Formatting function for row details - modify as you need */
        function format(d ) {
            // `d` is the original data object for the row
            // let overtime = parseFloat(d.overtime);

            let this_format = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
                '<tr>' +
                '<td><strong>Month</strong></td>' +
                '<td>' + d.month + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td><strong>Number of day on</strong></td>' +
                '<td>' + d.dayon + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td><strong>Number of day on</strong></td>' +
                '<td>' + d.dayoff + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td><strong>Number of overtime hours</strong> </td>' +
                '<td>' + d.overtime + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td><strong> Number of undertime hours </strong></td>' +
                '<td>'+ d.undertime + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td><strong> Number of leaves</strong> </td>' +
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
                '<td><strong> Base Salary</strong> </td>' +
                '<td>' + d.base + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td><strong> Number of maximum leaves</strong> </td>' +
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
                    {"data": "allowance"},
                    {"data": "subsidy"},
                    {"data": "insurance"},
                    {"data": "salary"},
                    {
                        "className": 'details-control',
                        "orderable": false,
                        "data": 'action',
                        "defaultContent": ''
                    },
                    {"data": "dayon",  "visible": false},
                    {"data": "dayoff",  "visible": false},
                    {"data": "overtime",  "visible": false},
                    {"data": "undertime",  "visible": false},
                    {"data": "dayleave",  "visible": false},
                    {"data": "base",  "visible": false},
                    {"data": "maxleaves",  "visible": false},
                ],
                "order": [[1, 'asc']]
            });

            // Add event listener for opening and closing details
            $('input').on('click', function (e) {
                let idClicked = '#'+e.target.id;
                let tr = $(idClicked).parent().parent();
                let row = table.row(tr);
                console.log(idClicked)
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

