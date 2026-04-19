<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    private function buildFullName(?string $firstName, ?string $middleName, ?string $lastName, ?string $suffix): string
    {
        $parts = array_filter([
            trim((string) $firstName),
            trim((string) $middleName),
            trim((string) $lastName),
            trim((string) $suffix),
        ], fn ($value) => $value !== '');

        return trim(implode(' ', $parts));
    }

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
            'name' => 'nullable|string|max:255',
            'first_name' => ['required', 'string', 'max:80', 'regex:/^(?=.*\pL)(?!.*-.*-)(?!-)(?!.*-$)[\pL\s-]+$/u'],
            'middle_name' => ['nullable', 'string', 'max:80', 'regex:/^(?=.*\pL)(?!.*-.*-)(?!-)(?!.*-$)[\pL\s-]+$/u'],
            'last_name' => ['required', 'string', 'max:80', 'regex:/^(?=.*\pL)(?!.*-.*-)(?!-)(?!.*-$)[\pL\s-]+$/u'],
            'suffix' => ['nullable', 'string', 'max:20', 'regex:/^[\pL\pN\s\.\-]+$/u'],
            'current_password' => 'nullable|required_with:new_password|string',
            'new_password' => [
                'nullable',
                'string',
                'min:8',
                'max:10',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'confirmed',
            ],
        ], [
            'first_name.regex' => 'First name may contain letters, spaces, and one hyphen only.',
            'middle_name.regex' => 'Middle name may contain letters, spaces, and one hyphen only.',
            'last_name.regex' => 'Last name may contain letters, spaces, and one hyphen only.',
            'suffix.regex' => 'Suffix contains invalid characters.',
            'new_password.min' => 'Password must be at least 8 characters.',
            'new_password.max' => 'Password cannot exceed 10 characters.',
            'new_password.regex' => 'Password must include uppercase, lowercase, and a number.',
        ]);

        $user->first_name = trim((string) ($validated['first_name'] ?? ''));
        $user->middle_name = trim((string) ($validated['middle_name'] ?? ''));
        $user->last_name = trim((string) ($validated['last_name'] ?? ''));
        $user->suffix = trim((string) ($validated['suffix'] ?? ''));
        $user->name = $this->buildFullName(
            $user->first_name,
            $user->middle_name,
            $user->last_name,
            $user->suffix
        );

        if (!empty($validated['new_password'])) {
            if (!Hash::check((string) $request->input('current_password'), $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Current password is incorrect.'])
                    ->withInput();
            }

            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return redirect()
            ->route('admin.profile.show')
            ->with([
                'toast_type' => 'success',
                'toast_message' => 'Profile updated successfully.',
            ]);
    }
}
