<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20"/>
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20"/>
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20"/>
            </div>
        </div>
        <div
            class="relative px-3 py-4 h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            @php
                dump(session()->all());
                $configuration = \Idkwhoami\FluxTables\Configuration\TableConfiguration::forModel(\App\Models\User::class)
                    ->columnTogglePersistence(\Idkwhoami\FluxTables\Configuration\Persistence::database())
                    ->paginationPersistence(\Idkwhoami\FluxTables\Configuration\Persistence::session())
                    ->columns([
                        \Idkwhoami\FluxTables\Columns\TextColumn::make('name')
                            ->searchable()
                            ->sortable()
                            ->label("Username"),
                        \Idkwhoami\FluxTables\Columns\TextColumn::make('email')
                            ->searchable()
                            ->sortable()
                            ->toggleable()
                            ->toggled()
                            ->label("Email"),
                        \Idkwhoami\FluxTables\Columns\DateColumn::make('updated_at')
                            ->toggleable()
                            ->sortable()
                            ->label("Last Updated")
                    ])
                    ->filters([
                        \Idkwhoami\FluxTables\Filters\DateRangeFilter::make('created_at')
                            ->label('Created Between')
                    ])
            @endphp
            <livewire:flux-table :configuration="$configuration" />
        </div>
    </div>
</x-layouts.app>
