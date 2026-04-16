<?php

namespace App\Livewire\Tasks;

use App\Repositories\TaskRepository;
use Livewire\Component;
use Livewire\WithPagination;
use Flux;

class TaskManager extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch() { $this->resetPage(); }

    public function delete($id, TaskRepository $repo)
    {
        $repo->delete($id);
        Flux::toast(
            variant: 'danger',
            heading: __('Delete Task'),
            text: __('Task deleted successfully.'),
        );
    }

    public function render(TaskRepository $repo)
    {
        return view('livewire.tasks.task-manager', [
            'tasks' => $repo->getPaginated($this->search)
        ])->layout('layouts.app');
    }
}
