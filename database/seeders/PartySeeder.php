<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Party;

class PartySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parties = [
            // Customers
            [
                'party_type' => 'customer',
                'title' => 'Metro Shopping Mall',
                'short_name' => 'MSM',
                'address' => '123 Commercial Avenue, Gulshan-e-Iqbal, Karachi, Pakistan',
                'contact' => '+92-21-1234567',
                'email' => 'procurement@metromall.com',
                'contact_person' => 'Ahmed Khan',
                'bank_name' => 'Habib Bank Limited',
                'iban' => 'PK36SCBL0000001123456702',
                'ntn' => '1234567-8',
                'status' => 'active',
            ],
            [
                'party_type' => 'customer',
                'title' => 'Ocean Freight Solutions',
                'short_name' => 'OFS',
                'address' => '456 Port Road, Kemari, Karachi, Pakistan',
                'contact' => '+92-21-9876543',
                'email' => 'operations@oceanfreight.com',
                'contact_person' => 'Fatima Ali',
                'bank_name' => 'United Bank Limited',
                'iban' => 'PK15UNIL0109000116253201',
                'ntn' => '2345678-9',
                'status' => 'active',
            ],
            [
                'party_type' => 'customer',
                'title' => 'Continental Electronics',
                'short_name' => 'CE',
                'address' => '789 Industrial Area, Lahore, Pakistan',
                'contact' => '+92-42-5555555',
                'email' => 'logistics@continental.com',
                'contact_person' => 'Muhammad Hassan',
                'bank_name' => 'MCB Bank Limited',
                'iban' => 'PK70MUCB0000000000000123',
                'ntn' => '3456789-0',
                'status' => 'active',
            ],
            // Vendors
            [
                'party_type' => 'vendor',
                'title' => 'Prime Fuel Suppliers',
                'short_name' => 'PFS',
                'address' => '321 Energy Complex, Clifton, Karachi, Pakistan',
                'contact' => '+92-21-7777777',
                'email' => 'sales@primefuel.com',
                'contact_person' => 'Asim Malik',
                'bank_name' => 'Allied Bank Limited',
                'iban' => 'PK19ABPA0000001234567890',
                'ntn' => '4567890-1',
                'status' => 'active',
            ],
            [
                'party_type' => 'vendor',
                'title' => 'AutoCare Maintenance Services',
                'short_name' => 'ACMS',
                'address' => '654 Workshop Street, Faisalabad, Pakistan',
                'contact' => '+92-41-3333333',
                'email' => 'service@autocare.com',
                'contact_person' => 'Tariq Ahmed',
                'bank_name' => 'Bank Al Falah Limited',
                'iban' => 'PK04ALFH0000000000000987',
                'ntn' => '5678901-2',
                'status' => 'active',
            ],
            [
                'party_type' => 'vendor',
                'title' => 'TechParts Distribution',
                'short_name' => 'TPD',
                'address' => '987 Parts Market, Rawalpindi, Pakistan',
                'contact' => '+92-51-4444444',
                'email' => 'info@techparts.com',
                'contact_person' => 'Sana Qureshi',
                'bank_name' => 'Standard Chartered Bank',
                'iban' => 'PK89SCBL0000001234567890',
                'ntn' => '6789012-3',
                'status' => 'inactive',
            ],
        ];

        foreach ($parties as $party) {
            Party::create($party);
        }
    }
}
