<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();

        $data = array(
            'code' => 200,
            'status' => 'success',
            'categories' => $categories
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
                'name' => 'required'
            ]);

            // Save category
            if ($validate->fails()) {
                $data = array(
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Incorrect params.',
                    'errors' => $validate->errors()
                );
            } else {
                $category = new Category();
                $category->name = $params_array['name'];
                $category->save();

                $data = array(
                    'code' => 200,
                    'status' => 'success',
                    'category' => $category
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
        return "Create CategoryController";
    }

    public function destroy()
    {
        return "Destroy CategoryController";
    }

    public function update($id, Request $request)
    {
        // Collect data with POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {

            // Validate data
            $validate = Validator::make($params_array, [
                'name' => 'required'
            ]);

            // Save category
            if ($validate->fails()) {
                $data = array(
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Incorrect params.',
                'errors' => $validate->errors()
                );
            } else {
                unset($params_array['id']);
                unset($params_array['created_at']);

                $category = Category::where('id', $id)->update($params_array);

                if ($category) {
                    $data = array(
                        'code' => 200,
                        'status' => 'success',
                        'category' => $params_array
                    );
                } else {
                    $data = array(
                        'code' => 400,
                        'status' => 'error',
                        'message' => 'Error to update category.'
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
        $category = Category::find($id);

        if (is_object($category)) {
            $data = array(
                'code' => 200,
                'status' => 'success',
                'category' => $category
            );
        } else {
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'Category does not exist.'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function edit()
    {
        return "Edit CategoryController";
    }
}
