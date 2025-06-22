<div>
    <flux:breadcrumbs class="mb-6">
        <flux:breadcrumbs.item :href="route('dashboard')" wire:navigate class="hidden md:flex">
            {{ __('Home') }}
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item :href="route('decks.index')" wire:navigate class="hidden md:flex">
            {{ __('My Decks') }}
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>
            {{ __('Add Deck') }}
        </flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <flux:separator variant="subtle"/>

    <form wire:submit="store" class="my-6 max-w-md space-y-6">
        <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name"/>

        <flux:radio.group wire:model="is_public" label="Should the deck be visible to everyone?">
            <flux:radio value="1" label="Yes" />
            <flux:radio value="0" label="No" />
        </flux:radio.group>

        <div class="flex items-center gap-4">
            <div class="flex items-center justify-end">
                <flux:button
                    icon="plus"
                    variant="primary"
                    type="submit"
                    class="w-full"
                >
                    {{ __('Add Deck') }}
                </flux:button>
            </div>
        </div>
    </form>
</div>
