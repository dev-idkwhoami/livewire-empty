<div class="step-content space-y-4">
    <x-flux::input
        label="Email"
        id="account.email"
        type="email"
        wire:model="data.account.email"
        error="{{ $errors->first('account.email') }}"
    />

    <x-flux::input
        label="Password"
        id="account.password"
        type="password"
        wire:model="data.account.password"
        error="{{ $errors->first('account.password') }}"
    />

    <x-flux::input
        label="Confirm Password"
        id="account.password_confirmation"
        type="password"
        wire:model="data.account.password_confirmation"
        error="{{ $errors->first('account.password_confirmation') }}"
    />
</div>
