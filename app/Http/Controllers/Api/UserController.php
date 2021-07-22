<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};

use App\Models\User;
use App\Repositories\User\UserRepository;
use App\Http\Resources\User\{UserResource, UserCollection};
use App\Http\Requests\User\{CreateUserRequest, UpdateUserRequest};

class UserController extends Controller
{

    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index(Request $request): UserCollection
    {
        $users = $this->userRepo->get($request->take, $request->name, $request->sort_by, $request->order_by ?? 'asc');

        return new UserCollection($users);
    }

    public function store(CreateUserRequest $request): UserResource
    {
        $user = User::create($request->all());

        return new UserResource($user);
    }

    public function show(int $user): UserResource
    {
        $user = $this->userRepo->find($user);

        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, int $user): UserResource
    {
        $user = $this->userRepo->update($user, $request->all());

        return new UserResource($user);
    }

    public function destroy(int $user): JsonResponse
    {
        $this->userRepo->delete($user);

        return response()->json(['errors' => 'Pengguna dihapus']);
    }
}
