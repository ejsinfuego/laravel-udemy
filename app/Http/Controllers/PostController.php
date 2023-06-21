<?php

namespace App\Http\Controllers;

use App\Jobs\SendNewPostEmail;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class PostController extends Controller
{
    //
    public function search($term) {
        $posts = Post::search($term)->get();
        $posts->load('user:id,username,avatar');
        return $posts;
    }
    public function actuallyUpdate(Post $post, Request $request){
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        $post->update($incomingFields);

        return back()->with('success', 'Post successfully updated.');
    }
    public function showEditForm(Post $post) {

        return view('edit-post', ['post' => $post]);
    }
    public function delete(Post $post) {
        $post->delete();
        return redirect('/profile/' . auth()->user()->username)->with('success', 'you successfully deleted');
    }

    public function deleteApi(Post $post) {
        $post->delete();
        return 'true';
    }
    public function viewSinglePost(Post $post) {
        $post['body'] = strip_tags(Str::markdown($post->body), '<p><ul><ol><li><strong><em><h3>');

        return view('single-post', ['post' => $post]);
    }
    public function storeNewPost(Request $request) {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();

        $newPost = Post::create($incomingFields);

        dispatch(new SendNewPostEmail(['sendTo' => auth()->user()->email,'name' => auth()->user()->username, 'title' => $newPost->title]));
        

        return redirect("/post/{$newPost->id}")->with('success', 'New post succesfully created.');
    }

    public function storeNewPostAPi(Request $request) {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();

        $newPost = Post::create($incomingFields);

        dispatch(new SendNewPostEmail(['sendTo' => auth()->user()->email,'name' => auth()->user()->username, 'title' => $newPost->title]));
        

        return $newPost->id;
    }
    public function showCreateForm() {
        return view('create-post');
    }
}
