<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['peran']    = 'mahasiswa';

        $pengguna = User::create($data);
        $token = $pengguna->createToken('token-perangkat')->plainTextToken;

        return response()->json([
            'sukses' => true,
            'pesan'  => 'Registrasi berhasil',
            'data'   => [
                'user'  => [
                    'id'    => $pengguna->id,
                    'nama'  => $pengguna->name,
                    'email' => $pengguna->email,
                    'peran' => $pengguna->peran,
                ],
                'token' => $token,
            ],
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $pengguna = User::where('email', $request->email)->first();

        if (! $pengguna || ! Hash::check($request->password, $pengguna->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau kata sandi salah.'],
            ]);
        }

        $pengguna->update([
            'terakhir_login' => now(),
        ]);

        $token = $pengguna->createToken('token-perangkat')->plainTextToken;

        return response()->json([
            'sukses' => true,
            'pesan'  => 'Login berhasil',
            'data'   => [
                'user'  => [
                    'id'    => $pengguna->id,
                    'nama'  => $pengguna->name,
                    'email' => $pengguna->email,
                    'peran' => $pengguna->peran,
                ],
                'token' => $token,
            ],
        ]);
    }

    public function profil(Request $request): JsonResponse
    {
        $pengguna = $request->user();

        return response()->json([
            'sukses' => true,
            'data'   => [
                'id'         => $pengguna->id,
                'nama'       => $pengguna->name,
                'email'      => $pengguna->email,
                'peran'      => $pengguna->peran,
                'kemampuan'  => $pengguna->currentAccessToken()->abilities,
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'sukses' => true,
            'pesan'  => 'Logout berhasil',
        ]);
    }

    public function logoutSemua(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'sukses' => true,
            'pesan'  => 'Seluruh sesi perangkat telah diakhiri',
        ]);
    }

    public function ubahPassword(Request $request): JsonResponse
    {
        $request->validate([
            'password_lama' => ['required', 'string'],
            'password_baru' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $pengguna = $request->user();

        if (! Hash::check($request->password_lama, $pengguna->password)) {
            throw ValidationException::withMessages([
                'password_lama' => ['Kata sandi lama salah.'],
            ]);
        }

        $pengguna->update([
            'password' => Hash::make($request->password_baru),
        ]);

        return response()->json([
            'sukses' => true,
            'pesan'  => 'Kata sandi berhasil diubah',
        ]);
    }
}
