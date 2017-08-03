<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Repositories;


class OperationLogs extends Repository
{
    protected $table = 'wz_operation_logs';
    protected $fillable
        = [
            'user_id',
            'message',
            'context',
            'created_at',
        ];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    /**
     * 记录业务日志
     *
     * @param integer $user_id
     * @param string  $message
     * @param array   $context
     *
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public static function log($user_id, string $message, array $context = [])
    {
        return self::create([
            'user_id' => $user_id,
            'message' => $message,
            'context' => $context,
        ]);
    }

    /**
     * 记录业务日志
     *
     * @param        $user_id
     * @param array  $context
     * @param string $message
     * @param array  ...$args
     *
     * @return OperationLogs|\Illuminate\Database\Eloquent\Model
     */
    public static function logf($user_id, array $context, string $message, ...$args)
    {
        return self::log($user_id, sprintf($message, ...$args), $context);
    }

    public function setContextAttribute($value)
    {
        $this->attributes['context'] = json_encode($value,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function getContextAttribute($value)
    {
        if (!is_string($value)) {
            return $value;
        }
        return json_decode($value, true);
    }
}