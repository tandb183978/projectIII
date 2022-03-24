@extends('layouts.app')

@section('title', 'employee')

@section('css-internal-link')

@endsection

@section('app-content')
    @include('include.app.content_header', ['name' => 'Leave form of '.$name, 'pre' => 'Admin', 'cur' => 'Leave detail'])

    <br>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div style="-moz-box-shadow:    3px 3px 5px 6px #ccc;
                            -webkit-box-shadow: 3px 3px 5px 6px #ccc;
                            box-shadow:         3px 3px 5px 6px #ccc;">
                    {!! $format !!}
                </div>
            </div>
        </div>

        <br> <br>
        <div class="row">
{{--            <div class="col-lg-2 ml-auto">--}}
{{--                <h4><a href="#" class="btn btn-success btn-lg"> <i class="fas fa-check-circle"></i><strong>&nbsp;Accept</strong></a></h4>--}}
{{--            </div>--}}

{{--            <div class="col-lg-2">--}}
{{--                <a href="#" class="btn btn-danger btn-lg"> <i class="fas fa-times-circle"></i><strong>&nbsp;Decline</strong></a>--}}
{{--            </div>--}}
            @if ($leave->status == 'Not seen')
                <div class="col-lg-4 mr-auto">
                    <div class="text-danger">
                        <strong><span id="message-danger"> </span></strong>
                    </div>
                    <div class="text-success">
                        <strong><span id="message-success"> </span></strong>
                    </div>
                </div>

                <div class="col-lg-2 ml-auto">
                    <form method="post" action="{{route('admin.leave.leave_process', $leave->id)}}">
                        {{csrf_field()}}
                        <button type="submit" id="declined" name="declined" value="declined" class="btn btn-danger btn-lg">
                            <i class="fas fa-times-circle"></i><strong>&nbsp;Decline</strong>
                        </button>
                    </form>
                </div>

                <div class="col-lg-2">
                    <form method="post" action="{{route('admin.leave.leave_process', $leave->id)}}">
                        {{csrf_field()}}
                        <button type="submit" id="accepted" name="accepted" value="accepted" class="btn btn-success btn-lg">
                            <i class="fas fa-check-circle"></i><strong>&nbsp;Accept</strong>
                        </button>
                    </form>
                </div>
            @elseif ($leave->status == 'Declined')
                <div class="col-lg-4 mr-auto">
                    <div class="text-danger">
                        <strong>You have declined this leave form!</strong>
                    </div>
                </div>
            @else
                <div class="col-lg-4 mr-auto">
                    <div class="text-success">
                        <strong>You have accepted this leave form!</strong>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection

@section('js')
    <script>
        let message = '{{$message}}';
        if (message === 'Declined'){
            $('#message-danger').replaceWith('You have declined this leave form!');
            $('#declined').attr('disabled', true);
            $('#accepted').attr('disabled', true);
        } else if (message === 'Accepted'){
            $('#message-success').replaceWith('You have accepted this leave form!');
            $('#declined').attr('disabled', true);
            $('#accepted').attr('disabled', true);
        }
    </script>
@endsection
