<?php

use Illuminate\Database\Seeder;

class ApiCredentialsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('api_credentials')->delete();
        
        DB::table('api_credentials')->insert([
                ['name' => 'client_id', 'value' => '129278844351513', 'site' => 'Facebook'],
                ['name' => 'client_secret', 'value' => 'c276e08ae94a608fb1261442790413fc', 'site' => 'Facebook'],
                ['name' => 'client_id', 'value' => '800519828894-9qt0dh3ev5007c47cf89m7ul2vkn4apm.apps.googleusercontent.com', 'site' => 'Google'],
                ['name' => 'client_secret', 'value' => 'RlUCUja9a7RtX8qH2wUBRJpw', 'site' => 'Google'],
                ['name' => 'client_id', 'value' => 'G0DLXz75sPrDVpx6HEIuIhbBi', 'site' => 'Twitter'],
                ['name' => 'client_secret', 'value' => 'g630RX61tedmY0Ya7WPWGncjaSzOgFBvnt5boiuYzemTiG67lx', 'site' => 'Twitter'],                
                ['name' => 'cloudinary_name', 'value' => 'spiffy', 'site' => 'Cloudinary'],
                ['name' => 'cloudinary_key', 'value' => '537475456743331', 'site' => 'Cloudinary'],
                ['name' => 'cloudinary_secret', 'value' => '4a74X_aoyB8gYXw2Uok7DkRTFc0', 'site' => 'Cloudinary'],
                ['name' => 'cloud_base_url', 'value' => 'http://res.cloudinary.com/spiffy', 'site' => 'Cloudinary'],
                ['name' => 'cloud_secure_url', 'value' => 'https://res.cloudinary.com/spiffy', 'site' => 'Cloudinary'],
                ['name' => 'cloud_api_url', 'value' => 'https://api.cloudinary.com/v1_1/spiffy', 'site' => 'Cloudinary'],
                ['name' => 'apple_service_id', 'value' => 'com.trioangle.spiffy.service.demo', 'site' => 'Apple'],
                ['name' => 'apple_team_id', 'value' => 'W89HL6566S', 'site' => 'Apple'],
                ['name' => 'apple_key_id', 'value' => 'RK77BMK54P', 'site' => 'Apple'],
                ['name' => 'apple_key_file', 'value' => 'key.txt', 'site' => 'Apple'],
            ]);
    }
}
