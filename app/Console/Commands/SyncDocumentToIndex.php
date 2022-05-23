<?php

namespace App\Console\Commands;

use App\Components\Search\Search;
use App\Repositories\Document;
use Illuminate\Console\Command;

/**
 * 文档索引同步到搜索引擎
 */
class SyncDocumentToIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync-index:document';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sync document to index server for search';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Document::withTrashed()->chunk(10, function ($docs) {
            /** @var Document $doc */
            foreach ($docs as $doc) {
                try {
                    if (!empty($doc->deleted_at)) {
                        Search::get()->deleteIndex($doc->id);
                        $this->info(sprintf("delete document %s ok", $doc->title));
                    } else {
                        Search::get()->syncIndex($doc);
                        $this->info(sprintf("sync document %s ok", $doc->title));
                    }
                } catch (\Exception $ex) {
                    $this->error("{$ex->getFile()}:{$ex->getLine()} {$ex->getMessage()}");
                }
            }
        });
    }

}
