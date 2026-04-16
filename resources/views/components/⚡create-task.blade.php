<?php

use Livewire\Component;
use App\Livewire\Forms\TaskForm;


new class extends Component
{
    public TaskForm $form; // هنا حقنّا الفورم أوبجكت

    public function save()
    {
        $this->form::store();
        
        // سنضيف لاحقاً هنا التنبيهات (Flash Messages)
        session()->flash('status', 'تمت إضافة المهمة بنجاح!');
    }
};
?>

<div class="p-6 bg-slate-900 rounded-2xl border border-slate-800 shadow-2xl">
    <h2 class="text-xl font-bold text-white mb-6 border-r-4 border-indigo-500 pr-3">إضافة مهمة جديدة</h2>

    <form wire:submit="save" class="space-y-5">
        <div>
            <label class="block text-slate-400 text-sm mb-2">عنوان المهمة</label>
            <input type="text" wire:model="form.title" 
                   class="w-full bg-slate-800 border-none rounded-lg text-white focus:ring-2 focus:ring-indigo-500 transition">
            @error('form.title') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-slate-400 text-sm mb-2">الوصف التفصيلي</label>
            <textarea wire:model="form.description" rows="3"
                      class="w-full bg-slate-800 border-none rounded-lg text-white focus:ring-2 focus:ring-indigo-500 transition"></textarea>
            @error('form.description') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <button type="submit" 
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg transition-all active:scale-95 flex justify-center items-center gap-2">
            <span wire:loading.remove>حفظ المهمة</span>
            <div wire:loading class="animate-spin rounded-full h-5 w-5 border-2 border-white border-t-transparent"></div>
        </button>
    </form>
</div>
