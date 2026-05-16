<x-admin-layout>
    <div class="max-w-4xl mx-auto space-y-8">
        <div>
            <h1 class="text-4xl font-black text-text-primary">Konfigurasi Pre-Test</h1>
            <p class="text-text-muted mt-2">Atur jumlah soal dan paket soal untuk Pre-Test mahasiswa</p>
        </div>

        <div class="bg-card rounded-2xl shadow-sm border border-border p-8">
            <form action="{{ route('admin.pretest.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Paket Soal -->
                <div>
                    <label for="bank_group" class="block text-sm font-bold text-text-light-secondary mb-2">Paket Soal (Opsional)</label>
                    <select name="bank_group" id="bank_group" class="w-full rounded-xl border-border bg-surface-dark text-text-primary p-4 focus:ring-primary focus:border-primary">
                        <option value="">-- Semua Paket Soal (Campur) --</option>
                        @foreach($bankGroups as $groupId => $groupName)
                            <option value="{{ $groupId }}" {{ $config['bank_group'] == $groupId ? 'selected' : '' }}>
                                {{ $groupName }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-sm text-text-muted mt-2">Kosongkan untuk mengambil soal dari Bank Group 0 (Pre-Test) atau semua bank jika tidak cukup. Pilih spesifik bank_group untuk mengambil soal dari paket tertentu saja.</p>
                </div>

                <div class="bg-surface-light dark:bg-black/20 p-6 rounded-xl border border-border">
                    <h3 class="text-sm font-bold text-text-primary mb-4">Komposisi Soal Pre-Test:</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="easy_count" class="block text-sm font-medium text-text-muted mb-2">Soal Easy</label>
                            <input type="number" name="easy_count" id="easy_count" value="{{ $config['composition']['Easy'] ?? 10 }}" min="0" class="w-full rounded-xl border-border bg-surface-dark text-text-primary p-3 focus:ring-primary focus:border-primary">
                        </div>
                        
                        <div>
                            <label for="medium_count" class="block text-sm font-medium text-text-muted mb-2">Soal Medium</label>
                            <input type="number" name="medium_count" id="medium_count" value="{{ $config['composition']['Medium'] ?? 10 }}" min="0" class="w-full rounded-xl border-border bg-surface-dark text-text-primary p-3 focus:ring-primary focus:border-primary">
                        </div>
                        
                        <div>
                            <label for="hard_count" class="block text-sm font-medium text-text-muted mb-2">Soal Hard</label>
                            <input type="number" name="hard_count" id="hard_count" value="{{ $config['composition']['Hard'] ?? 10 }}" min="0" class="w-full rounded-xl border-border bg-surface-dark text-text-primary p-3 focus:ring-primary focus:border-primary">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-8 py-4 bg-primary hover:bg-accent-hover text-black font-bold rounded-xl shadow-lg shadow-primary/20 transition-all transform hover:scale-105 flex items-center gap-2">
                        <span class="material-symbols-outlined">save</span>
                        Simpan Konfigurasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
