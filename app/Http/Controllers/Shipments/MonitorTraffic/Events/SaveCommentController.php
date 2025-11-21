<?php

namespace App\Http\Controllers\Shipments\MonitorTraffic\Events;

use App\Http\Controllers\BaseController;
use App\Models\General\StorageComment;
use Illuminate\Http\Request;

class SaveCommentController extends BaseController
{
    public function index(Request $request, $id)
    {
        if ($request->has('comment')) {
            if ($request['comment'] !== '') {
                StorageComment::createCommentary('orderdeliveries', $id, $request);
            }
        }

        return $this->redirectBackInfo();
    }
}
