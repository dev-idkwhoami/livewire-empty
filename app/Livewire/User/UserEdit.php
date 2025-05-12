<?php

namespace App\Livewire\User;

use App\Livewire\Forms\UserForm;
use App\Models\User;
use Idkwhoami\FluxTables\Traits\InteractsWithTable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class UserEdit extends Component
{
    use InteractsWithTable;

    public UserForm $form;
    public User $model;

    public function mount(): void
    {
        $this->form->fill($this->model->toArray());
    }

    public function save(): void
    {
        if ($this->form->update($this->model)) {
            $this->form->fill($this->model->toArray());

            $this->closeModal();
        }

        $this->refreshTable();
    }

    public function render(): View
    {
        return view('livewire.user.user-edit');
    }

    public function retrieveModel(mixed $id): Model
    {
        return User::query()->findOrFail($id);
    }
}
