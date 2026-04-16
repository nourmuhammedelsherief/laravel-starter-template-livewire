<div class="flex h-full w-full flex-1 flex-col gap-6 bg-white p-4 dark:bg-[#0a0a0a] lg:p-8">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('tasks.index') }}" wire:navigate class="text-base font-bold">{{ __('Tasks') }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item class="text-base font-extrabold text-neutral-900 dark:text-white">{{ $isEdit ? __('Edit') : __('Create') }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="max-w-2xl">
        <flux:card class="border-zinc-200 bg-white shadow-2xl dark:border-zinc-700 dark:bg-[#0a0a0a]">
            <div class="space-y-6">
                <div class="text-start">
                    <flux:heading size="lg" class="text-lg font-extrabold text-neutral-900 dark:text-white">{{ $isEdit ? __('Edit Task') : __('Create Task') }}</flux:heading>
                    <flux:subheading class="text-base font-bold text-neutral-900 dark:text-white">{{ __('Please fill in the required details.') }}</flux:subheading>
                </div>

                <form wire:submit="save" class="space-y-6">
                    <flux:field>
                        <flux:label class="text-base font-extrabold text-neutral-900 dark:text-white">{{ __('Title') }}</flux:label>
                        <flux:input wire:model="form.title" placeholder="{{ __('Enter title here...') }}" class="border-zinc-200 bg-neutral-50 text-base font-bold text-neutral-900 placeholder:text-neutral-500 focus:border-indigo-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white dark:placeholder:text-zinc-500" />
                        <flux:error name="form.title" />
                    </flux:field>

                    <flux:field>
                        <flux:label class="text-base font-extrabold text-neutral-900 dark:text-white">{{ __('Description') }}</flux:label>
                        <flux:textarea wire:model="form.description" rows="5" placeholder="{{ __('Write a detailed description...') }}" class="border-zinc-200 bg-neutral-50 text-base font-bold text-neutral-900 placeholder:text-neutral-500 focus:border-indigo-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white dark:placeholder:text-zinc-500" />
                        <flux:error name="form.description" />
                    </flux:field>

                    <div class="flex items-center justify-start gap-3 border-t border-zinc-200 pt-4 dark:border-zinc-700">
                        <flux:button type="submit" variant="primary" class="bg-indigo-600 px-10 text-base font-extrabold hover:bg-indigo-700">
                            {{ $isEdit ? __('Update Task') : __('Save Task') }}
                        </flux:button>
                        <flux:button href="{{ route('tasks.index') }}" wire:navigate variant="ghost" class="text-base font-bold text-neutral-900 hover:text-black dark:text-white dark:hover:text-zinc-300">{{ __('Cancel') }}</flux:button>
                    </div>
                </form>
            </div>
        </flux:card>
    </div>
</div>
