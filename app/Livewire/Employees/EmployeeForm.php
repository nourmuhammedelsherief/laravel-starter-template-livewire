<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use App\Livewire\Forms\EmployeeValidationForm;
use App\Repositories\EmployeeRepository;
use Flux;

class EmployeeForm extends Component
{
    public EmployeeValidationForm $form;
    public $isEdit = false;

    public function mount($id = null)
    {
        if ($id) {
            $repo = app(EmployeeRepository::class); 
            
            $employee = $repo->findById($id);
            $this->form->setEmployee($employee);
            $this->isEdit = true;
        }
    }

    public function save(EmployeeRepository $repo)
    {
        $this->isEdit ? $this->form->update($repo) : $this->form->store($repo);
        Flux::toast(
            variant: 'success',
            heading: $this->isEdit ? __('Employee updated') : __('Employee created'),
            text: __('Employee saved successfully.'),
        );
        return $this->redirect(route('employees.index'), navigate: true);
    }
    public function render()
    {
        return view('livewire.employees.employee-form')->layout('layouts.app');
    }
}
