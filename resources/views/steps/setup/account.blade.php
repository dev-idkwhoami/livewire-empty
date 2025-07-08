<div class="step-content space-y-4">
    <flux:input
        label="Email"
        id="email"
        type="email"
        wire:model.live="data.account.email"
    />

    <flux:input
        label="Password"
        id="password"
        type="password"
        wire:model.live="data.account.password"
    />

    <flux:field>
        <flux:label>
            Confirm Password
        </flux:label>
        <flux:input
            id="password_confirmation"
            type="password"
            wire:model.live="data.account.password_confirmation"
        />
        <flux:error name="data.account.password_confirmation" />
    </flux:field>
</div>
