<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CommentController extends Controller
{
    /**
     * create a comment
     * content and post_id are required
     * return a comment if created successfully
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content'    => 'required|string|min:4',
            'post_id'    => 'required'
        ], [
            'content.required' => "Veuillez saisir le contenu",
            'content.min' => "Le champ contenu doit contenir au moins 4 caractéres",
        ]);
        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->messages()->first(), 400
            ]);
        }
        $post = Post::find($request->post_id);
        if(is_null($post)){
            return response()->json([
                'message' => 'post not found',
            ], 404);
        }
        $comment = new Comment();
        $comment->content = $request->content;
        $comment->user_id = auth()->user()->id;
        $comment->post_id =$post->id;
        $comment->created_at = Carbon::now();
        $comment->save();
        return response()->json([
            'message' => 'comment created successfuly',
            'data' => $comment->load('user'),
        ], 200);
    }
    /**
     * delete comment
     * return success message if deleted successfully
     */
    public function delete($id)
    {
        $comment = Comment::find($id);
        if (is_null($comment)) {
            return response()->json([
                'message' => 'comment not found',
            ], 404);
        }
        if ($comment->user_id != auth()->user()->id) {
            return response()->json([
                'message' => 'Not authorized !',
            ], 403);
        }
        $comment->delete();
        return response()->json([
            'message' => 'comment deleted successfuly',
        ], 200);
    }
    /**
     * get all comments for a given post and load user
     * return comments with user
     */

    public function postComments($id)
    {
        $post = Post::find($id);
        if (is_null($post)) {
            return response()->json([
                'message' => 'post not found',
            ], 404);
        }
        $comments= Comment::where('post_id',$post->id)->orderBy('created_at','desc')->with('user')->get();
        return response()->json([
            'message' => 'success',
            'data' => $comments,
        ], 200);
    }
}
