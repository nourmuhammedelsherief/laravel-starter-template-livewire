<?php

namespace App\Livewire;

use Livewire\Component;

class LanguageSwitcher extends Component
{
    public string $locale = 'en';

    public function mount(): void
    {
        $this->locale = session('locale', app()->getLocale());
    }

    public function switchLocale(string $locale)
    {
        if (! in_array($locale, ['en', 'ar'], true)) {
            return;
        }

        session(['locale' => $locale]);
        app()->setLocale($locale);
        $this->locale = $locale;

        // الحل السحري: بنعمل reload كامل للمتصفح عشان الـ RTL يطبق 
        // ونخرج من طلب الـ POST اللي بيعمل الإيرور
        $this->js('window.location.reload()');
    }

    public function render()
    {
        return view('livewire.language-switcher');
    }
}
