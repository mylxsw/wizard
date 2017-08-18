<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

$storagePath = env('STORAGE_PATH');
if (empty($storagePath)) {
    return [];
}

return [
    'storage' => $storagePath,
];