@extends('layouts.app')

@section('title', 'employee')

@section('css-external-link')
@endsection

@section('app-content')
    @include('include.app.content_header', ['name' => 'Update fees for '.$name. ' in '.explode('-', $month)[1].'-'.explode('-', $month)[0], 'pre' => 'Admin', 'cur' => 'Update fee'])

    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-11 ">
                <div class="card">
                    <form method="post" action="{{route('admin.salary.store_fee', [$salary_id, $title])}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div style="margin-left: 5%;" class="card-body">
                            <div class="form-group row">
                                <label for="monthid" class="col-sm-3 col-form-label"><strong>Month</strong> </label>
                                <div class="col-sm-8">
                                    <input name="" type="text" class="form-control" id="monthid" value="{{$month}}" disabled>
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
                        <div class="card-footer text-center">
                            <div class="form-group row">
                                <div style="margin-left:20%" class="offset-sm-3 col-sm-7">
                                    <button type="submit" class="btn btn-primary">Update</button>
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
{{--    <script>--}}
{{--        $('#imageprofileid').on('change',function(){--}}
{{--            //get the file name--}}
{{--            let splitPath = $(this).val().split('\\');--}}
{{--            let fileName = splitPath[splitPath.length-1];--}}
{{--            //replace the "Choose a file" label--}}
{{--            $(this).next('.custom-file-label').html(fileName);--}}
{{--        })--}}
{{--    </script>--}}
@endsection
