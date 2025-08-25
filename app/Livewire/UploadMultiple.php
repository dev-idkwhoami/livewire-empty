<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class UploadMultiple extends Component
{
    use WithFileUploads;

    /**
     * @var TemporaryUploadedFile[]
     */
    public array $files = [];

    public function render(): View
    {
        return view('livewire.upload-multiple');
    }

    public function dump(): void
    {
        dump($this->files);

        foreach ($this->files as $file) {
            $file->store('public');
        }
    }
}
