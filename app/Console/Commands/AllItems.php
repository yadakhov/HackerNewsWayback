<?php

namespace App\Console\Commands;

use App\Models\AllItem;
use Illuminate\Console\Command;
use Yadakhov\Curl;

class AllItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hn:allitems';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all items information.';

    /**
     * TopStories constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $startId = AllItem::orderBy('id', 'desc')->first();

        if (empty($startId)) {
            $startId = 1;
        } else {
            $startId = $startId->id;
        }

        $endId = Curl::getInstance()->get('https://hacker-news.firebaseio.com/v0/maxitem.json');

        for ($i = $startId; $i <= $endId; $i++) {
            $data = $this->getItem($i);

            if (isset($data['id'])) {
                AllItem::insertOnDuplicateKey($data);
                $this->info($i . ': done with ' . array_get($data, 'title') . ' of ' . $endId);
            }
        }
    }

    /**
     * Curl the item api and return as an array
     *
     * @param $id
     *
     * @return array
     */
    protected function getItem($id)
    {
        $url = sprintf('https://hacker-news.firebaseio.com/v0/item/%s.json', $id);

        $json = Curl::getInstance()->get($url);
        $json = json_decode($json, true);

        // For deleted items we just return the delete flag.
        if (!empty($json['deleted'])) {
            return [
                'id' => array_get($json, 'id'),
                'deleted' => array_get($json, 'deleted'),
            ];
        }

        $data = [
            'id' => array_get($json, 'id'),
            'deleted' => array_get($json, 'deleted', false),
            'type' => array_get($json, 'type'),
            'by' => array_get($json, 'by'),
            'time' => array_get($json, 'time'),
            'text' => array_get($json, 'text'),
            'dead' => array_get($json, 'dead', false),
            'parent' => array_get($json, 'parent'),
            'kids' => json_encode(array_get($json, 'kids')), // array
            'url' => array_get($json, 'url'),
            'score' => array_get($json, 'score'),
            'title' => array_get($json, 'title'),
            'parts' => json_encode(array_get($json, 'parts')),  // array
            'descendants' => array_get($json, 'descendants'),
        ];

        if (!empty($data['time'])) {
            $data['time'] = date('Y-m-d H:i:s', $data['time']);
        }

        return $data;
    }
}
