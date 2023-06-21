<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FollowController extends Controller
{
    //
    public function createFollow(User $user) {
        // you cannot follow yourself
        if ($user->id === auth()->user()->id) {
            return back()->with('failure', 'you cannot follow yourself');
        }
        //you cannot follow someone you're already following
        $existCheck = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();

        if ($existCheck) {
            return back()->with('failure', 'you are already following the user');
        }

        $newFollow = new Follow;
        $newFollow->user_id = auth()->user()->id;
        $newFollow->followedUser = $user->id;
        $newFollow->save();

        return back()->with('success', 'User successfully followed.');
    }

    public function removeFollow(User $user) {
        Follow::where([['user_id', '=', auth()->user()->id], ['followedUser', '=', $user->id]])->delete();
        
        return back()->with('success', 'user successfully unfollow');
    }

}
