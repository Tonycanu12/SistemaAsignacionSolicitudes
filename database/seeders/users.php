<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class users extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'John Doe',
                'role' => 'Employer',
                'email' => 'john.doe@example.com',
                'email_verified_at' => now(), // Puedes usar null si no quieres verificar el correo
                'password' => Hash::make('password'),
                'remember_token' => null, // Puedes dejarlo null si no se usa
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'role' => 'Analyst',
                'email' => 'jane.smith@example.com',
                'email_verified_at' => now(), // Puedes usar null si no quieres verificar el correo
                'password' => Hash::make('password'),
                'remember_token' => null, // Puedes dejarlo null si no se usa
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tony Stark',
                'role' => 'Analyst',
                'email' => 'tony.stark@example.com',
                'email_verified_at' => now(), // Puedes usar null si no quieres verificar el correo
                'password' => Hash::make('password'),
                'remember_token' => null, // Puedes dejarlo null si no se usa
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Francky Jones',
                'role' => 'Analyst',
                'email' => 'francky.jones@example.com',
                'email_verified_at' => now(), // Puedes usar null si no quieres verificar el correo
                'password' => Hash::make('password'),
                'remember_token' => null, // Puedes dejarlo null si no se usa
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sanji Vinsmoke',
                'role' => 'Analyst',
                'email' => 'sanji.vinsmoke@example.com',
                'email_verified_at' => now(), // Puedes usar null si no quieres verificar el correo
                'password' => Hash::make('password'),
                'remember_token' => null, // Puedes dejarlo null si no se usa
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jacob Marley',
                'role' => 'Employer',
                'email' => 'jacob.marley@example.com',
                'email_verified_at' => now(), // Puedes usar null si no quieres verificar el correo
                'password' => Hash::make('password'),
                'remember_token' => null, // Puedes dejarlo null si no se usa
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Maria Garcia',
                'role' => 'Employer',
                'email' => 'maria.garcia@example.com',
                'email_verified_at' => now(), // Puedes usar null si no quieres verificar el correo
                'password' => Hash::make('password'),
                'remember_token' => null, // Puedes dejarlo null si no se usa
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
