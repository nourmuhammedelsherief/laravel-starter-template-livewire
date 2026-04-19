<div class="flex h-full w-full flex-1 flex-col gap-6 bg-white p-4 dark:bg-[#0a0a0a] lg:p-8">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <flux:heading size="xl" level="1" class="text-lg font-extrabold text-neutral-900 dark:text-white">{{ __('Employee Management') }}</flux:heading>
            <flux:subheading class="text-base font-bold text-neutral-900 dark:text-white">{{ __('Track and manage your daily employees from here.') }}</flux:subheading>
        </div>
        <flux:button href="{{ route('employees.create') }}" wire:navigate variant="primary" icon="plus" class="text-base font-black">
            {{ __('Add Employee') }}
        </flux:button>
    </div>

    <div class="max-w-sm">
        <flux:input wire:model.live="search" placeholder="{{ __('Search employees...') }}" icon="magnifying-glass" class="bg-neutral-50 text-base font-bold text-neutral-900 dark:bg-zinc-900 dark:text-white" />
    </div>

    <flux:card class="border-zinc-200 bg-white dark:border-zinc-700 dark:bg-[#0a0a0a]">
        <flux:table :paginate="$employees">
            <flux:table.columns>
                <flux:table.column class="text-start text-base font-extrabold text-neutral-900 dark:text-white">{{ __('Details') }}</flux:table.column>
                <flux:table.column class="text-start text-base font-extrabold text-neutral-900 dark:text-white">{{ __('Created At') }}</flux:table.column>
                <flux:table.column class="text-center text-base font-extrabold text-neutral-900 dark:text-white">{{ __('Actions') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($employees as $Employee)
                    <flux:table.row :key="$Employee->id">
                        <flux:table.cell>
                            <div class="flex flex-col">
                                <span class="text-base font-bold text-neutral-900 dark:text-white">{{ $Employee->title }}</span>
                                <span class="text-sm font-bold text-neutral-700 dark:text-zinc-300">{{ Str::limit($Employee->description, 60) }}</span>
                            </div>
                        </flux:table.cell>

                        <flux:table.cell class="text-base font-bold text-neutral-700 dark:text-zinc-300">
                            {{ $Employee->created_at->format('Y-m-d') }}
                        </flux:table.cell>

                        <flux:table.cell>
                            <div class="flex justify-center gap-2">
                                <flux:button href="{{ route('employees.edit', $Employee->id) }}" wire:navigate variant="ghost" size="sm" icon="pencil-square" />

                                <flux:modal.trigger name="delete-employee-{{ $Employee->id }}">
                                    <flux:button variant="ghost" size="sm" icon="trash" class="text-red-500" />
                                </flux:modal.trigger>

                                <flux:modal name="delete-employee-{{ $Employee->id }}" class="min-w-[22rem]">
                                    <div class="space-y-6">
                                        <div class="text-start">
                                            <flux:heading size="lg" class="text-lg font-extrabold text-neutral-900 dark:text-white">{{ __('Delete Employee') }}</flux:heading>
                                            <flux:subheading class="text-base font-bold text-neutral-900 dark:text-white">
                                                {{ __('Are you sure you want to delete ":title"?', ['title' => $Employee->title]) }}
                                            </flux:subheading>
                                        </div>

                                        <div class="flex items-center gap-2 border-t border-zinc-200 pt-4 dark:border-zinc-700">
                                            <flux:spacer />
                                            <flux:modal.close>
                                                <flux:button variant="ghost" class="text-base font-bold">{{ __('Cancel') }}</flux:button>
                                            </flux:modal.close>
                                            <flux:button wire:click="delete({{ $Employee->id }})" variant="danger" class="text-base font-black">{{ __('Delete') }}</flux:button>
                                        </div>
                                    </div>
                                </flux:modal>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="3" class="py-20 text-center text-base font-bold italic text-neutral-600 dark:text-zinc-400">
                            {{ __('No employees found yet.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
<script shadow>
    document.addEventListener('livewire:init', () => {
        Echo.channel('employees-channel')
            .listen('EmployeeProcessed', (e) => {
                // إظهار التنبيه باستخدام Flux Toast اللي عندك
                Flux.toast({
                    variant: 'success',
                    heading: 'تحديث لحظي',
                    text: 'تمت معالجة المهمة: ' + e.title
                });
            });
    });
</script>
