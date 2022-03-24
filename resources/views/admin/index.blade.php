@extends('layouts.app')

@section('title', 'admin')

@section('app-content')
    <!-- Content Wrapper. Contains page content -->
    <!-- Content Header (Page header) -->
    @include('include.app.content_header', ['name' => 'Admin Page', 'pre' => 'Home', 'cur' => 'Page'])
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <row class="">
                <div class="col-md-8 mx-auto" style="margin-top: 50px">
                    <div class="jumbotron" style="color: grey">
                        <h1 class="display-4 text-success">Welcome to Admin Interface</h1>
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
