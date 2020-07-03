@extends('layouts.app')
<style type="text/css">
    .avatar{
        border-radius:100%;
        max-width: 100px;
    }
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
            @if(count($errors) > 0)
            @foreach($errors->all() as $error)
            <div class="alert alert-danger">{{$error}}</div>
            @endforeach
            @endif

            @if(session('response'))
            <div class="alert alert-success">{{session('response')}}</div>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">Blog</div>

                <div class="panel-body">
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif

                    <div class="col-md-4">
                        @if(!empty($profile))
                        <img src="{{ $profile->profile_picture }}" class="avatar" alt="" />
                        @else
                        <img src="{{ url('images/avatar.png') }}" class="avatar" alt="" />
                        @endif

                        @if(!empty($profile))
                        <p class="lead">{{ $profile->name }}</p>
                        @else
                        <p></p>
                        @endif

                        @if(!empty($profile))
                        <p class="lead">{{ $profile->designation }}</p>
                        @else
                        <p></p>
                        @endif

                    </div>
                    <div class="col-md-8">
                        @if(count($posts) > 0)
                        @foreach($posts->all() as $post)
                        <h4>{{$post->post_title}}</h4>
                        <img class="topic-image" src="{{ $post->post_image }}" alt="">
                        <p>{{ substr($post->post_body, 0, 350) }}</p>

                        <ul class="nav nav-pills">
                            <li role="presentation"> 
                                <a href='{{ url("/view/{$post->id}") }}'>
                                    <span class="fas fa-eye">View</span>
                                </a>
                            </li>
                            @if(Auth::id() == 1)
                            <li role="presentation"> 
                                <a href='{{ url("/edit/{$post->id}") }}'>
                                    <span class="fas fa-edit">Edit</span>
                                </a>
                            </li>
                            <li role="presentation"> 
                                <a href='{{ url("/delete/{$post->id}") }}'>
                                    <span class="fas fa-trash">Delete</span>
                                </a>
                            </li>
                            @endif
                        </ul>

                        <cite style="">Published on: {{date('M j, Y H:i', strtotime($post->updated_at))}}</cite>
                        <hr/>
                        @endforeach
                        @else
                        <p>No Articles Available</p>
                        @endif

                        {{$posts->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection