<?php

namespace App\Http\Controllers;

use App\Events\DocumentCreated;
use App\Repositories\Document;
use App\Repositories\DocumentHistory;
use App\Repositories\Project;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Log;

/**
 * 批量导入
 *
 * @package App\Http\Controllers
 */
class ImportController extends Controller
{
    /**
     * 批量导入文档
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function documents(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'file' => 'required|file',
            ],
            [
                'file.required' => '导入的文件不能为空',
                'file.file'     => '导入的文件不能为空',
            ]
        );

        $file = $request->file('file');
        $fileExtension = strtolower($file->getClientOriginalExtension());

        $checkData = [
            'extension'  => $fileExtension,
            'project_id' => $id,
        ];
        $checkRule = [
            'extension'  => 'in:' . implode(',', ['zip', 'md', 'markdown', 'yml', 'yaml', 'json']),
            'project_id' => "required|integer|min:1|project_exist",
        ];

        $pageId = $request->input('page_id');
        if (!empty($pageId)) {
            $checkData['page_id'] = $pageId;
            $checkRule['page_id'] = "integer|min:1|page_exist:{$id}";
        }

        $this->validateParameters(
            $checkData,
            $checkRule,
            ['extension.in' => '上传文件类型不支持']
        );

        /** @var Project $project */
        $project = Project::where('id', $id)->firstOrFail();
        $this->authorize('page-add', $project);

        switch ($fileExtension) {
            case 'zip':
                $this->importZip($project, $file, $pageId);
                break;
            case 'md': // no break
            case 'markdown':
                $this->importMarkdown($project, $file, $pageId);
                break;
            case 'json': // no break
            case 'yaml': // no break
            case 'yml':
                $this->importSwagger($project, $file, $pageId);
                break;
        }

