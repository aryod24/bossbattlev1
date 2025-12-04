<x-app-layout>
    <div x-data="bossQuiz()" x-init="init()" class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Boss Header Section -->
            <div class="bg-card overflow-hidden shadow-sm sm:rounded-lg border border-border mb-6 p-6 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <!-- Boss Avatar Placeholder -->
                    <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center text-2xl border-2 border-text-primary">
                        👾
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <h2 class="text-2xl font-bold text-text-primary" x-text="bossName"></h2>
                        <div class="w-full h-4 bg-background-dark rounded-full mt-2 overflow-hidden border border-border relative">
                            <div class="h-full transition-all duration-500 absolute top-0 left-0"
                                 :style="`width: ${(bossHpCurrent/bossHpMax)*100}%`"
                                 :class="hpBarColor()">
                            </div>
                        </div>
                        <span class="text-sm text-text-muted mt-1 block" x-text="`HP: ${bossHpCurrent}/${bossHpMax}`"></span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-mono font-bold" :class="timerWarning()" x-text="formatTimer(timeRemaining)"></div>
                    <div class="text-sm text-text-muted">Time Remaining</div>
                </div>
            </div>

            <!-- Question Section -->
            <div class="bg-card overflow-hidden shadow-sm sm:rounded-lg border border-border p-6">
                <div class="mb-4 flex justify-between items-center">
                    <span class="text-lg font-medium text-text-primary">
                        Soal <span x-text="currentQuestionIndex + 1"></span> dari <span x-text="questions.length"></span>
                    </span>
                    <span class="px-3 py-1 bg-background-dark rounded text-sm text-text-muted" x-text="currentQuestion.tipe.replace('_', ' ').toUpperCase()"></span>
                </div>

                <div class="text-xl text-text-primary mb-6" x-html="currentQuestion.soal_text"></div>

                <!-- Multiple Choice -->
                <template x-if="currentQuestion.tipe === 'multiple_choice'">
                    <div class="space-y-3">
                        <template x-for="option in ['A','B','C','D']">
                            <label class="flex items-center p-4 border border-border rounded-lg cursor-pointer hover:bg-background-dark transition-colors"
                                   :class="selectedAnswer === option ? 'border-primary ring-1 ring-primary' : ''">
                                <input type="radio"
                                       :name="'answer_'+currentQuestion.id"
                                       :value="option"
                                       x-model="selectedAnswer"
                                       class="hidden">
                                <div class="flex items-center w-full">
                                    <span class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-full bg-background-dark text-text-primary font-bold mr-3 border border-border"
                                          :class="selectedAnswer === option ? 'bg-primary text-black border-primary' : ''"
                                          x-text="option"></span>
                                    <span class="text-text-primary" x-text="currentQuestion['pilihan_'+option.toLowerCase()]"></span>
                                </div>
                            </label>
                        </template>
                    </div>
                </template>

                <!-- Short Answer -->
                <template x-if="currentQuestion.tipe === 'short_answer'">
                    <textarea x-model="selectedAnswer"
                              placeholder="Tulis jawaban Anda..."
                              class="w-full rounded-md bg-background-dark border-border text-text-primary shadow-sm focus:border-primary focus:ring-primary h-32 p-4"></textarea>
                </template>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end">
                    <button @click="submitAnswer()"
                            :disabled="!selectedAnswer || isSubmitting"
                            class="bg-primary hover:bg-primary/80 text-black font-bold py-2 px-6 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center transition-colors">
                        <span x-show="!isSubmitting">Submit Jawaban</span>
                        <span x-show="isSubmitting" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Feedback Toast -->
        <div x-show="showFeedback" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-2"
             class="fixed bottom-6 right-6 px-6 py-4 rounded-lg shadow-lg text-white font-bold z-50"
             :class="feedbackCorrect ? 'bg-green-600' : 'bg-red-600'">
            <span x-text="feedbackMessage"></span>
        </div>
    </div>

    <script>
        function bossQuiz() {
            return {
                questions: {{ Js::from($questions) }},
                sessionId: {{ $session->id }},
                raidId: {{ $soloRaid->id }},
                bossHpMax: {{ $session->boss_hp_awal }},
                bossHpCurrent: {{ $session->boss_hp_akhir }},
                bossName: '{{ $soloRaid->{'boss_'.strtolower($session->level).'_name'} }}',
                deadline: new Date('{{ $deadline->toIso8601String() }}').getTime(),
                timeRemaining: 0,
                
                currentQuestionIndex: 0,
                selectedAnswer: '',
                isSubmitting: false,
                showFeedback: false,
                feedbackCorrect: false,
                feedbackMessage: '',
                timerInterval: null,
                warningShown: false,
                startTime: null,

                get currentQuestion() {
                    return this.questions[this.currentQuestionIndex];
                },

                init() {
                    this.updateTimer(); // Initial update
                    this.startTimer();
                    this.startTime = Date.now();
                    // Find first unanswered question
                    const firstUnanswered = this.questions.findIndex(q => !q.is_answered);
                    if (firstUnanswered !== -1) {
                        this.currentQuestionIndex = firstUnanswered;
                    } else if (this.questions.length > 0 && this.questions.every(q => q.is_answered)) {
                        // All answered, maybe redirect to result?
                        window.location.href = `/solo/result/${this.sessionId}`;
                    }
                },

                startTimer() {
                    this.timerInterval = setInterval(() => {
                        this.updateTimer();
                    }, 1000);
                },

                updateTimer() {
                    const now = new Date().getTime();
                    const diff = this.deadline - now;
                    this.timeRemaining = Math.max(0, Math.floor(diff / 1000));

                    if (this.timeRemaining === 30 && !this.warningShown) {
                        this.warningShown = true;
                    }
                    
                    if (this.timeRemaining <= 0) {
                        this.autoFinish();
                    }
                },

                formatTimer(seconds) {
                    if (seconds < 0) return "0:00";
                    const mins = Math.floor(seconds / 60);
                    const secs = seconds % 60;
                    return `${mins}:${secs.toString().padStart(2, '0')}`;
                },

                timerWarning() {
                    return this.timeRemaining < 30 ? 'text-red-500 animate-pulse' : 'text-primary';
                },

                hpBarColor() {
                    const percentage = (this.bossHpCurrent / this.bossHpMax) * 100;
                    if (percentage > 50) return 'bg-green-500';
                    if (percentage > 20) return 'bg-yellow-500';
                    return 'bg-red-500';
                },

                submitAnswer() {
                    if (!this.selectedAnswer || this.isSubmitting) return;

                    this.isSubmitting = true;
                    const timeSpent = Math.floor((Date.now() - this.startTime) / 1000);

                    fetch(`/solo/${this.raidId}/battle/action`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            session_id: this.sessionId,
                            question_id: this.currentQuestion.id,
                            jawaban_user: this.selectedAnswer,
                            waktu_jawab_detik: timeSpent,
                            urutan_soal: this.currentQuestion.urutan
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.isSubmitting = false;
                        this.showFeedback = true;
                        this.feedbackCorrect = data.is_correct;
                        this.feedbackMessage = data.feedback_message;
                        
                        // Update Boss HP
                        this.bossHpCurrent = data.boss_hp_current;

                        // Mark as answered locally
                        this.questions[this.currentQuestionIndex].is_answered = true;

                        setTimeout(() => {
                            this.showFeedback = false;
                            this.nextQuestion();
                        }, 2000);
                    })
                    .catch(err => {
                        console.error(err);
                        this.isSubmitting = false;
                        alert('Error submitting answer. Please try again.');
                    });
                },

                nextQuestion() {
                    if (this.currentQuestionIndex < this.questions.length - 1) {
                        this.currentQuestionIndex++;
                        this.selectedAnswer = '';
                        this.startTime = Date.now();
                    } else {
                        this.finishQuiz();
                    }
                },

                finishQuiz() {
                    clearInterval(this.timerInterval);
                    fetch(`/solo/${this.raidId}/battle/finish/${this.sessionId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        window.location.href = `/solo/result/${this.sessionId}`;
                    });
                },

                autoFinish() {
                    this.finishQuiz();
                }
            }
        }
    </script>
</x-app-layout>
