<div>
    <div class="flex mb-6 justify-between">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('dashboard')" wire:navigate>
                {{ __('Home') }}
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item :href="route('decks.index')" wire:navigate>
                {{ __('My Decks') }}
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item :href="route('cards.index', $deck)" wire:navigate>
                {{ $deck->name }}
            </flux:breadcrumbs.item>
        </flux:breadcrumbs>

        <flux:button icon="plus" variant="primary" :href="route('cards.create', $deck)" wire:navigate>
            {{ __('Add Card') }}
        </flux:button>
    </div>

    <flux:separator variant="subtle" class="mb-6"/>

    <div class="grid md:grid-cols-3 gap-6 mb-6">
        @foreach ($cards as $card)
            <div class="flex items-center gap-4 p-4 rounded-xl border border-neutral-200 dark:border-neutral-700 ">
                <div class="flex-1 flex flex-col">
                    <div class="text-lg">
                        {{ $card->question }}
                    </div>
                    <div class="text-gray-400 dark:text-gray-500 text-sm">
                        {{ $card->answer }}
                    </div>
                </div>
                <flux:button.group>
                    <flux:button
                        icon="pencil-square"
                        variant="primary"
                        :href="route('cards.edit', [$deck, $card])"
                        wire:navigate
                    />
                    <flux:button
                        icon="trash"
                        variant="primary"
                        wire:click="delete({{ $card->id }})"
                        wire:confirm="{{ __('Are you sure you want to delete this card?') }}"
                    />
                </flux:button.group>
            </div>
        @endforeach
    </div>

    {{ $cards->links() }}
</div>
