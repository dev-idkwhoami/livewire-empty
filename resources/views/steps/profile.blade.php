<div class="step-content space-y-4">
    <x-flux::input
        label="Name"
        id="profile.name"
        wire:model="data.profile.name"
        error="{{ $errors->first('profile.name') }}"
    />

    <x-flux::textarea
        label="Bio"
        id="profile.bio"
        wire:model="data.profile.bio"
        rows="4"
        error="{{ $errors->first('profile.bio') }}"
    />

    <x-flux::checkbox
        label="Set up preferences now"
        id="profile.setup_preferences"
        wire:model="data.profile.setup_preferences"
    />
</div>
