<?php

namespace App\Livewire\Columns;

use App\Models\User;
use Idkwhoami\FluxTables\Abstracts\Column\Column;
use Idkwhoami\FluxTables\Traits\RefreshesWithTable;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class UserNameInput extends Component
{
    use RefreshesWithTable;

    public User $user;
    public string $name = '';

    public function mount(User $value, Column $column): void
    {
        $this->user = $value;
        $this->name = $this->user->name;
    }

    public function updatedName(string $value): void
    {
        $this->user->update(['name' => $value]);
    }

    public function render(): View
    {
        return view('livewire.columns.user-name-input');
    }

    #[On('flux-tables::table::refresh')] public function handleRefresh(): void
    {
        $this->user->refresh();
        $this->fill($this->user->toArray());
    }
}
