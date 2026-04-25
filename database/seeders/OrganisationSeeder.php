<?php

namespace Database\Seeders;

use App\Models\Glossary;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OrganisationSeeder extends Seeder
{
    public function run(): void
    {
        // ── Demo Organisation ───────────────────────────────────────
        $org = Organisation::firstOrCreate(
            ['slug' => 'ministry-of-finance'],
            [
                'name'               => 'Ministry of Finance',
                'api_key'            => Organisation::generateApiKey(),
                'email'              => 'digital@finance.gov.in',
                'website'            => 'https://finmin.nic.in',
                'department'         => 'Department of Economic Affairs',
                'is_active'          => true,
                'monthly_char_limit' => 2_000_000,
            ]
        );

        // ── Super Admin ─────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@janbhasha.in'],
            [
                'name'            => 'JanBhasha Admin',
                'password'        => Hash::make('password'),
                'organisation_id' => null,
                'role'            => 'super_admin',
            ]
        );

        // ── Org Admin ───────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'finance@janbhasha.in'],
            [
                'name'            => 'Finance Admin',
                'password'        => Hash::make('password'),
                'organisation_id' => $org->id,
                'role'            => 'admin',
            ]
        );

        // ── Translator User ─────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'translator@janbhasha.in'],
            [
                'name'            => 'Ravi Translator',
                'password'        => Hash::make('password'),
                'organisation_id' => $org->id,
                'role'            => 'translator',
            ]
        );

        // ── Demo Glossary Terms ─────────────────────────────────────
        $terms = [
            ['source_term' => 'Ministry',              'target_term' => 'मंत्रालय'],
            ['source_term' => 'Government of India',   'target_term' => 'भारत सरकार'],
            ['source_term' => 'Gazette Notification',  'target_term' => 'राजपत्र अधिसूचना'],
            ['source_term' => 'Financial Year',        'target_term' => 'वित्त वर्ष'],
            ['source_term' => 'Budget',                'target_term' => 'बजट'],
            ['source_term' => 'Department',            'target_term' => 'विभाग'],
            ['source_term' => 'Commissioner',          'target_term' => 'आयुक्त'],
            ['source_term' => 'Notification',          'target_term' => 'अधिसूचना'],
        ];

        foreach ($terms as $term) {
            Glossary::firstOrCreate(
                [
                    'organisation_id' => $org->id,
                    'source_term'     => $term['source_term'],
                ],
                [
                    'target_term'    => $term['target_term'],
                    'case_sensitive' => false,
                ]
            );
        }

        $this->command->info("✅ Demo org created. API Key: {$org->fresh()->api_key}");
    }
}
