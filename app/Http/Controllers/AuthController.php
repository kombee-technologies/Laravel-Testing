<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Register a new user and return access token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validate user input
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'contact_number' => 'required|numeric|digits:10',
            'postcode' => 'required|numeric|digits:6',
            'password' => 'required|string|min:8|confirmed',
            'gender' => 'required|in:male,female,other',
            'state_id' => 'required|integer|exists:states,id',
            'city_id' => 'required|integer|exists:cities,id',
            'roles' => 'required|array',
            'hobbies' => 'nullable|array',
            'uploaded_files' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create new user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'postcode' => $request->postcode,
            'password' => bcrypt($request->password),
            'gender' => $request->gender,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'roles' => json_encode($request->roles),
            'hobbies' => json_encode($request->hobbies),
            'uploaded_files' => json_encode($request->uploaded_files),
        ]);

        // Handle file uploads (if provided)
        if ($request->hasFile('files')) {
            $uploadedFiles = [];
            foreach ($request->file('files') as $file) {
                $filePath = $file->store('uploads', 'public');
                $uploadedFiles[] = $filePath;
            }
            $user->files = json_encode($uploadedFiles);
            $user->save();
        }

        // Fetch OAuth password client
        $oauthClient = Client::where('password_client', 1)->latest()->first();
        if (!$oauthClient) {
            return response()->json(['error' => 'OAuth password client not found'], 500);
        }

        // Generate access token for new user
        $data = [
            'grant_type' => 'password',
            'client_id' => $oauthClient->id,
            'client_secret' => $oauthClient->secret,
            'username' => $request->email,
            'password' => $request->password,
        ];

        $tokenRequest = app('request')->create('/oauth/token', 'POST', $data);
        $tokenResponse = json_decode(app()->handle($tokenRequest)->getContent());

        return response()->json([
            'user' => $user,
            'access_token' => $tokenResponse->access_token ?? null,
            'refresh_token' => $tokenResponse->refresh_token ?? null,
            'expires_in' => $tokenResponse->expires_in ?? null,
        ]);
    }

    /**
     * Login user and create token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials)) {
        return redirect()->back()->with('error', 'Invalid credentials');
    }

    $user = Auth::user();
    $token = $user->createToken('Web Token')->accessToken;

    session(['api_token' => $token]); // Store token in session for future API calls

    return redirect()->route('users.index')->with('success', 'User Logged in Successfully');
}


    /**
     * Refresh Access Token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'refresh_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Refresh token is required'], 400);
        }

        $oauthClient = Client::where('password_client', 1)->latest()->first();
        if (!$oauthClient) {
            return response()->json(['error' => 'OAuth password client not found'], 404);
        }

        $data = [
            'grant_type' => 'refresh_token',
            'client_id' => $oauthClient->id,
            'client_secret' => $oauthClient->secret,
            'refresh_token' => $request->refresh_token,
        ];

        try {
            $tokenRequest = app('request')->create('/oauth/token', 'POST', $data);
            $response = app()->handle($tokenRequest);
            $tokenResult = json_decode($response->getContent());

            if (isset($tokenResult->access_token)) {
                return response()->json([
                    'access_token' => $tokenResult->access_token,
                    'refresh_token' => $tokenResult->refresh_token,
                    'expires_in' => $tokenResult->expires_in,
                ]);
            }

            return response()->json(['error' => $tokenResult->error ?? 'Unknown error occurred'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'server_error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get Authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        return response()->json(Auth::user());
    }


    public function showLoginForm()
{
    return view('auth.login'); // Ensure this file exists in `resources/views/auth/`
}
    public function showRegisterForm()
{
    return view('auth.register'); // Ensure this file exists in `resources/views/auth/`
}

}
