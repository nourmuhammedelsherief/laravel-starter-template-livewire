<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Repositories\EmployeeRepository;

class EmployeeValidationForm extends Form
{
    public ?int $id = null;

    #[Validate('required|min:3')]
    public $name = '';
    #[Validate('required|email')]
    public $email = '';
    #[Validate('required|min:3')]
    public $title = '';

    public function setEmployee($employee)
    {
        $this->id = $employee->id;
        $this->name = $employee->name;
        $this->email = $employee->email;
        $this->title = $employee->title;
    }

    public function store(EmployeeRepository $repo)
    {
        $this->validate();
        $repo->store($this->all());
        $this->reset(['title', 'name' , 'email']);
    }

    public function update(EmployeeRepository $repo)
    {
        $this->validate();
        $repo->update($this->id, $this->except('id'));
        $this->reset();
    }
}
