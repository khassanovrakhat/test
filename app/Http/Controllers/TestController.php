<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Student;
use App\Models\Question;
use \Firebase\JWT\JWT;
use Matrix\Exception;

class TestController extends Controller
{
    // private $key = "secretkey";
    public function create(Request $request){
        if(empty($request->token)){
            return "not registered";
        }
        
        $body = JWT::decode($request->token, env('JWT_SECRET'), ['HS256']);
        $test = new Test();
        try {
            // $test->description = $request->description;
            $test->title = $request->title;
            $test->subject = $request->subject;
            $test->teacher_id = $body->data->user_id;
        }
        catch (Exception $e){
            return $e->getMessage();
        }
        $test->save();
        return response()->json(array('message' => 'succeed', 'code' => 0));
    }
    public function update(Request $request){
        $test = Test::where('id',$request->only('id'))->first();
        if(!$test){
            return response()->json(array('message' => 'Test doenst exist', 'code' => 1));
        }
        try {
            $test->description = $request->description;

            $test->title = $request->title;
            $test->subject = $request->subject;
            if($request->student_id){
                $test->students()->detach();
                $students = Student::whereIn('id', $request->students_id)->get();
                $test->students()->attach($students);
            }
        }
        catch(Exception $e){
            return $e->getMessage();
        }

        $test->save();
        return response()->json(array('message' => 'succeed', 'code' => 0));
    }
    public function delete(Request $request){
        if(!Test::where('id', $request->only('id'))){
            return "Test doenst exist";
        }
        Test::where('id', $request->only('id'))->delete();
        Question::where('test_id', $request->only('id'))->delete();
        return "Succeed";
    }
    public function viewTests(Request $request){
        $body = JWT::decode($request->token, env('JWT_SECRET'), ['HS256']);
        $tests = Test::where('teacher_id', $body->data->user_id)->get();
        return response()->json($tests);
    }
    public function purpose(Request $request){
        $test = Test::where('id', $request->id)->first();
        $time = $request->end_time;
        // echo $request->id; 
        //return date('d/M/Y h:i:s', strtotime($time));
        $students = Student::whereIn('id', $request->students_id)->get();
        $test->students()->attach($students, ['begin_time' => date('d/m/Y H:i:s', strtotime($request->begin_time)),
                                             'end_time' => date('d/m/Y H:i:s', strtotime($request->end_time)),
                                             'size' => $request->test_size]);
    }
}