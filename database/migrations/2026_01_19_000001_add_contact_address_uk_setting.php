<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('settings')->where('key', 'contact_address_uk')->exists();

        if (!$exists) {
            DB::table('settings')->insert([
                'key' => 'contact_address_uk',
                'value' => '86-90 Paul Street, London, England, EC2A 4NE',
                'type' => 'textarea',
                'group' => 'contact',
                'label' => 'UK Address',
                'description' => 'United Kingdom branch address',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $phoneValue = '+44 7848 195975';
        $whatsappValue = '+44 7848 195975';

        if (DB::table('settings')->where('key', 'contact_phone')->exists()) {
            DB::table('settings')
                ->where('key', 'contact_phone')
                ->update(['value' => $phoneValue, 'updated_at' => now()]);
        } else {
            DB::table('settings')->insert([
                'key' => 'contact_phone',
                'value' => $phoneValue,
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Phone Number',
                'description' => 'Main contact phone',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (DB::table('settings')->where('key', 'contact_whatsapp')->exists()) {
            DB::table('settings')
                ->where('key', 'contact_whatsapp')
                ->update(['value' => $whatsappValue, 'updated_at' => now()]);
        } else {
            DB::table('settings')->insert([
                'key' => 'contact_whatsapp',
                'value' => $whatsappValue,
                'type' => 'text',
                'group' => 'contact',
                'label' => 'WhatsApp Number',
                'description' => 'WhatsApp contact number',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Cache::forget('settings_all');
        Cache::forget('setting_contact_address_uk');
        Cache::forget('setting_contact_phone');
        Cache::forget('setting_contact_whatsapp');
    }

    public function down(): void
    {
        DB::table('settings')->where('key', 'contact_address_uk')->delete();
    }
};
