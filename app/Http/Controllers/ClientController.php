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

        //Deploy 

        $name = request()->query('name');
        $limit = request()->query('limit');
        $phone = request()->query('phone');

        $query = Client::query();
        $query->limit($limit ? $limit : 10);

        if ($name) {
            $query->where('name', 'like', "%$name%");
        }

        if ($phone) {
            $query->where('phone', 'like', "%$phone%");
        }

        $clients = $query->get();

        return response()->json($clients, 200);
    }

    //Se o cliente não existe, ele é criado e cria uma estimativa para o cliente
    //Se o cliente já existe, ele atualiza o nome do cliente e atualiza a estimativa para o cliente
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
                'final_value_discount' => ($request->fatura_copel * 0.8),
            ]
        );

        SendClientCreatedEmail::dispatch($client, $clientEstimate);

        return response()->json($client, 200);
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
}
