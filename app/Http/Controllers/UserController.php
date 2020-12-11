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
     * @throws \Exception
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

        $data = $request->all();
        $userPhone = User::where('phone', $data['phone'])->first();

        if ($userPhone) {
            $validator->errors()->add('phone', 'Phone number already exists');
            $response['data'] = [];
            $response['msg'] = 'Error occurred';
            $response['code'] = 400;
            $response['errors'] = $validator->messages();
            return response()->json($response);
        }

        $data['email'] = 'foodforall@nohunger'.bin2hex(random_bytes(10)).'.com';
        $data['password'] = 'xpass';

        $response['code'] = 201;
        $errors = $validator->messages();

        $user = User::create($data);
        $token = $user->createToken('food-for-all')->accessToken;
        $response['msg'] = 'Registration successful';
        $response['data'] = ['token' => $token];
        $response['errors'] = $errors;

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
