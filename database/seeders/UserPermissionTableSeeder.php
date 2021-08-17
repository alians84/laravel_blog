<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //создать связи между пользователями и правами
        foreach (User::all() as $user){
            foreach (Permission::all() as $perm){
                if (rand(1,20) == 10){
                    $user->permissions()->attach($perm->id);
                }
            }
        }
    }
}
