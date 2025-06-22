<div>
    <flux:breadcrumbs class="mb-6">
        <flux:breadcrumbs.item :href="route('dashboard')" wire:navigate class="hidden md:flex">
            {{ __('Home') }}
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item :href="route('decks.index')" wire:navigate class="hidden md:flex">
            {{ __('My Decks') }}
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item :href="route('cards.index', $deck)" wire:navigate class="hidden md:flex">
            {{ $deck->name }}
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>
            {{ __('Add Deck') }}
        </flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <flux:separator variant="subtle"/>

    <form wire:submit="store" class="my-6 max-w-md space-y-6">
        <flux:input
            wire:model="question"
            :label="__('Question')"
            type="text"
            required
            autofocus
            autocomplete="question"
        />

        <flux:input wire:model="answer" :label="__('Answer')" type="text" required autofocus autocomplete="Answer"/>

        <div class="flex items-center gap-4">
            <div class="flex items-center justify-end">
                <flux:button icon="plus" variant="primary" type="submit" class="w-full">
                    {{ __('Add Card') }}
                </flux:button>
            </div>
        </div>
    </form>
</div>
