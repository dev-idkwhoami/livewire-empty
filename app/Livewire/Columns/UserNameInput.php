<?php

namespace App\Livewire\Columns;

use App\Models\User;
use Idkwhoami\FluxTables\Abstracts\Column\Column;
use Idkwhoami\FluxTables\Livewire\SimpleTable;
use Livewire\Component;

class UserNameInput extends Component
{
    public User $user;
    public string $name = '';

    public function mount(User $value, Column $column)
    {
        $this->user = $value;
        $this->name = $this->user->name;
    }

    public function updatedName(string $value): void
    {
        $this->user->update(['name' => $value]);
        SimpleTable::reload();
    }

    public function render()
    {
        return view('livewire.columns.user-name-input');
    }
}
