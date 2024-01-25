<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Payments\PaymentMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(base_path('database/data/payment-method.json'));
        $data = json_decode($json, true);
        foreach ($data as $method) {
            PaymentMethod::create([
                'name' => Arr::get($method, 'name'),
                'slug' => Arr::get($method, 'slug'),
                'tax' => Arr::get($method, 'tax'),
            ]);
        }
    }
}
