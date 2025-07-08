<div class="step-content space-y-4">
    <flux:checkbox
        label="Enable notifications"
        id="notifications"
        wire:model.live="data.preferences.notifications"
    />

    <flux:select
        label="Theme"
        id="theme"
        wire:model.live="data.preferences.theme"
    >
        <option value="">Select a theme</option>
        <option value="light">Light</option>
        <option value="dark">Dark</option>
        <option value="system">System</option>
    </flux:select>
</div>
