<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <div class="relative h-full flex-1 rounded-xl border border-neutral-200 dark:border-neutral-700 p-8">

            @php
                \Illuminate\Support\Facades\Auth::login(\App\Models\User::first());

                $filters = [
                    \Idkwhoami\FluxTables\Concretes\Filter\DeletedFilter::make('deleted')
                        ->label('Deletion State')
                        ->default(\Idkwhoami\FluxTables\Enums\DeletionState::WithoutDeleted->value),
                    \Idkwhoami\FluxTables\Concretes\Filter\DateRangeFilter::make('created')
                        ->property('created_at')
                        ->label('Created'),
                ];

                $columns = [
                    \Idkwhoami\FluxTables\Concretes\Column\TextColumn::make('name')
                        ->label('Username')
                        ->searchable()
                        ->property('name'),
                    \Idkwhoami\FluxTables\Concretes\Column\DatetimeColumn::make('created')
                        ->humanReadable()
                        ->label("Created")
                        ->sortable()
                        ->property('created_at'),
                    \Idkwhoami\FluxTables\Concretes\Column\DatetimeColumn::make('deleted')
                        ->label("Deleted")
                        ->default('n/a')
                        ->property('deleted_at'),
                    \Idkwhoami\FluxTables\Concretes\Column\ActionColumn::make('actions')
                        ->actions([
                            Idkwhoami\FluxTables\Abstracts\Action\ModalAction::make('open')
                                ->label('Open')
                                ->icon('arrow-top-right-on-square')
                                ->link()
                                ->component('user-delete-confirmation'),
                            Idkwhoami\FluxTables\Abstracts\Action\DirectAction::make('delete')
                                ->visible(fn(\Illuminate\Database\Eloquent\Model $model) => auth()->user()->isNot($model) && !$model->deleted_at)
                                ->label('Delete')
                                ->icon('trash-2')
                                ->action(\Idkwhoami\FluxTables\Concretes\Action\DeleteAction::class),
                            Idkwhoami\FluxTables\Abstracts\Action\DirectAction::make('restore')
                                ->visible(fn(\Illuminate\Database\Eloquent\Model $model) => auth()->user()->isNot($model) && $model->deleted_at)
                                ->label('Restore')
                                ->icon('rotate-ccw')
                                ->action(\Idkwhoami\FluxTables\Concretes\Action\RestoreAction::class),
                        ]),
                ];
            @endphp

            <livewire:flux-simple-table title="Users" :model="\App\Models\User::class" :default-toggled-columns="['created']" :$filters :$columns />

        </div>
    </div>
</x-layouts.app>
