<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function dashboard(){
        return view('user.dashboard');
    }


    public function register (request $request){
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'name' => 'required|max:30',
            'device_name' => 'required|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json(
                [ $validator->errors()],400
            );
        }else
        {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'device_name' => $request->device_name,
            ]);
            
            $user->attachRole('user');
            $user = User::where('email', $request->email)->first();
            return $user->createToken($request->device_name)->plainTextToken;
        }



    }
    public function login( request $request){
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json(
                [ $validator->errors()],400
            );
        }

        $user = User::where('email', $request->email)->first();
        
        if (! $user || ! Hash::check($request->password, $user->password)) {

            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);

            //return response()->json(
            //        ['error' => 'the provided credentials were incorrect']
            //);
        }
        
        //$user->tokens()->delete();
        return $user->createToken($request->device_name)->plainTextToken;
    }

    public function posts(request $request){
        $posts = Post::get();
        return $posts;
    }

    public function index(request $request){
        return Auth()->user()->posts;
    }

    public function newPost(Request $request){

        
        $validator = Validator::make($request->all(), [
            'Title' => 'required|max:50',
            'Description' => 'required|max:255',
        ]);


        if ($validator->fails()) {
            return response()->json(
                ['error' => $validator->errors()],400
            );
        }

        if($post = Post::create([
            'Title' => $request->Title,
            'Description' => $request->Description,
            'Status' => false,
            'user_id' => auth()->id()
        ]));
        
        return response()->json(
            'success'
        );

    }


    public function deletePost (Post $Post){
        
        $this->authorize('delete', $Post);

        // $post = Post::find(['id' => request('id')])->first();
        // if ($post->user_id !== auth()->user()->id){
        //     return "no perms";
        // }else{
        //     if($post->delete()){
        //         return "deleted post";
        //     }else{
        //         return "there was an error deleting the post";
        //     }
        // };


    }


}
