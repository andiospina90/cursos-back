<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return response()->json(User::all(), 200);
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
        try {
            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'last_name' => 'required',
                'age'=> 'required',
            ]);
    
            if ($validate->fails()) {
                return response()->json($validate->errors(), 400);
            }

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'last_name' => $request->last_name,
                'age' => $request->age,
            ]);

            return response()->json([
                'message' => 'Student created successfully',
                'status' => 'success',
                'code' => 200
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Student updated failed',
                'status' => 'failed',
                'code' => 400
            ], 400);
        }





    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User  $student)
    {
        return response()->json($student, 200);
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
    public function update(Request $request, User $student)
    {
        try {
            
            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'last_name' => 'required',
                'age'=> 'required',
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors(), 400);
            }

            $student->update([
                'name' => $request->name,
                'email' => $request->email,
                'last_name' => $request->last_name,
                'age' => $request->age,
            ]);

            return response()->json([
                'message' => 'Student updated successfully',
                'status' => 'success',
                'code' => 200
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Student updated failed',
                'status' => 'failed',
                'code' => 400
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $student)
    {
        try {
            $student->delete();
            return response()->json([
                'message' => 'User deleted successfully',
                'status' => 'success',
                'code' => 200
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Student deleted failed',
                'status' => 'failed',
                'code' => 400
            ], 400);
        }
    }
}
