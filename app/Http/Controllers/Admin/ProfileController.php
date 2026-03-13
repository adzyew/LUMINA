<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }

        return view('admin.profile.show', [
            'user' => $user,
        ]);
    }

    public function edit()
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }

        return view('admin.profile.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'current_password' => 'nullable|required_with:new_password|string',
            'new_password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'confirmed',
            ],
        ], [
            'new_password.min' => 'Password must be at least 8 characters.',
            'new_password.regex' => 'Password must include uppercase, lowercase, and a number.',
        ]);

        $user->name = $validated['name'];

        if (!empty($validated['new_password'])) {
            if (!Hash::check((string) $request->input('current_password'), $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Current password is incorrect.'])
                    ->withInput();
            }

            $user->password = $validated['new_password'];
        }

        $user->save();

        return redirect()
            ->route('admin.profile.show')
            ->with('success', 'Profile updated successfully.');
    }
}
