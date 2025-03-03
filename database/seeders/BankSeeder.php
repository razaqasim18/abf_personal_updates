<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\BusinessAccount;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{

    public function run(): void
    {
        Bank::create([
            'name' => 'easy paisa',
            'slug' => 'easy_paisa',
        ]);
        BusinessAccount::create([
            'bank_id' => 1,
            'account_holder_name' => env('APP_NAME'),
            'account_number' => '09001921212121212',
            'account_iban' => 'PKEASYPAISA09001921212121212',
        ]);
    }
}
