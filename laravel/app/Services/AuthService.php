<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\LoginDTO;
use App\DTO\SignUpDTO;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthService
{
    /**
     * @throws Throwable
     */
    public function signUp(SignUpDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $user = User::query()->create([
                'name' => $dto->name,
                'email' => $dto->email,
                'password' => $dto->password, // В модели User password => hashed, что автоматически хэширует его
            ]);

            $token = $user->createToken(config('app.name'))->plainTextToken;
            return [
                'token' => $token,
                'user' => $user,
            ];
        });
    }

    /**
     * @throws Throwable
     */
    public function login(LoginDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $user = User::query()->where('users.email', $dto->email)->first();

            throw_if(!$user, new Exception( 'Такого пользователя не существует', 404));
            throw_if(!Hash::check($dto->password, $user->password), new Exception('Такой комбинации email и пароля не существует', 404));

            $token = $user->createToken(config('app.name'))->plainTextToken;

            return [
                'token' => $token,
                'user' => $user,
            ];
        });
    }
}
