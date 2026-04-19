<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public array $salesOverview = [
        3200, 4200, 3900, 5100, 5600, 6100, 6800, 7200, 6900, 7600, 8200, 9100,
    ];

    public array $userDistribution = [
        'labels' => ['Free Plan', 'Pro Plan', 'Enterprise'],
        'series' => [58, 30, 12],
    ];

    public array $monthlyRevenue = [
        18000, 22500, 21000, 27800, 30100, 33200, 34800, 36500, 35400, 38900, 41200, 45800,
    ];

    public array $months = [
        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
    ];

    public function render()
    {
        return view('livewire.dashboard')->layout('layouts.app');
    }
}
