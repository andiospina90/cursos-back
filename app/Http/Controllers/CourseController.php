<?php

namespace App\Http\Controllers;

use App\Models\course;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return response()->json(course::all(), 200);
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
                'course_name' => 'required',
                'course_duration' => 'required',
                'course_start_date' => 'required',
                'course_end_date' => 'required'
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors(), 400);
            }

            Course::create([
                'course_name' => $request->course_name,
                'course_duration' => $request->course_duration,
                'course_start_date' => $request->course_start_date,
                'course_end_date' => $request->course_end_date

            ]);

            return response()->json([
                'message' => 'Course created successfully',
                'status' => 'success'
                
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Course creation failed',
                'status' => 'failed'
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(course $course)
    {
        return response()->json($course, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, course $course)
    {

        try {
            $validate = Validator::make($request->all(), [
                'course_name' => 'required',
                'course_duration' => 'required',
                'course_start_date' => 'required',
                'course_end_date' => 'required'
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors(), 400);
            }

            $course->update([
                'course_name' => $request->course_name,
                'course_duration' => $request->course_duration,
                'course_start_date' => $request->course_start_date,
                'course_end_date' => $request->course_end_date

            ]);

            return response()->json([
                'message' => 'Course updated successfully',
                'status' => 'success',
                'code' => 200
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Course update failed',
                'status' => 'failed',
                'code' => 400
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(course $course)
    {
        try {
            $course->delete();
            return response()->json([
                'message' => 'Course deleted successfully',
                'status' => 'success',
                'code' => 200
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Course deletion failed',
                'status' => 'failed',
                'code' => '400'
            ], 400);
        }
    }

    public function addStudent(Request $request, course $course, User $student)
    {
        try {

           
            $check = $course->students()->where('student_id', $student->id)->exists();
            
            if($check){
                return response()->json([
                    'message' => 'Student already registered for course',
                    'status' => 'failed',
                    'code' => 400
                ], 400);
            }

            $course->students()->attach($student->id);

            
            return response()->json([
                'message' => 'Student added to course successfully',
                'status' => 'success',
                'code' => 200
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Student addition to course failed',
                'status' => 'failed',
                'code' => 400,
                'data' => $th->getMessage()
            ], 400);
        }
    }

    public function removeStudent(Request $request, course $course, User $student)
    {
        try {

            $check = $course->students()->where('student_id', $student->id)->exists();
            
            if(!$check){
                return response()->json([
                    'message' => 'Student not registered for course',
                    'status' => 'failed',
                    'code' => 400
                ], 400);
            }

            $course->students()->detach($student->id);

            
            return response()->json([
                'message' => 'Student removed from course successfully',
                'status' => 'success',
                'code' => 200
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Student removal from course failed',
                'status' => 'failed',
                'code' => 400,
                'data' => $th->getMessage()
            ], 400);
        }
    }

    public function getCourseByStudent(User $student)
    {
        try {

            $courses = $student->courses()->get();
            
            return response()->json([
                'message' => 'Courses fetched successfully',
                'status' => 'success',
                'code' => 200,
                'data' => $courses
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Courses fetch failed',
                'status' => 'failed',
                'code' => 400,
                'data' => $th->getMessage()
            ], 400);
        }
    }


    public function getStudentsCourses(){
        try {

            $enrrolledStuednts = DB::table('course_student')
            ->join('courses', 'courses.id', '=', 'course_student.course_id')
            ->join('users', 'users.id', '=', 'course_student.student_id')
            ->get();

            return response()->json([
                'message' => 'Courses fetched successfully',
                'status' => 'success',
                'code' => 200,
                'data' => $enrrolledStuednts
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Courses fetch failed',
                'status' => 'failed',
                'code' => 400,
                'data' => $th->getMessage()
            ], 400);
        }
    }
}
