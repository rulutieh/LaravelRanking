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
            $scores = Score::select('hash','uid','sco','kk','cc','gg','bb','mm','maxcombo','bitwise','created_at')
            ->where('hash','=',$hash)
            ->orderBy('sco','desc')->limit(100)->get();

            $myscore = Score::select('hash','uid','sco','kk','cc','gg','bb','mm','maxcombo','bitwise','created_at')
            ->where('hash','=',$hash)
            ->where('uid','=',$uid)->first();

            $collection = collect(
                Score::where('hash',$hash)
                ->orderBy('sco', 'DESC')
                ->get());
            $data       = $collection->where('uid', $uid);
            $value      = $data->keys()->first() + 1;
            
            if ($myscore){
                $myrank = User::select('id','name')
                ->where('id','=', $myscore->uid)->first();
                if (!empty($myrank))
                {
                    $myscore->uid =  $myrank->value('name');
                }
            }
            

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
               'myscore' => $myscore,
               'result' => 'retrieved',
               'score' => $scores->toArray(),
               'ranking'=> $value
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

            $scoretomodify = $score->where('sco','<=',$sco)
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
