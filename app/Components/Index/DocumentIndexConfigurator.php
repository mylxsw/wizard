<?php

namespace App\Components\Index;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class DocumentIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    protected $name = 'wizard_doc_index';

    /**
     * @var array
     */
    protected $settings = [
        'analysis' => [
            'analyzer' => [
                'es_std' => [
                    'type' => 'standard',
                ]
            ]
        ]
    ];
}