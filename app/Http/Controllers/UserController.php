<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the users (staff only).
     */
    public function index()
    {
        $users = User::where('role', 'staff')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff',
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => "Membuat akun staff baru: {$user->name} ({$user->email})"
        ]);

        return redirect()->route('users.index')->with('success', 'Akun staff berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Hanya bisa edit staff, tidak bisa edit admin
        if ($user->role === 'admin') {
            return redirect()->route('users.index')->with('error', 'Tidak dapat mengedit akun admin!');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->is_active = $request->has('is_active') ? true : false;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => "Mengupdate akun staff: {$user->name}"
        ]);

        return redirect()->route('users.index')->with('success', 'Akun staff berhasil diupdate!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus akun admin!');
        }

        $name = $user->name;
        $user->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => "Menghapus akun staff: {$name}"
        ]);

        return redirect()->route('users.index')->with('success', 'Akun staff berhasil dihapus!');
    }

    /**
     * Toggle user active status
     */
    public function toggleActive(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => "Akun staff {$user->name} {$status}"
        ]);

        return redirect()->route('users.index')->with('success', "Akun berhasil {$status}!");
    }
}
