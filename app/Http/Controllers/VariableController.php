<?php

namespace App\Http\Controllers;

use App\Models\Variable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VariableController extends Controller
{
    public function index()
    {
        $variable = Variable::all();

        return response()->json($variable, 200);
    }

    public function update(Request $request, $id)
    {

        $rules = [
            'value' => 'numeric|min:0'
        ];

        $messages = [
            'value.numeric' => 'O campo valor deve ser um número',
            'value.min' => 'O campo valor deve ser maior que 0'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $variable = Variable::findOrFail($id);

        $variable->update($request->all());

        return response()->json($variable, 200);
    }
}
