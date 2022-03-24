<div style="margin-top:10px; height: 70px" class="content-header">
    <div class="container-fluid">
        <div class="row mb-0">
            <div class="col-sm-6">
                <h3 style="line-height: 70px" class="m-0 text-dark">{{$name}}</h3>
            </div>
            <!-- /.col -->
            <div class="col-md-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        @can('admin-access')
                            <small><a href="{{route('admin.index')}}">{{$pre}}</a></small>
                        @endcan
                        @can('employee-access')
                                <small><a href="{{route('employee.index')}}">{{$pre}}</a></small>
                        @endcan
                    </li>
                    <li class="breadcrumb-item active">
                        <small>{{$cur}} </small>
                    </li>
                </ol>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /.content-header -->
