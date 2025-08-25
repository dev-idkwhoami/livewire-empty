<div
    x-data="{ uploading: false, progress: 0 }"
    x-on:livewire-upload-start="uploading = true"
    x-on:livewire-upload-finish="uploading = false"
    x-on:livewire-upload-cancel="uploading = false"
    x-on:livewire-upload-error="uploading = false"
    x-on:livewire-upload-progress="progress = $event.detail.progress"
>
    <form class="flex flex-col gap-6" wire:submit.prevent="dump">

        <!-- File Input -->
        <flux:input type="file" wire:model="file"/>

        <!-- Progress Bar -->
        <div x-cloak x-show="uploading">
            <progress max="100" x-bind:value="progress"></progress>
        </div>

        <flux:button type="submit">Submit</flux:button>
    </form>
</div>
