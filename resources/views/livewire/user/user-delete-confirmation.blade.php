<div class="p-12">
    <flux:heading>
        Are you sure you want to delete this user ?
    </flux:heading>
    <flux:subheading>
        Deleting a user will disable their account and archive all of their data. After 30 Days everything will be deleted.
    </flux:subheading>

    <flux:text>
        {{ $this->model->id }}
    </flux:text>

    <flux:text>
        {{ $this->model->name }}
    </flux:text>

    <div class="flex">
        <flux:spacer />
        <flux:button wire:click.prevent="confirm">
            I understand
        </flux:button>
    </div>
</div>
