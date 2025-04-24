<?php

namespace MagicBox\LaraQuickKit\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Console\Command;

class AdminUserSeeder extends Seeder
{
    public function run(Command $command = null): void
    {
        $email = 'admin@laraquickkit.test';

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin Global',
                'password' => Hash::make('password'),
            ]
        );

        if ($role = Role::where('name', 'admin_global')->first()) {
            $user->assignRole($role);
        }

        if ($command) {
            $command->info("User admin_global berhasil dibuat dengan email: {$email} dan password: password");
        }
    }
}
