<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name', 'asc')->paginate(10);
        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'kasir'])],
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password, // Hasshed by model cast
                'role' => $request->role,
            ]);

            // Sync with Operator table
            \App\Models\Operator::create([
                'id_operator' => $request->username, // Assuming username is used as id_operator
                'nama_lengkap' => $request->name,
                'username' => $request->username,
                'password_hash' => \Illuminate\Support\Facades\Hash::make($request->password),
                'level_akses' => $request->role,
                'status_aktif' => true,
            ]);
        });

        return redirect()->route('admin.user.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'kasir'])],
        ]);

        $oldUsername = $user->username;

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $user, $oldUsername) {
            $data = [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'role' => $request->role,
            ];

            if ($request->filled('password')) {
                $data['password'] = $request->password;
            }

            $user->update($data);

            // Sync with Operator table
            $operator = \App\Models\Operator::where('id_operator', $oldUsername)->first();
            if ($operator) {
                $operatorData = [
                    'id_operator' => $request->username,
                    'nama_lengkap' => $request->name,
                    'username' => $request->username,
                    'level_akses' => $request->role,
                ];
                if ($request->filled('password')) {
                    $operatorData['password_hash'] = \Illuminate\Support\Facades\Hash::make($request->password);
                }
                $operator->update($operatorData);
            }
        });

        return redirect()->route('admin.user.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if (\Illuminate\Support\Facades\Auth::id() === $user->id) {
            return redirect()->route('admin.user.index')->with('error', 'Anda tidak bisa menghapus diri sendiri.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
            // Delete associated operator first
            \App\Models\Operator::where('id_operator', $user->username)->delete();
            $user->delete();
        });

        return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus.');
    }
}
