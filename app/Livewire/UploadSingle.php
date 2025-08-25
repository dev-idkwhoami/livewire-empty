<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class UploadSingle extends Component
{
    use WithFileUploads;

    public ?TemporaryUploadedFile $file = null;

    public function render(): View
    {
        return view('livewire.upload-single');
    }

    public function dump(): void
    {
        dump($this->file);

        $this->file->store('public');
    }

}
