<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class HomeController extends Controller
{
    private $post;
    private $user;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
    }

    /**
     * Show the application dashboard. 
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $home_posts = $this->getHomePosts();
        $suggested_users = $this->getSuggestedUsers();

        return view('users.home')
                ->with('home_posts', $home_posts)
                ->with('suggested_users', $suggested_users);
    }

    private function getHomePosts()
    {
        $all_posts = $this->post->latest()->get();
        $home_posts = []; //In case the $home_posts is empty, it will not return NULL, but empty instead

        foreach($all_posts as $post)
        {
            if($post->user->isFollowed() || $post->user->id === Auth::user()->id){
                $home_posts[] = $post;
            }
        }

        return $home_posts;
    }

    private function getSuggestedUsers()
    {
        $all_users = $this->user->all()->except(Auth::user()->id);
        $suggested_users = [];

        foreach($all_users as $user){
            if(!$user->isFollowed()){
                $suggested_users[] = $user;
            }
        }

        return array_slice($suggested_users,0,2);
    }

    public function show()
    {
       $authUserId = Auth::user()->id;

           $suggested_users = User::where('id', '!=', $authUserId)
                                    ->whereDoesntHave('followers', function($query) use ($authUserId){
                                        $query->where('follower_id', $authUserId);
                                    })
                                    ->paginate(6);
             return view('users.suggestions')
                     ->with('suggested_users', $suggested_users);
    }

    public function search(Request $request)
{   

    $users = $this->user->where('name', 'like', '%' . $request->search . '%')->paginate(10); 
    
    return view('users.search')->with('users', $users)->with('search', $request->search);
}
}
