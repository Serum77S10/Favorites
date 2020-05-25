<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User; // 追加
use App\Micropost; // 追加


class UserfavoriteController extends Controller
{

    public function store($microposts_id)
    {
        \Auth::user()->add_favorite($microposts_id);
        return back();
    }

    public function destroy($microposts_id)
    {
        \Auth::user()->unfavorite($microposts_id);
        return back();
    }

}
