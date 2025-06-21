<div>
    <div class="flex mb-6 justify-between">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('dashboard')" wire:navigate>Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item :href="route('decks.index')" wire:navigate>My Decks</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Deck | {{ $deck->name }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        <flux:button icon="plus" variant="primary" :href="route('cards.create', $deck)" wire:navigate>
            Add Card
        </flux:button>
    </div>

    <flux:separator variant="subtle"/>
</div>
