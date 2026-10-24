<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\Configuration\TablesDataBase\SystemTable;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\User;
use Illuminate\Http\Request;

class AutoCompleteController extends Controller
{
    public function autocompletuser(Request $request)
    {
        $query = $request->get('query');
        $filterResult = User::where('users.name', 'like', "%{$query}%")->select(['name'])->get();

        return response()->json($filterResult);
    }

    public function autocompletenametable(Request $request)
    {
        $query = $request->get('query');
        $tables = SystemTable::where('systemtables.tableid', 'like', "%{$query}%")->get();

        return response()->json($tables);
    }

    public function autocompletesku(Request $request)
    {
        $query = $request->get('query');
        $partnumber = InventoryPartnumber::controlAccessUser()
            ->where('prtnum', 'like', "%{$query}%")->pluck('prtnum');

        return response()->json($partnumber);
    }
}
