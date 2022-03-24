@extends('layouts.mail')

@section('css_external_link')
    @parent
    <link rel="stylesheet" href="/css/mail/detail.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Google+Sans">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">

@endsection

@section('mail.content')
    <div class="card-header">
        <h2></h2>
    </div>


    <div class="card-body" style="'" id="mail-detail">
        <div class="row" style="height: 70px">
            <div class="col-lg-1">
                <span>  </span>
            </div>
            <div class="col-lg-10" >
                <h3 style="font-family: 'Google Sans',serif">{{$root_mail->subject}}</h3>
            </div>

        </div>
        <hr style="margin-top: 0">

        @foreach($mails as $i => $mail)
        <div>
            <div class="reply row" id='for-visible-{{$i}}'>
                        <!-- Image -->
                <div class="for-hover-visible col-lg-1"  style="text-align: right">
                    <img style="width:50px !important; height: 50px !important;" src="{{\Illuminate\Support\Facades\Storage::url($images[$i])}}" class="user-image rounded-circle elevation-2" alt="user_image">
                </div>
                        <!-- Navigation bar -->
                <div class="for-hover-visible col-lg-6" >
                    <strong>{{$senders[$i]}}</strong> <br>
                    <span style="font-family: 'Roboto',serif; font-size: 12px; color:darkgrey"> tới {{$receivers[$i]}} </span>

                    <span class="dropdown">
                        <button type="button" class="btn" data-toggle="dropdown" title_handmade_bottom="Detail">
                            <i style="color:grey" class="fas fa-caret-down" ></i>
                        </button>
                        <span class="dropdown-menu" style="padding-left: 10px; width:550px!important">
                            <span class="row">
                                <span style="width: 140px; color:grey">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;From:</span>
                                <span style="width: 400px">{{$mail->sender}}</span>
                            </span>
                            <span class="row">
                                <span style="width: 140px; color:grey">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To:</span>
                                <span style="width: 400px">{{$mail->receivers}}</span>
                            </span>
                            <span class="row">
                                <span style="width: 140px; color:grey">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Day:</span>
                                <span style="width: 400px">{{\Carbon\Carbon::parse($mail->created_at)->format("H:i").', '.\Carbon\Carbon::parse($mail->created_at)->format("d").' thg '.\Carbon\Carbon::parse($mail->created_at)->format("m").', '.\Carbon\Carbon::parse($mail->created_at)->format("Y")}}</span>
                            </span>
                            <span class="row">
                                <span style="width: 140px; color:grey">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subject:</span>
                                <span style="width: 400px">{{$root_mail->subject}}</span>
                            </span>
                            <span class="row">
                                <span style="width: 140px; color:grey">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sent by:</span>
                                <span style="width: 400px">{{explode('@', $mail->sender)[1]}}</span>
                            </span>
                            <span class="row">
                                <span style="width: 140px; color:grey">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Authenticated by:</span>
                                <span style="width: 400px">{{explode('@', $mail->sender)[1]}}</span>
                            </span>
                        </span>
                    </span>
                </div>
                <div class="for-hover-visible col-lg-3">
                    <div class="d-flex">
                    <span class="ml-auto" style="font-size: 13px">{{\Carbon\Carbon::parse($mail->created_at)->format("H:i").', '.\Carbon\Carbon::parse($mail->created_at)->format("d").' thg '.\Carbon\Carbon::parse($mail->created_at)->format("m").', '.\Carbon\Carbon::parse($mail->created_at)->format("Y").' ('.$mail->created_at->diffForHumans().')'}}</span>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="d-flex">
                        <div class="mr-auto">
{{--                            /* HERE OK? */--}}
                            @if ($senders[$i] == 'EM user')
                                <div class='option-for-mail' style="float:left; display:none">
                            @else
                                <div class='option-for-mail' style="float:left">
                            @endif
                                    <button id='reply-{{$i}}' style="border: 0; background-color: white" title_handmade_bottom="Reply this mail" >
                                        <i class="fas fa-reply" ></i>
                                    </button>
                                </div>

                            @if ($senders[$i] == 'EM user')
                                <form class='option-for-mail' method='post' action='{{route('mail.delete_mail', $mail->id)}}' style="float:left; display:none">
                            @else
                                <form class='option-for-mail' method='post' action='{{route('mail.delete_mail', $mail->id)}}' style="float:left">
                            @endif
                                    @csrf
                                    @method('DELETE')
                                    <button id='delete-{{$i}}' style="border: 0; background-color: white"  title_handmade_bottom="Delete this mail">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                            <!-- Nếu đã favor rồi thì click vào mất favor -->
                            @if ($senders[$i] == 'EM user')
                                <form class='option-for-mail' method='post' action='{{route('mail.mark_mail_favorite', $mail->id)}}' style="float:left; display:none">
                            @else
                                <form class='option-for-mail' method='post' action='{{route('mail.mark_mail_favorite', $mail->id)}}' style="float:left">
                            @endif
                                    @csrf
                                    @method('post')
                                    @if ($favorites[$i] == true)
                                        <input type="hidden" name="favorite" value="true">
                                        <button id='favorite-{{$i}}' style="border: 0; background-color: white"  type='submit' title_handmade_bottom="Starred">
                                            <i style="color:yellow" class="fas fa-star"></i>
                                        </button>
                                    @else
                                        <input type="hidden" name="favorite" value="false">
                                        <button id='favorite-{{$i}}' style="border: 0; background-color: white"  type='submit' title_handmade_bottom="Not starred">
                                            <i  class="fas fa-star"></i>
                                        </button>
                                    @endif
                                </form>
                        </div>
                    </div>
                </div>
            </div>
                    <!--- Content--->
            <div class="row" id='for-visible-cnt-{{$i}}'>
                <div class="col-lg-1">

                </div>
                <div class="content col-lg-11">
                    <br>
                    <div>
                        <span id="mail-content">{{$mail->content}}</span>
                    </div>

                    @if ($mail->response_type != 'root' and $mail->response_type !== 'new_message')
                        <button style="border-radius: 100%; border: 0;" id="click-expand-content-{{$i}}" title_handmade_bottom="Show minified content">
                            <i  class="fas fa-ellipsis-h"></i>
                        </button>
                    @endif
                    <div class='auxis-forwarding' style="display: none">
                        <span id="mail-auxis-h-content">{!! $auxis_hidden_contents[$i] !!}</span>
                    </div>
                </div>
            </div>

                    <!--- Reply form --->
            <div id='reply-form-div-{{$i}}' class="reply-form-div row">
                <div class="col-lg-1 d-none" style="text-align: right">
                    <img style="width:50px !important; height: 50px !important;" src="{{\Illuminate\Support\Facades\Storage::url($images[$i])}}" class="user-image rounded-circle elevation-2" alt="user_image">
                </div>
                <div class="col-lg-11 d-none">
                    <div class="card">
                        <div class="card-header bg-light">
                            <!-- Button to choose answer type -->
                            <div class="answertype dropdown" style="float: left">
                                <button type="button" class="btn" data-toggle="dropdown" title_handmade_bottom="Answer type">
                                    <i id='rep_icon' style='display: block; float: left' class="fas fa-reply" ></i>
                                    <i id='rep_all_icon' style='display: none; float: left' class="fas fa-reply-all" ></i>
                                    <i id='forward_icon' style='display: none; float: left' class="fas fa-share-square" ></i>
                                    <i id='new_message_icon' style='display: none; float: left' class="fas fa-comments"></i>
                                    &nbsp;&nbsp;<i style="color:grey" class="fas fa-caret-down" ></i>
                                </button>
                                <div class="dropdown-menu" style="padding-left: 3px;">
                                    <a id='answertype-reply-{{$i}}' class="rep dropdown-item">
                                        <i class="fas fa-reply" ></i>&nbsp;&nbsp;&nbsp;&nbsp;
                                        Reply
                                    </a>
                                    <a id='answertype-replyall-{{$i}}' class="rep_all dropdown-item">
                                        <i class="fas fa-reply-all"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                                        Reply all
                                    </a>
                                    <a id='answertype-forwarding-{{$i}}' class="forwarding dropdown-item">
                                        <i class="fas fa-share-square"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                                        Forwarding
                                    </a>
                                    <a id='answertype-new-message-{{$i}}' style="display: none" class="forwarding dropdown-item">
                                        <i class="fas fa-comments"></i>
                                        &nbsp;&nbsp;&nbsp;&nbsp;New message
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <span class="dropdown-item-text">Do somethingelse</span>
                                </div>
                            </div>
                            <!-- Name -->
                            <span id='mail-reply-{{$i}}' style="color: grey; margin-top: 5px">
                                <!-- Cho reply -->
                                <span id="mail-reply-name" style="display: block">
                                    @if ($mail->sender == $email)
                                        {{$mail->receivers}}
                                    @else
                                        {{$mail->sender}}
                                    @endif
                                </span>
                                <!-- Cho forwarding -->
                                <span id="mail-forward-name" style="display:none;">
                                    <form id="extra-forward">
                                        <label for="forwardingid-{{$i}}">To: </label>
                                        <input style="width: 400px; border: 0" type="text" name="forwarding" id="forwardingid-{{$i}}">
                                    </form>
                                </span>
                                <!-- Cho new message -->
                                <span id="mail-new-message-name" style="display:none;">
                                    <form id="extra-new-message">
                                        <label for="messageid-{{$i}}">To: </label>
                                        <input style="width: 400px; border: 0" type="text" name="new-message" id="messageid-{{$i}}">
                                    </form>
                                </span>
                            </span>
                        </div>
                        <div class="card-body">
                            <!-- Text area -->
                            <form id='reply-form-{{$i}}' method="post" action="{{route('mail.store_reply')}}">
                                @csrf
                                <label for="contentid-{{$i}}"></label>
                                <textarea name='content' style="border: 0; width: 100%; height: 150px" id="contentid-{{$i}}"></textarea>

                                <div id='title' class='auxis-forwarding' contenteditable="true" style="display:none">
                                    <span>---------- Forwarded message ---------</span>
                                    <br><span>From: <strong>{{$mail->sender}}</strong></span>
                                    <br><span>Date: {{\Carbon\Carbon::parse($mail->created_at)->format('l, F') . ' ' . \Carbon\Carbon::parse($mail->created_at)->format('m, Y') . ' at ' . \Carbon\Carbon::parse($mail->created_at)->format('H:i')}}</span>
                                    <br><span>Subject: {{$root_mail->subject}} </span>
                                    <br><span>To: {{$mail->receivers}}</span>
                                </div>

                                <div id='all-reply' class='auxis-forwarding' contenteditable="true" style="display:none">
                                    <span id="alter-here"></span>
                                </div>

                                <button id='btn-form-{{$i}}' style="width: 15%; float:left" type="button" class="btn_submit btn btn-primary border-right">Send</button>
                                <label class="custom-file-upload" title_handmade_bottom="Attachment">
                                    <input type="file" name="attachment" />
                                    <i class="fas fa-paperclip"></i>
                                </label>


                                <input type="hidden" name="sender" value="{{$email}}"/>
                                <input type="hidden" name="response_to" value="{{$mail->id}}"/>
                                <input class='response_type' type="hidden" name="response_type" value=""/>
                                <input class='receivers' type="hidden" name="receivers" value=""/>
                                <button id="btn-close-{{$i}}" style="float:right; border: 0; border-radius: 15px" type='button' class="btn_close" title_handmade_bottom="Remove draft messages">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="for-invisible-{{$i}}" class='for-hover-invisible row'>
                    <!-- Image -->
            <div class="col-lg-1" style="text-align: right">
                <img style="width:50px !important; height: 50px !important;" src="{{\Illuminate\Support\Facades\Storage::url($images[$i])}}" class="user-image rounded-circle elevation-2" alt="user_image">
            </div>
                    <!-- Navigation bar -->
            <div class="col-lg-8" style="">
                <strong>{{$mail->sender}}</strong> <br>
                {{substr($mail->content, 0, 75)}}
            </div>
            <div class="col-lg-3" style="">
                <div class="d-flex">
                    <span class="ml-auto" style="font-size: 13px">{{\Carbon\Carbon::parse($mail->created_at)->format("H:i").', '.\Carbon\Carbon::parse($mail->created_at)->format("d").' thg '.\Carbon\Carbon::parse($mail->created_at)->format("m").', '.\Carbon\Carbon::parse($mail->created_at)->format("Y").' ('.$mail->created_at->diffForHumans().')'}}</span>
                </div>
            </div>
        </div>
        @if (!$loop->last)
            <hr style="border-color: black">
        @endif
        @endforeach

        <br><br>
        <div class="_butt row">
            <div class="col-lg-1">

            </div>
            <div class="col-lg-11">
                <div class="row">
                    <div class="col-lg-2 d-flex ">
                        @if ($senders[count($senders)-1] == 'EM user')
                            <button type="submit" class="_rep btn btn-outline-secondary mr-auto" disabled style="width: 135px">
                        @else
                            <button type="submit" class="_rep btn btn-outline-secondary mr-auto" style="width: 135px">
                        @endif
                                <i class="fas fa-reply" ></i>
                                <span style="font-family: 'Google Sans',serif"> &nbsp;Reply </span>
                            </button>
                    </div>

                    <div class="col-lg-2 d-flex ">
                        @if ($senders[count($senders)-1] == 'EM user')
                            <button type="submit" class="_forward btn btn-outline-secondary mr-auto" disabled style="width: 135px">
                        @else
                            <button type="submit" class="_forward btn btn-outline-secondary mr-auto" style="width: 135px">
                        @endif
                                <i class="fas fa-share-square"></i>
                                <span style="font-family: 'Google Sans',serif"> Forwarding </span>
                            </button>
                    </div>
                    <div class="col-lg-2 d-flex ">
                        @if ($senders[count($senders)-1] == 'EM user')
                            <button type="submit" class="_new_message btn btn-outline-secondary mr-auto" disabled style="width: 135px">
                        @else
                            <button type="submit" class="_new_message btn btn-outline-secondary mr-auto" style="width: 135px">
                        @endif
                                <i class="fas fa-comments"></i>
                                <span style="font-family: 'Google Sans',serif"> &nbsp;New message </span>
                            </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    @parent


    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function (){
            let email = '{{$email}}';
            let mails = @json($mails);
            let receivers = @json($receivers);
            let two_last_button = false;
            let new_message = false;
            for (let i = 0; i<mails.length-1; i++){
                $('#for-visible-'+i).children().css('display', 'none');
                $('#for-visible-cnt-'+i).children().css('display', 'none');
                $('#for-invisible-'+i).children().css('display', 'block');
            }
            $('#for-visible-'+(mails.length-1)).children().css('display', 'block');
            $('#for-visible-cnt-'+(mails.length-1)).children().css('display', 'block');
            $('#for-invisible-'+(mails.length-1)).children().css('display', 'none');


            /* Hover form */
            $('.reply-form-div .col-lg-11 .card').css('border-radius', '10px 10px 10px 10px');

            $('.reply-form-div .col-lg-11 .card').hover(function(){
                $(this).css('box-shadow', '0 0 11px rgba(33,33,33,.2)');
                $(this).css('cursor', 'pointer');
            })
            $('.reply-form-div .col-lg-11 .card').mouseleave(function(){
                $(this).css('box-shadow', 'none');
                $(this).css('cursor', 'context-menu');
            })

            /* Toggle hidden */
            let cur_receivers = '';
            let receive_type = '';

            $('.reply .col-lg-2 .mr-auto .option-for-mail button').on('click', function(){
                let btn_id = $(this).attr('id');
                let id = btn_id.split('-').slice(-1)[0];
                    /* Delete mail */
                if (btn_id.includes('delete-') || btn_id.includes('favorite-')) {
                    /* let x = 1 */
                }
                    /* Reply mail */
                else {
                    $('#reply-form-div-'+id+' .col-lg-1').toggleClass('d-block');
                    $('#reply-form-div-'+id+' .col-lg-11').toggleClass('d-block');
                    /* Lấy nội dung người gửi vào biến cur_receivers */
                    cur_receivers = $(this).closest('.reply').siblings('.reply-form-div').find('#mail-reply-name').first().html();
                    receive_type = 'reply';
                    /* Nếu receiver chỉ có 1 người và sender không là 'tôi' thì bỏ button reply-all */
                    if (!receivers[id].includes(', ') && mails[id].sender !== email){
                        $(this).closest('.reply').siblings('.reply-form-div').find('.rep_all').first().css('display', 'none');
                    }
                }

            })
            /* Choose answer type */
            $('.reply-form-div .col-lg-11 .card .card-header .answertype .dropdown-menu a').click(function () {
                let a_id = $(this).attr('id');
                let id = a_id.split('-').slice(-1)[0];
                let mail_reply = $(this).parent().parent().next();

                let rep_all = a_id.search('answertype-replyall-');
                let rep = a_id.search('answertype-reply-');
                let forward = a_id.search('answertype-forwarding-');
                let new_message = a_id.search('answertype-new-message-');

                /* Nếu sender của mail này là 'tôi' thì reply chỉ là receivers */
                if (email === mails[id].sender) {
                    if (rep_all !== -1) {
                        cur_receivers = mails[id].receivers;
                        receive_type = 'reply_all';
                    } else if (rep !== -1) {
                        cur_receivers = mails[id].receivers;
                        receive_type = 'reply';
                    } else if (forward !== -1) {
                        /* Nếu forwarding thì hiển thị input + */
                        receive_type = 'forwarding';
                    } else {
                        receive_type = 'new_message';
                    }
                } else {
                    /* Nếu sender không phải user hiện tại thì phải bỏ 'tôi' đi */
                    let this_receivers = receivers[id].split(', ');
                    let tmp = '' + mails[id].sender;
                    for (let i = 0; i < this_receivers.length; i++) {
                        if (this_receivers[i] !== 'tôi')
                            tmp += ', ' + this_receivers[i];
                    }
                    /* Nếu chỉ gửi cho 'tôi' thì bỏ reply-all button */
                    if (this_receivers.length === 1) {
                        if (rep !== -1) {
                            cur_receivers = tmp;
                            receive_type = 'reply';
                        } else if (forward !== -1) {
                            /* Nếu forwarding thì hiển thị input + */
                            receive_type = 'forwarding';
                        } else if (rep_all !== -1) {
                            /* btn rep_all cannot appear*/
                        } else {
                            receive_type = 'new_message';
                        }
                    } else {  /* Nếu gửi cho nhiều người */
                        if (rep_all !== -1) {
                            cur_receivers = tmp;
                            receive_type = 'reply_all';
                        } else if (rep !== -1) {
                            cur_receivers = mails[id].sender;
                            receive_type = 'reply';
                        } else if (forward !== -1) {
                            /* Nếu forwarding thì hiển thị input + */
                            receive_type = 'forwarding';
                        } else {
                            receive_type = 'new_message';
                        }
                    }
                }
                if (rep !== -1 || rep_all !== -1) {
                    $(mail_reply.children('#mail-reply-name')).replaceWith('<span id="mail-reply-name">' + cur_receivers + '</span>');
                    $(this).closest('.card-header').siblings('.card-body').find('.auxis-forwarding').css('display','none');
                    $(this).closest('.answertype').next().children('#mail-forward-name').css('display', 'none');
                    $(this).closest('.answertype').next().children('#mail-new-message-name').css('display', 'none');
                    $(this).closest('.answertype').next().children('#mail-reply-name').css('display', 'block');
                    if (rep+1) {
                        $(this).parent().prev().find('#rep_icon').css('display', 'block');
                        $(this).parent().prev().find('#rep_all_icon').css('display', 'none');
                    } else {
                        $(this).parent().prev().find('#rep_icon').css('display', 'none');
                        $(this).parent().prev().find('#rep_all_icon').css('display', 'block');
                    }
                    $(this).parent().prev().find('#forward_icon').css('display', 'none');
                    $(this).parent().prev().find('#new_message_icon').css('display', 'none');
                }
                else {
                    /* Change icon */
                    $(this).parent().prev().find('#rep_icon').css('display', 'none');
                    $(this).parent().prev().find('#rep_all_icon').css('display', 'none');
                    let content = '';
                    if (forward !== -1) {
                        $(this).parent().prev().find('#new_message_icon').css('display', 'none');
                        $(this).parent().prev().find('#forward_icon').css('display', 'block');

                        content = '<br><span>' + $(this).closest('.reply-form-div').prev().find('#mail-content').html();
                        content += '<p>' + $(this).closest('.reply-form-div').prev().find('#mail-auxis-h-content').html()+'</p>';
                        $(this).closest('.answertype').next().children('#mail-reply-name').css('display', 'none');
                        $(this).closest('.answertype').next().children('#mail-new-message-name').css('display', 'none');
                        $(this).closest('.answertype').next().children('#mail-forward-name').css('display', 'block');
                        $(this).closest('.card-header').siblings('.card-body').find('#alter-here').replaceWith(content);
                        $(this).closest('.card-header').siblings('.card-body').find('.auxis-forwarding').css('display','block');
                    }
                    if (new_message !== -1) {
                        $(this).parent().prev().find('#forward_icon').css('display', 'none');
                        $(this).parent().prev().find('#new_message_icon').css('display', 'block');
                        /* Không hiển thị comment ... */
                        $(this).closest('.answertype').next().children('#mail-reply-name').css('display', 'none');
                        $(this).closest('.answertype').next().children('#mail-forward-name').css('display', 'none');
                        $(this).closest('.answertype').next().children('#mail-new-message-name').css('display', 'block');
                        $(this).closest('.card-header').siblings('.card-body').find('.auxis-forwarding').css('display','none');
                    }
                    /* Close name of reply to form of name forwarding */
                }
            });
            /* Submit form */
            $('.reply-form-div .col-lg-11 .card .card-body form .btn_submit').click(function (){
                let btn_id = $(this).attr('id');
                let id = btn_id.split('-').slice(-1)[0];
                /* Thêm value cho input response_type và receiver */

                $(this).siblings('.response_type').val(receive_type);
                if (receive_type === 'forwarding')  /* Forwarding thì nhận receivers tại form input */
                    $(this).siblings('.receivers').val($(this).closest('.card-body').prev().find('input').val());
                else
                    $(this).siblings('.receivers').val(cur_receivers);
                /* Submit */

                $(this).parent().submit();

            });

            /* Show/Hide expanded content */
            $('.row .content button').click(function (){
                if ($(this).siblings('.auxis-forwarding').css('display') === 'none'){
                    $(this).siblings('.auxis-forwarding').css('display', 'block');
                    $(this).attr('title_handmade_bottom', 'Hide expanded content')
                } else {
                    $(this).siblings('.auxis-forwarding').css('display', 'none');
                    $(this).attr('title_handmade_bottom', 'Show minified content')

                }
            })

            /* Button close reply */
            $('.reply-form-div .col-lg-11 .card .card-body form .btn_close').click(function (){
                let btn_id = $(this).attr('id');
                let id = btn_id.split('-').slice(-1)[0];
                $('#reply-form-div-'+id+' .col-lg-1').toggleClass('d-block')
                $('#reply-form-div-'+id+' .col-lg-11').toggleClass('d-block');
                if (two_last_button){
                    two_last_button = false;
                    $('._butt').children('.col-lg-1').css('display', 'block');
                    $('._butt').children('.col-lg-11').css('display', 'block');
                }
            });

            /* Manipulate with 2(3) last button */

            $('._butt .col-lg-11 .row .col-lg-2 ._rep').click(function (){
                two_last_button = true;
                $(this).closest('._butt').children('.col-lg-1').css('display', 'none');
                $(this).closest('._butt').children('.col-lg-11').css('display', 'none');
                $('.reply .col-lg-2 .mr-auto .option-for-mail #reply-'+(mails.length-1)).click();
                $('.reply-form-div .col-lg-11 .card .card-header .answertype .dropdown-menu #answertype-reply-'+(mails.length-1)).click();
            })

            $('._butt .col-lg-11 .row .col-lg-2 ._forward').click(function (){
                two_last_button = true;
                $(this).closest('._butt').children('.col-lg-1').css('display', 'none');
                $(this).closest('._butt').children('.col-lg-11').css('display', 'none');
                $('.reply .col-lg-2 .mr-auto .option-for-mail #reply-'+(mails.length-1)).click();
                $('.reply-form-div .col-lg-11 .card .card-header .answertype .dropdown-menu #answertype-forwarding-'+(mails.length-1)).click();
                // console.log(1);
            })

            $('._butt .col-lg-11 .row .col-lg-2 ._new_message').click(function (){
                two_last_button = true;
                new_message = true;
                $(this).closest('._butt').children('.col-lg-1').css('display', 'none');
                $(this).closest('._butt').children('.col-lg-11').css('display', 'none');
                $('.reply .col-lg-2 .mr-auto .option-for-mail #reply-'+(mails.length-1)).click();
                $('.reply-form-div .col-lg-11 .card .card-header .answertype .dropdown-menu #answertype-new-message-'+(mails.length-1)).click();
                // console.log(1);
            })

            /* Hover to get detail */
            $('.for-hover-visible:not(:last-child)').hover(function(){
               $(this).css('cursor', 'pointer');

               let cursor_id = $(this).parent().attr('id');
               let id = cursor_id.split('-').slice(-1)[0];
               $(this).click(function(){
                   $('#for-visible-'+id).children().css('display', 'none');
                   $('#for-visible-cnt-'+id).children().css('display', 'none');
                   $('#for-invisible-'+id).children().css('display', 'block');

               });
            });

            $('.for-hover-invisible').not('.col-lg-2').hover(function(){
                $(this).css('cursor', 'pointer');
                let cursor_id = $(this).attr('id');
                let id = cursor_id.split('-').slice(-1)[0];
                $(this).click(function(){
                    $('#for-visible-'+id).children().css('display', 'block');
                    $('#for-visible-cnt-'+id).children().css('display', 'block');
                    $('#for-invisible-'+id).children().css('display', 'none');
                });
            });

        });
    </script>
@endsection

