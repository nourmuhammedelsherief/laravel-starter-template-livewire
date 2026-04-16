<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Repositories\TaskRepository;

class TaskForm extends Form
{
    public ?int $id = null;

    #[Validate('required|min:3')]
    public $title = '';

    #[Validate('required|min:5')]
    public $description = '';

    public function setTask($task)
    {
        $this->id = $task->id;
        $this->title = $task->title;
        $this->description = $task->description;
    }

    public function store(TaskRepository $repo)
    {
        $this->validate();
        $repo->store($this->all());
        $this->reset(['title', 'description']);
    }

    public function update(TaskRepository $repo)
    {
        $this->validate();
        $repo->update($this->id, $this->except('id'));
        $this->reset();
    }
}
