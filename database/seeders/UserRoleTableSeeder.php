<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //создать связи между пользователями и ролями
        foreach (User::all() as $user){
            foreach (Role::all() as $role){
                if ($user->id == 1 && $role->slug == 'root'){
                    $user -> roles()->attach($role->id);
                }
                if (in_array($user->id,[2,3]) && $role -> slug == 'admin'){
                    $user -> roles()->attach($role->id);
                }
                if ($user->id > 3 && $role->slug == 'user'){
                    $user ->roles()->attach($role -> id);
                }
            }
        }
    }
}
