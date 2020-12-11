<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        $rules = [];
        $messages = [];
        $response = [];

        $validator = Validator::make($request->all(), $rules, $messages, [
            'firstname' => 'required',
            'lastname' => 'required',
            'gender' => 'required',
            'phone' => 'required|unique:users,phone',
            'home_address' => 'required',
        ]);

        $user = User::create($request->all());
        $token = $user->createToken('food-for-all')->accessToken;
        $response['msg'] = 'Registration successful';
        $response['code'] = count($validator->errors()) > 0 ? 400 : 201;
        $response['data'] = ['token' => $token];
        $response['errors'] = $validator->errors();

        return response()->json($response);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        return response()->json([]);
    }
}
