<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use App\Category;
use App\Post;
use App\Like;
use App\Dislike;
use App\Comment;
use Auth;

class PostController extends Controller
{
    public function post(){
    	$categories = Category::all();
    	return view('posts.post', ['categories' => $categories]);
    }

    public function addPost(Request $request){
    	$this->validate($request, [
    		'post_title' => 'required',
    		'post_body' => 'required',
    		'category_id' => 'required',
    		'post_image' => 'required'
    	]);
    	$post = new Post;
    	$post->post_title = $request->input('post_title');
    	$post->user_id = Auth::user()->id;
    	$post->post_body = $request->input('post_body');
    	$post->category_id = $request->input('category_id');
    	if(Input::hasFile('post_image')){
    		$file = Input::file('post_image');
    		$file->move(public_path(). '/posts/', $file->getClientOriginalName());
    		$url = URL::to("/") . '/posts/'. $file->getClientOriginalName();
    	}
    	$post->post_image = $url;
    	$post->save();
    	return redirect('/home')->with('response', 'Article added Succesfully!');
    }

    public function view($post_id){
        $posts = Post::where('id', '=', $post_id)->get();
        $likePost = Post::find($post_id);
        $likeCount = Like::where(['post_id' => $likePost->id])->count();
        $dislikeCount = Dislike::where(['post_id' => $likePost->id])->count();
        $categories = Category::all();
        $comments = DB::table('users')
        ->join('comments', 'users.id', '=', 'comments.user_id')
        ->join('posts', 'comments.post_id', '=', 'posts.id')
        ->select('users.name', 'comments.*')
        ->where(['posts.id' => $post_id])
        ->get();
        return view('posts.view', ['posts' => $posts, 'categories' => $categories, 'likeCount' => $likeCount, 'dislikeCount' => $dislikeCount, 'comments' => $comments]);
    }

    public function edit($post_id){
       $categories = Category::all();
       $posts = Post::find($post_id);
       $category = Category::find($posts->category_id);
       return view('posts.edit', ['categories' => $categories, 'posts' => $posts, 'category' => $category]);
    }

    public function editPost(Request $request, $post_id){
       $this->validate($request, [
          'post_title' => 'required',
          'post_body' => 'required',
          'category_id' => 'required',
          'post_image' => 'required'
      ]);
       $post = new Post;
       $post->post_title = $request->input('post_title');
       $post->user_id = Auth::user()->id;
       $post->post_body = $request->input('post_body');
       $post->category_id = $request->input('category_id');
       if(Input::hasFile('post_image')){
          $file = Input::file('post_image');
          $file->move(public_path(). '/posts/', $file->getClientOriginalName());
          $url = URL::to("/") . '/posts/'. $file->getClientOriginalName();
      }
      $post->post_image = $url;
      $data = array(
          'post_title' => $post->post_title,
          'user_id' => $post->user_id,
          'post_body' => $post->post_body,
          'category_id' => $post->category_id,
          'post_image' => $post->post_image,
      );
      Post::where('id', $post_id)
      ->update($data);
      $post->update();
      return redirect('/home')->with('response', 'Article updated Succesfully!');
    }

    public function deletePost($post_id){
     Post::where('id', $post_id)
     ->delete();
     return redirect('/home')->with('response', 'Article deleted Succesfully!');
    }

    public function category($category_id){
       $categories = Category::all();
       $posts = DB::table('posts')
       ->join('categories', 'posts.category_id', '=', 'categories.id')
       ->select('posts.*', 'categories.*')
       ->where(['categories.id' => $category_id])
       ->get();
       return view('categories.categoriesposts', ['categories' => $categories, 'posts' => $posts]);
    }

    public function like($id){
       $loggedin_user = Auth::user()->id;
       $like_user = Like::where(['user_id' => $loggedin_user, 'post_id' => $id])->first();
        if(empty($like_user->user_id)){
          $user_id = Auth::user()->id;
          $email = Auth::user()->email;
          $post_id = $id;
          $like = new Like;
          $like->$user_id = $user_id;
          $like->$email = $email;
          $like->$post_id = $post_id;
          $like->save();
          return redirect("/view/{$id}");
            }
        else{
          return redirect("/view/{$id}");
        }
    }

    public function dislike($id){
        $loggedin_user = Auth::user()->id;
        $like_user = Dislike::where(['user_id' => $loggedin_user, 'post_id' => $id])->first();
        if(empty($like_user->user_id)){
            $user_id = Auth::user()->id;
            $email = Auth::user()->email;
            $post_id = $id;
            $like = new Dislike;
            $like->$user_id = $user_id;
            $like->$email = $email;
            $like->$post_id = $post_id;
            $like->save();
            return redirect("/view/{$id}");
        }
        else{
            return redirect("/view/{$id}");
        }
    }

    public function comment(Request $request, $post_id){
        $this->validate($request, [
            'comment' => 'required'
        ]);
        $comment = new Comment;
        $comment->user_id = Auth::user()->id;
        $comment->post_id = $post_id;
        $comment->comment = $request->input('comment');
        $comment->save();
        return redirect("/view/{$post_id}")->with('response', 'Comment updated Succesfully!');

    }
}