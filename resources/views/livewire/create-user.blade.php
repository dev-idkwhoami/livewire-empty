<div class="p-4">
    <form class="flex flex-col space-y-6 justify-between" wire:submit.prevent="create">
        <flux:input label="Username" wire:model.blur="name"></flux:input>
        <flux:input label="Email address" wire:model.blur="email"></flux:input>

        <div class="flex">
            <flux:spacer />
            <flux:button variant="primary" type="submit">Create User</flux:button>
        </div>
    </form>
</div>
