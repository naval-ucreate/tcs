<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BoardActivities\BoardActivitiesRepository as BoardActivity;
use App\Repositories\BoardConfigurations\BoardConfigurationsRepository as BoardConfig;
use App\Repositories\Cards\CardRepository as Card;
use App\Repositories\CardBugs\CardBugsRepository;
class ReportController extends Controller
{
    private $board_activity, $board_config, $card, $card_bugs;
    public function __construct(BoardActivity $board_activity, BoardConfig $board_config, Card $card, CardBugsRepository $card_bugs){
        $this->board_activity = $board_activity;
        $this->board_config = $board_config;
        $this->card = $card;
        $this->card_bugs = $card_bugs;
    }

    public function getActivies($board_id) {
        return view('dashboard/activity');
    }

    public function getReport($board_id, $date = '') {
        $report = [];
        
        $total_story = $this->card_bugs->getCardCount($board_id);
        $total_bugs = $this->card_bugs->getBugCount($board_id);
        $total_revert = $this->card_bugs->getRevertCount($board_id);
        $report[] = ["Total Story", $total_story, "#3967C8"];
        $report[] = ["Total Bugs", $total_bugs, "#DB3A1B"];
        $report[] = ["Total Revert", $total_revert, "#FF9722"];

        return $report;
    }





}
