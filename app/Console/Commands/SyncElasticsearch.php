<?php

namespace App\Console\Commands;

use Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;

class SyncElasticsearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:elasticsearch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sync index to elasticsearch';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    }
}
