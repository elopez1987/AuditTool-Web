<?php

namespace App\Http\Controllers\Configuration\LogSystem;

use App\Http\Controllers\BaseController;
use App\Models\JobModel;

class JobsController extends BaseController
{
    public function index()
    {
        $jobs = JobModel::get();

        return view('Configuration.Jobs.index', compact('jobs'));

    }
}
