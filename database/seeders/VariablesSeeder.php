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
                'value' => 30
            ],
            [
                'name' => 'var_bifasica',
                'value' => 50
            ],
            [
                'name' => 'var_trifasica',
                'value' => 100
            ],
            [
                'name' => 'var_kvCopel',
                'value' => 0.82
            ],
            [
                'name' => 'var_taxaTusd',
                'value' => 0.04
            ]
        ];

        foreach ($variables as $variable){
            Variable::create($variable);
        }
    }
}
