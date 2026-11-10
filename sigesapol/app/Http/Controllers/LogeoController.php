<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Logeo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LogeoController extends Controller
{
  public function getCountUsersActives() {
    // $dateCurrent = Carbon::now()->format('Y-m-d');
    // $countUsersActives = DB::table('logeos')
    //                         ->select(DB::raw('count(distinct id_user) as total'))
    //                         ->whereRaw("DATE(login_at) = ?", [$dateCurrent])
    //                         ->whereNull('logout_at')
    //                         ->value('total');
    $dateCurrent = Carbon::now()->subMinutes(40);
    $countUsersActives = DB::table('logeos')
                            ->select(DB::raw('COUNT(DISTINCT id_user) as total'))
                            ->where('login_at', '>=', $dateCurrent)
                            ->whereNull('logout_at')
                            ->value('total');
    return response()->json(['count_users_actives' => $countUsersActives]);  
  }
}
