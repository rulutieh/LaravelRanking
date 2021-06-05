<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Score;
use App\User;

class ScoreController extends Controller
{
    public function all()
    {

    }
    public function get($hash, $uid)
    {
        try{
            $dt = Carbon::now()->format('Y-m-d H:i:s');

            $collection = collect(
                Score::where('hash', $hash)
                ->select('hash','uid','sco','kk','cc','gg','bb','mm','maxcombo','bitwise','updated_at')
                ->orderBy('sco', 'DESC')->orderBy('updated_at', 'ASC')
                ->get());
            $data       = $collection->where('uid', $uid);
            $myrank      = $data->keys()->first() + 1;
            $myscore     = $data->first();            
            $ranking    = $collection->slice(0,50)->ToArray();
            $users = DB::table('users')->get();
            foreach ($ranking as $i => $rank) {
                $user = $users->where('id', $rank['uid'] )->first();
                if (!empty($user))
                {   
                    $ranking[$i]['uid'] = $user->name;
                }
            }
            return response()->json([
               'myscore' => $myscore,
               'result' => 'retrieved',
               'score' => $ranking,
               'ranking'=> $myrank
            ]);
        }
        catch (\Thorowable $th){
            Log::error($th);

            return response()->json([
                'date' => $dt,
                'result' => 'Failed',
                'message' => 'Getting Score'
            ], 422);
        }
    }
    public function add($hash, $uid, $sco, $kk, $cc, $gg, $bb, $mm, $maxcombo, $bitwise)
    {
        $dt = Carbon::now()->format('Y-m-d H:i:s');
        try{
            /*
            $score = DB::table('scores')->insert([

                'key'=>$key,
                'pname'=>$pname,
                'score'=>$score,
                'acc'=>$acc,
                'state'=>$state,
                'maxcombo'=>$maxcombo,
                'date'=> $date,
                'created_at' => $dt, 
                'updated_at' => $dt
            ]);
            */

            $score = Score::where('uid' ,'=', $uid)
            ->where('hash','=',$hash)->get();

            $scoretomodify = $score->where('sco','<',$sco)
            ->first();

            if ($scoretomodify)
            $scoretomodify
            ->update([
                'sco'=>$sco,
                'kk'=>$kk,
                'cc'=>$cc,
                'gg'=>$gg,
                'bb'=>$bb,
                'mm'=>$mm,
                'maxcombo'=>$maxcombo,
                'bitwise'=>$bitwise,
                'updated_at' => $dt
            ]);

            if (!$score->first()){
            $newscore = Score::create([
                'hash'=>$hash,
                'uid'=>$uid,
                'sco'=>$sco,
                'kk'=>$kk,
                'cc'=>$cc,
                'gg'=>$gg,
                'bb'=>$bb,
                'mm'=>$mm,
                'maxcombo'=>$maxcombo,
                'bitwise'=>$bitwise,
                'created_at' => $dt, 
                'updated_at' => $dt
            ]);
            }
            
            

            return response()->json([
                'date' => $dt,
                'result' => 'Submit',
                'message' => 'Score Registering'
            ], 200);
        }
        catch (\Thorowable $th){
            Log::error($th);

            return response()->json([
                'date' => $dt,
                'result' => 'Failed',
                'message' => 'Score Registering'
            ], 422);
        }
    }
}
