<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\model\Sports;
use App\model\Team;
use App\model\Competetion;
use App\model\Match;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class AddSeriesForMatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'series:day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add the series in order to add the matches';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        try {
            $sports_id = Sports::all('id')->toArray();
            $sport_array=[];
            $match_array = [];
            $teams = [];
            foreach ($sports_id as $id) {
                $ids = $id['id'];
                $client = new Client();
                $competetions = json_decode($client->request('GET', "http://172.105.34.253/api/v1/betting_api/get_series_by_sport.php?sport_id=$ids")->getBody(), true);
                if (count($competetions)>0){
                   foreach ($competetions as $key => $value) {
                        $insert_array = [
                            'sport_id' => $ids,
                            'event_id' => $value['seriesId'],
                            'name'     => $value['SeriesName'],
                            'active'   => $this->getActive($value['is_online'])
                        ];
                        array_push($sport_array, $insert_array);
                    }
                }
            }

            $competition_id = Competetion::all('event_id')->toArray();
            $final_competition_id = array_map(function($value){
                return $value['event_id'];
            }, $competition_id);
            unset($competition_id);
            if (count($final_competition_id) > 0){
                foreach ($sport_array as $key => $value) {
                    if (in_array($value['event_id'], $final_competition_id)){
                        unset($sport_array[$key]);
                    } else {
                        array_push($final_competition_id, $value['event_id']);
                    }
                }
            }
            if (count($sport_array) > 0) {
                if (Competetion::insert($sport_array)){
                    $competition_id = Competetion::all('event_id')->toArray();
                    $final_competition_id = array_map(function($value){
                        return $value['event_id'];
                    }, $competition_id);
                    unset($competition_id);
                } 
            }
            foreach ($final_competition_id as $key => $value) {  
                $client = new Client();
                $matches = json_decode($client->request('GET', "http://172.105.34.253/api/v1/betting_api/get_match_by_series.php?series_id=$value")->getBody(), true);
                if(count($matches) > 0) {
                    foreach ($matches as $key => $value2) {
                        
                        $insert_array_for_match = [
                            'market_id'      => $value2['marketId'],
                            'teams'          => $this->teams($value2['eventName']),
                            'event_id'       => $value2['eventId'],
                            'sport_id'       => $value2['EventTypeId'],
                            'sport_type'     => "100",
                            'competition_id' => $value,
                            'inplay'         => 1,   
                            'name'           => $value2['eventName'],
                            'date'           => $this->date($value2['eventDate']),
                            'active'         => 1 ,
                            'complete'       => 0
                        ];
                        foreach (json_decode($value2['market_runner_json'], true) as $value3) {
                            $insert_array_for_teams = [
                            'name'   => $value3['name'],
                            'short_name' => 'test',
                            'selection_id' => $value3['selectionId']
                            ];
                            array_push($teams, $insert_array_for_teams);
                        }
                        array_push($match_array, $insert_array_for_match);
                    }
                }    
            }
            
            $match_id = Match::all('event_id')->toArray();
            $final_match_id = array_map(function($value){
                return $value['event_id'];
            }, $match_id);
            unset($match_id);
            if (count($final_match_id) > 0){
                foreach ($match_array as $key => $value) {
                    if (in_array($value['event_id'], $final_match_id)){
                        unset($match_array[$key]);
                    } else {
                        array_push($final_match_id, $value['event_id']);
                    }
                }
            }

            $team_id = Team::all('event_id')->toArray();
            $final_team_id = array_map(function($value) {
                return $value['event_id'];
            }, $team_id);
            unset($team_id);
            if (count($final_team_id) > 0) {
                foreach ($teams as $key => $value) {
                    if (in_array($value['event_id'], $final_team_id)) {
                        unset($teams[$key]);
                    } else {
                        array_push($final_team_id, $value['event_id']);
                    }
                }
            }
            if (count($match_array) > 0) {
                Match::insert($match_array);
                Team::insert($teams);    
            }
        } catch (Exception $e) {

            return response()->json('error', $e);
        }
    }

    protected function getActive($value) {
        if($value == 'Y'){
            return 1;
        } else {
            return 0;
        }
    }

    protected function teams($value) {
        $teams = str_replace(" vs ", "|", $value);
        return $teams;
    }

    protected function date($value) {
       $date = date('Y-m-d H:i:s', strtotime($value));
       return $date;
    }
}
