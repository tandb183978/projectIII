
        <!---- Error ----->
@if(count($errors))
    <div class="alert alert-danger">
        <ol>
            @foreach($errors as $error)
                <li>{{$error}}</li>
            @endforeach
        </ol>

    </div>
@endif

        <!--- Success --->
@if(session('success'))
    <div class="alert alert-success">
        {{session()->get('success')}}
    </div>
@endif
