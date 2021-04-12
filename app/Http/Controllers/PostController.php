<?php

namespace App\Http\Controllers;

use App\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * create a post
     * content is required and min length is 4
     * return post if created successfully
     */
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'content'    => 'required|string|min:4',
        ], [
            'content.required' => "Veuillez saisir le contenu",
            'content.min' => "Le champ contenu doit contenir au moins 4 caractéres",
        ]);
        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->messages()->first(), 400
            ]);
        }
        $post = new Post();
        $post->content = $request->content;
        $post->user_id = auth()->user()->id;
        $post->created_at = Carbon::now();
        $post->save();
        return response()->json([
           'message'=>'post created successfuly',
           'data'=>$post->load('user'),
        ],200);
    }
    /**
     * delete a post
     * delete all post comments
     * return success message if deleted successfully
     */
    public function delete($id)
    {
        $post = Post::find($id);
        if(is_null($post)){
            return response()->json([
                'message' => 'post not found',
            ], 404);
        }
        if($post->user_id != auth()->user()->id){
            return response()->json([
                'message' => 'Not authorized !',
            ], 403);
        }
        $post->comments()->delete();
        $post->delete();
        return response()->json([
            'message' => 'post deleted successfuly',
        ], 200);
    }
    /**
     * fetch all posts
     * return posts with comments and user
     */
    public function posts(Request $request)
    {
        $posts = Post::orderBy('created_at','desc')->with('user','comments.user')->get();
        return response()->json([
            'message' => 'success',
            'data' => $posts,
        ], 200);
    }


}
