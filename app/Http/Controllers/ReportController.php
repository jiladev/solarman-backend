<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function handleReport(Request $request)
    {
        $rules = [
            'client_id' => 'required | exists:clients,id',
            'consume_kv_copel' => 'required | numeric | min:0',
            'public_light_value' => 'required | numeric | min:0',
            'ult_fatura_copel' => 'required | numeric | min:0',
            'light_fase_copel' => 'required | numeric | min:0',
            'percentage_value' => 'required | numeric | min:0',
        ];

        $messages = [
            'client_id.required' => 'O campo id do cliente é obrigatório',
            'consume_kv_copel.required' => 'O campo consumo kv copel é obrigatório',
            'consume_kv_copel.numeric' => 'O campo consumo kv copel deve ser um número',
            'consume_kv_copel.min' => 'O campo consumo kv copel deve ser no mínimo 0',
            'public_light_value.required' => 'O campo valor da luz pública é obrigatório',
            'public_light_value.numeric' => 'O campo valor da luz pública deve ser um número',
            'public_light_value.min' => 'O campo valor da luz pública deve ser no mínimo 0',
            'ult_fatura_copel.required' => 'O campo última fatura copel é obrigatório',
            'ult_fatura_copel.numeric' => 'O campo última fatura copel deve ser um número',
            'ult_fatura_copel.min' => 'O campo última fatura copel deve ser no mínimo 0',
            'light_fase_copel.required' => 'O campo luz fase copel é obrigatório',
            'light_fase_copel.numeric' => 'O campo luz fase copel deve ser um número',
            'light_fase_copel.min' => 'O campo luz fase copel deve ser no mínimo 0',
            'percentage_value.required' => 'O campo valor percentual é obrigatório',
            'percentage_value.numeric' => 'O campo valor percentual deve ser um número',
            'percentage_value.min' => 'O campo valor percentual deve ser no mínimo 0',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $request['user_id'] = auth()->user()->id;

        $report = Report::updateOrCreate(
            ['client_id' => $request->client_id],
            $request->all()
        );

        return response()->json($report, 201);
    }

    public function index(){
        $client_id = request()->query('client_id');
        $user_id = request()->query('user_id');
        $limit = request()->query('limit');

        $query = Report::query();

        $query->limit($limit ? $limit : 10);

        if($client_id){
            $query->where('client_id', $client_id);
        }

        if($user_id){
            $query->where('user_id', $user_id);
        }

        $reports = $query->get();

        return response()->json($reports, 200);
    }

    public function show($id){
        $report = Report::findOrFail($id)->load(['client']);

        return response()->json($report, 200);
    }
}
