<?php

namespace App\Livewire;

use Idkwhoami\FluxWizards\Core\Step;
use Idkwhoami\FluxWizards\Core\Wizard;
use Idkwhoami\FluxWizards\Traits\HasWizard;
use Livewire\Component;

class UserCreationWizard extends Component
{
    use HasWizard;

    protected function createWizard(): Wizard
    {
        // Create a new wizard with initial data using the fluent API
        return Wizard::make($this->data)
            ->name('user-registration')
            ->steps([
                Step::make('account')
                    ->name('Account Information')
                    ->rules([
                        'email' => 'required|email',
                        'password' => 'required|min:8',
                        'password_confirmation' => 'required|same:password',
                    ])
                    ->fields([
                        'email',
                        'password',
                        'password_confirmation',
                    ]),

                Step::make('profile')
                    ->name('Profile Information')
                    ->rules([
                        'name' => 'required|string|max:255',
                        'bio' => 'nullable|string|max:1000',
                    ])
                    ->fields([
                        'name',
                        'bio',
                        'setup_preferences', // Optional field to control flow
                    ]),

                Step::make('preferences')
                    ->name('User Preferences')
                    ->rules([
                        'notifications' => 'boolean',
                        'theme' => 'required|in:light,dark,system',
                    ])
                    ->fields([
                        'notifications',
                        'theme',
                    ]),
            ])
            ->flow(function ($currentStep, $data) {
                // Example of conditional flow logic
                if ($currentStep === 'profile' && isset($data['profile']['setup_preferences']) && $data['profile']['setup_preferences']) {
                    return 'preferences';
                }

                // Default to the next step in sequence
                return null;
            });
    }

    public function complete()
    {
        // Validate the final step
        $this->nextStep();

        // If we're still on the last step, there were validation errors
        if (!$this->wizard->isLastStep()) {
            return;
        }

        // Process the completed wizard data
        // This is where you would typically create a user, etc.
        $userData = [
            'email' => $this->data['account']['email'],
            'password' => bcrypt($this->data['account']['password']),
            'name' => $this->data['profile']['name'],
        ];

        // Create the user
        // $user = \App\Models\User::create($userData);

        // Set preferences if they were provided
        if (isset($this->data['preferences'])) {
            // $user->preferences()->create([
            //     'notifications' => $this->data['preferences']['notifications'] ?? false,
            //     'theme' => $this->data['preferences']['theme'] ?? 'system',
            // ]);
        }

        // Redirect or show success message
        session()->flash('message', 'Registration completed successfully!');

        // Reset the wizard
        $this->wizard = $this->createWizard();
        $this->data = $this->wizard->getData();
    }
}
