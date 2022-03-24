<?php

namespace App\Repositories\Mail;


use App\Models\Mail;
use App\Repositories\BaseRepository;

class MailRepository extends BaseRepository implements \App\Repositories\Mail\MailRepositoryInterface {
    /**
     * Lấy model
     */
    public function getModel()
    {
        return Mail::class;
    }

    public function getAllInbox($email): array
    {
        $mails = $this->model->orderby('created_at', 'asc')->get();
        $inboxes = array();
        foreach ($mails as $mail){
            $receivers = explode(', ', $mail->receivers);
            foreach ($receivers as $receiver){
                if ($receiver == $email) {
                    array_push($inboxes, $mail);
                    break;
                }
            }
        }
        return $inboxes;
    }

    public function getAllOutbox($email): array
    {
        $mails = $this->model->orderby('created_at', 'asc')->get();
        $outboxes = array();
        foreach ($mails as $mail){
            $sender = $mail->sender;
            if ($sender == $email)
                array_push($outboxes, $mail);
        }
        return $outboxes;
    }

    /**
     * @param $email
     * Lấy tất cả mail liên quan tới $email (có thể thêm ngoại trừ mail root, .aka. chỉ lấy childview)
     * @param string $order
     * @param bool $except_root
     */
    public function getAllRelevantMail($email, $order = 'asc', $except_root = true): array
    {
        $mails = $this->model->orderby('updated_at', $order)->get();
        $relevants = array();
        foreach ($mails as $mail){
                /* Sender */
            $sender = $mail->sender;
            if ($sender == $email){
                if (($mail->id == $mail->response_to and !$except_root) or ($mail->id != $mail->response_to)) {
                    array_push($relevants, $mail);
                    continue;
                }
            }
                /* Receiveres */
            $receivers = explode(', ', $mail->receivers);
            foreach ($receivers as $receiver){
                if ($receiver == $email){
                    array_push($relevants, $mail);
                    break;
                }
            }
        }
        return $relevants;
    }

    public function count(): int
    {
        return count($this->model->all());
    }

    public function maxId(){
        $maximum_Mail = $this->model->orderby('id', 'desc')->first();
        return ($maximum_Mail)?$maximum_Mail->id:0;
    }


}
