<?php

use Illuminate\Database\Seeder;

class PropertiesTriggersTableSeeder extends Seeder
{

    protected $properties = [
         [
             'name' => 'order_total',
             'label' => 'Order Total',
             'triggers' => [
                'exceeds' => 1
            ]
        ],
        [
            'name' => 'vendor',
            'label' => 'Vendor',
            'triggers' => [
                'new' => 0
            ]
        ],
        [
            'name' => 'single_item',
            'label' => 'Any Single Item',
            'triggers' => [
                'over' => 1,
                'new' => 0,
                'exceeds mean by' => 1
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
        DB::table('properties')->truncate();
        DB::table('triggers')->truncate();
        foreach($this->properties as $property) {

            $id = DB::table('properties')->insertGetId([
                'name' => $property['name'],
                'label' => $property['label']
            ]);

            foreach($property['triggers'] as $trigger => $needsLimit ){
                DB::table('triggers')->insert([
                    'description' => $trigger,
                    'has_limit' => $needsLimit,
                    'property_id' => $id
                ]);
            }
        }
    }
}
