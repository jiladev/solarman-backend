<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $query = User::query();

        $query->join('reports', 'reports.user_id', '=', 'users.id');

        $query->whereNull('reports.deleted_at');

        $query->select(
            'users.id',
            'users.name',
            'users.phone',
            DB::raw('COUNT(reports.id) as numReports'),
        );

        $query->groupBy('users.id', 'users.name', 'users.phone');

        $users = $query->get();

        return response()->json($users->map(function ($user) {
            return [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                ],
                'numReports' => $user->numReports,
            ];
        }), 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|min:10|max:11|unique:users,phone',
            'password' => 'required:min:6'
        ];

        $messages = [
            'name.required' => 'O campo nome é obrigatório',
            'email.required' => 'O campo email é obrigatório',
            'email.email' => 'O campo email deve ser um email válido',
            'phone.required' => 'O campo telefone é obrigatório',
            'password.required' => 'O campo senha é obrigatório',
            'phone.min' => 'O campo telefone deve ter entre 10 e 11 dígitos',
            'phone.max' => 'O campo telefone deve ter entre 10 e 11 dígitos',
            'password.min' => 'O campo senha deve ter no mínimo 6 caracteres',
            'email.unique' => 'Este email já está em uso',
            'phone.unique' => 'Este telefone já está em uso'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $user = User::create($request->all());

        return response()->json($user, 201);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json($user, 200);
    }

    public function update(Request $request, $id)
    {

        //teste deploy 2
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'required|min:11|max:11|unique:users,phone,'.$id,
            'password' => 'required:min:6'
        ];

        $messages = [
            'name.required' => 'O campo nome é obrigatório',
            'email.required' => 'O campo email é obrigatório',
            'email.email' => 'O campo email deve ser um email válido',
            'phone.required' => 'O campo telefone é obrigatório',
            'password.required' => 'O campo senha é obrigatório',
            'phone.min' => 'O campo telefone deve ter 11 dígitos',
            'phone.max' => 'O campo telefone deve ter 11 dígitos',
            'password.min' => 'O campo senha deve ter no mínimo 6 caracteres',
            'email.unique' => 'Este email já está em uso',
            'phone.unique' => 'Este telefone já está em uso'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $user = User::findOrFail($id);

        $user->update($request->all());

        return response()->json($user, 200);
    }
}
