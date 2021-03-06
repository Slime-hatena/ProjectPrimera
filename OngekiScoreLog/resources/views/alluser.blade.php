@extends('layouts.app')

@section('title', 'すべてのユーザー')
@section('sidemark_alluser', "is-active")
@section('hero_title', "すべてのユーザー")
@section('additional_footer')
    <script type="text/javascript" src="{{ mix('/js/sortAllUserTable.js') }}"></script>
@endsection

@section('content')
    <article class="box">
        <div id="sort_table" class="table_wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th class="sort" data-sort="sort_id">ID</th>
                        <th class="sort" data-sort="sort_name">Name</th>
                        <th class="sort" data-sort="sort_trophy">Trophy</th>
                        <th class="sort" data-sort="sort_lv">Lv</th>
                        <th class="sort" data-sort="sort_rating">Rating</th>
                        <th class="sort" data-sort="sort_max">(Max)</th>
                        <th class="sort" data-sort="sort_bp">BP</th>
                        <th class="sort" data-sort="sort_update">Update</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="sort" data-sort="sort_id">ID</th>
                        <th class="sort" data-sort="sort_name">Name</th>
                        <th class="sort" data-sort="sort_trophy">Trophy</th>
                        <th class="sort" data-sort="sort_lv">Lv</th>
                        <th class="sort" data-sort="sort_rating">Rating</th>
                        <th class="sort" data-sort="sort_max">(Max)</th>
                        <th class="sort" data-sort="sort_bp">BP</th>
                        <th class="sort" data-sort="sort_update">Update</th>
                    </tr>
                </tfoot>
                <tbody class="list">
                    @foreach ($users as $item)
                        <tr>
                            <td class="sort_id">{{$item->user_id}}</td>
                        <td class="sort_name"><span class="sort-key">{{mb_convert_kana($item->name, 'a')}}</span><a href="/user/{{$item->user_id}}">{{$item->name}}</a></td>
                            <td class="sort_trophy">{{$item->trophy}}</td>
                            <td class="sort_lv">{{$item->level}}</td>

                            @if (strtotime($item->updated_at) >= strtotime(config('env.ongeki-version-date')))
                                <td class="sort_rating">{{$item->rating}}</td>
                                <td class="sort_max">{{$item->rating_max}}</td>
                            @else
                                <td class="sort_rating">({{$item->rating}})</td>
                                <td class="sort_max">({{$item->rating_max}})</td>
                            @endif
                            
                            <td class="sort_bp">{{$item->battle_point}}</td>
                            <td class="sort_update">{{date('Y-m-d', strtotime($item->updated_at))}}<span class="sort-key">{{date('H:i:s', strtotime($item->updated_at))}}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </article>
@endsection
