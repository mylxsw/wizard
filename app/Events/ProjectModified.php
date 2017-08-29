<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Events;

use App\Repositories\Project;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProjectModified
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $project;
    private $op;

    /**
     * ProjectModified constructor.
     *
     * @param Project $project
     * @param string  $op 执行的操作类型
     */
    public function __construct(Project $project, string $op)
    {
        $this->project = $project;
        $this->op      = $op;
    }

    public function getProject()
    {
        return $this->project;
    }

    public function getOp()
    {
        return $this->op;
    }

    public function isBasicUpdate()
    {
        return $this->op == 'basic';
    }

    public function isPrivilegeUpdate()
    {
        return $this->op == 'privilege';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
