<?php

namespace App\Http\Controllers;

use App\Traits\General\Responser;
use App\Traits\GetParameters;


class BaseController extends Controller
{
    use Responser;
    use GetParameters;

}
