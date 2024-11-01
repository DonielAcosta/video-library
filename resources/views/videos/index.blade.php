@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Videos</h1>

    @foreach ($videos as $video)
        <div class="video">
            <h3>{{ $video->title }}</h3>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/{{ $video->youtube_url }}" frameborder="0" allowfullscreen></iframe>
            <p>{{ $video->description }}</p>
        </div>
    @endforeach

    <h2>YouTube Search Results</h2>
    @foreach ($youtubeVideos as $youtubeVideo)
        <div class="youtube-video">
            <h3>{{ $youtubeVideo['snippet']['title'] }}</h3>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/{{ $youtubeVideo['id']['videoId'] }}" frameborder="0" allowfullscreen></iframe>
            <p>{{ $youtubeVideo['snippet']['description'] }}</p>
        </div>
    @endforeach
</div>
@endsection
