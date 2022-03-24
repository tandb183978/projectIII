@extends('layouts.mail')

@section('css_external_link')
    @parent
    <link rel="stylesheet" href="/css/mail/index.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
@endsection

@section('mail.content')
    <div class="card-header">
{{--        <h2>{{$type}}</h2>--}}
        <div class="row">
            <div class="square_o" style="padding-left: 4px; width: 100px; display: block">
                <button class="butt_header border-0  btn-lg" title_handmade_bottom="Select">
                    <i class="fas fa-square"></i>
                </button>
            </div>
            <div class="check_square" style="padding-left: 4px; width: 100px;display: none">
                <button class="butt_header border-0  btn-lg" title_handmade_bottom="Select">
                    <i class="fas fa-check-square"></i>
                </button>
            </div>

            <div class="check_minus" style="padding-left: 4px; width: 100px;display: none">
                <button class="butt_header border-0  btn-lg" title_handmade_bottom="Select">
                    <i class="fas fa-minus-square"></i>
                </button>
            </div>

            <div class="option" style="width: 250px;display: none">
                <button class="butt_header border-0 btn-lg" onclick="delete_selected_mails()" type="button" title_handmade_bottom="Delete">
                    <i class="fas fa-trash"></i>
                </button>
                <button class="butt_header border-0 btn-lg" onclick="add_selected_mails_to_favoriteRepo()" title_handmade_bottom="Add to your favorite">
                    <i class="fas fa-star"></i>
                </button>
                <button class="butt_header border-0 btn-lg" onclick="mark_selected_mails_important()" title_handmade_bottom="Mark important ">
                    <i class="fas fa-exclamation-circle"></i>
                </button>
                <button class="butt_header border-0 btn-lg" onclick="reload()" title_handmade_bottom="Refresh">
                    <i class="fas fa-redo-alt"></i>
                </button>
            </div>
        </div>
    </div>
    <br>
    <table id="index" class="table table-borderless table table-hover row-border display nowrap">
        <thead>
        <tr>
            <th style="width: 5%; text-align: center;"></th>
            <th style="width: 19%; text-align: center; "></th>
            <th style="width: 60%; text-align: center; "></th>
            <th style="width: 16%; text-align: left;"></th>
            <th style="width: 16%;  display: none"></th>
        </tr>
        </thead>

        <tbody>
            @foreach($roots as $i=>$root)
                <tr id='{{$i}}' class="rowDataTable">
                    <td style="text-align: center;">
                        <label for="checkbox-{{$i}}"></label>
                        <input style="width: 17px;height: 17px !important;vertical-align: middle " type="checkbox" id="checkbox-{{$i}}" name="checkbox"/>
                    </td>
                    <td style="text-align: left;">
                        {{(strlen($names[$i]) > 30)?(substr($names[$i], 0, 30).'...'):$names[$i]}}
                        <span style="font-size: 10px; color:darkgrey">{{$number_mail[$i]}}</span>
                    </td>
                    <td style="text-align: left;">
                        {{$root->subject??'(Không có chủ đề)'}}&nbsp;-&nbsp;
                        <span style="color:darkgrey">{{$contents[$i]}}</span>
                    </td>
                    <td id='date-{{$i}}' style="text-align: right; display:block"><small><strong>{{$date[$i]}}</strong></small></td>
                    <td id='icon-{{$i}}' style="text-align: right; display: none">
                        <form class='mail-important' style="float:right;" method="post" action="{{route('mail.mark_root_mail_important', $root->id)}}" enctype="multipart/form-data">
                            @csrf
                            <button type="submit" style="border: 0" title_handmade_bottom="Mark important">
                                <i class="fas fa-exclamation-circle"></i>
                            </button>
                        </form>
                        <form class='mail-favorite' style="float:right; " method="post" action="{{route('mail.mark_root_mail_favorite', $root->id)}}" enctype="multipart/form-data">
                            @csrf
                            <button type="submit" style="border: 0" title_handmade_bottom="Add to your favorite">
                                <i class="fas fa-star"></i>
                            </button>
                        </form>
                        <form class='mail-delete' method='post' style="float:right;" action="{{route('mail.delete_root_mail', $root->id)}}" enctype="multipart/form-data">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="border: 0; " title_handmade_bottom="Delete">
                                <i style="width: 1.2em" class="fas fa-trash" ></i>
                            </button>
                        </form>
                        <form class='mail-delete-permanent' method='post' style="float:right; display: none" action="{{route('mail.delete_root_mail', $root->id)}}" enctype="multipart/form-data">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="border: 0; " title_handmade_bottom="Delete permanent">
                                <i style="width: 1.2em" class="fas fa-trash" ></i>
                            </button>
                        </form>
                        <form class='mail-detail' style="float:right; " method="get" action="{{route('mail.'.$type.'.detail', $root->id)}}" enctype="multipart/form-data">
                            @csrf
                            <button type="submit" style="border: 0" title_handmade_bottom="Detail">
                                <i class="fas fa-info-circle"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Hidden form for hidden input like delete, favorite... */ -->
    <div style="display: none">
        <form id="hidden-delete-form" action="">
            @csrf
            @method('DELETE')
            <input type="hidden" name="id_selections" value="">
{{--            <input type="button" id="btn-submit">--}}
        </form>
    </div>
    <div style="display: none">
        <form id="hidden-important-form" action="">
            @csrf
            @method('POST')
            <input type="hidden" name="id_selections" value="">
            {{--            <input type="button" id="btn-submit">--}}
        </form>
    </div>
    <div style="display: none">
        <form id="hidden-favorite-form" action="">
            @csrf
            @method('POST')
            <input type="hidden" name="id_selections" value="">
            {{--            <input type="button" id="btn-submit">--}}
        </form>
    </div>

