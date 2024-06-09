@extends('layouts.sidebar')

@section('content')
<!-- スクール詳細 -->
<div class="vh-100 d-flex" style="align-items:center; justify-content:center;">
  <div class="w-50 m-auto h-75">
    @if(isset($reserveSetting) && $reserveSetting->users->isNotEmpty())
      <p><span>{{ $reserveSetting->setting_reserve }}</span><span class="ml-3">{{ $reserveSetting->setting_part }}部</span></p>
      <div class="h-75 border">
        <table class="">
          <tr class="text-center">
            <th class="w-25">ID</th>
            <th class="w-25">名前</th>
            <th class="w-25">場所</th>
          </tr>
          @foreach($reserveSetting->users as $user)
          <tr class="text-center">
            <td class="w-25">{{ $user->id }}</td>
            <td class="w-25">{{ $user->over_name }}{{ $user->under_name }}</td>
            <td class="w-25">リモート</td>
          </tr>
          @endforeach
        </table>
      </div>
    @else
      <p>予約が見つかりませんでした。</p>
    @endif
  </div>
</div>
@endsection
