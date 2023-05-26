<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $users = json_decode(Storage::get("dev/users.json"), 1);
        $data = json_decode(file_get_contents(storage_path('app/dev/users.json')), 1);
        $users = [];

        for ($i = 1; $i <= 5; $i++) {
            $avatar= "a$i.jpg";

            $name = 'user' . sprintf("%03d", $i);
            $users[] = [
                'id' => $i,
                'name' => $name,
                'email' => "$name@example.com",
                'password' => bcrypt("$name@example.com"),
                'current_team_id' => $i,
                'profile_photo_path' => "profile-photos/$avatar",
                'about' => null,
                'created_at' => now()->subMonths(rand(0,3))->subDays(rand(0,30)),
                'updated_at' => now()->subMonths(rand(0,3))->subDays(rand(0,30)),
            ];
        }
        // dd(tmr(),$users);

        foreach ($data as $id => $item) {
            $avatar = Str::afterLast($item['img'], '/');

            $users[] = [
                'id' => $id,
                'name' => $item['name'],
                'email' => "user$id@example.com",
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'current_team_id' => $id,
                'profile_photo_path' => "profile-photos/$avatar",
                'about' => $item['about'],
                'created_at' => now()->subMonths(rand(0,3))->subDays(rand(0,30)),
                'updated_at' => now()->subMonths(rand(0,3))->subDays(rand(0,30)),
            ];
        }

        // dd(tmr(),$users);
        $teams = [];
        foreach ($users as $user){
            $teams[] = [
                'id' => $user['current_team_id'],
                'user_id' => $user['id'],
                'name' => $user['name'] . '\'s Team',
                'personal_team' => 1,
                'created_at' => $user['created_at'],
                'updated_at' => $user['updated_at'],
            ];
        }

        DB::table('users')->upsert($users, ['id']);
        DB::table('teams')->upsert($teams, ['id']);
    }
}
