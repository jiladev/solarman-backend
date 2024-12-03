<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientEstimate;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Variable;
use Knp\Snappy\Pdf;
use Spatie\Browsershot\Browsershot;

class ReportController extends Controller
{
    public function handleReport(Request $request)
    {
        $rules = [
            'consume_kv_copel' => 'required | numeric | min:0',
            'public_light' => 'required | numeric | min:0',
            'fatura_copel' => 'required | numeric | min:0',
            'min_tax' => 'required | numeric | min:0',
            'percentage_value' => 'required | numeric | min:0',
        ];

        $messages = [
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

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $request['user_id'] = auth()->user()->id;

        $report = new Report();

        $client = Client::where('phone', $request->phone_client)->first();
        if (!$client) {
            $client = Client::create([
                'name' => $request->name_client,
                'phone' => $request->phone_client,
            ]);
        }

        $clientEstimate = ClientEstimate::updateOrCreate(
            ['client_id' => $client->id],
            [
                'fatura_copel' => $request->fatura_copel,
                'final_value_discount' => ($request->fatura_copel * (1 - $request->min_tax)),
            ]
        );

        $report->client_id = $client->id;

        $report->fill($request->all());

        info('Report created', $report->toArray());

        $var_kvCopel = Variable::where('name', 'var_kvCopel')->first();

        //Calculando o consumo final da copel
        $report->consume_kv_copel_final = $report->consume_kv_copel * $var_kvCopel->value;

        //Calculando o consumo da cooperativa
        $report->consume_kv_coop = $report->consume_kv_copel - $report->min_tax;

        //Calculando o consumo final da cooperativa
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

        $report->save();

        $outputFilePath = storage_path('app/reports/report.pdf');

        if (file_exists($outputFilePath)) {
            unlink($outputFilePath);
        }


        //Formatando valores
        $currentValue = $this->formatCurrency($report->fatura_copel);
        $valueCoop = $this->formatCurrency($report->fatura_copel - ($report->fatura_copel * 0.1355604396));
        $econMensal = $this->formatCurrency($report->fatura_copel * 0.1355604396);
        $econAnual = $this->formatCurrency(($report->fatura_copel * 0.1355604396) * 12);

        $verdeDesconto = $this->formatCurrency($report->fatura_copel * 0.1355604396);
        $amarelaDesconto = $this->formatCurrency($report->fatura_copel * 0.1455604396);
        $vermelhaDesconto = $this->formatCurrency($report->fatura_copel * 0.1555604396);
        $vermelhaP1Desconto = $this->formatCurrency($report->fatura_copel * 0.1555604396);
        $vermelhaP2Desconto = $this->formatCurrency($report->fatura_copel * 0.1555604396);
        $escassezDesconto = $this->formatCurrency($report->fatura_copel * 0.1655604396);

        $clientName = $client->name;

        $html = view('pdf.report', compact(
            'report',
            'currentValue',
            'valueCoop',
            'econMensal',
            'econAnual',
            'clientName',
            'verdeDesconto',
            'amarelaDesconto',
            'vermelhaDesconto',
            'vermelhaP1Desconto',
            'vermelhaP2Desconto',
            'escassezDesconto'
        ))->render();

        $snappy = new Pdf('/usr/bin/wkhtmltopdf');
        $snappy->setOption('enable-local-file-access', true);
        $snappy->generateFromHtml($html, $outputFilePath);

        if (!file_exists($outputFilePath)) {
            return response()->json(['message' => 'Erro ao gerar o relatório'], 500);
        }

        return response()->download($outputFilePath, 'report.pdf', [
            'Content-Type' => 'application/pdf',
            'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ])->deleteFileAfterSend(true);
    }

    public function index()
    {
        $client_id = request()->query('client_id');
        $user_id = request()->query('user_id');
        $name = request()->query('name');
        $phone = request()->query('phone');
        $limit = (int) request()->query('limit', 5);
        $sort_options = request()->query('sort_options', []);

        if (!is_array($sort_options)) {
            $sort_options = [0, 2, 0, 0];
        }

        $query = Report::query();

        $query->join('clients', 'clients.id', '=', 'reports.client_id');

        if ($client_id) {
            $query->where('reports.client_id', $client_id);
        }

        if ($user_id) {
            $query->where('reports.user_id', $user_id);
        }

        if ($name) {
            $query->where('clients.name', 'like', '%' . $name . '%');
        }

        if ($phone) {
            $query->where('clients.phone', 'like', '%' . $phone . '%');
        }

        if ($sort_options[0] !== 0) {
            if ($sort_options[0] === 1) {
                $query->orderBy('clients.name', 'asc');
            } else {
                $query->orderBy('clients.name', 'desc');
            }
        } else if ($sort_options[1] !== 0) {
            if ($sort_options[1] === 1) {
                $query->orderBy('reports.created_at', 'asc');
            } else {
                $query->orderBy('reports.created_at', 'desc');
            }
        } else if ($sort_options[2] !== 0 || $sort_options[3] !== 0) {
            if ($sort_options[2] === 1 || $sort_options[3] === 1) {
                $query->orderBy('reports.fatura_copel', 'asc');
            } else {
                $query->orderBy('reports.fatura_copel', 'desc');
            }
        }

        $baseQuery = clone $query;

        $query->select(
            'reports.id',
            'reports.client_id',
            'clients.name as client_name',
            'clients.phone as client_phone',
            'reports.created_at',
            'reports.fatura_copel',
        );

        $reports = $query->paginate($limit, ['*'], 'page');

        $formattedResponse = $reports->getCollection()->map(function ($report) {
            $discountedValue = $report->fatura_copel - ($report->fatura_copel * 0.1355604396);

            return [
                'id' => $report->id,
                'client' => [
                    'id' => $report->client_id,
                    'name' => $report->client_name,
                    'phone' => $report->client_phone,
                ],
                'datetime' => $report->created_at,
                'originalValue' => $report->fatura_copel,
                'discountedValue' => (float) number_format($discountedValue, 2, '.', ''),
            ];
        });

        return response()->json([
            'data' => $formattedResponse,
            'pagination' => [
                'current_page' => $reports->currentPage(),
                'total_pages' => $reports->lastPage(),
                'per_page' => $reports->perPage(),
                'total' => $reports->total(),
            ]
        ], 200);
    }

    public function show($id)
    {
        $report = Report::findOrFail($id)->load(['client']);

        return response()->json($report, 200);
    }

    private function formatCurrency($value)
    {
        return number_format($value, 2, ',', '.');
    }

    public function destroy($id)
    {
        $report = Report::findOrFail($id);

        $report->delete();

        return response()->json([], 200);
    }

    public function generatePdf($id)
    {
        $report = Report::findOrFail($id);
        $client = Client::findOrFail($report->client_id);

        $currentValue = $this->formatCurrency($report->fatura_copel);
        $valueCoop = $this->formatCurrency($report->fatura_copel - ($report->fatura_copel * 0.1355604396));
        $econMensal = $this->formatCurrency($report->fatura_copel * 0.1355604396);
        $econAnual = $this->formatCurrency(($report->fatura_copel * 0.1355604396) * 12);

        $verdeDesconto = $this->formatCurrency($report->fatura_copel * 0.1355604396);
        $amarelaDesconto = $this->formatCurrency($report->fatura_copel * 0.1455604396);
        $vermelhaDesconto = $this->formatCurrency($report->fatura_copel * 0.1555604396);
        $vermelhaP1Desconto = $this->formatCurrency($report->fatura_copel * 0.1555604396);
        $vermelhaP2Desconto = $this->formatCurrency($report->fatura_copel * 0.1555604396);
        $escassezDesconto = $this->formatCurrency($report->fatura_copel * 0.1655604396);

        $clientName = $client->name;

        $outputFilePath = storage_path('app/reports/report.pdf');

        if (file_exists($outputFilePath)) {
            unlink($outputFilePath);
        }

        $html = view('pdf.report', compact(
            'report',
            'currentValue',
            'valueCoop',
            'econMensal',
            'econAnual',
            'clientName',
            'verdeDesconto',
            'amarelaDesconto',
            'vermelhaDesconto',
            'vermelhaP1Desconto',
            'vermelhaP2Desconto',
            'escassezDesconto'
        ))->render();

        $snappy = new Pdf('/usr/bin/wkhtmltopdf');
        $snappy->setOption('enable-local-file-access', true);
        $snappy->generateFromHtml($html, $outputFilePath);

        if (!file_exists($outputFilePath)) {
            return response()->json(['message' => 'Erro ao gerar o relatório'], 500);
        }

        return response()->download($outputFilePath, 'report.pdf', [
            'Content-Type' => 'application/pdf',
            'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ])->deleteFileAfterSend(true);
    }
}
