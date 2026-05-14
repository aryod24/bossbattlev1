@props(['user'])

<div x-data="{ editing: @js($errors->has('nama')), nama: @js(old('nama', $user->nama)) }">
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <div class="flex items-center justify-between gap-3">
                <x-input-label for="nama" :value="__('Nama')" />
                <button
                    type="button"
                    class="text-xs font-semibold text-primary hover:text-primary/80 transition"
                    x-show="!editing"
                    x-on:click="editing = true; $nextTick(() => $refs.namaInput.focus())"
                >
                    Edit Profil
                </button>
            </div>
            <x-text-input
                id="nama"
                name="nama"
                type="text"
                class="mt-1 block w-full"
                x-ref="namaInput"
                x-model="nama"
                x-bind:readonly="!editing"
                x-bind:class="editing ? 'bg-background-dark' : 'bg-surface-dark/60 text-text-muted'"
                autocomplete="name"
                required
            />
            <x-input-error class="mt-2" :messages="$errors->get('nama')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                name="email"
                type="email"
                class="mt-1 block w-full bg-surface-dark/60 text-text-muted cursor-not-allowed"
                :value="old('email', $user->email)"
                readonly
                autocomplete="username"
                required
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-text-primary">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-text-muted hover:text-text-primary rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-500">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <div class="flex items-center gap-3" x-show="editing">
                <x-primary-button>{{ __('Save') }}</x-primary-button>
                <button
                    type="button"
                    class="text-sm text-text-muted hover:text-text-primary transition"
                    x-on:click="editing = false; nama = @js(old('nama', $user->nama))"
                >
                    Batal
                </button>
            </div>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-text-muted"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</div>
