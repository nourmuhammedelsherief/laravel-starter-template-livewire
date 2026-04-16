<?php

use Livewire\Component;
use App\Repositories\TaskRepository;
use Livewire\Attributes\On; 

new class extends Component {
    public $tasks;

    // حقن الـ Repository هنا
    public function boot(TaskRepository $repo) {
        $this->tasks = $repo->getAll();
    }

    #[On('task-created')] // الاستماع للحدث لتحديث القائمة
    public function refreshTasks(TaskRepository $repo) {
        $this->tasks = $repo->getAll();
    }

    public function delete($id, TaskRepository $repo) {
        $repo->delete($id);
        $this->refreshTasks($repo);
    }
}; ?>

<div class="mt-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">قائمة المهام</h2>
        <span class="bg-indigo-500/20 text-indigo-400 px-3 py-1 rounded-full text-sm">
            إجمالي: {{ count($tasks) }}
        </span>
    </div>

    <div class="grid gap-4">
        @forelse($tasks as $task)
            <div class="p-4 bg-slate-800/50 border border-slate-700 rounded-xl flex justify-between items-center hover:bg-slate-800 transition shadow-sm">
                <div>
                    <h3 class="text-white font-semibold">{{ $task->title }}</h3>
                    <p class="text-slate-400 text-sm">{{ $task->description }}</p>
                </div>
                <button wire:click="delete({{ $task->id }})" 
                        wire:confirm="هل أنت متأكد من الحذف؟"
                        class="p-2 text-red-400 hover:bg-red-500/10 rounded-lg transition">
                     حذف
                </button>
            </div>
        @empty
            <p class="text-slate-500 text-center py-10">لا توجد مهام حالياً.. ابدأ بإضافة واحدة!</p>
        @endforelse
    </div>
</div>
