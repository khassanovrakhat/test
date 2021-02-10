<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use Matrix\Exception;
use \Firebase\JWT\JWT;


class QuestionController extends Controller
{
    public function view(Request $request){
        try {
            
            $questions = Question::where('test_id', $request->test_id)->get();
            // print_r($questions); exit();
            foreach ($questions as $question){ 
                return response()->json(array('result' => $question->content, 'code' => 0));
            }
        }
        catch (Exception $e){
            return response()->json(array('message' => $e->getMessage(), 'code' => 1));
        }
    }
    public function create(Request $request){
        try {
            $addQuestions = new Question(); 
            $countQuestions = Question::where('test_id', $request->test_id)->count(); 
                   
            
            // if($request->type == 'single'){
            $addQuestions->content = $request->content;
            $addQuestions->test_id = $request->test_id ;
            $addQuestions->type = $request->type;
            if($countQuestions == null){
                $addQuestions->priority = 1;
            }else{
                $countQuestions += 1;
                $addQuestions->priority = $countQuestions;
            }
                
                
            // }
            
            // $question->save();
            // $question->content = $request->only('content');
            // $question->type = $request->only('type');
            // $question->test_id = $request->only('test_id');
            // if($question->type != 'open') {
            //     $question->answer = $request->only('answer');
            //     foreach ($question->answer as $ans){
            //         $answer = new Answer();
            //         $answer->content = $ans->content;
            //         $answer->score = $ans->score;
            //         $answer->save();
            //     }
            // }
            $addQuestions->save();
            // $addAnswers->save();
            return response()->json(array('result' => 'succeed', 'code' => 0));

        }
        catch (Exception $e){
            return response()->json(array('message' => $e->getMessage(), 'code' => 1));
        }  
    }
    public function update(Request $request){
        try{
            //  print_r($request->only('right_answer')); exit();
            // $addAnswers = new Answer();
            $questions = Question::where('test_id', $request->test_id)->get()->last();
            for ($i=0; $i < count($request->only('content')['content']); $i++) { 
                $data = array([
                    'content' => $request->only('content')['content'][$i],
                    'mark' => $request->only('mark')['mark'][$i],
                    'question_id' => $questions->id,
                    'right_answer' => $request->only('right_answer')['right_answer'][$i],
                    'test_id' => $request->test_id
                ]);
                Answer::Insert($data);
            }
            return response()->json(array('result' => 'succeed', 'code' => 0));
        }
        catch (Exception $e){
            return response()->json(array('message' => $e->getMessage(), 'code' => 1));
        }
    }
    // public function update(Request $request){
    //     $question = Question::where('id', $request->only('id'))->first();
    //     try{
    //         if($question->content)
    //             $question->content = $request->only('content');
    //         if($question->type)
    //             $question->type = $request->only('type');
    //         if($question->type != 'multiple') {
    //             $question = Question::where('id', $request->only('id'))->first();
    //             $answer = Answer::where('id', $question->id)->get();
    //             foreach ($answer as $ans){
    //                 $ans ->delete();
    //             }
    //             $question->answer = $request->only('answer');
    //             foreach ($question->answer as &$ans){
    //                 $answer = new Answer();
    //                 $answer->content = $ans->content;
    //                 $answer->score = $ans->score;
    //                 $answer->save();
    //             }
    //         }
    //     }
    //     catch (Exception $e){
    //         return $e->getMessage();
    //     }

    //     $question->save();
    //     return "succeed";
    // }
    public function delete(Request $request){
        try{
            $question = Question::where('id', $request->only('id'))->first();
            $answer = Answer::where('id', $question->id)->get();
            foreach ($answer as $ans){
                $ans ->delete();
            }
            $question->delete();
            return response()->json(array('result' => 'succeed', 'code' => 0));
        }
        catch (Exception $e){
            return response()->json(array('message' => $e->getMessage(), 'code' => 1));
        }
    }
}
