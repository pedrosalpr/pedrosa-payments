<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Services\Users\UserService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
        $this->middleware('auth:api');
    }

    public function balance()
    {
        $user = Auth::user();
        $balance = $this->userService->balance($user->id);
        return response()->json(['balance' => $balance]);
    }
}
