<div class="step-content space-y-4">
    <flux:input
        label="Name"
        id="name"
        wire:model.blur="data.profile.name"
    />

    <flux:textarea
        label="Bio"
        id="bio"
        wire:model.blur="data.profile.bio"
        rows="4"
    />

    <flux:checkbox
        label="Set up preferences now"
        id="setup_preferences"
        wire:model.blur="data.profile.setup_preferences"
    />
</div>
