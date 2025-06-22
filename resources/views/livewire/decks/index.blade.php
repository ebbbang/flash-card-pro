<div>
    <div class="flex mb-6 justify-between">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('dashboard')" wire:navigate>
                {{ __('Home') }}
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item>
                {{ __('My Decks') }}
            </flux:breadcrumbs.item>
        </flux:breadcrumbs>

        <flux:button icon="plus" variant="primary" :href="route('decks.create')" wire:navigate>
            {{ __('Add Deck') }}
        </flux:button>
    </div>

    <flux:separator variant="subtle" class="mb-6"/>

    <div class="grid md:grid-cols-3 gap-6 mb-6">
        @foreach ($decks as $deck)
            <a
                href="{{ route('cards.index', $deck) }}"
                class="flex items-center gap-4 p-4 rounded-xl border border-neutral-200 dark:border-neutral-700 cursor-pointer"
                wire:navigate
            >
                <flux:icon.rectangle-stack class="size-12"/>
                <div class="flex flex-col">
                    <div class="text-lg">
                        {{ $deck->name }}
                    </div>
                    <div class="text-gray-400 dark:text-gray-500 text-sm">
                        {{ $deck->cards_count }} {{ __('Cards') }}
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    {{ $decks->links() }}
</div>
