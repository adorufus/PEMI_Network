<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PostController extends Controller{
    public function getDashboard(){
        //order status dari yang terbaru
        $posts = Post::orderBy('created_at', 'desc')->get();
        return view('dashboard', ['posts' => $posts]);
    }
    
    //membuat post baru
    public function postCreatePost(Request $request){
        //validasi
        $this->validate($request, [
           'body' => 'required'
        ]);
        $post = new Post();
        $post->body = $request['body'];
        $message = "Terjadi sebuah kesalahan!";
        if($request->user()->posts()->save($post)){
            $message = "Status berhasil dikirim!";
        }
        return redirect()->route('dashboard')->with(['message' => $message]);
    }
    
    //menghapus post
    public function getDeletePost($post_id){
        $post = Post::where('id', $post_id)->first();
        if(Auth::user() != $post->user){
            return redirect()->back();
        }
        $post->delete();
        return redirect()->route('dashboard')->with(['message' => 'Status berhasil di hapus!']);
    }
}