<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('API Access')" :subheading=" __('Get Access to public API')">
        @if($token)
            <div class="flex flex-col gap-2">
                <div
                    x-data="{
                    text: '{{ $token }}',
                    copied: false,
                    copy() {
                        if (navigator.clipboard?.writeText) {
                            navigator.clipboard.writeText(this.text).then(() => {
                                this.copied = true
                                setTimeout(() => this.copied = false, 2000)
                            })
                        } else {
                            alert('Clipboard API not supported in http unsecure request. Please copy manually.');
                        }
                    }
                }"
                    class="flex gap-2 items-center"
                >
                    <flux:input readonly variant="filled" value="{{ $token }}"/>

                    <div x-show="copied" class="text-green-600">
                        <flux:button icon="clipboard-document-check"/>
                    </div>
                    <div x-show="!copied" @click="copy">
                        <flux:button icon="clipboard"/>
                    </div>
                </div>
                <div class="text-xs">
                    Important: This is your personal access token. Please copy and store it securely now — you won’t be able to see it again. If you lose it, you’ll need to generate a new one.
                </div>
            </div>
        @else
            <div class="flex flex-col gap-2">
                <div>
                    <flux:button variant="primary" wire:click="generateToken">
                        {{ __('Generate API Token') }}
                    </flux:button>
                </div>
                <div class="text-xs">
                    Notice: Generating a new access token will delete all your existing tokens. This means any apps or devices using old tokens will no longer be authorized.
                </div>
            </div>
        @endif
    </x-settings.layout>
</section>
