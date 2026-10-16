<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\LoginDTO;
use App\DTO\SignUpDTO;
use App\Heplers\ApiResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignUpRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Throwable;

class AuthController extends Controller
{
    public function signUp(SignUpRequest $request, AuthService $authService)
    {
        try {
            return ApiResponse::success($authService->signUp(new SignUpDTO(
                name:       $request['name'],
                email:      $request['email'],
                password:   $request['password']
            )));
        } catch (Throwable $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function login(LoginRequest $request, AuthService $authService)
    {
        try {
            return ApiResponse::success($authService->login(new LoginDTO(
                email:      $request['email'],
                password:   $request['password']
            )));
        } catch (Throwable $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function logout(Request $request)
    {
        try {
            return ApiResponse::success(
                $request->user()->tokens()->delete()
            );
        } catch (Throwable $exception) {
            return ApiResponse::error($exception);
        }
    }
}
