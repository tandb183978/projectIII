@extends('layouts.app')

@section('title', 'employee')

@section('css-external-link')

@endsection

@section('app-content')
    {{--        Profile for employee--}}
{{--    <button class="btn btn-secondary btn-round btn-block" data-toggle="modal" data-target=".animate" data-ui-class="a-roll"		    >Roll</button>--}}

    <!-- Modal -->
    <div class="modal animate " style="overflow-y: hidden; margin-left: -17%; position: fixed" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="true">
        <div  class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="width: 200%!important;">
                <div class="modal-header" style="background-color: darkgrey" >
                    <h5 class="modal-title text-center" id="exampleModalLabel">ACCOUNT INFORMATION</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <strong><span style="color: red">PERSONAL INFORMATION</span></strong>
                    <hr style="height: 3px; background-color: orange">
                    <div class="row">
                        <div class="col-lg-4 text-center" style="">
                            <img class="" style="display: block; vertical-align: middle;  max-width: 100%;" src="{{\Illuminate\Support\Facades\Storage::url($employee->image_profile)}}" alt="user_image">
                        </div>
                        <div class="col-lg-6">
                                <p><strong style="font-weight: bold">Họ và tên: </strong> {{$user->name}}</p>
                                <p><strong style="font-weight: bold">Email: </strong> {{$user->email}}</p>
                                <p><strong style="font-weight: bold">Giới tính: </strong> {{($employee->gender=='female')?'Nữ':'Nam'}}</p>
                                <p><strong style="font-weight: bold">Ngày sinh: </strong> {{\Carbon\Carbon::parse($employee->birth_day)->format('d-m-Y')}}</p>
                                <p><strong style="font-weight: bold">Số điện thoại: </strong> {{$employee->phone}}</p>
                                <p><strong style="font-weight: bold">Địa chỉ: </strong> {{$employee->address}}</p>
                                <p><strong style="font-weight: bold">Quốc tịch: </strong> {{$employee->country}}</p>
                        </div>
                    </div>
                    <strong><span style="color: red">COMPANY INFORMATION</span></strong>
                    <hr style="height: 3px; background-color: orange">
                    <div class="row">
                        <div class="col-sm-10">
                            <p><strong style="font-weight: bold">Phòng công tác: </strong> {{$employee->department}}</p>
                            <p><strong style="font-weight: bold">Vị trí: </strong> {{$employee->position}}</p>
                            <p><strong style="font-weight: bold">Lương cơ bản: </strong> {{$base_salary}} VND</p>
                            <p><strong style="font-weight: bold">Lương tháng hiện tại: </strong> {{$pre_salary}} VND</p>
                        </div>
                    </div>

                </div>
                <div class="modal-footer" style="background-color: #718096" >
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

        <!--- Content Header --->
    @include('include.app.content_header', ['name' => 'Employee Page', 'pre' => 'Employee', 'cur' => 'Page'])
        <!--- Main content --->
        <section class="content">
            <div class="container-fluid">
                <row class="">
                    <div class="col-md-8 mx-auto" style="margin-top: 50px">
                        <div class="jumbotron">
                            <h1 class="display-4 text-success">Welcome to Employee Interface</h1>
                            <p class="lead">This is employee management application.</p>
                            <hr class="my-4">
                            <p></p>
                        </div>
                    </div>
                </row>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
        <!-- /.content-wrapper -->
@endsection

@section('js')
    <script>
        $(function(){
            $('[role=dialog]')
                .on('show.bs.modal', function(e) {
                    $(this)
                        .find('[role=document]')
                        .removeClass()
                        .addClass('modal-dialog ' + $(e.relatedTarget).data('ui-class'))
                })
        })
    </script>
@endsection
