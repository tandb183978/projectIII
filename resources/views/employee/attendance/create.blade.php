@extends('layouts.app')

@section('title', 'employee')

@section('css-external-link')
@endsection

@section('app-content')
    <!-- Content header --->
    @include('include.app.content_header', ['name' => 'Attendance form', 'pre' => 'Attendance', 'cur' => 'Form'])
    <!-- Main content --->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <!---- Form: use card bootstrap ---->
                    <div class="card">
                        <div class="card-header bg-info">
                            <h3 class="card-title">Attendance</h3>
                        </div>

                        <div class="card-body">
                            <form method="post" action="{{route('employee.attendance.store.entry', $attendance->id)}}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <button type="submit" style="width: 100%;text-align: left" class="btn btn-primary" id="entryid"> Register for entry attendance: </button>
                                        </div>
                                        <div class="col-sm-8">
                                            @if ($status == 'Absent')
                                                <span class="btn text-danger">
                                                    <strong>You have no record for entry attendance today.</strong>
                                                </span>
                                            @elseif ($status == 'Left (allowed)')
                                                <span class="btn text-primary">
                                                    <strong>You have registered for leave today.</strong>
                                                </span>
                                            @else
                                                <span class="btn text-success">
                                                    <strong>You have registered for entry attendance today.</strong>
                                                </span>
                                            @endif
                                        </div>

                                    </div>
                            </form>
                            <form method="post" action="{{route('employee.attendance.store.exit', $attendance->id??'default')}}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                @method('PUT')
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <button type="submit" style="width: 100%;text-align: left" class="btn btn-primary" id="exitid"> Register for exit attendance: </button>
                                        </div>
                                        <div class="col-sm-8" >
                                            @if ($status == 'Left (allowed)')
                                                <span class="btn text-primary">
                                                    <strong>You have registered for leave today.</strong>
                                                </span>
                                            @elseif ($status != 'Present')
                                                <span class="btn text-danger">
                                                    <strong>You have no record for exit attendance today.</strong>
                                                </span>
                                            @else
                                                <span class="btn text-success">
                                                    <strong>You have registered for exit attendance today.</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                            </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

            <!--- Attendance today--->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-0">
                    <div class="col-sm-6">
                        <h4 style="line-height: 70px" class="m-0 text-dark">Attendance today </h4>
                    </div>
                </div>

                <table id="attendancetoday_id" style="width: 83%; border: 1px solid #ddd !important;" class="table table-bordered table-hover">
                    <thead class="thead-dark">
                    <tr>
                        <th style="width: 15%; text-align: center;">Entry Time</th>
                        <th style="width: 15%; text-align: center;">Exit Time</th>
                        <th style="width: 15%; text-align: center;">Status</th>
                    </tr>
                    </thead>

                    <tbody>
                        <tr>
                            @if ($status == 'Absent')
                                <td style='text-align: center; vertical-align: middle'> <h5><span class="badge badge-pill badge-warning">No record</span> </h5></td>
                                <td style='text-align: center; vertical-align: middle'> <h5><span class="badge badge-pill badge-warning">No record</span> </h5></td>
                                <td style='text-align: center; vertical-align: middle'> <h5><span class="badge badge-pill badge-danger">Absent</span></h5></td>
                            @elseif ($status == 'Left (allowed)')
                                <td style='text-align: center; vertical-align: middle'> <h5><span class="badge badge-pill badge-warning">Left</span> </h5></td>
                                <td style='text-align: center; vertical-align: middle'> <h5><span class="badge badge-pill badge-warning">Left</span> </h5></td>
                                <td style='text-align: center; vertical-align: middle'> <h5><span class="badge badge-pill badge-primary">Allowed</span></h5></td>
                            @else
                                <td style='text-align: center; vertical-align: middle'> {{$attendance->entry_time}} </h5></td>
                                @if ($status != 'Present')
                                    <td style='text-align: center; vertical-align: middle'> <h5><span class="badge badge-pill badge-warning">No record</span> </h5></td>
                                    <td style='text-align: center; vertical-align: middle'> <h5><span class="badge badge-pill badge-info">Working</span></h5></td>
                                @else
                                    <td style='text-align: center; vertical-align: middle'> {{$attendance->exit_time}}</td>
                                    <td style='text-align: center; vertical-align: middle'> <h5><span class="badge badge-pill badge-success">Present</span></h5></td>
                                @endif
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
@endsection

@section('js')
    <script>
            let status = '{{$status}}';
            if (status === 'Absent'){
                document.getElementById('exitid').disabled = true;
            } else if (status === 'Working'){
                document.getElementById('entryid').disabled = true;
            } else if (status === 'Present' || status === 'Left (allowed)') {
                document.getElementById('entryid').disabled = true;
                document.getElementById('exitid').disabled = true;
            }
    </script>
@endsection
