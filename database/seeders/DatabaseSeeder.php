<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


      // ini untuk admin
      $roleAdmin = Role::create(['name' => 'admin']);
      $userAdmin = User::create([
        'username'  => 'admin1',
        'email'     => 'admin1@gmail.com',
        'password'  => Hash::make('admin1'),
      ]);

      $userAdmin->assignRole($roleAdmin);
    }
}
