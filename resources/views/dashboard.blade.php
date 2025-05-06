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
                    \Idkwhoami\FluxTables\Concretes\Filter\ValuePresentFilter::make('email_verified')
                        ->property('email_verified_at')
                        ->label('Exclude unverified')
                        ->description('Hide all users that haven\'t verified their email address.')
                        ->pillContent('Unverified excluded'),
                    \Idkwhoami\FluxTables\Concretes\Filter\BooleanFilter::make('banned')
                        ->property('banned'),
                ];

                $columns = [
                    \Idkwhoami\FluxTables\Concretes\Column\ComponentColumn::make('name')
                        ->label('Username')
                        ->sortable()
                        ->searchable()
                        ->component('columns.user-name-input')
                        ->property('name'),
                    \Idkwhoami\FluxTables\Concretes\Column\DatetimeColumn::make('created')
                        ->humanReadable()
                        ->label("Created")
                        ->sortable()
                        ->property('created_at'),
                    \Idkwhoami\FluxTables\Concretes\Column\TextColumn::make('posts')
                        ->count()
                        ->label('Posts')
                        ->relation('posts')
                        ->property('posts_count'),
                    \Idkwhoami\FluxTables\Concretes\Column\DatetimeColumn::make('email_verified')
                        ->label("Email Verified At")
                        ->sortable()
                        ->property('email_verified_at'),
                    \Idkwhoami\FluxTables\Concretes\Column\JsonColumn::make('preferences_users_pagination')
                        ->label("Preferences Users Pagination")
                        ->sortable()
                        ->property('preferences')
                        ->type(\Idkwhoami\FluxTables\Enums\JsonPropertyType::Integer)
                        ->path('pagination.users'),
                    \Idkwhoami\FluxTables\Concretes\Column\JsonColumn::make('preferences_locale')
                        ->label("Preferences Locale")
                        ->sortable()
                        ->property('preferences')
                        ->path('locale'),
                    \Idkwhoami\FluxTables\Concretes\Column\BooleanColumn::make('banned')
                        ->label('Banned')
                        ->property('banned'),
                    \Idkwhoami\FluxTables\Concretes\Column\DatetimeColumn::make('deleted')
                        ->label("Deleted")
                        ->default('n/a')
                        ->property('deleted_at'),
                    \Idkwhoami\FluxTables\Concretes\Column\ActionColumn::make('actions')
                        ->actions([
                            Idkwhoami\FluxTables\Abstracts\Action\DirectAction::make('open')
                                ->access(fn(\App\Models\User $user, \Illuminate\Database\Eloquent\Model $value) => $user->isNot($value))
                                ->label('Edit')
                                ->icon('pencil')
                                ->link()
                                ->variant('ghost')
                                ->operation(Idkwhoami\FluxTables\Concretes\Operation\RouteOperation::make('edit')->route('test.route')->navigate()),
                            Idkwhoami\FluxTables\Abstracts\Action\ModalAction::make('open')
                                ->label('Open')
                                ->icon('arrow-top-right-on-square')
                                ->link()
                                ->variant('ghost')
                                ->component('user.user-delete-confirmation'),
                            Idkwhoami\FluxTables\Abstracts\Action\DirectAction::make('delete')
                                ->visible(fn(\Illuminate\Database\Eloquent\Model $model) => auth()->user()->isNot($model) && !$model->deleted_at)
                                ->label('Delete')
                                ->icon('trash-2')
                                ->operation(Idkwhoami\FluxTables\Concretes\Operation\DeleteOperation::make('delete')),
                            Idkwhoami\FluxTables\Abstracts\Action\DirectAction::make('restore')
                                ->visible(fn(\Illuminate\Database\Eloquent\Model $model) => auth()->user()->isNot($model) && $model->deleted_at)
                                ->label('Restore')
                                ->icon('rotate-ccw')
                                ->operation(Idkwhoami\FluxTables\Concretes\Operation\RestoreOperation::make('restore')),
                        ]),
                ];

                /*session()->flush();*/
            @endphp

            @dump(session()->all())

            <livewire:flux-simple-table page-name="up" create="create-user" title="Users" :model="\App\Models\User::class" :default-toggled-columns="['created']" :$filters :$columns />

            {{--@dump(\Illuminate\Support\Facades\Context::allHidden())--}}
        </div>
        <div class="relative h-full flex-1 rounded-xl border border-neutral-200 dark:border-neutral-700 p-8">

            @php
                $filters = [
                ];

                $columns = [
                    \Idkwhoami\FluxTables\Concretes\Column\TextColumn::make('name')
                        ->label('Title')
                        ->searchable()
                        ->property('name'),
                    \Idkwhoami\FluxTables\Concretes\Column\TextColumn::make('author')
                        ->label('Author')
                        ->relation('user')
                        ->property('name'),
                    \Idkwhoami\FluxTables\Concretes\Column\DatetimeColumn::make('created')
                        ->humanReadable()
                        ->label("Created")
                        ->sortable()
                        ->property('created_at'),
                    \Idkwhoami\FluxTables\Concretes\Column\TextColumn::make('tags')
                        ->count()
                        ->label('Tags')
                        ->property('tags_count')
                        ->relation('tags'),
                ];
            @endphp

            <livewire:flux-simple-table search-name="test" page-name="pp" title="Posts" :model="\App\Models\Post::class" :$filters :$columns />

            {{--@dump(session()->all())--}}
            {{--@dump(\Illuminate\Support\Facades\Context::allHidden())--}}
        </div>
    </div>
</x-layouts.app>
