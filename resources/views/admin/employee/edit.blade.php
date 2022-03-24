@extends('layouts.app')

@section('title', 'employee')

@section('css-external-link')
@endsection

@section('app-content')
    @include('include.app.content_header', ['name' => 'Edit Profile Employee '.$employee->id, 'pre' => 'Admin', 'cur' => 'Edit employee'])

    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-11 ">
                <div class="card">
                    <form method="post" action="{{route('admin.employee.edit', ['employee_id' => $employee->id])}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div style="margin-left: 5%;" class="card-body">
                            <div class="form-group row">
                                <label for="firstnameid" class="col-sm-3 col-form-label"><strong>First Name</strong> </label>
                                <div class="col-sm-8">
                                    <input name="firstname" type="text" class="form-control" id="firstnameid" value="{{$employee->first_name}}">
                                    @error('firstname')
                                    <div class="text-danger">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="lastnameid" class="col-sm-3 col-form-label"><strong>Last Name</strong></label>
                                <div class="col-sm-8">
                                    <input name="lastname" type="text" class="form-control" id="lastnameid"
                                           value="{{$employee->last_name}}">
                                    @error('lastname')
                                    <div class="text-danger">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="dobid" class="col-sm-3 col-form-label"><strong>Date of birth</strong></label>
                                <div class="col-sm-8">
                                    <input name="dob" type="date" class="form-control" id="dobid"
                                           value="{{\Carbon\Carbon::parse($employee->birth_day)->format('Y-m-d')}}">
                                    @error('dob')
                                    <div class="text-danger">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="emailid" class="col-sm-3 col-form-label"><strong>Email</strong> </label>
                                <div class="col-sm-8">
                                    <input name="email" type="text" class="form-control" id="emailid"
                                           value="{{$employee->user->email}}">
                                    @error('email')
                                    <div class="text-danger">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="genderid" class="col-sm-3 col-form-label"><strong> Gender</strong> </label>
                                <div class="col-sm-8">
                                    <select id="genderid" class="form-control" name="gender">
                                        <option value="{{$employee->gender}}"> {{ucfirst($employee->gender)}}</option>      <!---- Xử lý sau -->
                                        @if ($employee->gender === 'male'){
                                            <option value="female"> Female </option>
                                        }
                                        @else
                                            <option value="male"> Male </option>
                                        @endif
                                        <option value="other"> Other </option>
                                    </select>
                                    @error('gender')
                                    <div class="text-danger">
                                        Please select a gender
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phoneid" class="col-sm-3 col-form-label"> <strong>Phone</strong> </label>
                                <div class="col-sm-8">
                                    <input name="phone" type="text" class="form-control" id="phoneid"
                                           value="{{$employee->phone}}">
                                    @error('phone')
                                    <div class="text-danger">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="departmentid" class="col-sm-3 col-form-label"> <strong>Department</strong> </label>
                                <div class="col-sm-8">
                                    <select id="departmentid" class="form-control" name="department">
                                        <option value="{{$employee->department}}"> {{$employee->department}}</option>
                                        @foreach(['Marketing', 'Production', 'Purchase', 'Account and Finance', 'Human Resource Management', 'Research and Development'] as $department)
                                            @if ($department != $employee->department)
                                                <option value="{{$department}}"> {{$department}} </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('department')
                                    <div class="text-danger">
                                        Please select a department
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="positionid" class="col-sm-3 col-form-label"> <strong>Position</strong> </label>
                                <div class="col-sm-8">
                                    <select id="positionid" class="form-control" name="position">
                                        <option value="{{$employee->position}}"> {{$employee->position}}</option>
                                        @foreach(['Staff', 'Deputy', 'Manager'] as $position)
                                            @if ($position != $employee->position)
                                                <option value="{{$position}}"> {{$position}} </option>
                                            @endif
                                        @endforeach
                                        <option value="Staff"> Staff </option>
                                        <option value="Deputy"> Deputy </option>
                                        <option value="Manager"> Manager </option>
                                    </select>
                                    @error('position')
                                    <div class="text-danger">
                                        Please select a position
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="addressid" class="col-sm-3 col-form-label"><strong> Address</strong> </label>
                                <div class="col-sm-8">
                                    <input name="address" type="text" class="form-control" id="addressid"
                                           value="{{$employee->address}}">
                                    @error('address')
                                    <div class="text-danger">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="countryid" class="col-sm-3 col-form-label"> <strong>Country</strong> </label>
                                <div class="col-sm-8">
                                    <input name="country" type="text" class="form-control" id="countryid"
                                           value="{{$employee->country}}">
                                    @error('country')
                                    <div class="text-danger">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="imageprofileid" class="col-sm-3 col-form-label"><strong>Employee Image</strong></label>
                                <div class="col-sm-8">
                                    <input name="imageprofile" type="file" id="imageprofileid" class="custom-file-input">
                                    <label style="margin-left: 15px; width: 364px" class="custom-file-label">Choose file...</label>
                                    @error('imageprofile')
                                    <div class="text-danger">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="passwordid" class="col-sm-3 col-form-label"> <strong>Password </strong></label>
                                <div class="col-sm-8">
                                    <input name="password" type="password" class="form-control" id="passwordid" placeholder="At least 8 character" value="{{old('password')}}">
                                    @error('password')
                                    <div class="text-danger">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="passwordconfirmid" class="col-sm-3 col-form-label"> <strong>Confirm Password</strong> </label>
                                <div class="col-sm-8">
                                    <input name="password_confirmation" type="password" class="form-control" id="passwordconfirmid" placeholder="At least 8 character" value="{{old('passwordconfirm')}}">
                                    @error('passwordconfirm')
                                    <div class="text-danger">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <div class="form-group row">
                                <div style="margin-left:20%" class="offset-sm-3 col-sm-7">
                                    <button type="submit" class="btn btn-primary">Submit</button>
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
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $('#imageprofileid').on('change',function(){
            //get the file name
            let splitPath = $(this).val().split('\\');
            let fileName = splitPath[splitPath.length-1];
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        })
    </script>
@endsection
