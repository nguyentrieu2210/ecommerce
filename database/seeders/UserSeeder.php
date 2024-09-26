<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // User::factory()->count(120)->create();
        // User::create([
        //     'name' => 'Nguyễn Văn Triệu',
        //     'email' => 'admin@gmail.com',
        //     'phone' => '123456789',
        //     'address' => '100B, Đường Láng',
        //     'description' => 'Người sáng lập E+',
        //     'password' => bcrypt('123456'), // Bạn có thể sử dụng bcrypt để mã hóa mật khẩu
        //     'publish' => 2,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        //     'user_catalogue_id' => 1,
        //     'deleted_at' => null,
        //     'remember_token' => null,
        //     'email_verified_at' => now(),
        // ]);
    }
}
