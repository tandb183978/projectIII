
<div id="composebox" class="col-lg-5 ml-auto" style="display:none; bottom: 0; right:3%; position: fixed">
    <div class="card" style="border-top-right-radius: 10px; border-top-left-radius: 10px;">
        <div id='composer-header' class="card-header bg-secondary" style="border-top-right-radius: 10px; border-top-left-radius: 10px;padding: 10px;">
            <div class="row">
                <div id='thumoi' class="col-lg-3 mr-auto">
                    <strong style="color:white; font-size: 16px; padding-left: 15px">Thư mới</strong>
                </div>

                <div id='icon' class="col-lg-2 ml-auto">
                    <div class="row">
                        <div class="col-lg-4">
                            <button style="border: 0" id="minimize-composer" class="btn-secondary" title_handmade_bottom="Minimize">
                                <i class="fas fa-window-minimize"></i>
                            </button>

                            <button style="border: 0; display: none" id="maximize-composer" class="btn-secondary" title_handmade_top="Maximize">
                                <i class="fas fa-window-maximize"></i>
                            </button>
                        </div>

                        <div class="col-lg-2">
                            <button style="border: 0" id="close-composer" class="btn-secondary" title_handmade_bottom="Close">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id='composer-body' class="card-body">
            <form method="post" action="{{route('mail.compose')}}" >
                {{csrf_field()}}
                <input style="width: 92%; border: 0; float:left" name="receiver" type="text" id="receiverid" placeholder="Người nhận">
                <span style="width: 5%" title_handmade_bottom="Carbon copy">
                    <a id="cc">&nbsp;Cc</a>
                </span>
                @error('receiver')
                <div class="text-danger">
                    {{$message}}
                </div>
                @enderror
                <input style="width: 100%; border: 0; display: none" name="cc" type="text" id="ccid" placeholder="Cc" >
                @error('cc')
                <div class="text-danger">
                    {{$message}}
                </div>
                @enderror

                <hr style="margin-top: 7px">
                <input style="width: 100%; border: 0" name="subject" type="text" id="subjectid" placeholder="Chủ đề">

                <hr style="margin-top: 7px">

                <textarea style="width: 100%; border: 0" name="content" rows="15" placeholder="Content"></textarea>
                <input id='submit' style="width: 15%" type="submit" class="btn btn-primary border-right">
                <label class="custom-file-upload" title_handmade_bottom="Attachment">
                    <input type="file" name="attachment" />
                    <i class="fas fa-paperclip"></i>
                </label>
            </form>

        </div>
    </div>
</div>
