<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Validator;

class TodoListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todolists = TodoList::with('category')->get(['id','category_id','title']);

        return response()->json([
            'message'=>'Todo List!!',
            'data'=>$todolists
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'category_id' => 'required',
            'title' => 'required|unique:todo_lists,title',
        ]);

        if($validator->fails()){
            $response = [
                'success' => 'false',
                'data' => 'Validation Error.',
                'message' => $validator->errors(),
            ];
            return response()->json($response,400);
        }
        $todoList = TodoList::create($request->post());

        return response()->json([
            'message'=>'ToDo Created Successfully!!',
            'category'=>$todoList
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $todolists = TodoList::where('category_id',$id)->get(['id','category_id','title']);
        return response()->json([
            'message'=>'Todo List!!',
            'data'=>$todolists
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $todoList = TodoList::find($id);
        $todoList->delete();
        return response()->json([
            'message'=>'Todo Delete Successfully Done.',
        ]);
    }
}
