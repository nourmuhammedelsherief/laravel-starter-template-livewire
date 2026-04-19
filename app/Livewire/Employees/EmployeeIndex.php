<?php

namespace App\Livewire\Employees;

use App\Repositories\EmployeeRepository;
use Livewire\Component;
use Livewire\WithPagination;
use Flux;


class EmployeeIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch() { $this->resetPage(); }

    public function delete($id, EmployeeRepository $repo)
    {
        $repo->delete($id);
        Flux::toast(
            variant: 'danger',
            heading: __('Delete Task'),
            text: __('Task deleted successfully.'),
        );
    }

    public function render(EmployeeRepository $repo)
    {
        return view('livewire.employees.employee-index', [
            'employees' => $repo->getPaginated($this->search)
        ])->layout('layouts.app');
    }
}
