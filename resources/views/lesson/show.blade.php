<h1>{{ $lesson->name }}</h1>
<div>
    <span>空き状況: {{ $lesson->vacancyLevel->mark()}}</span>
</div>
<!-- ここから追加 -->
<div>
    <!--@ if($lesson->vacancyLevel->mark() === '◎' || $lesson->vacancyLevel->mark() === '△')-->
    @can('reserve', $lesson)
        <button class="btn btn-primary">このレッスンを予約する</button>
    @else
        <span class="btn btn-primary disabled">予約できません</span>
    <!--@ endif-->
    @endcan
</div>
<!-- ここまで追加 -->
