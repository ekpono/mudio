<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(UserRequest $request)
    {
        $users = User::withCount('media')
            ->when($request->has('query'), function($query) use($request) {
                $search = $request->query('query');
                $query->where('name', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%");
            })
            ->paginate(Helper::API_PER_PAGE);

        return UserResource::collection($users);
    }

    public function store(CreateUserRequest $request)
    {
        $user = (new UserService())->create($request);

        return response()->json([
           'data' => $user,
           'message' => 'Successfully fetched'
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role ?? 'user',
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->filled('is_blocked')) {
            $data['is_blocked'] = $request->is_blocked;
        }

        $user->update($data);

        return response()->json([
            'data' => $user,
            'message' => 'User updated successfully',
        ]);
    }
}
