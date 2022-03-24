<?php

namespace App\Repositories\User;


use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements \App\Repositories\User\UserRepositoryInterface {
    /**
     * Lấy model
     */
    public function getModel()
    {
        return User::class;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function allEmailAvailable($emails): array
    {
        $emails = explode(', ', $emails);
        foreach ($emails as $email){
            $user = $this->model->where('email', $email)->first();
            if (!$user)
                return array(false, $email);
        }

        return array(true, null);
    }

    public function getNameByEmail($email){
        return $this->model->where('email', $email)->first()->name;
    }



}
