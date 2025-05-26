<div class="step-content space-y-4">
    <x-flux::checkbox
        label="Enable notifications"
        id="preferences.notifications"
        wire:model="data.preferences.notifications"
        error="{{ $errors->first('preferences.notifications') }}"
    />

    <x-flux::select
        label="Theme"
        id="preferences.theme"
        wire:model="data.preferences.theme"
        error="{{ $errors->first('preferences.theme') }}"
    >
        <option value="">Select a theme</option>
        <option value="light">Light</option>
        <option value="dark">Dark</option>
        <option value="system">System</option>
    </x-flux::select>
</div>
