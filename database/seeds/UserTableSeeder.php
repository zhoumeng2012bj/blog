<?php
/**
 * Created by PhpStorm.
 * User: zhanglei
 * Date: 2017/5/19
 * Time: 10:16
 */
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0;$i<10;$i++){
            DB::table('users')->insert([
                'name' => str_random(10),
                'email' => str_random(10).'@gmail.com',
                'password' => bcrypt('secret'),
                'phone' => str_random(10),
                'avatar' => '',

            ]);
        }
         DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@qq.com',
            'password' => bcrypt('123456'),
            'phone' => str_random(10),
            'avatar' => '',

        ]);
    }
}
