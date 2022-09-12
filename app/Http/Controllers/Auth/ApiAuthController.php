<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Str;

class ApiAuthController extends Controller
{
    /**
     * Register new user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);

        $validatedData['password'] = bcrypt($request->password);
        $validatedData['api_token'] = Str::random(60);
        $validatedData['status'] = 'Active';

        $user = User::create($validatedData);

        return response()->json($user, Response::HTTP_OK);
    }

    /**
     * Update user profile
     *
     * @param Request $request
     * @param $userId
     * @return JsonResponse
     */
    public function updateProfile(Request $request, $userId): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|max:55',
            'email' => 'required|email|min:1|max:191|unique:users,email,' . $userId
        ]);

        if (!empty($request->password)) {
            $data['password'] = bcrypt($request->password);
        }

        $user = User::find($userId);
        $user->update($data);

        return response()->json($user, Response::HTTP_OK);
    }

    /**
     * Login user using email and password
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user && $user->status != 'Active') {
            return response()->json([
                'error' => 'Your account is deactivated now. Please activate and try login.',
                'code' => 401,
            ], 401);
        }

        if (!auth()->attempt($loginData)) {
            return response()->json([
                'error' => 'Unauthenticated user',
                'code' => 401,
            ], 401);
        }

        $user = auth()->user();
        $user->api_token = Str::random(60);
        $user->save();

        return response()->json($user, Response::HTTP_OK);
    }
}
