<x-admin-layout>
    <div class="max-w-4xl mx-auto space-y-8">
        <div>
            <h1 class="text-4xl font-black text-text-primary">Research Reports</h1>
            <p class="text-text-muted mt-2">
                Export per-responden untuk analisis NASA-TLX. Boss Battle dikelompokkan otomatis berdasarkan
                <strong>level_adaptif</strong> hasil Pre-Test — mahasiswa Easy → Boss Easy, Medium → Boss Medium,
                Hard → Boss Hard.
            </p>
        </div>

        <div class="bg-card rounded-2xl shadow-sm border border-border p-8">
            <h2 class="text-xl font-bold text-text-primary mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined">download</span>
                Export Data
            </h2>

            <form action="{{ route('admin.reports.export') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-text-light-secondary mb-2">Pilih Sumber Data</label>
                    <select name="report_source" required
                            class="w-full rounded-xl border-border bg-surface-dark text-text-primary p-4 focus:ring-primary focus:border-primary">
                        <option value="" disabled selected>Pilih sumber data...</option>

                        <optgroup label="Pre-Test (30 soal — penentu level_adaptif)">
                            <option value="pretest:all">
                                Semua Kelas
                                @if(($pretestStats ?? collect())->sum())
                                    ({{ ($pretestStats ?? collect())->sum() }} responden)
                                @endif
                            </option>
                            <option value="pretest:TI-2D">
                                TI-2D
                                @if(isset($pretestStats['TI-2D']))
                                    ({{ $pretestStats['TI-2D'] }} responden)
                                @endif
                            </option>
                            <option value="pretest:TI-2E">
                                TI-2E
                                @if(isset($pretestStats['TI-2E']))
                                    ({{ $pretestStats['TI-2E'] }} responden)
                                @endif
                            </option>
                        </optgroup>

                        <optgroup label="Boss Battle — group by level_adaptif (pretest)">
                            <option value="boss:Easy">
                                Kelompok Easy (pretest 0–40%)
                                @if(($bossStats['Easy'] ?? 0) > 0)
                                    ({{ $bossStats['Easy'] }} responden)
                                @endif
                            </option>
                            <option value="boss:Medium">
                                Kelompok Medium (pretest 41–70%)
                                @if(($bossStats['Medium'] ?? 0) > 0)
                                    ({{ $bossStats['Medium'] }} responden)
                                @endif
                            </option>
                            <option value="boss:Hard">
                                Kelompok Hard (pretest 71–100%)
                                @if(($bossStats['Hard'] ?? 0) > 0)
                                    ({{ $bossStats['Hard'] }} responden)
                                @endif
                            </option>
                            <option value="boss:NoPretest">
                                Tanpa Pre-Test (belum mengerjakan pre-test)
                                @if(($bossStats['NoPretest'] ?? 0) > 0)
                                    ({{ $bossStats['NoPretest'] }} responden)
                                @endif
                            </option>
                        </optgroup>

                        @if($events->count())
                            <optgroup label="Boss Battle — Event (Multiplayer)">
                                @foreach($events as $event)
                                    <option value="event:{{ $event->id }}">
                                        {{ $event->title }}
                                        (Created: {{ $event->created_at->format('d M Y') }})
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                    </select>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div class="bg-surface-light dark:bg-black/20 p-4 rounded-xl border border-border">
                        <p class="text-sm font-bold text-text-primary mb-2">Pre-Test CSV berisi:</p>
                        <ul class="text-sm text-text-muted space-y-1 list-disc list-inside">
                            <li>nim, nama, kelas</li>
                            <li>skor_pretest_raw, persentase_pretest, <strong>level_adaptif</strong></li>
                            <li>waktu_pretest, jumlah_benar per level (Easy/Medium/Hard)</li>
                            <li>Placeholder NASA-TLX (MD/PD/TD/OP/EF/FR + skor)</li>
                        </ul>
                    </div>

                    <div class="bg-surface-light dark:bg-black/20 p-4 rounded-xl border border-border">
                        <p class="text-sm font-bold text-text-primary mb-2">Boss Battle CSV berisi:</p>
                        <ul class="text-sm text-text-muted space-y-1 list-disc list-inside">
                            <li>Identitas + level_adaptif (hasil pre-test)</li>
                            <li>Durasi, waktu_tersisa, status (Menang/Kalah/Timeout)</li>
                            <li>Skor, akurasi, soal benar/salah, HP boss & player</li>
                            <li>XP & peringkat leaderboard <em>sebelum</em> &amp; <em>sesudah</em></li>
                            <li>Badge yang unlock di sesi + total badge</li>
                            <li>Placeholder NASA-TLX</li>
                        </ul>
                    </div>
                </div>

                <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-4 text-sm text-amber-200">
                    <p class="font-bold mb-1">Logika auto-grouping</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>
                            <strong>Pre-Test</strong> dideteksi dari flag <code>is_pretest = true</code> (30 soal,
                            tidak terikat raid). Diambil sesi finish pertama per responden.
                        </li>
                        <li>
                            <strong>Boss Battle</strong> dikumpulkan dari semua sesi yang sudah selesai di raid
                            ber-tipe <code>boss</code> (sumber data sama dengan halaman <em>monitoring</em>).
                            Pengelompokan dilakukan berdasarkan <code>pretest_score</code>:
                            0–40 = Easy, 41–70 = Medium, 71–100 = Hard. Responden yang belum sempat mengerjakan
                            pre-test masuk ke kelompok <strong>Tanpa Pre-Test</strong>.
                        </li>
                        <li>
                            Boss yang dimainkan (Easy/Medium/Hard) tidak menyaring data — tercatat di kolom
                            <code>level_sesi</code>. Mahasiswa pretest=Easy yang sudah progresi ke Boss Medium
                            tetap masuk ke kelompok Easy.
                        </li>
                        <li>
                            Akun uji (<code>usertest@gmail.com</code>) dan sesi yang belum selesai selalu dieksklusi.
                        </li>
                    </ul>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="px-8 py-4 bg-primary hover:bg-accent-hover text-black font-bold rounded-xl shadow-lg shadow-primary/20 transition-all transform hover:scale-105 flex items-center gap-2">
                        <span class="material-symbols-outlined">file_download</span>
                        Download CSV
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
