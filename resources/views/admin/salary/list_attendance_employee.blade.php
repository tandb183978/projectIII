@extends('layouts.app')

@section('title', 'admin')

@section('css_external_link')
    <!-- DataTables -->
    <link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.css"/>
{{--    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css"/>--}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css"/>
    <!-- Date range picker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.js"/>

@endsection

@section('app-content')
    <!--- Content header --->
    @include('include.app.content_header', ['name' => 'Attendance Employees', 'pre'=>'Admin', 'cur'=>'Attendance Employees'])
    <!--- Main content --->
    <br>

    <div class="container-fluid">
        <div class="card">
            <div style="padding-top: 20px" class="card-header bg-info">
                <div class="card-title">
                    <h5>
                        <strong> Attendance Employees
                            @if ($get_all)
                                at all
                            @else
                                @if ($start_date == $end_date)
                                    @if ($start_date == \Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format("d-m-Y"))
                                        on this day
                                    @else
                                        on {{$start_date}}
                                    @endif
                                @else
                                    from {{$start_date}} to {{$end_date}}
                                @endif
                            @endif
                        </strong>
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <form action="{{route('admin.salary.employee_attendance.get_range')}}" method="post">
                            {{csrf_field()}}
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <label style="margin-top: 5px" for="start_dateid"><strong>From date: </strong> </label>
                                </div>
                                <div class="col-sm-4">
                                    <input name="start_date" type="date" class="form-control" id="start_dateid" placeholder="{{old('start_date')}}">
                                    @error('start_date')
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
                                <label for="end_dateid" class="col-sm-2 col-form-label"><strong>To date: </strong> </label>
                                <div class="col-sm-4">
                                    <input name="end_date" type="date" class="form-control" id="end_dateid" placeholder="{{old('end_date')}}">
                                    @error('end_date')
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
                        <table class="table table-bordered table-hover" id="list_attendances">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 5%; text-align: center;">#</th>
                                    <th style="width: 20%; text-align: center;">Date</th>
                                    <th style="width: 20%; text-align: center;">Name</th>
                                    <th style="width: 15%; text-align: center;">Department</th>
                                    <th style="width: 15%; text-align: center;">Position</th>
                                    <th style="width: 15%; text-align: center;">Status</th>
                                    <th class="none">
                                        <span style="padding-left: 0"> Entry Record</span>
                                    </th>
                                    <th class="none">Exit Record</th>
                                    <th class="none">Working Hours</th>
                                    <th class="none">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < count($attendances); $i++)
                                    <tr>
                                        <td style='text-align: center; vertical-align: middle'> {{$i + 1}} </td>
                                        <td style='text-align: center; vertical-align: middle'> {{$attendances[$i]->date}} </td>
                                        <td style='text-align: center; vertical-align: middle'> {{$names[$i]}} </td>
                                        <td style='text-align: center; vertical-align: middle'> {{$departments[$i]}} </td>
                                        <td style='text-align: center; vertical-align: middle'> {{$positions[$i]}} </td>
                                        <td style='text-align: center; vertical-align: middle'>
                                            <h5>
                                                @if ($status[$i] == 'Absent')
                                                    <span class="badge badge-pill badge-danger"> Absent </span>
                                                @elseif ($status[$i] == 'Working')
                                                    <span class="badge badge-pill badge-info"> Working </span>
                                                @elseif ($status[$i] == 'Left (allowed)')
                                                    <span class="badge badge-pill badge-primary"> &nbsp;&nbsp;&nbsp;Left&nbsp;&nbsp;&nbsp; </span>
                                                @else
                                                    <span class="badge badge-pill badge-success"> Present </span>
                                                @endif

        {{--                                        <span id="button-{{$i+1}}" ><i class="fas fa-plus-circle " style="padding-top:6px;color:blue"></i></span>--}}
                                            </h5>
                                        </td>
                                        @if ($status[$i] == 'Absent')
                                            <td style='text-align: center; '>&nbsp;&nbsp;&ensp;&ensp;No record</td>
                                            <td style='text-align: center; '>&nbsp;&emsp;&ensp;&ensp;No record</td>
                                            <td style='text-align: center; '>&nbsp;&nbsp;&nbsp;00:00:00</td>
                                            <td style='text-align: center; '>&nbsp;&nbsp;&emsp;&ensp;&ensp;No detail</td>
                                        @elseif ($status[$i] == 'Working')
                                            <td style='text-align: center;' >&nbsp;&ensp;&ensp;{{\Carbon\Carbon::parse($attendances[$i]->entry_time)->format("H:i:s")}}</td>
                                            <td style='text-align: center;'>&nbsp;&emsp;&ensp;&ensp;No record</td>
                                            <td style='text-align: center;' >&nbsp;&nbsp;&nbsp;00:00:00</td>
                                            <td style='text-align: center; '>&nbsp;&nbsp;&emsp;&ensp;&ensp;Working</td>
                                        @elseif ($status[$i] == 'Left (allowed)')
                                            <td style='text-align: center;' >&nbsp;&ensp;&ensp;&nbsp;No record</td>
                                            <td style='text-align: center;'>&nbsp;&emsp;&ensp;&ensp;No record</td>
                                            <td style='text-align: center;' >&nbsp;&nbsp;&nbsp;00:00:00</td>
                                            <td style='text-align: center; '>&nbsp;&nbsp;&emsp;&ensp;&ensp;Left (allowed by admin@gmail.com)</td>
                                        @else
                                            <td style='text-align: center;'>&nbsp;&ensp;&ensp;&nbsp;{{\Carbon\Carbon::parse($attendances[$i]->entry_time)->format("H:i:s")}}</td>
                                            <td style='text-align: center; '>&nbsp;&emsp;&ensp;&ensp;{{\Carbon\Carbon::parse($attendances[$i]->exit_time)->format("H:i:s")}}</td>
                                            <td style='text-align: center; '>&nbsp;&nbsp;&nbsp;{{$attendances[$i]->working_hours}}</td>
                                            <td style='text-align: center; '>&nbsp;&nbsp;&emsp;&ensp;&ensp;{{($attendances[$i]->working_hours<8)?'Làm việc thiếu giờ':'Tốt'}}</td>

                                        @endif
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

        {{--    source: https://datatables.net/examples/api/row_details.html --}}
        <!-- DataTables -->
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#list_attendances').DataTable({
                    "responsive": true,
                    "bAutoWidth": true,
                });

            });
        </script>

@endsection

