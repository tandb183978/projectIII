@extends('layouts.app')

@section('title', 'admin')

@section('css_external_link')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="/css/admin/list_employee.css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
@endsection

@section('app-content')
    <!--- Content header --->
    @include('include.app.content_header', ['name' => 'List of employees', 'pre'=>'Employee', 'cur'=>'List'])
    <!--- Main content --->
    <br>

    <div class="container-fluid">
        <table id="list_employees" class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th style="width: 0; text-align: center;"><small>#</small></th>
                    <th style="width: 0; text-align: center;"><small>UserId</small></th>
                    <th style="width: 10%; text-align: center;"><small>Name</small></th>
                    <th style="width: 10%; text-align: center;"><small>Email</small></th>
                    <th style="width: 0; text-align: center;"><small>Birthday</small></th>
                    <th style="width: 0; text-align: center;"><small>Phone</small></th>
                    <th style="width: 0; text-align: center;"><small>Department</small></th>
                    <th style="width: 5%; text-align: center;"><small>Position</small></th>
                    <th style="width: 0; text-align: center;"><small>Sex</small></th>
                    <th style="width: 0; text-align: center;"><small>Address</small></th>
                    <th style="width: 0; text-align: center;"><small>Country</small></th>
                    <th style="width: 30%; text-align: center;"><small>Image</small></th>
                    <th style="width: 0; text-align: center;"><small></small></th>
                </tr>
            </thead>

            <tbody>
                @foreach($employees as $employee)
                    <tr>
                        <td style='text-align: center; vertical-align: middle'> {{$employee->id}} </td>
                        <td style='text-align: center; vertical-align: middle'> {{$employee->user_id}} </td>
                        <td style='text-align: center; vertical-align: middle'> {{$employee->last_name}} {{$employee->first_name}} </td>
                        <td style='text-align: center; vertical-align: middle'><small>{{explode("@", $employee->user()->first()->email)[0].' @'.explode("@", $employee->user()->first()->email)[1]}} </small> </td>
                        <td style='text-align: center; vertical-align: middle'> {{\Carbon\Carbon::parse($employee->birth_day)->format("d/m/Y")}} </td>
                        <td style='text-align: center; vertical-align: middle'> {{$employee->phone}} </td>
                        <td style='text-align: center; vertical-align: middle'> {{$employee->department}} </td>
                        <td style='text-align: center; vertical-align: middle'> {{$employee->position}} </td>
                        <td style='text-align: center; vertical-align: middle'> {{$employee->gender}} </td>
                        <td style='text-align: center; vertical-align: middle'> {{$employee->address}}</td>
                        <td style='text-align: center; vertical-align: middle'> {{$employee->country}}</td>
                        <td style='text-align: center; vertical-align: middle'>
                            <div class="card">
                                <img class="card-img-top" src="{{\Illuminate\Support\Facades\Storage::url($employee->image_profile)}}" alt="employee_image">
                            </div>
                        </td>
                        <td style='text-align: center; vertical-align: middle'>
                            <div class="btn btn-info" style="width: 100%" title_handmade_bottom="Edit">
                                <a href="{{route('admin.employee.edit_view', ['employee_id'=>$employee->id])}}">
                                    <i class="fas fa-edit" ></i>
{{--                                    <span style="font-size: 10px">Edit</span>--}}
                                </a>
                            </div>

                            <br> <br>

                            <div class="btn btn-danger trigger-btn" style="width: 100%" title_handmade_bottom="Delete">
                                <a href="#delete-modal-{{$loop->index}}" data-toggle="modal">
                                    <i class="fas fa-trash" ></i>
{{--                                    <span style="font-size: 10px">Delete</span>--}}
                                </a>
                            </div>


                            <!-- Modal HTML -->
                            <div id="delete-modal-{{$loop->index}}" class="modal fade">
                                <div class="modal-dialog modal-confirm">
                                    <div class="modal-content">
                                        <div class="modal-header flex-column">
                                            <div class="icon-box">
                                                <i class="material-icons">&#xE5CD;</i>
                                            </div>
                                            <h4 class="modal-title w-100">Are you sure?</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Do you really want to delete this account? This process cannot be undone.</p>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <form method="post" action="{{route('admin.employee.delete', ['employee_id'=>$employee->id])}}">
                                                @method('delete')
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </td>
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
            $('#list_employees').DataTable({
                "bPaginate": true,
                "bLengthChange": true,
                "bInfo": true,
                "bAutoWidth": true,
                "lengthMenu": [10, 20, 50, 100, 200, 500],
                /*
                initComplete: function () {
                    this.api().columns().every( function () {
                        var column = this;
                        var select = $('<select><option value=""></option></select>')
                            .appendTo( $(column.footer()).empty() )
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );

                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                    } );
                }
                */

            });
        });
    </script>
@endsection

