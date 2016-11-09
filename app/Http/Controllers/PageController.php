<?php

namespace App\Http\Controllers;

use App\Models\TopStory;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * GET /
     */
    public function front(Request $request)
    {
        $at = $request->input('at');

        if (!empty($at)) {
            $at = Carbon::createFromFormat('Y-m-d H', $at);
            $topStory = TopStory::where('created_at', '=', $at->toDateTimeString())->first();
        }

        if (empty($topStory)) {
            $topStory = TopStory::orderBy('created_at', 'desc')->first();
        }

        $itemIds = $topStory->items;
        $itemIds = json_decode($itemIds, true);

        $items = Item::whereIn('id', $itemIds)->orderByRaw($this->getOrderByField($itemIds))->get();

        $createdAt = $topStory->created_at;
        $previousHour = $createdAt->subHour(1)->format('Y-m-d H');
        $previousDay = $createdAt->subDay(1)->format('Y-m-d H');

        $data = [
            'topStory' => $topStory,
            'items' => $items,
            'start' => 0,
            'previousHour' => $previousHour,
            'previousDay' => $previousDay,
        ];

        return view('page.front', $data);
    }

    /**
     * Build order by FIELD(id, "1", "2", "3")
     *
     * @param $ids
     * @return string
     */
    protected function getOrderByField($ids)
    {    //
        return  'FIELD(id, "' . implode('", "', $ids) . '")';
    }

    /**
     * GET /about
     */
    public function about()
    {
        return view('page.about');
    }
}
