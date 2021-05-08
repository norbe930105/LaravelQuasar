<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class Usuario extends Controller
{
    public function register(Request $request){
        $user =  User::create([
            'name' => $request->input('name'),
            'last' => $request->input('last'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        Auth::loginUsingId($user->id);
        $token = $user->createToken('token')->plainTextToken;
        return response([
            'userID' => $user->id,
            'token' => $token
        ], Response::HTTP_ACCEPTED);
    }

    public function login(Request $request){
        if (!Auth::attempt($request->only('email', 'password'))){
            return response([
                'message' => 'Usuario o contraseña incorrectos'
            ], Response::HTTP_NO_CONTENT);
        }

        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        return response([
            'userID' => $user->id,
            'token' => $token
        ], Response::HTTP_ACCEPTED);
    }

    public function logout(Request $request){
        $ident = $request->Ident;
        $user = Auth::user();
        if ($ident == $user->id){
            $user->tokens()->delete();
        }
        return response([
            'message' => 'Usuario deslogueado',
        ], Response::HTTP_ACCEPTED);
    }

    public function getUser(){
        return response([
            'message' => 'hola',
        ], Response::HTTP_ACCEPTED);
    }
}
