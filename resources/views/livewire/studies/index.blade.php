<div class="flex flex-col h-full">
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
            {{ __('Study Deck') }}
        </flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <flux:separator variant="subtle" class="mb-6"/>

    <div class="flex-1 flex justify-center">
        <div class="max-w-md w-full flex-1 flex flex-col justify-between">
            @if($currentCardIndex < $totalCards)
                <div class="flex flex-col space-y-6">
                    <div>
                        {{ $currentCardIndex + 1 }} / {{ $totalCards }} Cards
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                        <div class="bg-blue-600 h-4" style="width: {{ $this->progress }}%"></div>
                    </div>

                    <div class="text-center">
                        {{ $this->currentCard['question'] }}
                    </div>

                    @if($this->currentCard['answerRevealed'])
                        <div class="text-center">
                            {{ $this->currentCard['answer'] }}
                        </div>
                    @else
                        <div class="text-center">
                            <flux:button variant="primary" class="w-min" wire:click="revealAnswer()">
                                {{ __('Reveal Answer') }}
                            </flux:button>
                        </div>
                    @endif
                </div>

                @if($this->currentCard['answerRevealed'])
                    <div class="flex flex-col gap-4 justify-center items-center">
                        <flux:button
                            icon="hand-thumb-up"
                            variant="primary"
                            class="w-min"
                            wire:click="setAnsweredCorrectly(true)"
                        >
                            {{ __('I got it right!') }}
                        </flux:button>
                        <flux:button
                            icon="hand-thumb-down"
                            variant="primary"
                            color="red"
                            class="w-min"
                            wire:click="setAnsweredCorrectly(false)"
                        >
                            {{ __('Maybe next time...') }}
                        </flux:button>
                    </div>
                @endif
            @else
                <div class="flex flex-col gap-6 justify-center items-center h-full">
                    <div>
                        Score: {{ $this->score }} / {{ $totalCards }}
                    </div>
                    <flux:button
                        icon="arrow-uturn-left"
                        variant="primary"
                        :href="route('cards.index', $deck)"
                        wire:navigate
                    >
                        {{ $deck->name }}
                    </flux:button>
                </div>
            @endif
        </div>
    </div>
</div>
