<flux:dropdown position="bottom" align="end">
    <flux:button variant="ghost" icon="language" class="text-base font-bold text-neutral-900 dark:text-white">
        {{ __('Language') }}
    </flux:button>

    <flux:menu>
        <flux:menu.item wire:click="switchLocale('ar')" class="text-base font-bold">
            العربية
        </flux:menu.item>
        <flux:menu.item wire:click="switchLocale('en')" class="text-base font-bold">
            English
        </flux:menu.item>
    </flux:menu>
</flux:dropdown>
