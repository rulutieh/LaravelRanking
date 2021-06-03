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
    public function get($hash)
    {
        try{
            $dt = Carbon::now()->format('Y-m-d H:i:s');
            $scores = Score::select('hash','uid','sco','kk','cc','gg','bb','mm','maxcombo')
            ->where('hash','=',$hash)
            ->orderBy('sco','desc')->get();

            foreach ($scores as $score) {
                $user = User::select('id','name')
                ->where('id','=',$score->uid )->first();
                if (!empty($user))
                {
                    $score->uid = $user->value('name');
                }
            }
            unset($score);
            return response()->json([
               'date' => $dt,
                'score' => $scores->toArray(),
                'result' => 'retrieved'
            ]);
        }
        catch (\Thorowable $th){
            Log::error($th);

            return response()->json([
                'date' => $dt,
                'message' => 'Score Registering',
                'result' => 'Failed'
            ], 422);
        }
    }
    public function add($hash, $uid, $sco, $kk, $cc, $gg, $bb, $mm, $maxcombo)
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
            $score = DB::table('scores')
            ->where('uid' ,'=', $uid)
            ->where('hash','=',$hash)->delete();
            
            //$score1 = clone $score;
            //$score1->where('sco','>',$sco)->first();

            $score1 = DB::table('scores')
            ->where('uid' ,'=', $uid)
            ->where('hash','=',$hash)
            ->where('sco','>',$sco)->first();

            if (!$score1){
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
                'created_at' => $dt, 
                'updated_at' => $dt
            ]);
            }

            return response()->json([
                'date' => $dt,
                'message' => 'Score Registering',
                'result' => 'Submit'
            ], 200);
        }
        catch (\Thorowable $th){
            Log::error($th);

            return response()->json([
                'date' => $dt,
                'message' => 'Score Registering',
                'result' => 'Failed'
            ], 422);
        }
    }
}
