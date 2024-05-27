<?php

namespace App\Console\Commands;

use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use function Laravel\Prompts\text;
use function Laravel\Prompts\password;

class CreateUserCommand extends Command
{
    protected $signature = 'create:user';

    protected $description = 'Create a new user';

    public function handle(): int
    {
        try {
            $name = text(
                label: 'What is your name?',
                default: config('constants.base_user_name'),
                validate: fn (string $value) => match (true) {
                    strlen($value) < 3 => 'The name must be at least 3 characters.',
                    strlen($value) > 255 => 'The name must not exceed 255 characters.',
                    default => null
                }
            );

            $email = text(
                label: 'What is your email?',
                default: config('constants.base_user_email'),
                validate: fn (string $value) => match (true) {
                    !filter_var($value, FILTER_VALIDATE_EMAIL) => 'Invalid Email',
                    strlen($value) > 255 => 'The email must not exceed 255 characters.',
                    User::where('email', $value)->exists() => 'The email address already exists.',
                    default => null,
                }
            );

            $password = password(
                label: 'What is your password?',
                validate: fn (string $value) => match (true) {
                    strlen($value) < 8 => 'The password must be at least 8 characters.',
                    default => null
                }
            );

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);

            event(new Registered($user));

            $this->info("User $user->name created!");
            $this->info("\nDone\n");

            return 0;
        } catch (Exception $e) {
            $this->error('Error Creating User');
            $this->error($e->getMessage());
            $this->line('');

            return 1;
        }
    }
}
