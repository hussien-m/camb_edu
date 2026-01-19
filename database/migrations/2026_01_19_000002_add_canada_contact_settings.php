<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!DB::table('settings')->where('key', 'contact_phone_ca')->exists()) {
            DB::table('settings')->insert([
                'key' => 'contact_phone_ca',
                'value' => null,
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Canada Phone Number',
                'description' => 'Canada branch phone number',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!DB::table('settings')->where('key', 'contact_whatsapp_ca')->exists()) {
            DB::table('settings')->insert([
                'key' => 'contact_whatsapp_ca',
                'value' => null,
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Canada WhatsApp Number',
                'description' => 'Canada branch WhatsApp number',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Cache::forget('settings_all');
        Cache::forget('setting_contact_phone_ca');
        Cache::forget('setting_contact_whatsapp_ca');
    }

    public function down(): void
    {
        DB::table('settings')->where('key', 'contact_phone_ca')->delete();
        DB::table('settings')->where('key', 'contact_whatsapp_ca')->delete();
    }
};
