<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\GameSession;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserManagementController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    public function __construct()
    {
        // Only allow admins and organizers
        $this->middleware(['auth', 'hasPermission:' . Role::Admin->name]);
    }

    public function index()
    {
        $users = User::withoutGlobalScopes()->orderBy('name')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => \App\Enums\Role::cases(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', "unique:users,email,{$user->id}"],
            'password' => ['nullable', 'string', 'min:8'],
            'phone_number' => ['nullable', 'string', 'max:50'],
            'info' => ['nullable', 'string', 'max:5000'],
            'role' => ['required', 'integer', 'in:1,2,3'],
            'is_blocked' => ['boolean', function ($attribute, $value, $fail) use ($user){
                // Prevent blocking admins
                if ($user->role === \App\Enums\Role::Admin) {
                    $fail('Administrator accounts cannot be blocked.');
                }
            },],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        if (! isset($data['is_blocked'])) {
            $data['is_blocked'] = 0;
        }

        $user->update($data);

        return redirect()->route('admin.user.edit', $user->id)
            ->with('success', 'User details updated successfully.');
    }
}
