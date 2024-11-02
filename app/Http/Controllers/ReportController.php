<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Variable;

class ReportController extends Controller
{
    public function handleReport(Request $request)
    {
        $rules = [
            'client_id' => 'required | exists:clients,id',
            'consume_kv_copel' => 'required | numeric | min:0',
            'public_light' => 'required | numeric | min:0',
            'fatura_copel' => 'required | numeric | min:0',
            'min_tax' => 'required | numeric | min:0',
            'percentage_value' => 'required | numeric | min:0',
        ];

        //Teste Deploy 

        $messages = [
            'client_id.required' => 'O campo id do cliente é obrigatório',
            'consume_kv_copel.required' => 'O campo consumo kv copel é obrigatório',
            'consume_kv_copel.numeric' => 'O campo consumo kv copel deve ser um número',
            'consume_kv_copel.min' => 'O campo consumo kv copel deve ser no mínimo 0',
            'public_light.required' => 'O campo valor da luz pública é obrigatório',
            'public_light.numeric' => 'O campo valor da luz pública deve ser um número',
            'public_light.min' => 'O campo valor da luz pública deve ser no mínimo 0',
            'fatura_copel.required' => 'O campo última fatura copel é obrigatório',
            'fatura_copel.numeric' => 'O campo última fatura copel deve ser um número',
            'fatura_copel.min' => 'O campo última fatura copel deve ser no mínimo 0',
            'min_tax.required' => 'O campo luz fase copel é obrigatório',
            'min_tax.numeric' => 'O campo luz fase copel deve ser um número',
            'min_tax.min' => 'O campo luz fase copel deve ser no mínimo 0',
            'percentage_value.required' => 'O campo valor percentual é obrigatório',
            'percentage_value.numeric' => 'O campo valor percentual deve ser um número',
            'percentage_value.min' => 'O campo valor percentual deve ser no mínimo 0',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $request['user_id'] = auth()->user()->id;

        $report = new Report();

        $report->fill($request->all());

        info('Report created', $report->toArray());

        $var_kvCopel = Variable::where('name', 'var_kvCopel')->first();

        //Calculando o consumo final da copel  REDEPLOY
        $report->consume_kv_copel_final = $report->consume_kv_copel * $var_kvCopel->value;

        //Calculando o consumo da cooperativa DEPLOY
        $report->consume_kv_coop = $report->consume_kv_copel - $report->min_tax;

        //Calculando o consumo final da cooperativa DEPLOY
        $report->consume_kv_coop_final = $report->consume_kv_coop * $report->percentage_value;

        //Calculando a taxa tusd
        $var_taxaTusd = Variable::where('name', 'var_taxaTusd')->first();
        $report->taxa_tusd = $report->consume_kv_coop * $var_taxaTusd->value;

        //Calculando o valor da fase
        $report->fasic_value = $report->min_tax * $var_kvCopel->value + $report->public_light + $report->taxa_tusd;

        //Calculando o desconto
        $report->discount = $report->consume_kv_copel_final - $report->consume_kv_coop_final;

        //Calculando o valor final da cooperativa
        $report->final_value_coop = $report->consume_kv_coop_final + $report->fasic_value;

        //Calculando o desconto mensal
        $report->discount_monthly = $report->fatura_copel - $report->final_value_coop;

        //Calculando o desconto percentual
        $report->discount_percentage = ($report->discount_monthly / $report->fatura_copel) * 100;

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
