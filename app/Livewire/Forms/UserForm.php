<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Idkwhoami\FluxTables\Traits\TableForm;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Form;

class UserForm extends Form
{
    use TableForm;

    public string $name = '';
    public string $email = '';

    public function rulesStore(): array
    {
        return [
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|string|email|max:255|unique:users,email',
        ];
    }

    public function rulesUpdate(User $user): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'name')->ignoreModel($user),
            ],
            'email' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'email')->ignoreModel($user),
            ],
        ];
    }

    public function store(): ?Model
    {
        $this->validatedForAction();

        return User::query()->create(array_merge(
            $this->validatedForAction(),
            ['password' => Hash::make('password')]
        ));
    }


    public function configureModel(): string
    {
        return User::class;
    }
}
