<?php

namespace App\Http\Controllers;

use App\Traits\Web\General\GetParameters;
use App\Traits\Web\General\Responser;

class BaseController extends Controller
{
    use GetParameters;
    use Responser;
}
