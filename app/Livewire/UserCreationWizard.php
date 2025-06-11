<?php

namespace App\Livewire;

use Idkwhoami\FluxWizards\Concretes\Step;
use Idkwhoami\FluxWizards\Concretes\Wizard;
use Idkwhoami\FluxWizards\Traits\HasWizard;
use Livewire\Component;

class UserCreationWizard extends Component
{
    use HasWizard;

    protected function createWizard(): Wizard
    {
        // Create a new wizard with initial data using the fluent API
        return Wizard::make('user-registration')
            ->root(
                Step::make('account')
                    ->label('Account')
                    ->rules([
                        'email' => 'required|email',
                        'password' => 'required|min:8',
                        'password_confirmation' => 'required|same:password',
                    ])
                    ->children([
                        Step::make('profile')
                            ->label('Profile Information')
                            ->rules([
                                'name' => 'required|string|max:255',
                                'bio' => 'nullable|string|max:1000',
                            ])
                            ->children([
                                Step::make('preferences')
                                    ->label('User Preferences')
                                    ->rules([
                                        'notifications' => 'boolean',
                                        'theme' => 'required|in:light,dark,system',
                                    ])
                            ])
                    ])
            );
    }

    public function complete(array $data): void
    {
        dd("Finished", $data);
    }

}
