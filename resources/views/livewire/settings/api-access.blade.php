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
                    Important: This is your personal access token. Please copy and store it securely now — you won’t be
                    able to see it again. If you lose it, you’ll need to generate a new one.
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
                    Notice: Generating a new access token will delete all your existing tokens. This means any apps or
                    devices using old tokens will no longer be authorized.
                </div>
            </div>
        @endif

        <flux:separator variant="subtle" class="my-6"/>

        <div class="flex-1 self-stretch max-md:pt-6">
            <flux:heading>API Logs</flux:heading>
            <flux:subheading>API Logs from last 30 days</flux:subheading>

            <div class="mt-5">
                <div class="overflow-hidden rounded-xl shadow border border-gray-200 mb-4">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-100 text-gray-600 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3">Url</th>
                            <th class="px-4 py-3">Method</th>
                            <th class="px-4 py-3">IP</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @foreach($apiLogs as $apiLog)
                            <tr class="hover:bg-gray-50 hover:text-gray-600">
                                <td class="px-4 py-2">
                                    {{ $apiLog->url }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $apiLog->method }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $apiLog->ip }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $apiLogs->links() }}
            </div>
        </div>

    </x-settings.layout>
</section>
