<div class="p-4 w-96">
    <form class="flex flex-col space-y-6 justify-between" wire:submit.prevent="save">
        <flux:input label="Username" wire:model.blur="form.name"></flux:input>
        <flux:input label="Email address" wire:model.blur="form.email"></flux:input>

        <div class="flex">
            <flux:spacer />
            <flux:button variant="primary" type="submit">Update user</flux:button>
        </div>
    </form>
</div>
