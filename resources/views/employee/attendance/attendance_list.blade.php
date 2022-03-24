@extends('layouts.app')

@section('title', 'employee')

@section('css_external_link')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
@endsection

@section('app-content')
    <!--- Content header --->
    @include('include.app.content_header', ['name' => 'Attendance History', 'pre'=>'Employee', 'cur'=>'attendance history'])
    <!--- Main content --->
    <br>

    <div class="container-fluid">
        <table id="list_attendances" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th style="width: 5%; text-align: center;">STT</th>
                <th style="width: 15%; text-align: center;">Date</th>
                <th style="width: 15%; text-align: center;">Entry Time</th>
                <th style="width: 15%; text-align: center;">Exit Time</th>
                <th style="width: 15%; text-align: center;">Status</th>
                <th style="width: 15%; text-align: center;">Working Hours</th>
            </tr>
            </thead>

            <tbody>
            @foreach($attendance_list as $index => $attendance)
                <tr>
                    <td style='text-align: center; vertical-align: middle'> {{$index + 1}} </td>
                    <td style='text-align: center; vertical-align: middle'> {{$attendance->date}} </td>
                    @if ($attendance->status == 'Present')
                        <td style='text-align: center; vertical-align: middle'> {{\Carbon\Carbon::parse($attendance->entry_time)->format('H:i:s')}} </td>
                        <td style='text-align: center; vertical-align: middle'> {{\Carbon\Carbon::parse($attendance->exit_time)->format('H:i:s')}} </td>
                        <td style='text-align: center; vertical-align: middle'><h5><span class="badge badge-pill badge-success">Present</span> </h5></td>
                        <td style='text-align: center; vertical-align: middle'> {{$attendance->working_hours}} </td>
                    @elseif ($attendance->status == 'Working')
                        <td style='text-align: center; vertical-align: middle'>{{\Carbon\Carbon::parse($attendance->entry_time)->format("H:i:s")}}</td>
                        <td style='text-align: center; vertical-align: middle'>
                            <h5><span class="badge badge-pill badge-warning">No record </span> </h5>
                        </td>
                        <td style='text-align: center; vertical-align: middle'>
                            <h5><span class="badge badge-pill badge-info">Working </span> </h5>
                        </td>
                        <td style='text-align: center; vertical-align: middle'>
                            <h5><span class="badge badge-pill badge-warning">No record </span> </h5>
                        </td>
                    @elseif ($attendance->status == 'Left (allowed)')
                        <td style='text-align: center; vertical-align: middle'>
                            <h5><span class="badge badge-pill badge-light">Left </span> </h5>
                        </td>
                        <td style='text-align: center; vertical-align: middle'>
                            <h5><span class="badge badge-pill badge-light">Left </span> </h5>
                        </td>
                        <td style='text-align: center; vertical-align: middle'>
                            <h5><span class="badge badge-pill badge-primary">Allowed </span> </h5>
                        </td>
                        <td style='text-align: center; vertical-align: middle'>00:00:00 </td>
                    @else
                        <td style='text-align: center; vertical-align: middle'>
                            <h5><span class="badge badge-pill badge-warning">No record </span> </h5>
                        </td>
                        <td style='text-align: center; vertical-align: middle'>
                            <h5><span class="badge badge-pill badge-warning">No record </span> </h5>
                        </td>
                        <td style='text-align: center; vertical-align: middle'>
                            <h5><span class="badge badge-pill badge-danger">Absent </span> </h5>
                        </td>
                        <td style='text-align: center; vertical-align: middle'>00:00:00 </td>

                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection

@section('js')

    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function(){
            $('#list_attendances').DataTable({
                "bPaginate": true,
                "bLengthChange": true,
                "bInfo": true,
                "bAutoWidth": true,
                "lengthMenu": [10, 20, 50, 100, 200, 500],
            });
        });


    </script>

@endsection

