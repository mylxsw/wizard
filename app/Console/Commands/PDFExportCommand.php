<?php


namespace App\Console\Commands;


use App\Repositories\Document;
use Illuminate\Console\Command;
use TheCodingMachine\Gotenberg\Client;
use TheCodingMachine\Gotenberg\DocumentFactory;
use TheCodingMachine\Gotenberg\MarkdownRequest;

class PDFExportCommand extends Command
{
    protected $signature = 'export:pdf';
    protected $description = 'PDF 导出';

    public function handle()
    {
        $template = <<<TEMP
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>My PDF</title>
  </head>
  <body>
    {{ toHTML .DirPath "file.md" }}
  </body>
</html>
TEMP;

        /** @var Document $doc */
        $doc = Document::where('id', 249)->firstOrFail();

        $client = new Client('http://localhost:3000', new \Http\Adapter\Guzzle6\Client());
        $index = DocumentFactory::makeFromString('index.html', $template);
        $markdowns = [
            DocumentFactory::makeFromString('file.md', $doc->content),
        ];

        $request = new MarkdownRequest($index, $markdowns);
        $client->store($request, '/Users/mylxsw/Downloads/xxx.pdf');
    }
}