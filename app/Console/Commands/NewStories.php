<?php

namespace App\Console\Commands;

use App\Models\NewStory;
use Illuminate\Console\Command;
use Yadakhov\Curl;

class NewStories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hn:newstories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Take a snapshot of all the new stories.';

    /**
     * TopStories constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Check if last hours exists
        $lastHour = date('Y-m-d H:00:00', strtotime('now'));

        if (NewStory::where('created_at', $lastHour)->exists()) {
            $this->info('Last hour snapshot already exists.');
            exit;
        }

        $url = 'https://hacker-news.firebaseio.com/v0/newstories.json';
        $data = Curl::getInstance()->get($url);

        $row = new NewStory();
        $row->items = $data;
        $row->done = false;
        $row->created_at = $lastHour;
        $row->save();

        $this->info('Snapshot taken for: ' . $row->created_at);
    }
}
