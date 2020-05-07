<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function task()
    {
        return view('start');
    }

//    public function postTask(Request $request)
//    {
//        if ($request->ajax()) {
//            $input = $request->except('_token');
//
//            $rules = [
//                'name' => 'required|string',
//                'email' => 'required|email',
//            ];
//
//            $messages = [
//                'name.required' => 'Введите имя',
//                'email.required' => 'Введите email',
//            ];
//
//            $validator = Validator::make($input, $rules, $messages);
//
//            if ($validator->fails()) {
//                return redirect('/start')->withInput()->withErrors($validator);
//            }
//
//            return response()->json(['result' => true]);
//        }
//    }
}
