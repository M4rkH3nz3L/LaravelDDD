<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegisterUserController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        // TODO: Implement API controller logic

        return response()->json([
            'message' => 'Success',
        ]);
    }
}
