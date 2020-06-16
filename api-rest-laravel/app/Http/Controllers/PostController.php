<?php

namespace App\Http\Controllers;

use App\Helpers\Utilities;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth', ['except' => [
            'index',
            'show',
            'getFile',
            'getPostByCategory',
            'getPostByUser']
        ]);
    }

    public function index()
    {
        $posts = Post::all();

        $data = array(
            'code' => 200,
            'status' => 'success',
            'posts' => $posts
        );

        return response()->json($data, $data['code']);
    }

    public function store(Request $request)
    {
        // Collect data with POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {

            // Validate data
            $validate = Validator::make($params_array, [
                'title' => 'required',
                'content' => 'required',
                'category_id' => 'required|numeric',
                'image' => 'required',
            ]);

            // Save post
            if ($validate->fails()) {

                $data = array(
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Incorrect params.',
                    'errors' => $validate->errors()
                );

            } else {

                // Get identified user
                $utilities = new Utilities();
                $user = $utilities->getIdentified($request);

                // Create Post
                $post = new Post();
                $post->user_id = $user->sub;
                $post->category_id = $params_array['category_id'];
                $post->title = $params_array['title'];
                $post->content = $params_array['content'];
                $post->image = $params_array['image'];
                $post->save();

                $data = array(
                    'code' => 200,
                    'status' => 'success',
                    'post' => $post
                );

            }

        } else {

            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'No params received.'
            );

        }

        return response()->json($data, $data['code']);
    }

    public function create()
    {
        return "Create PostController";
    }

    public function destroy($id, Request $request)
    {
        // Get identified user
        $utilities = new Utilities();
        $user = $utilities->getIdentified($request);

        // Get post
        $post = Post::where('id', $id)
            ->where('user_id', $user->sub)
            ->first();

        if (!empty($post)) {

            // Delete post
            $post->delete();

            $data = array(
                'code' => 200,
                'status' => 'success',
                'post' => $post
            );

        } else {

            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'Post does not exists.'
            );

        }
        return response()->json($data, $data['code']);
    }

    public function update($id, Request $request)
    {
        // Collect data with POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {

            // Validate data
            $validate = Validator::make($params_array, [
                'title' => 'required',
                'content' => 'required',
                'category_id' => 'required|numeric',
                'image' => 'required',
            ]);

            // Save post
            if ($validate->fails()) {
                $data = array(
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Incorrect params.',
                    'errors' => $validate->errors()
                );
            } else {
                unset($params_array['id']);
                unset($params_array['user_id']);
                unset($params_array['created_at']);
                unset($params_array['user']);

                // Get identified user
                $utilities = new Utilities();
                $user = $utilities->getIdentified($request);

                // Update post
//                $where = [
//                   'id' => $id,
//                   'user_id' => $user->sub
//                ];
//                $post = Post::updateOrCreate($where, $params_array);
                $post = Post::where('id', $id)
                    ->first();

                if (!empty($post)) {

                    if ($post->user_id === $user->sub) {

                        $post = Post::where('id', $id)
                            ->update($params_array);

                        $data = array(
                            'code' => 200,
                            'status' => 'success',
                            'post' => $post,
                            'changes' => $params_array
                        );

                    } else {

                        $data = array(
                            'code' => 400,
                            'status' => 'error',
                            'message' => 'You do not have permission to update the post.'
                        );

                    }

                } else {
                    $data = array(
                        'code' => 400,
                        'status' => 'error',
                        'message' => 'Error to update post.'
                    );
                }
            }

        } else {

            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'No params received.'
            );

        }

        return response()->json($data, $data['code']);
    }

    public function show($id)
    {
        $post = Post::find($id);

        if (is_object($post)) {
            $data = array(
                'code' => 200,
                'status' => 'success',
                'post' => $post
            );
        } else {
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'Post does not exist.'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function edit()
    {
        return "Edit PostController";
    }

    public function upload(Request $request)
    {
        $utilities = new Utilities();
        return $utilities->uploadFile($request, 'required|image|mimes:jpg,jpeg,png,gif', 'posts');
    }

    public function getFile($filename)
    {
        $utilities = new Utilities();
        return $utilities->getFile($filename, 'posts');
    }

    public function getPostByCategory($id)
    {
        $posts = Post::where('category_id', $id)->get();

        $data = array(
            'code' => 200,
            'status' => 'success',
            'posts' => $posts
        );

        return response()->json($data, $data['code']);
    }

    public function getPostByUser($id)
    {
        $posts = Post::where('user_id', $id)->get();

        $data = array(
            'code' => 200,
            'status' => 'success',
            'posts' => $posts
        );

        return response()->json($data, $data['code']);
    }
}
