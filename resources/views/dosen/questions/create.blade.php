<x-dosen-layout>
    <!-- PageHeading -->
    <header class="mb-6">
        <h1 class="text-4xl font-black tracking-tight text-text-primary">Tambah Soal Baru</h1>
    </header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-card overflow-hidden shadow-sm sm:rounded-lg border border-border">
                <div class="p-6 text-text-primary">
                    <form method="POST" action="{{ route('dosen.questions.store') }}" x-data="{ type: 'multiple_choice' }">
                        @csrf

                        <!-- Bank Group -->
                        <div class="mb-4">
                            <label for="bank_group" class="block text-sm font-medium text-text-muted">Question Bank</label>
                            <select id="bank_group" name="bank_group" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm" required>
                                @php
                                    $availableBanks = \App\Models\QuestionBank::select('bank_group', 'bank_name')
                                        ->distinct()
                                        ->groupBy('bank_group', 'bank_name')
                                        ->orderBy('bank_group')
                                        ->get();
                                @endphp
                                @foreach($availableBanks as $bank)
                                    <option value="{{ $bank->bank_group }}" {{ old('bank_group', request('bank', 1)) == $bank->bank_group ? 'selected' : '' }}>
                                        {{ $bank->bank_name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('bank_group')" class="mt-2" />
                        </div>

                        <!-- Level -->
                        <div class="mb-4">
                            <label for="level" class="block text-sm font-medium text-text-muted">Level</label>
                            <select id="level" name="level" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm" required>
                                <option value="Easy">Easy</option>
                                <option value="Medium">Medium</option>
                                <option value="Hard">Hard</option>
                            </select>
                            <x-input-error :messages="$errors->get('level')" class="mt-2" />
                        </div>

                        <!-- Question Text -->
                        <div class="mb-4">
                            <label for="soal_text" class="block text-sm font-medium text-text-muted">Question Text</label>
                            <textarea id="soal_text" name="soal_text" rows="3" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm" required>{{ old('soal_text') }}</textarea>
                            <x-input-error :messages="$errors->get('soal_text')" class="mt-2" />
                        </div>

                        <!-- Type -->
                        <div class="mb-4">
                            <label for="tipe" class="block text-sm font-medium text-text-muted">Type</label>
                            <select id="tipe" name="tipe" x-model="type" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm" required>
                                <option value="multiple_choice">Multiple Choice</option>
                                <option value="short_answer">Short Answer</option>
                            </select>
                            <x-input-error :messages="$errors->get('tipe')" class="mt-2" />
                        </div>

                        <!-- Multiple Choice Options -->
                        <div x-show="type === 'multiple_choice'" class="space-y-4 mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="pilihan_a" class="block text-sm font-medium text-text-muted">Option A</label>
                                    <input type="text" id="pilihan_a" name="pilihan_a" value="{{ old('pilihan_a') }}" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                                </div>
                                <div>
                                    <label for="pilihan_b" class="block text-sm font-medium text-text-muted">Option B</label>
                                    <input type="text" id="pilihan_b" name="pilihan_b" value="{{ old('pilihan_b') }}" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                                </div>
                                <div>
                                    <label for="pilihan_c" class="block text-sm font-medium text-text-muted">Option C</label>
                                    <input type="text" id="pilihan_c" name="pilihan_c" value="{{ old('pilihan_c') }}" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                                </div>
                                <div>
                                    <label for="pilihan_d" class="block text-sm font-medium text-text-muted">Option D</label>
                                    <input type="text" id="pilihan_d" name="pilihan_d" value="{{ old('pilihan_d') }}" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Correct Answer -->
                        <div class="mb-4">
                            <label for="jawaban_benar" class="block text-sm font-medium text-text-muted">Correct Answer</label>
                            <input type="text" id="jawaban_benar" name="jawaban_benar" value="{{ old('jawaban_benar') }}" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm" required placeholder="For multiple choice, enter the full text of the correct option">
                            <p class="text-xs text-text-muted mt-1">Make sure this matches exactly one of the options if Multiple Choice.</p>
                            <x-input-error :messages="$errors->get('jawaban_benar')" class="mt-2" />
                        </div>

                        <!-- XP Weight -->
                        <div class="mb-6">
                            <label for="bobot_xp" class="block text-sm font-medium text-text-muted">XP Weight</label>
                            <input type="number" id="bobot_xp" name="bobot_xp" value="{{ old('bobot_xp', 10) }}" class="mt-1 block w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm" required min="1">
                            <x-input-error :messages="$errors->get('bobot_xp')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('dosen.questions.index') }}" class="text-text-muted hover:text-text-primary">Cancel</a>
                            <button type="submit" class="bg-primary hover:bg-primary/80 text-white font-bold py-2 px-4 rounded">
                                Create Question
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-dosen-layout>
