@extends('layouts.sidebar')

@section('content')
<!-- スクール詳細 -->
<div class="w-100 vh-100 d-flex" style="align-items:center; justify-content:center;">
    <div class="h-75 custom_box">
        @if(isset($reserveSetting) && $reserveSetting->users->isNotEmpty())
        <p class="margin_style2"><span>{{ \Carbon\Carbon::parse($reserveSetting->setting_reserve)->format('Y年m月d日') }}</span><span class="ml-3">{{ $reserveSetting->setting_part }}部</span></p>
        <div class="border reservation_container">
            <table class="custom_table">
                <thead class="table_header">
                    <tr class="text_area">
                        <th class="text_box text_left">ID</th>
                        <th class="text_box text_center">名前</th>
                        <th class="text_box text_right">場所</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reserveSetting->users as $index => $user)
                    <tr class="text_area {{ $index % 2 === 0 ? 'even-row' : 'odd-row' }}">
                        <td class="text_box text_left">{{ $user->id }}</td>
                        <td class="text_box text_center">{{ $user->over_name }}{{ $user->under_name }}</td>
                        <td class="text_box text_right">リモート</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p>予約が見つかりませんでした。</p>
        @endif
    </div>
</div>
@endsection
