<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request){
        $params = ['to'=> $request->to, 'from' => $request->from, 'search' => $request->search];
        $logs = Log::when($request->search, function ($query) use ($params) {
            return $query->where(function ($query) use ($params) {
                return $query
                ->orWhere('user', 'LIKE', '%'.$params['search'].'%' )
                ->orWhere('email', 'LIKE', '%'.$params['search'].'%' )
                ->orWhere('rol', 'LIKE', '%'.$params['search'].'%' )
                ->orWhere('entity', 'LIKE', '%'.$params['search'].'%' )
                ->orWhere('event', 'LIKE', '%'.$params['search'].'%' );
            });
            });

        if(!empty($params['to']) && !empty($params['from'])){
            $logs->whereDate('date', '>=', $params['from'])
                 ->whereDate('date', '<=' , $params['to']);
        }

        $logs = $logs->orderBy('id', 'desc')->paginate(50);


        return view('admin.logs.index', compact('logs', 'params'));
    }
}
