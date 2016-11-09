<?php

namespace App\Http\Controllers;

use App\Models\TopStory;
use App\Models\Item;

class PageController extends Controller
{
    /**
     * GET /
     */
    public function front()
    {
        $topStory = TopStory::orderBy('created_at', 'desc')->first();

        $itemIds = $topStory->items;
        $itemIds = json_decode($itemIds, true);

        $items = Item::whereIn('id', $itemIds)->get();

        $start = 0;

        $data = [
            'topStory' => $topStory,
            'items' => $items,
            'start' => $start,
        ];

        return view('page.front', $data);
    }

    /**
     * GET /about
     */
    public function about()
    {
        return view('page.about');
    }
}
