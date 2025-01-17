@extends('layouts.app')
<style type="text/css">
    .fas{
        font-family: fontawesome;
    }
    .topic-image{
        max-width: 500px;
    }
</style>
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <div class="panel panel-default">
                <div class="panel-heading">Article View</div>

                <div class="panel-body">

                    <div class="col-md-4">
                        <ul class="list-group">
                            @if(count($categories) > 0)
                            @foreach($categories->all() as $category)
                            <li class="list-group-item">
                                <a href='{{ url("category/{$category->id}") }}'>{{$category->category}}</a>
                            </li>
                            @endforeach

                            @else
                            <p>No Category Found</p>

                            @endif
                        </ul>
                    </div>
                    <div class="col-md-8">
                        @if(count($posts) > 0)
                        @foreach($posts->all() as $post)
                        <h4>{{$post->post_title}}</h4>
                        <img class="topic-image" src="{{ $post->post_image }}" alt="">
                        <p>{{$post->post_body}}</p>

                        <cite style="">Published on: {{date('M j, Y H:i', strtotime($post->updated_at))}}</cite>
                        
                        <!-- <ul class="nav nav-pills">
                            <li role="presentation"> 
                                <a href='{{ url("/like/{$post->id}") }}'>
                                    <span class="fas fa-thumbs-up">Like ()</span>
                                </a>
                            </li>
                            <li role="presentation"> 
                                <a href='{{ url("/dislike/{$post->id}") }}'>
                                    <span class="fas fa-thumbs-down">Dislike ()</span>
                                </a>
                            </li>
                            <li role="presentation"> 
                                <a href='{{ url("/comment/{$post->id}") }}'>
                                    <span class="fas fa-comment">Comment</span>
                                </a>
                            </li>
                        </ul> -->
                        <hr/>

                        @endforeach
                        @else
                        <p>No Articles Available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection