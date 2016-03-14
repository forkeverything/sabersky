<?php

use Illuminate\Database\Seeder;

class PropertiesTriggersTableSeeder extends Seeder
{

    protected $ruleProperties = [
         [
             'label' => 'Order Total',
             'name' => 'order_total',
             'triggers' => [
                'exceeds' => [
                    'label' => 'Exceeds',
                    'has_limit' => 1
                ]
            ]
        ],
        [
            'label' => 'Vendor',
            'name' => 'vendor',
            'triggers' => [
                'new' => [
                    'label' => 'No Previous Orders',
                    'has_limit' => 0
                ]
            ]
        ],
        [
            'label' => 'Any Single Item',
            'name' => 'single_item',
            'triggers' => [
                'over' => [
                    'label' => 'Exceeds',
                    'has_limit' => 1
                ],
                'new' => [
                    'label' => 'First Order',
                    'has_limit' => 0
                ],
                'percentage_over_mean' => [
                    'label' => 'Over Mean by',
                    'has_limit' => 1
                ]
            ]
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rule_properties')->truncate();
        DB::table('rule_triggers')->truncate();
        foreach($this->ruleProperties as $property) {

            $propertyId = DB::table('rule_properties')->insertGetId([
                'name' => $property['name'],
                'label' => $property['label']
            ]);

            foreach($property['triggers'] as $name => $trigger ){
                DB::table('rule_triggers')->insert([
                    'name' => $name,
                    'label' => $trigger['label'],
                    'has_limit' => $trigger['has_limit'],
                    'rule_property_id' => $propertyId
                ]);
            }
        }
    }
}
