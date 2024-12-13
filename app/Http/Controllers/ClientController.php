<?php

namespace App\Http\Controllers;

use App\Jobs\SendClientCreatedEmail;
use App\Models\Client;
use App\Models\ClientEstimate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function index()
    {
        $name = request()->query('name');
        $phone = request()->query('phone');
        $limit = request()->query('limit', 10);

        $query = Client::query();

        if ($name) {
            $query->where('name', 'like', "%$name%");
        }

        if ($phone) {
            $query->where('phone', 'like', "%$phone%");
        }

        $clients = $query->paginate($limit);

        return response()->json($clients, 200);
    }


    public function handleClientEstimate(Request $request)
    {
        $rules = [
            'name' => 'required|min:3',
            'phone' => 'required|min:10|max:11',
            'fatura_copel' => 'required'
        ];

        $messages = [
            'name.required' => 'O campo nome é obrigatório',
            'name.min' => 'O campo nome deve ter no mínimo 3 caracteres',
            'phone.required' => 'O campo telefone é obrigatório',
            'phone.min' => 'O campo telefone deve ter no mínimo 10 caracteres',
            'phone.max' => 'O campo telefone deve ter no máximo 11 caracteres',
            'fatura_copel.required' => 'O campo fatura copel é obrigatório',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $client = Client::where('phone', $request->phone)->first();

        if ($client) {
            $client->name = $request->name;
            $client->save();
        } else {
            $client = Client::create([
                'name' => $request->name,
                'phone' => $request->phone,
            ]);
        }

        $clientEstimate = ClientEstimate::updateOrCreate(
            ['client_id' => $client->id],
            [
                'fatura_copel' => $request->fatura_copel,
                'final_value_discount' => ($request->fatura_copel - $request->fatura_copel * 0.1355604396),
            ]
        );

        SendClientCreatedEmail::dispatch($client, $clientEstimate);

        return response()->json(["client" => $client, "estimate" => $clientEstimate], 200);
    }

    public function show($id)
    {
        $client = Client::findOrFail($id)->load(['estimates', 'reports']);

        return response()->json($client, 200);
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);

        $client->delete();

        return response()->json([], 200);
    }

    public function clientsEstimates()
    {
        $clients = Client::with('estimates')->with('reports')->get();

        return response()->json($clients, 200);
    }
}
