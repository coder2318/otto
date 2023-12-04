<?php

namespace App\Http\Controllers\Dashboard;

use App\Data\Story\Status;

class DashboardController
{
    public function __construct(
        protected StoryController $bridge
    ) {
    }

    protected function story()
    {
        return request()->user()->stories()->firstOrCreate([], [
            'title' => 'Autobiography',
            'status' => Status::PENDING,
            'story_type_id' => 1,
        ]);
    }

    public function index()
    {
        return $this->bridge->show($this->story());
    }
}
