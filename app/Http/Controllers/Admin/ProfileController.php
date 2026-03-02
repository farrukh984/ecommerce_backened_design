<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.profile');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed',
        ]);

        if (!empty($validated['new_password'])) {
            if (empty($validated['current_password']) || !Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
            }
            $user->password = Hash::make($validated['new_password']);
        }

        if ($request->hasFile('profile_image')) {
            $user->profile_image = Cloudinary::upload($request->file('profile_image')->getRealPath(), ['folder' => 'profile_images'])->getSecurePath();
        }

        if ($request->hasFile('cover_image')) {
            $user->cover_image = Cloudinary::upload($request->file('cover_image')->getRealPath(), ['folder' => 'cover_images'])->getSecurePath();
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->address = $validated['address'];
        $user->city = $validated['city'];
        $user->state = $validated['state'];
        $user->zip_code = $validated['zip_code'];
        $user->country = $validated['country'] ?? 'Pakistan';
        
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
