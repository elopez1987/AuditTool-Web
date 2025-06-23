<?php

namespace App\Http\ViewComposers;

use App\Models\Configuration\NameSystem\NameSystem;
use Illuminate\View\View;

class SystemName
{
    public function compose(View $view)
    {
        $name = NameSystem::select(['name', 'version'])->first();
        $view->with('systemName',$name);
    }
}
