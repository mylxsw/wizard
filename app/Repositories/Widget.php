<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Widget
 *
 * @property string $name
 * @property string $ref_id
 * @property integer $type
 * @property string $description
 * @property string $content
 * @property integer $user_id
 * @property integer $operator_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Repositories
 */
class Widget extends Repository
{
    use SoftDeletes;

    const TYPE_MIND_MAPPING = 1;
    const TYPE_MX_GRAPH = 2;

    protected $table = 'wz_widgets';
    protected $fillable
        = [
            'name',
            'ref_id',
            'type',
            'description',
            'content',
            'user_id',
            'operator_id',
        ];

    public $dates = ['deleted_at'];

}