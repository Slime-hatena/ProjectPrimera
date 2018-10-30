<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\UserStatus;
use App\ScoreData;

class ViewUserController extends Controller
{
    public function redirectRandomUserPage(){
        return redirect("/user/" . rand(1, count(User::all())));
    }

    public function getMyUserPage(){
        $user = \Auth::user();

        if($user == null){
            return view('require');
        }

        return redirect("/user/" . $user->id);
    }

    public function getUserPage($id, $mode = null){
        $userStatus = new UserStatus();
        $status = $userStatus->getRecentUserData($id);

        $scoreData = new ScoreData();
        $scoreData->getRecentUserScore($id);
        $scoreData->addMusicData();
        $scoreData->addDetailedData();
        $score = $scoreData->value;

        array_multisort(array_column($score, 'updated_at'), SORT_DESC, $score);

        $submenuActive = [0 => "", 1 => "", 2 => "", 3 => ""];

        switch (true) {
            case ($mode === "technical"):
                $mode = "song_status_technical";
                $submenuActive[3] = "is-active";
                break;
            case ($mode === "battle"):
                $mode = "song_status_battle";
                $submenuActive[2] = "is-active";
                break;
            case ($mode === "details"):
                $mode = "song_status_details";
                $submenuActive[1] = "is-active";
                break;
            default:
                $mode = "song_status";
                $submenuActive[0] = "is-active";
                break;
        }

        
        $stat['difficulty'] = [
            "Basic" => [],
            "Advanced" => [],
            "Expert" => [],
            "Master" => [],
            "Lunatic" => [],

        ];
        $stat['level'] = [
            "Lv.1" => [],
            "Lv.2" => [],
            "Lv.3" => [],
            "Lv.4" => [],
            "Lv.5" => [],
            "Lv.6" => [],
            "Lv.7" => [],
            "Lv.7+" => [],
            "Lv.8" => [],
            "Lv.8+" => [],
            "Lv.9" => [],
            "Lv.9+" => [],
            "Lv.10" => [],
            "Lv.10+" => [],
            "Lv.11" => [],
            "Lv.11+" => [],
            "Lv.12" => [],
            "Lv.12+" => [],
            "Lv.13" => [],
            "Lv.13+" => [],
            "Lv.14" => [],
        ];

        $stat['average'] = $stat['level'];
        $stat['averageExist'] = $stat['level'];

        foreach ($score as $key => $value) {
            if($value->full_bell && $value->all_break){
                $score[$key]->rawLamp = "FB+FC+AB";
            }else if($value->full_bell && $value->full_combo){
                $score[$key]->rawLamp = "FB+FC";
            }else if($value->all_break){
                $score[$key]->rawLamp = "FC+AB";
            }else if($value->full_combo){
                $score[$key]->rawLamp = "FC";
            }else if($value->full_bell){
                $score[$key]->rawLamp = "FB";
            }else{
                $score[$key]->rawLamp = "-";
            }


            if($value->technical_high_score == 0){
                $score[$key]->rawTechnicalRank = "-";
            }else if($value->technical_high_score < 850000){
                $score[$key]->rawTechnicalRank = "under A";
            }else{
                $score[$key]->rawTechnicalRank = $value->technical_high_score_rank;
            }
            

            if($value->technical_high_score == 0){
                $key = "NP";
            }else if($value->technical_high_score < 850000){
                $key = "B";
            }else{
                $key = $value->technical_high_score_rank;
            }
            if(!isset($stat['difficulty'][$value->difficulty_str][$key])){
				$stat['difficulty'][$value->difficulty_str][$key] = 0;
            }
            $stat['difficulty'][$value->difficulty_str][$key] += 1;
            
            if(!isset($stat['difficulty'][$value->difficulty_str][$value->over_damage_high_score_rank])){
				$stat['difficulty'][$value->difficulty_str][$value->over_damage_high_score_rank] = 0;
            }
            $stat['difficulty'][$value->difficulty_str][$value->over_damage_high_score_rank] += 1;

            if(!isset($stat['difficulty'][$value->difficulty_str]["fc"])){
				$stat['difficulty'][$value->difficulty_str]["fc"] = 0;
            }
            $stat['difficulty'][$value->difficulty_str]["fc"] += $value->full_combo;
            
            if(!isset($stat['difficulty'][$value->difficulty_str]["ab"])){
				$stat['difficulty'][$value->difficulty_str]["ab"] = 0;
            }
            $stat['difficulty'][$value->difficulty_str]["ab"] += $value->all_break;
            
            if(!isset($stat['difficulty'][$value->difficulty_str]["fb"])){
				$stat['difficulty'][$value->difficulty_str]["fb"] = 0;
            }
			$stat['difficulty'][$value->difficulty_str]["fb"] += $value->full_bell;
        

            if($value->technical_high_score == 0){
                $key = "NP";
            }else if($value->technical_high_score < 850000){
                $key = "B";
            }else{
                $key = $value->technical_high_score_rank;
            }
            if(!isset($stat['level']["Lv." . $value->level_str][$key])){
                $stat['level']["Lv." . $value->level_str][$key] = 0;
            }
            $stat['level']["Lv." . $value->level_str][$key] += 1;

            if(!isset($sta['level']["Lv." . $value->level_str][$value->over_damage_high_score_rank])){
                $stat['level']["Lv." . $value->level_str][$value->over_damage_high_score_rank] = 0;
            }
            $stat['level']["Lv." . $value->level_str][$value->over_damage_high_score_rank] += 1;

            if(!isset($stat['level']["Lv." . $value->level_str]["fc"])){
                $stat['level']["Lv." . $value->level_str]["fc"] = 0;
            }
            $stat['level']["Lv." . $value->level_str]["fc"] += $value->full_combo;
            
            if(!isset($stat['level']["Lv." . $value->level_str]["ab"])){
                $stat['level']["Lv." . $value->level_str]["ab"] = 0;
            }
            $stat['level']["Lv." . $value->level_str]["ab"] += $value->all_break;
            
            if(!isset($stat['level']["Lv." . $value->level_str]["fb"])){
                $stat['level']["Lv." . $value->level_str]["fb"] = 0;
            }
            $stat['level']["Lv." . $value->level_str]["fb"] += $value->full_bell;


            if(!isset($stat['average']["Lv." . $value->level_str][$value->difficulty_str]["count"])){
                $stat['average']["Lv." . $value->level_str][$value->difficulty_str]['count'] = 0;
            }
            $stat['average']["Lv." . $value->level_str][$value->difficulty_str]['count']++;
    
            if(!isset($stat['average']["Lv." . $value->level_str][$value->difficulty_str]["score"])){
                $stat['average']["Lv." . $value->level_str][$value->difficulty_str]['score'] = 0;
            }
            $stat['average']["Lv." . $value->level_str][$value->difficulty_str]['score'] += $value->technical_high_score;

            if($value->technical_high_score !== 0){
                if(!isset($stat['averageExist']["Lv." . $value->level_str][$value->difficulty_str]["count"])){
                    $stat['averageExist']["Lv." . $value->level_str][$value->difficulty_str]['count'] = 0;
                }
                $stat['averageExist']["Lv." . $value->level_str][$value->difficulty_str]['count']++;
    
                if(!isset($stat['averageExist']["Lv." . $value->level_str][$value->difficulty_str]["score"])){
                    $stat['averageExist']["Lv." . $value->level_str][$value->difficulty_str]['score'] = 0;
                }
                $stat['averageExist']["Lv." . $value->level_str][$value->difficulty_str]['score'] += $value->technical_high_score;
            }
            
        }

        return view('user', compact('id', 'status', 'score', 'stat', 'mode', 'submenuActive'));
    }
}