        $this->alertSuccess(__('common.operation_success'));
        return redirect()->back();
    }

    /**
     * 导入 Markdown 文档
     *
     * @param Project $project
     * @param UploadedFile $file
     * @param null $pid
     * @return Document
     */
    private function importMarkdown(Project $project, UploadedFile $file, $pid = null)
    {
        return $this->createDocument(
            $project,
            Document::TYPE_DOC,
            basename($file->getClientOriginalName(), ".{$file->getClientOriginalExtension()}"),
            file_get_contents($file->getRealPath()),
            $pid
        );
    }

    /**
     * 导入 Swagger 文档
     *
     * @param Project $project
     * @param UploadedFile $file
     * @param null $pid
     * @return Document
     */
    private function importSwagger(Project $project, UploadedFile $file, $pid = null)
    {
        return $this->createDocument(
            $project,
            Document::TYPE_SWAGGER,
            basename($file->getClientOriginalName(), ".{$file->getClientOriginalExtension()}"),
            file_get_contents($file->getRealPath()),
            $pid
        );
    }

    /**
     * 批量导入 zip
     *
     * @param Project $project
     * @param UploadedFile $file
     * @param null $pid
     * @throws \Exception
     */
    private function importZip(Project $project, UploadedFile $file, $pid = null)
    {
        $zipArch = new \ZipArchive();
        if (!$zipArch->open($file->getRealPath())) {
            throw new \Exception('无法打开文件');
        }

        $validFiles = $this->extractValidFilenames($zipArch);
        $navigatorsMap = $this->createNavigatorsMap($project, $pid ?? 0);
        // 创建文档目录结构（目录 ——> 空白文档）
        $files = collect($validFiles)->map(function ($name) use ($project, &$navigatorsMap, $pid) {
            $dir = dirname($name);
            $dir = ($dir === '.' || $dir === '/') ? '' : $dir;

            $prefix = '';
            foreach (explode('/', $dir) as $i => $s) {
                $current = "{$prefix}/{$s}";
                if (!isset($navigatorsMap[$current])) {
                    $doc = $this->createDocument(
                        $project,
                        Document::TYPE_DOC,
                        $s,
                        '',
                        $navigatorsMap[$prefix] ?? ($pid ?? 0)
                    );
                    $navigatorsMap[$current] = $doc->id;
                }

                $prefix = $current;
            }


            $filePath = rtrim($prefix, '/') . '/' . basename($name, '.' . pathinfo($name)['extension']);
            return [
                'name' => isset($navigatorsMap[$filePath]) ? null : $name,
                'pid'  => $navigatorsMap["/{$dir}"] ?? null,
            ];
        })->filter(function ($file) {
            return !empty($file['name']);
        })->unique('name');

        // 写入文档
        $files->each(function ($file) use ($zipArch, $project) {
            $stream = $zipArch->getStream($file['name']);
            try {
                $content = stream_get_contents($stream);
                $basenameL = strtolower(basename($file['name']));
                $type = Str::endsWith($basenameL, ['.md', '.markdown']) ? Document::TYPE_DOC : Document::TYPE_SWAGGER;
                $title = basename($file['name'], '.' . pathinfo($file['name'])['extension']);

                $this->createDocument($project, $type, $title, $content, $file['pid']);
            } finally {
                fclose($stream);
            }
        });
    }

    /**
     * create a document
     *
     * @param Project $project 项目
     * @param int $type 文件类型
     * @param string $title 标题
     * @param mixed $content 文件内容
     * @param mixed $pid 上级页面id
     * @return Document
     */
    private function createDocument(Project $project, int $type, string $title, $content, $pid): Document
    {
        $pageItem = Document::create([
            'pid'               => $pid ?? 0,
            'title'             => $title,
            'description'       => '批量导入',
            'content'           => $content,
            'project_id'        => $project->id,
            'user_id'           => \Auth::user()->id,
            'last_modified_uid' => \Auth::user()->id,
            'type'              => $type,
            'status'            => Document::STATUS_NORMAL,
        ]);

        // 记录文档变更历史
        DocumentHistory::write($pageItem);
        event(new DocumentCreated($pageItem));

        return $pageItem;
    }

    /**
     * 过滤要导出的文档
     *
     * @param array $navigators
     * @param \Closure $filter
     *
     * @return array|mixed
     */
    private function filterNavigators(array $navigators, \Closure $filter)
    {
        foreach ($navigators as $nav) {
            if ($filter($nav)) {
                return $nav['nodes'] ?? [];
            }

            if (!empty($nav['nodes'])) {
                $sub = $this->filterNavigators($nav['nodes'], $filter);
                if (!empty($sub)) {
                    return $sub;
                }
            }
        }

        return [];
    }

    /**
     * 遍历所有目录
     *
     * @param array $navigators
     * @param \Closure $callback
     * @param array $parents
     */
    private function traverseNavigators(array $navigators, \Closure $callback, array $parents = [])
    {
        traverseNavigators($navigators, $callback, $parents, true);
    }

    /**
     * 创建导航 Map，用于查找文档上级 ID
     *
     * @param Project $project
     * @param int $pid
     * @return int[]
     */
    private function createNavigatorsMap(Project $project, int $pid): array
    {
        $navigators = navigatorSort(navigator($project->id, 0));
        if ($pid > 0) {
            $navigators = $this->filterNavigators($navigators, function (array $nav) use ($pid) {
                return (int)$nav['id'] === (int)$pid;
            });
        }

        $navigatorsMap = ['/' => $pid];
        $this->traverseNavigators($navigators, function ($nav, array $parents) use (&$navigatorsMap) {
            $fullPath = '';
            foreach ($parents as $par) {
                $fullPath = $fullPath . '/' . $par['name'];
            }

            $navigatorsMap[$fullPath . '/' . $nav['name']] = $nav['id'];
        });
        return $navigatorsMap;
    }

    /**
     * 从压缩包中提取可导入的文件名
     *
     * @param \ZipArchive $zipArch
     * @return array
     */
    private function extractValidFilenames(\ZipArchive $zipArch): array
    {
        $validFiles = [];
        for ($i = 0; $i < $zipArch->numFiles; $i++) {
            $name = $zipArch->getNameIndex($i);
            $basename = basename($name);

            if (Str::startsWith($basename, '.')) {
                continue;
            }

            $basenameL = strtolower($basename);
            if (!Str::endsWith($basenameL, ['.md', '.markdown', '.yaml', '.yml', '.json'])) {
                continue;
            }

            $validFiles[] = $name;
        }
        return $validFiles;
    }
}