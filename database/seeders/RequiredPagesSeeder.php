<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequiredPagesSeeder extends Seeder
{
    public function run()
    {
        $pages = [
            [
                'title' => 'Accreditations',
                'slug' => 'accreditations',
                'content' => '<div class="container py-5">
                    <h2>Our Accreditations</h2>
                    <p>Cambridge British International College - UK maintains the highest standards of academic excellence through various international accreditations and recognitions.</p>
                    <p>Our programs are recognized globally and meet international educational standards.</p>
                </div>',
                'is_published' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Verification of Issued Degree',
                'slug' => 'verification',
                'content' => '<div class="container py-5">
                    <h2>Degree Verification</h2>
                    <p>To verify the authenticity of a degree issued by Cambridge British International College - UK, please contact our verification department.</p>
                    <p>You can submit your verification request through our official channels.</p>
                </div>',
                'is_published' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Attestation',
                'slug' => 'attestation',
                'content' => '<div class="container py-5">
                    <h2>Attestation Services</h2>
                    <p>Cambridge British International College - UK provides attestation services for all issued certificates and degrees.</p>
                    <p>Our attestation process ensures your qualifications are recognized internationally.</p>
                </div>',
                'is_published' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($pages as $page) {
            DB::table('pages')->insertOrIgnore($page);
        }

        echo "✓ Required pages created successfully!\n";
    }
}
