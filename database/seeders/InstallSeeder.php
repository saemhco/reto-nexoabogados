<?php

namespace Database\Seeders;

use App\Http\Controllers\v1\Admin\SettingController;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //PASSPORT
        DB::table("oauth_clients")->insert([
            [
                "name" => "Laravel Personal Access Client",
                "secret" => "eIxp7YS8URpjfxx84a4wHtPnIqozmcuOg1aYNo9g",
                "provider" => null,
                "redirect" => env("APP_URL") . "/oauth/authorize",
                "personal_access_client" => 1,
                "password_client" => 0,
                "revoked" => 0,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ],
            [
                "name" => "Laravel Password Grant Client",
                "secret" => "OtbeW8uaerDKMxMhzqOZ6aRisSZ3rcNMIz4oJNRW",
                "provider" => "users",
                "redirect" => env("APP_URL") . "/oauth/authorize",
                "personal_access_client" => 0,
                "password_client" => 1,
                "revoked" => 0,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ]
        ]);
        DB::table("oauth_personal_access_clients")->insert([["client_id" => 1]]);

        $this->call([
            RoleSeeder::class,
        ]);


        // ==================== Defauld users ====================
        User::create(
            [
                'email'     => 'juan@nexoabogados.net',
                'name'      => 'Juan Pérez',
                'password'  => 'password',
                'email_verified_at' => now(),
            ]
        )->syncRoles(['Panel']);

        User::create(
            [
                'email'     => 'e.m.saul.angel@gmail.com',
                'name'      => 'Saúl Escandón',
                'password'  => 'password',
                'email_verified_at' => now(),
            ]
        )->syncRoles(['Abogado(a)']);
    }
}
