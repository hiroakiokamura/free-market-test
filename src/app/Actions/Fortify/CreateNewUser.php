<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Validation\Rules;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        try {
            // Input data logging
            Log::info('Registration attempt started', [
                'input' => array_merge($input, ['password' => 'HIDDEN'])
            ]);

            // Database connection test
            try {
                DB::connection()->getPdo();
                Log::info('Database connection successful', [
                    'database' => DB::connection()->getDatabaseName()
                ]);
            } catch (\Exception $e) {
                Log::error('Database connection failed', [
                    'error' => $e->getMessage()
                ]);
                throw $e;
            }

            // Validation
            $validator = Validator::make($input, [
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    'unique:users,email'
                ],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);
                throw new \Illuminate\Validation\ValidationException($validator);
            }

            Log::info('Validation passed, attempting to create user');

            // User creation
            try {
                $user = User::create([
                    'name' => $input['name'],
                    'email' => $input['email'],
                    'password' => Hash::make($input['password']),
                ]);

                Log::info('User created successfully', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);

                return $user;
            } catch (\Exception $e) {
                Log::error('Failed to create user', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error in user creation process', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
