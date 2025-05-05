<?php

namespace App\Livewire\User;

use App\Models\User;
use Flux\Flux;
use Idkwhoami\FluxTables\Abstracts\Action\ModalAction;
use Idkwhoami\FluxTables\Traits\InteractsWithTable;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class UserDeleteConfirmation extends Component
{
    use InteractsWithTable;

    public ModalAction $action;
    public mixed $id;

    public function mount(ModalAction $action, mixed $id): void
    {
        $this->id = $id;
        $this->action = $action;
    }

    public function confirm(): void
    {
        User::query()->findOrFail($this->id)->delete();

        Flux::modal($this->action->modalUniqueName($this->id))->close();

        $this->refreshTable();
    }

    public function render(): View
    {
        return view('livewire.user.user-delete-confirmation');
    }
}
