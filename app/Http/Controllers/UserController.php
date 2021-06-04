<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use DB;

class UserController extends Controller
{
    public function add($id, $pw)
    {
        $dt = Carbon::now()->format('Y-m-d H:i:s');
        try{

            $user = DB::table('users')
            ->where('name' ,'=', $id)
            ->first();

            if (!$user){
                $newuser = User::create([
                    'name'=>$id,
                    'password'=>$pw,
                    'email'=>$id
                ]);
            }

            return response()->json([
                'date' => $dt,
                'message' => 'Make Account',
                'result' => 'Submit'
            ], 200);
        }
        catch (\Thorowable $th){
            Log::error($th);

            return response()->json([
                'date' => $dt,
                'message' => 'Make Account',
                'result' => 'Failed'
            ], 422);
        }
    }
}
