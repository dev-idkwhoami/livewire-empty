<div class="step-content space-y-4">
    <flux:input
        label="Name"
        id="name"
        wire:model.live="data.profile.name"
    />

    <flux:textarea
        label="Bio"
        id="bio"
        wire:model.live="data.profile.bio"
        rows="4"
    />

    <flux:checkbox
        label="Set up preferences now"
        id="setup_preferences"
        wire:model.live="data.profile.setup_preferences"
    />
</div>
