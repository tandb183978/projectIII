<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Mail;
use App\Models\User;
use App\Repositories\Mail\MailRepository;
use App\Repositories\Salary\SalaryRepository;
use App\Repositories\User\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class MailController extends Controller
{
    protected $userRepo;
    protected $mailRepo;
    protected $parent;
    protected $current_mail;
    public function __construct(SalaryRepository $salaryRepo, UserRepository $userRepo, MailRepository $mailRepo)
    {
        parent::__construct($salaryRepo);
        $this->userRepo = $userRepo;
        $this->mailRepo = $mailRepo;
        $this->parent = array_fill(0, 1000, false);
        $this->middleware(function ($request, $next) {
            $this->current_mail=Auth::user()->email; // returns user
            return $next($request);
        });
    }

    public function findRoot($x){
        if ($this->parent[$x] == $x) return $x;
        $this->parent[$x] = $this->findRoot($this->parent[$x]);
        return $this->parent[$x];
    }

    public function dsu($inboxes){
        for ($i = 1; $i <= $this->mailRepo->maxId(); $i++)
            $this->parent[$i] = $i;

        foreach ($inboxes as $inbox){
            $u = $inbox->id; $v = $inbox->response_to;
            $root_u = $this->findRoot($u);
            $root_v = $this->findRoot($v);
            if ($root_u != $root_v){
                $this->parent[$root_u] = $root_v;
            }
        }
    }

    public function getAllRelevantMails($user_mail, $order = 'asc', $except_root = true, $url = 'all'): array
    {
        $user = User::where('email', $user_mail)->first();
        /* Dùng dsu để gom nhóm tập các mails có cùng root*/
        $mails = array();
        foreach ($user->mails as $mail)
            array_push($mails, $mail);
        $this->dsu($mails);

        $relevants = array();

        /* Lấy tập mail tuỳ vào url */
        if ($url == 'all') {
            foreach ($user->mails as $mail) {
                if ($mail->pivot->deleted == true) continue;
                if ($except_root and ($mail->response_type == 'root' and $mail->sender == $user_mail)) continue;
                array_push($relevants, $mail);
            }
        } else if ($url == 'outbox'){
            foreach ($user->mails as $mail){
                if ($mail->pivot->deleted == true) continue;
                if ($mail->sender == $user_mail){
                    array_push($relevants, $mail);
                }
            }
        } else if ($url == 'important'){
            foreach ($user->mails as $mail){
                if ($mail->pivot->deleted == true) continue;
                if ($mail->pivot->important == true){
                    array_push($relevants, $mail);
                }
            }
        } else if ($url == 'favorite'){
            foreach ($user->mails as $mail){
                if ($mail->pivot->deleted == true) continue;
                if ($mail->pivot->favorite == true){
                    array_push($relevants, $mail);
                }
            }
        } else if ($url == 'trash_bin'){
            foreach ($user->mails as $mail){
                if ($mail->pivot->deleted == true)
                    array_push($relevants, $mail);
            }
        }
        /* Sort theo thời gian updated */
        if ($order == 'desc') {
            usort($relevants, function ($a, $b) {
                return strcmp($a->updated_at, $b->updated_at) < 0;
            });
        }
        return $relevants;
    }

    public function compose(){
        $validator = Validator::make(request()->all(), [
            'receiver' => ['required', function($attribute, $value, $fail){
                $email = ($this->userRepo->allEmailAvailable($value))[1];
                if ($email){
                    $fail($email.' not exists.');
                }
            }],
            'cc' => [function($attribute, $value, $fail){
                if ($value){
                    $email = ($this->userRepo->allEmailAvailable($value))[1];
                    if ($email){
                        $fail($email.' not exists.');
                    }

                }
            }]
        ]);
        if ($validator->fails()){
            return back()->withErrors($validator);
        }
        /**
         * Tạo mail và bảng mail-user
         */
        $mail = Mail::create([
            'sender' => $this->current_mail,
            'receivers' => request('receiver').(request('cc')?(', '.request('cc')):''),
            'subject' => request('subject'),
            'content' => request('content'),
            'read' => null,
            'attachment' => (request()->file('attachment'))?(request()->file('attachment')->store('public/file')):null,
            'response_to' => 0,
            'response_type' => 'root',
        ]);
        $mail->response_to = $mail->id;
        $mail->save();

        $receivers = explode(', ', $mail->receivers);
        if (!in_array(Auth::user()->email, $receivers))
            array_push($receivers, $this->current_mail);
        foreach ($receivers as $receiver){
            $user = User::where('email', $receiver)->first();
            $mail->users()->attach($user, [
                'deleted' => false,
                'favorite' => false,
                'important' => false,
                'label' => null,
            ]);
        }
        return back()->with(['message' => 'success']);
    }

    public function index(){
        $this->current_mail = Auth::user()->email;

        $root_mails = array();
        $names = array();
        $contents = array();
        $number_mails = array();
        $dates = array();

        $data = [
            'type' => null,
            'email' => $this->current_mail,
            'roots' => null,
            'names' => null,
            'number_mail' => null,
            'contents' => null,
            'date' => null,
        ];

        $route = request()->url();
        $uri = '';
        if (str_contains($route, 'inbox')) $uri = 'inbox';
        if (str_contains($route, 'outbox')) $uri = 'outbox';
        if (str_contains($route, 'favorite')) $uri = 'favorite';
        if (str_contains($route, 'important')) $uri = 'important';
        if (str_contains($route, 'trash_bin')) $uri = 'trash_bin';

        $data['type'] = $uri;
        $relevants = $this->getAllRelevantMails($this->current_mail, 'desc', $uri =='inbox', ($uri=='inbox')?'all':$uri);

        /* Lấy data: dates, contents: mail cuối cùng, name: tất cả senders (hoặc receiver) */
        foreach ($relevants as $relevant) {
            $id = $this->parent[$relevant->id];
            $root = Mail::where('id', $id)->first();
            if (!in_array($root, $root_mails)) {
                $root_mails[$id] = $root;
                $dates[$id] = $relevant->created_at;
                $contents[$id] = $relevant->content;
                $number_mails[$id] = 1;

                /* Name của inbox/important/favorite/deleted là sender, name của outbox là receiver */
                if ($uri != 'outbox')
                    $names[$id] = User::where('email', $relevant->sender)->first()->name;
                else {
                    $receivers = explode(', ', $relevant->receivers);
                    foreach ($receivers as $i => $receiver) {
                        $name = User::where('email', $receiver)->first()->name;
                        $names[$id] = ($i)?($names[$id].', '.$name):($name);
                    }
                }

                $dates[$id] = Carbon::parse($relevant->created_at)->format("H:i");
                if (Carbon::parse($relevant->created_at)->format("Y-m-d") != $this->curDate())
                    $dates[$id] .= ', '. Carbon::parse($relevant->created_at)->format("l, F d ") . '(' . Carbon::parse($relevant->created_at)->diffForHumans().')';
            }
            else {
                $number_mails[$id] ++;
                if ($uri != 'outbox') {
                    $name = User::where('email', $relevant->sender)->first()->name;
                    if (!str_contains($names[$id], $name))
                        $names[$id] .= ', '.$name;
                }
                else {
                    $receivers = explode(', ', $relevant->receivers);
                    foreach ($receivers as $receiver) {
                        $name = User::where('email', $receiver)->first()->name;
                        if (!str_contains($names[$id], $name))
                            $names[$id] .= ', '. $name ;
                    }
                }
            }
        }

        $data['roots'] = array_values($root_mails);
        $data['date'] = array_values($dates);
        $data['contents'] = array_values($contents);
        $data['number_mail'] = array_values($number_mails);
        $data['names'] = array_values($names);

        return view('mail.index')->with($data);
    }

    public function detail($root_id){
        /* Mỗi repo có 1 kiểu detail khác nhau */
        $url = request()->url();
        $relevant_mails = [];
        $uri = '';
        $user_id = User::where('email', $this->current_mail)->first()->id;
        /* Lấy tất cả mail liên quan tới user tuỳ vào từng repo, với inbox, outbox lấy hết, important/favorite/trash chỉ lấy thư lọc */
        if (str_contains($url, 'inbox')) $uri = 'inbox';
        if (str_contains($url, 'outbox')) $uri = 'outbox';
        if (str_contains($url, 'favorite')) $uri = 'favorite';
        if (str_contains($url, 'important')) $uri = 'important';
        if (str_contains($url, 'trash_bin')) $uri = 'trash_bin';

        $relevant_mails = $this->getAllRelevantMails($this->current_mail, 'asc', false, ($uri=='inbox' or $uri=='outbox')?'all':$uri);
        /* Tìm tất cả mail có root là $root_id */
        $relevants = array();
        foreach ($relevant_mails as $relevant){
            if ($this->parent[$relevant->id] == $root_id)
                array_push($relevants, $relevant);
        }

        /* Trường hợp không có mail nào, xảy ra khi delete xong thì quay lại hộp thư đến hoặc hoặc hộp thư đi */
        if (count($relevants) == 0)
            return redirect()->route('mail.'.$uri.'.index');

        /* Lấy ảnh+receiver của từng sender trong relevants*/
        $senders = array();
        $images_arr = array();
        $receivers_arr = array();
        $auxis_hidden_contents_arr = array();
        $favorite_arr = array();

        foreach ($relevants as $relevant){
            $sender = User::with('employee')->where('email', $relevant->sender)->first();
            if ($sender->deleted == false) {
                array_push($senders, $sender->email);
                array_push($images_arr, ($sender->hasRole('admin')) ? "public/img/admin.jpg" : Employee::where('user_id', $sender->id)->first()->image_profile);
            } else {
                array_push($senders, 'EM user');
                array_push($images_arr,  "public/img/user.jpg");
            }
            /* Thay mail của receiver đang cần là 'tôi', bỏ mail của user hiện tại(TH khi user reply)*/
            $receivers = $relevant->receivers;

            /* Thay EM user nếu có trong receivers*/
            $receiver_mails = explode(', ', $receivers);
            foreach ($receiver_mails as $receiver_mail){
                foreach($relevant->users as $user) {
                    if ($user->email == $receiver_mail){
                        if ($user->deleted == true){
                            $receivers = str_replace($receiver_mail, 'EM user', $receivers);
                        }
                        break;
                    }
                }
            }

            $receivers = str_replace($this->current_mail, 'tôi', $receivers);
            array_push($receivers_arr, $receivers);
            /* Content của mỗi relevant phải chứa tất cả cha của nó */

            $auxis_hidden_content = '';

            if ($relevant->response_type != 'root' and $relevant->response_type != 'new_message') {
                $tmp = Mail::where('id', $relevant->response_to)->first();
                $created_at = $tmp->created_at;
                $i = 1;
                $pre_response_type = $relevant->response_type;
                while ($pre_response_type != 'root' and $pre_response_type != 'new_message') {
                    $auxis_hidden_content .= '<hr style="margin-top: -4px; margin-bottom: 8px; border-color: #D3D3D3">';
                    /* Reply or reply all: thêm on; Forwarding: thêm forwarded message */
                    if ($pre_response_type == 'forwarding') {
                        $auxis_hidden_content .= '<p style="padding-left:' . (15 * $i - 15) . 'px;font-family: Source Sans Pro; font-weight:400; font-size:14px">';
                        $auxis_hidden_content .= '---------- Forwarded message ---------<br>' . 'From: ' . $tmp->sender . '<br>To: ' . $tmp->receivers . '<br>Subject: ' . Mail::where('id', $root_id)->first()->subject;
                        $auxis_hidden_content .= '<br>Date: '.  Carbon::parse($created_at)->format('l, F') . ' ' . Carbon::parse($created_at)->format('m, Y') . ' at ' . Carbon::parse($created_at)->format('H:i'). '</p>';
                    } else {
                        $auxis_hidden_content .= '<p style="padding-left:' . (15 * $i - 15) . 'px;font-family: Source Sans Pro; font-weight:400; font-size:14px">On ';
                        $auxis_hidden_content .= Carbon::parse($created_at)->format('l, F') . ' ' . Carbon::parse($created_at)->format('m, Y') . ' at ' . Carbon::parse($created_at)->format('H:i') . ' ' . $tmp->sender . ' wrote: <br>';
                    }
                    $auxis_hidden_content .= '<span style="padding-left:15px; color:darkgrey">' . $tmp->content . '</span></p>';
                    $i++;
                    $pre_response_type = $tmp->response_type;
                    $tmp = Mail::where('id', $tmp->response_to)->first();
                    $created_at = $tmp->created_at;
                }
            }
            array_push($auxis_hidden_contents_arr, $auxis_hidden_content);
            /* Kiểm tra mail đã được đánh dấu favorite chưa */
            array_push($favorite_arr, DB::table('mail_user')->where('user_id', $user_id)->where('mail_id', $relevant->id)->first()->favorite);
        }
        $data = [
            'type' => $uri,
            'email' => $this->current_mail,
            'root_mail' => Mail::where('id', $root_id)->first(),
            'receivers' => $receivers_arr??null,
            'mails' => $relevants??null,
            'senders' => $senders??null,
            'auxis_hidden_contents' => $auxis_hidden_contents_arr??null,
            'images' => $images_arr??null,
            'favorites' => $favorite_arr,
        ];
        return view('mail.inbox.detail')->with($data);
    }

    public function store_reply (){
        $response_type = request('response_type');
        $receivers = request('receivers');
        $sender = request('sender');
        /* type có thể là 3 loại: reply, reply all, forwarding */
        if ($receivers){
            $mail = Mail::create([
                'sender' => $sender,
                'receivers' => $receivers,
                'subject' => null,
                'content' => request('content'),
                'read' => null,
                'attachment' => (request()->file('attachment'))?(request()->file('attachment')->store('public/file')):null,
                'response_to' => (int)request('response_to'),
                'response_type' => request('response_type'),
            ]);

            $receivers = explode(', ', $mail->receivers);
            if (!in_array(Auth::user()->email, $receivers))
                array_push($receivers, $this->current_mail);
            foreach ($receivers as $receiver){
                $user = User::where('email', $receiver)->first();
                $mail->users()->attach($user, [
                    'deleted' => false,
                    'favorite' => false,
                    'important' => false,
                    'label' => null,
                ]);
            }
        }

        return back();
    }


    public function delete_mail(){
        $mail_id = request()->route()->parameter('mail_id');
        /* Không cần thiết: $user = request()->user;*/
        $user = User::where('email', $this->current_mail)->first();
        $user->mails()->updateExistingPivot($mail_id, ['deleted' => true], true);
        return back();
    }

    public function mark_favorite(){
        $mail_id = request()->route()->parameter('mail_id');
        $starred = (request('favorite')=='true')?false:true;
        /* Không cần thiết: $user = request()->user;*/
        $user = User::where('email', $this->current_mail)->first();
        $user->mails()->updateExistingPivot($mail_id, ['favorite' => $starred], true);
        return back();
    }

    public function option_for_selected_mails(): \Illuminate\Http\RedirectResponse
    {
        $pre_url = app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();
        $url = request()->url();
        $option = '';
        $pre_uri =  '';
        $user_id = User::where('email', $this->current_mail)->first()->id;

        /* Tuỳ thuộc vào lựa chọn đang ở index nào */
        if (str_contains($pre_url, 'inbox')) $pre_uri = 'inbox';
        if (str_contains($pre_url, 'outbox')) $pre_uri = 'outbox';
        if (str_contains($pre_url, 'trash_bin')) $pre_uri = 'trash_bin';
        if (str_contains($pre_url, 'important')) $pre_uri = 'important';
        if (str_contains($pre_url, 'favorite')) $pre_uri = 'favorite';

        if (str_contains($url, 'delete')) $option = 'deleted';
        if (str_contains($url, 'important')) $option = 'important';
        if (str_contains($url, 'favorite')) $option = 'favorite';

        $root_id = request()->route()->parameter('root_id');               // Thư trực tiếp
        $selected_mails = explode(',' ,request('id_selections'));   // Thư được chọn

        $selected_roots = array();
        if (request('id_selections')) $selected_roots = $selected_mails;
        if ($root_id) array_push($selected_roots, $root_id);

        /* Tìm tập mail của user*/
        $user = User::where('email', $this->current_mail)->first();
        $mails = array();
        foreach ($user->mails as $mail)
            array_push($mails, $mail);
        $this->dsu($mails);
        /* Tập inbox/outbox/important là tập bao hàm tất cả mail liên quan, với trash_bin thì là xoá vĩnh viễn cuộc trò chuyện */
        if ($pre_uri == 'inbox' or $pre_uri == 'outbox') {
            foreach ($selected_roots as $root_id) {
                foreach ($mails as $mail) {
                    if ($this->parent[$mail->id] == $root_id) {
                        $user->mails()->updateExistingPivot($mail->id, [$option => true], true);
                    }
                }
            }
        }
        if ($pre_uri == 'trash_bin'){
            foreach ($selected_roots as $root_id) {
                foreach ($mails as $mail) {
                    if ($this->parent[$mail->id] == $root_id) {
                        $user->mails()->detach($mail->id);
                    }
                }
            }
        }
        /* Tập favorite là tập chỉ gồm các mail được chọn */
        if ($pre_uri == 'favorite') {
            foreach ($selected_roots as $root_id) {
                foreach ($mails as $mail) {
                    if ($this->parent[$mail->id] == $root_id) {
                        $favorite_status = DB::table('mail_user')->where('user_id', $user_id)->where('mail_id', $mail->id)->first()->favorite;
                        if ($favorite_status) $user->mails()->updateExistingPivot($mail->id, ['favorite' => false], true);
                    }
                }
            }
        }
        if ($pre_uri == 'important') {
            foreach ($selected_roots as $root_id) {
                foreach ($mails as $mail) {
                    if ($this->parent[$mail->id] == $root_id) {
                        $important_status = DB::table('mail_user')->where('user_id', $user_id)->where('mail_id', $mail->id)->first()->favorite;
                        if ($option == 'deleted' and $important_status) $user->mails()->updateExistingPivot($mail->id, ['important' => false], true);
                        if ($option == 'favorite') $user->mails()->updateExistingPivot($mail->id, ['favorite' => true], true);
                    }
                }
            }
        }
        return back();
    }
}

