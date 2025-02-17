<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $data = [];
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['password'] = $request->password;

            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ];

            $messages = [
                'name.required'  => 'El campo NAME es obligatorio.',
                'email.required'  => 'El campo EMAIL es obligatorio.',
                'email.email'    => 'El campo EMAIL debe tener formato de correo electrónico.',
                'email.unique'    => 'El EMAIL ya está registrado.',
                'password.required'  => 'El campo PASSWORD es obligatorio.',
                'password.min'  => 'El campo PASSWORD debe tener al menos 6 caracteres.',
            ];

            $validator = Validator::make($data, $rules, $messages);
            if ($validator->fails()) {
                $response = ['status' => false, 'message' => $validator->errors()->first()];
            } else {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                $response = ['status' => true, 'message' => 'Usuario registrado correctamente', 'data' => [
                    'user' => $user,
                    'token' => $user->createToken('auth_token')->plainTextToken,
                ]];
            }
        } catch (\Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];
        }
        return json_encode($response);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            $response = ['status' => false, 'message' => 'Credenciales incorrectas'];
        } else {
            $response = ['status' => true, 'token' => $user->createToken('auth_token')->plainTextToken];
        }

        return json_encode($response);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return json_encode(['status' => true, 'message' => 'Sesión cerrada']);
    }
}
