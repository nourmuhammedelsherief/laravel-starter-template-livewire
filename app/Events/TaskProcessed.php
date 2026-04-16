<?php
namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast; // مهم جداً للإرسال اللحظي
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskProcessed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * هنا بنمرر المهمة اللي تم معالجتها عشان نبعت بياناتها للمتصفح
     */
    public function __construct(public Task $task)
    {
        //
    }

    /**
     * تحديد القناة (Channel) اللي هنبعت عليها الإشارة.
     * هنستخدم قناة عامة (Public Channel) حالياً للسهولة.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('tasks-channel'),
        ];
    }

    /**
     * البيانات اللي عاوز تبعتها للمتصفح (اختياري)
     * لارافل بيبعت كل الـ Public properties تلقائياً، بس هنا بنحدد أكتر.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->task->id,
            'title' => $this->task->title,
            'message' => __('Task processed successfully!'),
        ];
    }
}
