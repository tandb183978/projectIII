<?php

namespace App\Repositories\Info_Details_Month;

use App\Models\Info_Details_Month;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

class Info_Details_MonthRepository extends BaseRepository implements \App\Repositories\Info_Details_Month\Info_Details_MonthRepositoryInterface {
    /**
     * Lấy model
     */
    public function getModel()
    {
        return Info_Details_Month::class;
    }
}
