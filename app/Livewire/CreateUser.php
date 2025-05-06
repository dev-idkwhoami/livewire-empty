<?php

namespace App\Livewire;

use App\Models\User;
use Flux\Flux;
use Idkwhoami\FluxTables\Traits\HasTableCreateComponent;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateUser extends Component
{
    use HasTableCreateComponent;

    #[Validate('required|string|max:255|unique:users,name')]
    public string $name = '';
    #[Validate('required|string|email|max:255|unique:users,email')]
    public string $email = '';

    public function create(): void
    {
        $this->validate();

        Flux::modal($this->modal)->close();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => 'password'
        ]);

        $this->redirect('/dashboard');
    }

    public function render(): View
    {
        return view('livewire.create-user');
    }
}
