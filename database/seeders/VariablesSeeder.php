<?php

namespace Database\Seeders;

use App\Models\Variable;
use Illuminate\Database\Seeder;

class VariablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $variables = [
            [
                'name' => 'var_monofasica',
                'value' => 1.0
            ],
            [
                'name' => 'var_bifasica',
                'value' => 2.0
            ],
            [
                'name' => 'var_trifasica',
                'value' => 3.0
            ],
            [
                'name' => 'var_kvCopel',
                'value' => 0.55
            ]
        ];

        foreach ($variables as $variable){
            Variable::create($variable);
        }
    }
}
