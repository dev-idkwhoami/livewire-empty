<?php

namespace App\Livewire;

use App\Livewire\Forms\UserForm;
use App\Models\User;
use Idkwhoami\FluxTables\Traits\HasTableCreateComponent;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CreateUser extends Component
{
    use HasTableCreateComponent;

    public UserForm $form;
    public User $model;

    public function create(): void
    {
        $user = $this->form->store();

        if ($user) {
            $this->reset('form');
            $this->closeModal();
            $this->refreshTable();
        }
    }

    public function render(): View
    {
        return view('livewire.create-user');
    }
}
