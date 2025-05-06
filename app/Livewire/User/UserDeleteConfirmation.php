<?php

namespace App\Livewire\User;

use App\Models\User;
use Flux\Flux;
use Idkwhoami\FluxTables\Abstracts\Action\ModalAction;
use Idkwhoami\FluxTables\Traits\InteractsWithTable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class UserDeleteConfirmation extends Component
{
    use InteractsWithTable;

    public function confirm(): void
    {
        User::query()->findOrFail($this->id)->delete();

        Flux::modal($this->action->modalUniqueName($this->id))->close();

        $this->refreshTable();
    }

    public function getModel(): Model
    {
        return User::query()->findOrFail($this->id);
    }

    public function render(): View
    {
        return view('livewire.user.user-delete-confirmation');
    }
}
