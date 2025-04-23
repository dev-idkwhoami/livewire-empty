<?php

namespace App\Livewire\Columns;

use App\Models\User;
use Idkwhoami\FluxTables\Abstracts\Column\Column;
use Idkwhoami\FluxTables\Livewire\SimpleTable;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class UserBannedCheckbox extends Component
{
    public User $user;
    public bool $banned = false;

    public function mount(User $value, Column $column)
    {
        $this->user = $value;
        $this->banned = $this->user->banned;
    }

    public function updatedBanned(bool $value): void
    {
        $this->user->update(['banned' => $value]);
        SimpleTable::reload();
    }

    public function render(): View
    {
        return view('livewire.columns.user-banned-checkbox');
    }
}
