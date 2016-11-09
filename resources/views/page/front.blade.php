@extends('_layouts.main')

@section('content')

    <div id="header">
        <a id="yc" href="http://www.ycombinator.com">
            <img src="https://news.ycombinator.com/y18.gif">
        </a>
        <h1><a href="/">Hacker News Wayback</a></h1>
        <span class="snapshot">
            Snapshot taken at:
            {{ $topStory->created_at }}
        </span>
        <span class="source">
            <a href="/about">About</a> |
            <a href="https://github.com/yadakhov/hackernewswayback">Source</a>
        </span>
    </div>

    <div>
        <a href="{{ route('front', ['at' => $previousDay]) }}">Previous Day</a> |
        <a href="{{ route('front', ['at' => $previousHour]) }}">Previous Hour</a>
    </div>

    <div class="news-view view v-transition">

        @foreach($items as $item)

            <div class="item">
                <span class="index">{{ ++$start }}.</span>
                <p>
                    <a class="title" href="{{ $item->url }}">
                        {{ $item->title }}
                    </a>
                    <span class="domain">
                        <a href="{{ $item->url }}">
                            <?php
                                $parts = parse_url($item->url);
                                if (isset($parts['host'])) {
                                    echo '('. $parts['host'] . ')';
                                }
                            ?>
                        </a>
                    </span>
                </p>
                <p class="subtext">
                    <span>
                        <a href="https://news.ycombinator.com/user?id={{ $item->by }}">
                            {{ $item->score }} points by {{ $item->by }}
                        </a>
                    </span>
                    on <a href="https://news.ycombinator.com/item?id={{ $item->id }}">{{ date('Y-m-d H:i:s', $item->time) }}</a>
                    <span class="comments-link"> |
                        <a href="https://news.ycombinator.com/item?id={{ $item->id }}">{{ $item->descendants }} comments</a>
                    </span>
                </p>
            </div>

        @endforeach

    </div>

@stop