@endsection

@section('js')
    @parent
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <script>
        let roots = @json($roots);
        let id_selections = new Set();
        let type = '{{$type}}';

        $(document).ready(function() {
            let who_clicked_checkbox = '';
            let table = $('#index').DataTable({
                "bPaginate": false,
                "bLengthChange": false,
                "bInfo": false,
                "bAutoWidth": false,
                "bSort" : false,
                columnDefs:[{
                    targets:2,
                    // className:"truncate"
                    render: function ( data, type, row ) {
                        let cutoff = 180;
                        return data.length > cutoff ?
                            data.substr( 0, cutoff ) +'…' :
                            data;
                    },
                }],
            });

            /* Tuỳ thuộc vào repo mà có các option tương ứng */
            if (type === 'inbox' || type === 'outbox'){
                /* Đầy đủ option */
            } else if (type === 'favorite'){
                /* Chỉ có detail và delete */
                $('td .mail-favorite').css('display', 'none');
                $('td .mail-important').css('display', 'none');
            } else if (type === 'important'){
                /* Không có important */
                $('td .mail-important').css('display', 'none');
            } else if (type === 'trash_bin'){
                /* Chỉ có detail và delete vĩnh viễn */
                $('td .mail-favorite').css('display', 'none');
                $('td .mail-important').css('display', 'none');
                $('td .mail-delete').css('display', 'none');
                $('td .mail-delete-permanent').css('display', 'block');
            }

            /* Display button */
            $('table tbody tr').mouseenter(function () {
                let row = table.row( this );
                let index = $(this).attr('id');
                $('#date-'+index).css('display', 'none');
                $('#icon-'+index).css('display', 'block');
            } );

            $('table tbody tr').mouseleave(function () {
                let row = table.row( this );
                let index = $(this).attr('id');
                $('#date-'+index).css('display', 'block');
                $('#icon-'+index).css('display', 'none');
            });

            /* Click to check box to select single mail*/
            $('#index tbody input[type="checkbox"]').click(function (){
                let checkbox_id = $(this).attr('id');
                let id = checkbox_id.split('-').slice(-1)[0];
                if($(this).prop("checked") === true){
                    id_selections.add(roots[id].id);
                    $('table tbody #'+id).css('background-color', 'cornflowerblue');    // row
                    $('table tbody #icon-'+id).find('button').css('background-color', 'cornflowerblue');   // icon
                    if (who_clicked_checkbox === '') {
                        $('.check_minus').css('display', 'block');
                        $('.square_o').css('display', 'none');
                        $('.check_square').css('display', 'none');
                        $('.option').css('display', 'block');
                    }
                } else {
                    id_selections.delete(roots[id].id);
                    $('table tbody #'+id).css('background-color', 'white')
                    $('table tbody #icon-'+id).find('button').css('background-color', 'white');   // icon
                    if (id_selections.size === 0) {
                        /* Do something ? */
                        if (who_clicked_checkbox === '') {
                            $('.check_square').css('display', 'none');
                            $('.square_o').css('display', 'block');
                            $('.check_minus').css('display', 'none');
                            $('.option').css('display', 'none');
                        }
                    }
                }
                console.log(id_selections);
            });

            /* Click to button square_o to select all mails or check_square to get rid of all mail*/
            $('.card-header').find('.square_o').click(function(){
                who_clicked_checkbox = 'square_o';
                $('.square_o').css('display', 'none');
                $('.check_minus').css('display', 'none');
                $('.check_square').css('display', 'block');
                $('.option').css('display', 'block');

                $('input[type="checkbox"]').each(function() {
                    $(this).click();
                });
                who_clicked_checkbox = '';
            })
            $('.card-header').find('.check_square').click(function(){
                who_clicked_checkbox = 'check_square';
                $('.square_o').css('display', 'block');
                $('.check_minus').css('display', 'none');
                $('.check_square').css('display', 'none');
                $('.option').css('display', 'none');

                $('input[type="checkbox"]').each(function() {
                    $(this).click();
                });
                who_clicked_checkbox = '';

            })
        })

        function reload(){
            window.location.reload();
        }

        function delete_selected_mails(){
            let form = $('#hidden-delete-form');
            form.attr('method', 'post');
            form.attr('action','{{route('mail.delete_selected_mails')}}');
            form.find('input[name="id_selections"]').val(Array.from(id_selections));
            form.submit();
        }

        function mark_selected_mails_important(){
            let form = $('#hidden-important-form');
            form.attr('method', 'post');
            form.attr('action','{{route('mail.mark_selected_mails_important')}}');
            form.find('input[name="id_selections"]').val(Array.from(id_selections));
            form.submit();
        }

        function add_selected_mails_to_favoriteRepo(){
            let form = $('#hidden-favorite-form');
            form.attr('method', 'post');
            form.attr('action','{{route('mail.mark_selected_mails_favorite')}}');
            form.find('input[name="id_selections"]').val(Array.from(id_selections));
            form.submit();
        }
    </script>
@endsection

