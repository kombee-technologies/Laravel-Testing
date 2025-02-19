<?php

namespace App\Http\Controllers;
use App\Models\User;
// use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\State;
use App\Models\City;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller {
    public function index() {
        $users = User::with(['city', 'state'])->get();
        return view('users.index', compact('users'));
    }

    public function create() {
        $states = State::all();
        return view('users.create', compact('states'));
    }


    public function store(Request $request) {
        Log::info('📝 Form Submitted Data: ', $request->all());
    
        try {
            $validated = $request->validate([
                'first_name' => 'required|alpha',
                'last_name' => 'required|alpha',
                'email' => 'required|email|unique:users',
                'contact_number' => 'required|digits:10',
                'postcode' => 'required|digits:6',
                'password' => 'required|confirmed',
                'gender' => 'required',
                'roles' => 'required|array',
                'hobbies' => 'nullable|array',  // Allow multiple hobbies as an array
                'city_id' => 'required',
                'state_id' => 'required',
                'files.*' => 'file|mimes:jpg,jpeg,png,pdf,docx|max:2048'
            ]);
    
            Log::info('✅ Validation Passed');
    
            $uploadedFiles = [];
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $filePath = $file->storeAs('uploads', $file->getClientOriginalName(), 'public');
                    Storage::setVisibility('uploads/' . $file->getClientOriginalName(), 'public');
                    Log::info('📂 File uploaded: ' . $filePath);
                    $uploadedFiles[] = $filePath;
                }
            }
    
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'contact_number' => $validated['contact_number'],
                'postcode' => $validated['postcode'],
                'password' => Hash::make($validated['password']),
                'gender' => $validated['gender'],
                'state_id' => $validated['state_id'],
                'city_id' => $validated['city_id'],
                'roles' => json_encode($validated['roles']),
                'hobbies' => json_encode($validated['hobbies'] ?? []),  // Store as JSON to prevent null values
                'uploaded_files' => json_encode($uploadedFiles)
            ]);
    
            Log::info('✅ User Created Successfully: ', ['id' => $user->id]);
    
            return redirect()->route('users.index')->with('success', 'User Created Successfully');
        } catch (\Exception $e) {
            Log::error('❌ Error Saving User: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Something went wrong!'])->withInput();
        }
    }
    public function edit(User $user) {
        $states = State::all();
        $cities = City::where('state_id', $user->state_id)->get();
        return view('users.edit', compact('user', 'states', 'cities'));
    }

    public function update(Request $request, User $user) {
        Log::info('📝 Updating User Data: ', $request->all());

        try {
            $validated = $request->validate([
                'first_name' => 'required|alpha',
                'last_name' => 'required|alpha',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'contact_number' => 'required|digits:10',
                'postcode' => 'required|digits:6',
                'gender' => 'required',
                'roles' => 'required|array',
                'city_id' => 'required',
                'state_id' => 'required',
                'files.*' => 'file|mimes:jpg,jpeg,png,pdf,docx|max:2048'
            ]);

            Log::info('✅ Validation Passed');

            $uploadedFiles = json_decode($user->uploaded_files, true) ?? [];
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $filePath = $file->storeAs('uploads', $file->getClientOriginalName(), 'public');
                    Storage::setVisibility('uploads/' . $file->getClientOriginalName(), 'public');
                    Log::info('📂 File uploaded: ' . $filePath);
                    $uploadedFiles[] = $filePath;
                }
            }

            $user->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'contact_number' => $validated['contact_number'],
                'postcode' => $validated['postcode'],
                'gender' => $validated['gender'],
                'state_id' => $validated['state_id'],
                'city_id' => $validated['city_id'],
                'roles' => json_encode($validated['roles']),
                'uploaded_files' => json_encode($uploadedFiles)
            ]);

            Log::info('✅ User Updated Successfully: ', ['id' => $user->id]);

            return redirect()->route('users.index')->with('success', 'User Updated Successfully');
        } catch (\Exception $e) {
            Log::error('❌ Error Updating User: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Something went wrong!'])->withInput();
        }
    }

    public function destroy(User $user) {
        try {
            // Delete associated files
            $uploadedFiles = json_decode($user->uploaded_files, true) ?? [];
            foreach ($uploadedFiles as $file) {
                Storage::disk('public')->delete($file);
                Log::info('🗑️ File deleted: ' . $file);
            }

            $user->delete();
            Log::info('✅ User Deleted Successfully: ', ['id' => $user->id]);

            return redirect()->route('users.index')->with('success', 'User Deleted Successfully');
        } catch (\Exception $e) {
            Log::error('❌ Error Deleting User: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Something went wrong!']);
        }
    }


    public function getCities(Request $request) {
        $cities = City::where('state_id', $request->state_id)->get();
        return response()->json($cities);
    }
    




    public function assignUserRole($userId, $roleId)
    {
        $user = User::find($userId);
        $role = Role::find($roleId);

        if (!$user || !$role) {
            return back()->with('error', 'User or role not found.');
        }

        $user->assignRole($role);
        return back()->with('success', 'Role assigned successfully!');
    }
}


            

