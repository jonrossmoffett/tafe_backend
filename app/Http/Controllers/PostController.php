<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;

class PostController extends Controller
{
    public function index(Request $request){
        return Auth()->user()->posts;
    }

    public function store(Request $request){
        $post = new Post;
        $post->Title = $request->Title;
        $post->Description = $request->Description;
        $post->Status = false;
        $post->save();
    }
    public function destroy(Request $request){
        $post = Post::find($request->id);
        
        if($post->delete()){
            return response()->json(
                ['deleted post']
            );
        }
        

        return response()->json(
            ['could not delete post']
        );

        // if the middleware is removed implement the bellow function
        //$user = Auth()->user();
        //$response = Gate::forUser($user)->inspect('delete-post', $post);
        //if($response->allowed()){
            //$post->delete();
        //}else{
           // echo $response->message();
        //}
    }
    public function viewPost(request $request){
        $post = Post::find($request->id);
        return $post;
        // if the middleware is removed implement the bellow function
        //$user = Auth()->user();
        //$response = Gate::forUser($user)->inspect('view-post',$post);
        //if($response->allowed()){
            // return $post;
        //}else{
            //echo $response->message();
        //}
    }
    public function editPost(request $request){

        $validator = Validator::make($request->all(), [
            'Title' => 'required|max:30',
            'Description' => 'required|max:255',
            'Status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(
                [ $validator->errors()],400
            );
        }else{

            $post = Post::find($request->id);

            $post->Title = $request->Title;
            $post->Description = $request->Description;
            $post->Status = $request->Status;
            $post->save();

             return response()->json(['edited post']);

        }



        // if the middleware is removed implement the bellow function
        //$user = Auth()->user();
        //$response = Gate::forUser($user)->inspect('delete-post', $post);
        //if($response->allowed()){
            //$post->delete();
        //}else{
           // echo $response->message();
        //}
    }
}
