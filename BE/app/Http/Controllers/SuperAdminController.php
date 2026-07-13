<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SuperAdminController extends Controller
{
    /**
     * Display Superadmin Dashboard with User statistics.
     */
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalSuperadmin = User::where('role', User::ROLE_SUPERADMIN)->count();
        $totalRt = User::where('role', User::ROLE_RT)->count();
        $totalBendahara = User::where('role', User::ROLE_BENDAHARA)->count();
        $totalWarga = User::where('role', User::ROLE_WARGA)->count();

        $recentUsers = User::latest()->take(5)->get();
        $recentLogs = ActivityLog::with('user')->latest()->take(5)->get();

        return view('superadmin.dashboard', compact(
            'totalUsers',
            'totalSuperadmin',
            'totalRt',
            'totalBendahara',
            'totalWarga',
            'recentUsers',
            'recentLogs'
        ));
    }

    /**
     * Display a listing of users with search and role filter.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('superadmin.users.index', compact('users'));
    }

    /**
     * Show form for creating a new user.
     */
    public function create()
    {
        return view('superadmin.users.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'integer', Rule::in([User::ROLE_SUPERADMIN, User::ROLE_RT, User::ROLE_BENDAHARA, User::ROLE_WARGA])],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('superadmin.users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    /**
     * Show form for editing an existing user.
     */
    public function edit(User $user)
    {
        return view('superadmin.users.edit', compact('user'));
    }

    /**
     * Update specified user in database.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'integer', Rule::in([User::ROLE_SUPERADMIN, User::ROLE_RT, User::ROLE_BENDAHARA, User::ROLE_WARGA])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('superadmin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Delete user from database.
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('superadmin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
