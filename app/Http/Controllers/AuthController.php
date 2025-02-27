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
//     public function login(Request $request)
// {
//     $credentials = [
//         'email' => $request->get('email'),
//         'password' => $request->get('password'),
//     ];

//     if (!Auth::attempt($credentials)) {
//         return back()->withErrors(['error' => 'Invalid credentials']);
//     }

//     $user = Auth::user();

//     $oauthClient = Client::where('password_client', 1)->latest()->first();
//     if (!$oauthClient) {
//         return back()->withErrors(['error' => 'OAuth password client not found']);
//     }

//     $data = [
//         'grant_type' => 'password',
//         'client_id' => $oauthClient->id,
//         'client_secret' => $oauthClient->secret,
//         'username' => $request->email,
//         'password' => $request->password,
//     ];

//     $tokenRequest = app('request')->create('/oauth/token', 'POST', $data);
//     $tokenResponse = json_decode(app()->handle($tokenRequest)->getContent());

//     if (isset($tokenResponse->access_token)) {
//         session(['access_token' => $tokenResponse->access_token]);

//         // return redirect()->route('users.index')->with('success', 'User logged in successfully');
//         // return dd(route('users.index')); // Check if it generates the correct URL
//         return redirect('http://127.0.0.1:8000/users')->with('success', 'User logged in successfully');

//     }

//     return back()->withErrors(['error' => 'Token generation failed']);
// }



public function login(Request $request)
{
    $credentials = [
        'email' => $request->email,
        'password' => $request->password,
    ];

    if (!Auth::attempt($credentials)) {
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    $user = Auth::user();

    // Fetch OAuth client for password grant
    $oauthClient = Client::where('password_client', 1)->latest()->first();
    if (!$oauthClient) {
        return response()->json(['error' => 'OAuth password client not found'], 500);
    }

    // Prepare data for OAuth token request
    $data = [
        'grant_type' => 'password',
        'client_id' => $oauthClient->id,
        'client_secret' => $oauthClient->secret,
        'username' => $request->email,
        'password' => $request->password,
    ];

    $tokenRequest = app('request')->create('/oauth/token', 'POST', $data);
    $tokenResponse = json_decode(app()->handle($tokenRequest)->getContent());

    if (isset($tokenResponse->access_token)) {
        return response()->json([
            'access_token' => $tokenResponse->access_token,
            'token_type' => 'Bearer',
            'expires_in' => $tokenResponse->expires_in,
        ]);
    }

    return response()->json(['error' => 'Token generation failed'], 500);
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
