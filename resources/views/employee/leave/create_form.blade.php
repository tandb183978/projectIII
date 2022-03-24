@extends('layouts.app')

@section('title', 'admin')

@section('css_external_link')
@endsection

@section('app-content')
    @include('include.app.content_header', ['name' => 'Create form for leave', 'pre' => 'Employee', 'cur' => 'Create form'])
    <br>
    <div style=" background-image: url('https://media.istockphoto.com/photos/sun-and-cloud-background-with-a-pastel-colored-picture-id1160134500?k=6&m=1160134500&s=612x612&w=0&h=qeu9YTz5U3gMPNwgVEuSIezlj-oie5ZFECrx73ZFjsM='); background-size: 1500px!important; background-repeat: no-repeat; margin-left: 40px; " class="container">
        <div class="row">
            <div class="col-lg-10 mr-auto">
                <div style="margin-left: -15px; background-color: lightseagreen;" class="card">
                    <form method="post" action="{{route('employee.leave.store_form')}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div style="margin-left: 10px;" class="card-body">
                            <div class="form-group row">
                                <label for="toAdminid" class="col-sm-3 col-form-label"><h5><strong>To: </strong> </h5></label>
                                <div class="col-sm-8">
                                    <input name="toAdmin" type="text" class="form-control" id="toAdminid" placeholder="Fill your reason..." value="{{-- Điền email admin vào đây --}}admin@gmail.com">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="reasonid" class="col-sm-3 col-form-label"><h5><strong>Reason</strong> </h5></label>
                                <div class="col-sm-8">
                                    <input name="reason" type="text" class="form-control" id="reasonid" placeholder="Fill your reason..." value="{{old('reason')}}">
                                    @error('reason')
                                    <div class="text-danger">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="descriptionid" class="col-sm-3 col-form-label"><h5><strong>Description</strong></h5></label>
                                <div class="col-sm-8">
                                    <textarea name="description" type="text" style="height: 200px !important; vertical-align: top;" class="form-control" id="descriptionid"
                                              placeholder="Fill your description..." value="{{old('description')}}"></textarea>
                                    @error('description')
                                    <div class="text-danger">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="multipledaysid" class="col-sm-3 col-form-label"><h5><strong> Multiple days</strong></h5> </label>
                                <div class="col-sm-8">
                                    <select id="multipledaysid" class="form-control" name="multipledays">
                                        <option value=""> -- Select your option --</option>
                                        <option value="Yes"> Yes </option>
                                        <option value="No"> No </option>
                                    </select>
                                    @error('multipledays')
                                    <div class="text-danger">
                                        Please select an option
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div style="display: none" class="form-group row " id="start_leave_day">
                                <label for="start_leave_dayid" class="col-sm-3 col-form-label"><h5><strong>From date</strong></h5></label>
                                <span style="margin-left: -3px;" class="col-sm-8">
                                    <input name="start_leave_day" type="date" class="form-control" id="start_leave_dayid"
                                           value="{{old('start_leave_day')}}">
                                </span>
                                @error('start_leave_day')
                                    <div style="margin-left:245px" class="text-danger">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>

                            <div style="display: none;" class="form-group row" id="end_leave_day">
                                <label for="end_leave_dayid" class="col-sm-3 col-form-label"><h5><strong>To date</strong></h5></label>
                                <span style="margin-left: -3px;" class="col-sm-8">
                                    <input name="end_leave_day" type="date" class="form-control" id="end_leave_dayid"
                                           value="{{old('end_leave_day')}}">
                                </span>
                                @error('end_leave_day')
                                    <div style="margin-left:245px" class="text-danger">
                                        {{$message}}
                                    </div>
                                @enderror
                            </div>

                            <div style="display: none" class="form-group row" id="leave_day">
                                <label for="leave_dayid" class="col-sm-3 col-form-label"><h5><strong>Date</strong></h5></label>
                                <span style="margin-left: -3px;" class="col-sm-8">
                                    <input name="leave_day" type="date" class="form-control" id="leave_dayid"
                                           value="{{old('leave_day')}}">
                                </span>
                                @error('leave_day')
                                <div style="margin-left:245px" class="text-danger">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>

                        <div class="card-footer text-center">
                            <div class="form-group row">
                                <div style="margin-left:20%" class="offset-sm-3 col-sm-8">
                                    <button type="submit" class="btn btn-danger"><h5>Create</h5></button>
                                </div>
                            </div>
                        </div>
                        @if(session('message'))
                            <div class="text-danger">
                                <strong>{{session()->get('message')}}</strong>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function(){

            let multipleDay = '{{session()->get('multipleDay')??'null'}}';
            console.log(multipleDay);
            if (multipleDay == 'Yes'){
                $('#leave_day').css('display', 'none');
                $('#start_leave_day').css('display', 'block');
                $('#end_leave_day').css('display', 'block');
            } else if (multipleDay == 'No') {
                $('#leave_day').css('display', 'block');
                $('#start_leave_day').css('display', 'none');
                $('#end_leave_day').css('display', 'none');
            }
            $("select.form-control").change(function(){
                var selectedMultipleDays = $(this).children("option:selected").val();
                if (selectedMultipleDays == 'Yes'){
                    $('#leave_day').css('display', 'none');
                    $('#start_leave_day').css('display', 'block');
                    $('#end_leave_day').css('display', 'block');
                } else if (selectedMultipleDays == 'No') {
                    $('#leave_day').css('display', 'block');
                    $('#start_leave_day').css('display', 'none');
                    $('#end_leave_day').css('display', 'none');
                } else {
                    $('#leave_day').css('display', 'none');
                    $('#start_leave_day').css('display', 'none');
                    $('#end_leave_day').css('display', 'none');
                }
            });
        });
    </script>
@endsection
