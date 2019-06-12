<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Repositories\BoardConfigurations\BoardConfigurationsRepository as BoardConfig;

class Performance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:PerformanceCard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command create the performance card in trello borad which board are confing in system';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    private $board_config;

    public function __construct(BoardConfig $board_config)
    {
        parent::__construct();
        $this->board_config = $board_config;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $performance = $this->board_config->getperformancelist();
        if (count($performance)) {
            foreach($performance as $key => $value) {
                $list_id = $value->list->trello_list_id;
                $token = $value->board->owner_token;
                $card_info = [
                    'name' => 'performance_card_'.date('d/m/y'),
                    'desc' => 'Weakly based performance card',
                    'pos' => 'top',
                    'dueComplete' => "false"
                ];
                app('trello')->createCard($list_id, $token, $card_info);
            }
            $this->info("card create successfully");
            return; 
        }
        $this->error("No configuration found");
    }
}
