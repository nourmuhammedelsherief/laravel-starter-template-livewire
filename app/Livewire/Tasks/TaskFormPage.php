<?php

namespace App\Livewire\Tasks;

use App\Livewire\Forms\TaskForm;
use App\Repositories\TaskRepository;
use Livewire\Component;
use Flux;

class TaskFormPage extends Component
{
    public TaskForm $form;
    public $isEdit = false;

    public function mount($id = null)
    {
        if ($id) {
            $repo = app(TaskRepository::class); 
            
            $task = $repo->findById($id);
            $this->form->setTask($task);
            $this->isEdit = true;
        }
    }

    public function save(TaskRepository $repo)
    {
        $this->isEdit ? $this->form->update($repo) : $this->form->store($repo);
        Flux::toast(
            variant: 'success',
            heading: $this->isEdit ? __('Task updated') : __('Task created'),
            text: __('Task saved successfully.'),
        );
        return $this->redirect(route('tasks.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.tasks.task-form-page')->layout('layouts.app');
    }
}
