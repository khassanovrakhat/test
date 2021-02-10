<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\Test;
use App\Models\Student;
use App\Models\Results;
use \Firebase\JWT\JWT;


class StudentController extends Controller
{
    public function check($answers){
        $total = 0;
        $arr = [];
        foreach($answers as $answer){
            $ans = Answer::where('id', $answer->id)->first();
            $question = $ans->question;
            if(array_key_exists($question->id, $arr)){
                if($arr[$question->id] == -1){
                    continue;
                }
                if($ans->score==0){
                    $arr[$question->id] = -1;
                }else{
                    $ans[$question->id] += $ans->score;
                }
            }
            else{
                if($ans->score==0){
                    $arr[$question->id] = -1;
                }else {
                    $ans[$question->id] += $ans->score;
                }
            }
        }
        $marks = array_values($arr);
        foreach ($marks as $mark){
            $total += max(0, $mark);
        }
        return $total;
    }
    public function viewTest(Request $request){
        try{
            $body = JWT::decode($request->token, env('JWT_SECRET'), ['HS256']);
            $tests = Test::where('id', $request->test_id)->first();
            // print_r($tests->title); exit();
            // foreach($tests as $test){
                return response()->json(array('result' => $tests->title, 'code' => 0));
            // }
            
        }
        catch (\Exception $e){
            return response()->json(array('message' => $e->getMessage(), 'code' => 1));
        }
        
    }
    public function getTestQuestion(Request $request){
        try{
            if(empty($request->token)){
                return response()->json(array('message' => 'not registered', 'code' => 2));
            }
            $body = JWT::decode($request->token, env('JWT_SECRET'), ['HS256']);
            $questions = Question::where('test_id', $request->test_id)->get();
            $answers = Answer::where('test_id', $request->test_id)->get()->toArray();
            // print_r($answers);
            // exit();
            $result = array();
            for ($i = 0; $i < count($questions) ; $i++) { 
                $result[$i]['question_id'] = $questions[$i]['id'];
                $result[$i]['question'] = $questions[$i]['content'];
                $result[$i]['type'] = $questions[$i]['type'];
                for ($j = 0; $j < count($answers) ; $j++) {
                    if($questions[$i]['id'] == $answers[$j]['question_id']){
                        $result[$i]['answers'][] = $answers[$j]['id'];
                    }
                }
                $result[$i]['answers'] = Arr::shuffle($result[$i]['answers']);
            }
            
            return response()->json(array('result' => $result, 'code' => 0));
        }
        catch (\Exception $e){
            return response()->json(array('message' => $e->getMessage(), 'code' => 1));
        }
    }
    public function testQuestionCheck(Request $request){
        try{
            if(empty($request->token)){
                return response()->json(array('message' => 'not registered', 'code' => 2));
            }
            $body = JWT::decode($request->token, env('JWT_SECRET'), ['HS256']);
            // print_r($body->data->user_id);
            $student_answer = $request->studentAnswer;
            // print_r($student_answer); exit();
            $questions = Question::where('test_id', $request->test_id)->get()->toArray();
            $answers = Answer::where('test_id', $request->test_id)->where('mark', '!=', 0)->get()->toArray();
            
            $result = array();
            $cntMark = 0;
            for ($i = 0; $i < count($questions); $i++) { 
                // $studentAnswer = json_decode($request->studentAnswer[$i]['answer']);
                $result[$i]['question_id'] = $questions[$i]['id'];
                $result[$i]['question'] = $questions[$i]['content'];
                $result[$i]['type'] = $questions[$i]['type'];
                for ($j = 0; $j < count($answers); $j++) { 
                    if($questions[$i]['id'] == $answers[$j]['question_id']){
                        $result[$i]['mark'][] = $answers[$j]['mark'];
                        $result[$i]['answer_id'][] = $answers[$j]['id'];
                        // $result[$i]['right_answer'][] = $answers[$j]['right_answer']; //don't need
                        if($questions[$i]['id'] == $student_answer[$i]['question_id']){
                            $result[$i]['student_answer'] = json_decode($student_answer[$i]['answer']); //need answer_id
                        }
                    }
                }
            }
            // print_r($result); exit();
            // foreach($result as $res){
            //     if($res['right_answer'] == $res['student_answer']){
            //         foreach($res['mark'] as $mark){
            //             $cntMark += $mark;   
            //         } 
            //     }
            // }
            for($i = 0; $i < count($result); $i++){
                if($result[$i]['answer_id'] == $result[$i]['student_answer']){
                    for($j = 0; $j < count($result[$i]['mark']); $j++){
                        // print_r($result[$i]['right_answer'][$j]);
                        $cntMark += $result[$i]['mark'][$j];   
                    } 
                }
            }

            // $data = array([
            //     'student_id' => $body->data->user_id,
            //     'test_id' => $request->test_id,
            //     'score' => $cntMark,
            //     'result' => json_encode($result),
            //     // 'begin_time' => $request->test_id,
            //     // 'end_time' => $request->test_id
            // ]);
            // Results::Insert($data);
            // // print_r($body->data->user_id);
            // // print_r($result);
            // // print_r($cntMark);
            
            return response()->json(array('result' => $result,'count' => $cntMark, 'code' => 0));
            // exit();

            // foreach ($questions as $question) {
                // return response()->json(array('result' => $questions, 'code' => 0));
            // }
        }
        catch (\Exception $e){
            return response()->json(array('message' => $e->getMessage(), 'code' => 1));
        }
    }
}
