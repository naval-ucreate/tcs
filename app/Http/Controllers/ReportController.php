<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BoardActivities\BoardActivitiesRepository as BoardActivity;
use App\Repositories\BoardConfigurations\BoardConfigurationsRepository as BoardConfig;
use App\Repositories\Cards\CardRepository as Card;
class ReportController extends Controller
{
    private $board_activity, $board_config, $card;
    public function __construct(BoardActivity $board_activity, BoardConfig $board_config,Card $card){
        $this->board_activity = $board_activity;
        $this->board_config = $board_config;
        $this->card = $card;
    }

    public function getActivies($board_id) {
        
        return view('dashboard/activity');
    }





}
