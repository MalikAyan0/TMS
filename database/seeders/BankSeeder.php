<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            [
                'name' => 'National Bank of Pakistan',
                'short_name' => 'NBP',
                'address' => 'NBP Head Office, I.I. Chundrigar Road, Karachi, Pakistan',
                'contact' => '+92-21-99220100',
                'email' => 'info@nbp.com.pk',
                'contact_person' => 'Ahmed Hassan',
                'status' => 'active',
            ],
            [
                'name' => 'Habib Bank Limited',
                'short_name' => 'HBL',
                'address' => 'HBL Plaza, I.I. Chundrigar Road, Karachi, Pakistan',
                'contact' => '+92-21-32412411',
                'email' => 'customercare@hbl.com',
                'contact_person' => 'Fatima Ali',
                'status' => 'active',
            ],
            [
                'name' => 'United Bank Limited',
                'short_name' => 'UBL',
                'address' => 'UBL Building, Jinnah Avenue, Blue Area, Islamabad, Pakistan',
                'contact' => '+92-51-9220025',
                'email' => 'info@ubl.com.pk',
                'contact_person' => 'Muhammad Khan',
                'status' => 'active',
            ],
            [
                'name' => 'MCB Bank Limited',
                'short_name' => 'MCB',
                'address' => 'MCB Centre, Main Gulberg, Lahore, Pakistan',
                'contact' => '+92-42-36041066',
                'email' => 'contactcenter@mcb.com.pk',
                'contact_person' => 'Sarah Ahmed',
                'status' => 'active',
            ],
            [
                'name' => 'Allied Bank Limited',
                'short_name' => 'ABL',
                'address' => 'Allied Bank Plaza, Club Road, Lahore, Pakistan',
                'contact' => '+92-42-37113016',
                'email' => 'info@abl.com',
                'contact_person' => 'Tariq Mahmood',
                'status' => 'inactive',
            ],
            [
                'name' => 'Bank Alfalah Limited',
                'short_name' => 'BAFL',
                'address' => 'Bank Alfalah Building, Shahrah-e-Faisal, Karachi, Pakistan',
                'contact' => '+92-21-38140786',
                'email' => 'info@bankalfalah.com',
                'contact_person' => 'Asma Sheikh',
                'status' => 'active',
            ],
        ];

        foreach ($banks as $bank) {
            Bank::create($bank);
        }
    }
}
