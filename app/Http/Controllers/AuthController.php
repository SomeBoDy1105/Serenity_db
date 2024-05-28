<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuthController extends Controller
{

    /**
     * Register a new user
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['first_name'] = $request->input('first_name');
        $data['last_name'] = $request->input('last_name');
        $data['email'] = $request->input('email');
        $data['password'] = Hash::make($data['password']);
        $data['role'] = $request->input('role');
        $data['age'] = $request->input('age');
        $data['gender'] = $request->input('gender');
        $data['username'] = $this->CreateName($data['email'], $data['role']);

        $user = User::create($data);
        $token = $user->createToken(User::USER_TOKEN);

        return $this->success([
            'user' => $user,
            'token' => $token->plainTextToken,
        ], 'User registered successfully', 201);
    }

    private function CreateName($email, $role)
    {

        // Convert words to arrays of characters
        $email = strstr($email, '@', true);
        $constantChars = str_split($email);
        $secondChars = str_split($role);

        // Shuffle the letters of each word
        shuffle($constantChars);
        shuffle($secondChars);

        // Convert arrays back to strings
        $mixedConstant = implode('', $constantChars);
        $mixedSecond = implode('', $secondChars);
        $username = $mixedConstant . $mixedSecond;
        $username = str_split($username);
        shuffle($username);
        $username = implode('', $username);
        // Return the combined mixed words
        return $username;
    }


    /**
     * Login a user
     *
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $isValid = $this->isValidCredential($request);

        if (!$isValid['success']) {
            return $this->error($isValid['message'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        $user = $isValid['user'];
        $token = $user->createToken(User::USER_TOKEN);

        return $this->success([
            'user' => $user,
            'token' => $token->plainTextToken,
        ], 'User logged in successfully');
    }



    /**
     * Check if the user credentials are valid
     *
     * @param LoginRequest $request
     * @return array
     */
    private function isValidCredential(LoginRequest $request): array
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if ($user === null) {
            return [
                'success' => false,
                'message' => 'Invalid credentials',
            ];
        }


        if (Hash::check($data['password'], $user->password)) {
            return [
                'success' => true,
                'user' => $user,
            ];
        }


        return [
            'success' => false,
            'message' => 'password is incorrect',
        ];
    }


    /**
     * Logout a user
     *
     * @return JsonResponse
     */
    public function loginWithToken(): JsonResponse
    {
        return $this->success(auth()->user(), 'User logged in successfully');
    }


    /**
     * Logout a user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success(null, 'User logged out successfully');
    }
}
