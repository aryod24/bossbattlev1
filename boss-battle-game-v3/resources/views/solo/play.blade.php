<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Solo Boss Battle - {{ $bossName }}</title>
    
    <!-- Tailwind CSS -->
    <!-- Vite Assets (Optimized) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Custom Animations Config (Moved from inline JS) */
        @keyframes bounce-short {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }
        @keyframes pop {
            0% { transform: scale(0.9); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .animate-bounce-short { animation: bounce-short 0.5s ease-in-out 1; }
        .animate-shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
        .animate-pop { animation: pop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
    </style>
    <style>
        body {
            background-color: #1e1e1e; 
            background-image: linear-gradient(#1e293b 1px, transparent 1px), linear-gradient(90deg, #1e293b 1px, transparent 1px);
            background-size: 50px 50px;
            background-position: center center;
        }
        .boss-hit {
            filter: brightness(1.5) sepia(1) hue-rotate(-50deg) saturate(5);
            transform: scale(0.95);
        }
        /* Scrollbar ala VS Code */
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #1e1e1e; }
        ::-webkit-scrollbar-thumb { background: #424242; border-radius: 0px; }
        ::-webkit-scrollbar-thumb:hover { background: #4f4f4f; }
        
        .code-keyword { color: #569cd6; } /* Blue */
        .code-string { color: #ce9178; }  /* Orange */
        .code-tag { color: #569cd6; }     /* Blue */
        .code-attr { color: #9cdcfe; }    /* Light Blue */
        
        /* Custom styles for rendered HTML content */
        .prose pre {
            background-color: #1e1e1e !important;
            border: 1px solid #333;
            border-radius: 0.125rem;
        }
        .prose code {
            color: #ce9178;
            font-family: 'Fira Code', monospace;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 lg:p-8 text-vscode-text font-sans"
      x-data="bossQuiz()" 
      x-init="init()">

    <!-- MAIN DASHBOARD CONTAINER -->
    <div class="w-full max-w-6xl bg-vscode-card rounded-xl shadow-2xl border border-vscode-border overflow-hidden relative animate-pop flex flex-col lg:flex-row h-auto lg:h-[85vh]">
        
        <!-- STATUS BAR (Top border decoration) -->
        <div class="absolute top-0 left-0 w-full h-1 bg-vscode-primary z-50"></div>

        <!-- LEFT PANEL: BATTLE ARENA (40%) -->
        <div class="lg:w-2/5 w-full bg-[#1e1e1e] relative flex flex-col border-b lg:border-b-0 lg:border-r border-vscode-border">
            
            <!-- Header Info -->
            <div class="absolute top-0 left-0 w-full p-6 flex justify-between items-start z-10">
                <div class="flex flex-col gap-2">
                    <span class="bg-vscode-primary/20 text-vscode-primary border border-vscode-primary/30 text-xs font-mono font-bold px-3 py-1 rounded-sm uppercase tracking-wider">
                        Level: {{ $session->level }}
                    </span>
                    <!-- Attempt count could be dynamic if passed, for now static or removed -->
                    <!-- <span class="text-vscode-muted text-xs font-mono">Attempt #1</span> -->
                </div>
                
                <!-- Timer Badge -->
                <div class="flex items-center gap-2 bg-vscode-card px-3 py-1.5 rounded-sm border border-vscode-border">
                    <i class="fa-solid fa-clock text-vscode-primary" :class="timeRemaining < 30 ? 'animate-pulse text-boss-red' : ''"></i>
                    <span class="font-bold text-vscode-text font-mono text-lg" x-text="formatTimer(timeRemaining)"></span>
                </div>
            </div>

            <!-- Main Boss Stage -->
            <div class="flex-1 flex flex-col items-center justify-center p-8 relative mt-12 lg:mt-0">
                
                <!-- Floating Damage Text -->
                <div x-show="showDamage" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-0 rotate(12deg) scale(1)"
                     x-transition:enter-end="opacity-100 transform -translate-y-10 rotate(12deg) scale(1.2)"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform -translate-y-10 rotate(12deg) scale(1.2)"
                     x-transition:leave-end="opacity-0 transform -translate-y-0 rotate(12deg) scale(1)"
                     class="absolute top-24 right-10 lg:right-20 bg-boss-red text-white font-black text-2xl px-4 py-2 rounded shadow-xl z-30 font-mono border border-red-400"
                     style="display: none;">
                    -1 HP!
                </div>

                <!-- Boss Avatar -->
                <div class="relative group cursor-pointer" 
                     :class="isHit ? 'animate-shake' : 'animate-float'"
                     @click="shakeBoss()">
                    <!-- Aura Effect -->
                    <div class="absolute inset-0 bg-vscode-primary rounded-full blur-3xl opacity-20 group-hover:opacity-40 transition-opacity duration-500"></div>
                    
                    <!-- Avatar Image -->
                    <div class="w-48 h-48 lg:w-64 lg:h-64 bg-[#2d2d2d] rounded-full border-4 border-vscode-border shadow-2xl flex items-center justify-center overflow-hidden relative z-20 transition-all duration-200"
                         :class="isHit ? 'boss-hit' : ''">
                        <img src="https://api.dicebear.com/9.x/bottts-neutral/svg?seed={{ $bossName }}&backgroundColor=1e1e1e" alt="Boss" class="w-full h-full object-cover opacity-90">
                    </div>

                    <!-- Boss Badge -->
                    <div class="absolute bottom-4 right-4 bg-vscode-card text-vscode-text text-xs font-mono px-3 py-1 rounded border border-vscode-border z-30 shadow-lg">
                        <span class="text-vscode-primary">const</span> boss = <span class="text-[#ce9178]">'{{ $bossName }}'</span>;
                    </div>
                </div>

                <div class="text-center mt-8 z-20">
                    <h2 class="text-2xl font-bold text-vscode-text tracking-tight mb-2 font-mono">{{ $bossName }}</h2>
                    
                    <!-- HP Bar Container -->
                    <div class="w-64 lg:w-72 mx-auto">
                        <div class="flex justify-between text-xs font-bold text-vscode-muted mb-1 uppercase tracking-wider font-mono">
                            <span>HP Status</span>
                            <span x-text="`${bossHpCurrent}/${bossHpMax}`"></span>
                        </div>
                        <div class="w-full bg-[#333333] h-4 rounded-sm border border-[#444] p-[1px] relative overflow-hidden">
                            <!-- HP Fill -->
                            <div class="h-full rounded-sm transition-all duration-500 ease-out w-full opacity-90"
                                 :style="`width: ${(bossHpCurrent/bossHpMax)*100}%`"
                                 :class="hpBarColor()"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Decoration Lines -->
            <div class="absolute bottom-4 left-4 text-vscode-border text-[10px] font-mono opacity-50">
                Ln <span x-text="currentQuestionIndex + 1"></span>, Col 12 &nbsp;&nbsp; UTF-8 &nbsp;&nbsp; JavaScript
            </div>
        </div>

        <!-- RIGHT PANEL: CONSOLE (60%) -->
        <div class="lg:w-3/5 w-full bg-vscode-bg flex flex-col relative z-0">
            
            <!-- Tabs / Breadcrumbs -->
            <div class="flex items-center bg-vscode-card border-b border-vscode-border">
                <div class="px-4 py-3 bg-[#1e1e1e] border-t-2 border-t-vscode-primary text-sm font-mono text-vscode-text flex items-center gap-2">
                    <i class="fa-brands fa-js text-yellow-500"></i>
                    soal_<span x-text="(currentQuestionIndex + 1).toString().padStart(2, '0')"></span>
                    <i class="fa-solid fa-xmark ml-2 text-vscode-muted hover:text-white cursor-pointer"></i>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="px-8 pt-6 pb-2">
                <div class="flex items-center justify-between mb-2 font-mono text-xs">
                    <span class="text-vscode-muted">PROGRESS_SESI</span>
                    <span class="text-vscode-primary" x-text="`${currentQuestionIndex + 1} / ${questions.length} Completed`"></span>
                </div>
                <div class="w-full bg-[#333] h-1 rounded-full overflow-hidden">
                    <div class="bg-vscode-primary h-full shadow-[0_0_10px_#007acc] transition-all duration-300"
                         :style="`width: ${((currentQuestionIndex + 1) / questions.length) * 100}%`"></div>
                </div>
            </div>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto px-8 py-4 custom-scrollbar flex flex-col">
                
                <!-- Question Block -->
                <div class="mb-6">
                    <div class="font-mono text-vscode-muted mb-2 text-sm">// Pertanyaan:</div>
                    <div class="font-medium text-lg lg:text-xl leading-relaxed text-[#d4d4d4] prose prose-invert max-w-none"
                         x-html="currentQuestion.soal_text">
                    </div>
                    
                    <!-- Debug / Cheat Sheet -->
                    <div class="mt-2 p-2 bg-[#2d2d2d] border border-dashed border-[#444] rounded text-xs text-[#858585] font-mono inline-block">
                        <i class="fa-solid fa-bug text-yellow-500 mr-1"></i> 
                        Using Cheat: Answer is <span x-text="currentQuestion.jawaban_benar" class="font-bold text-[#ce9178]"></span>
                    </div>
                </div>

                <!-- Answer Grid -->
                <div class="mt-auto pb-8">
                    <!-- Multiple Choice -->
                    <template x-if="currentQuestion.tipe === 'multiple_choice'">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <template x-for="option in ['A','B','C','D']">
                                <button @click="submitAnswer(option)"
                                        :disabled="isSubmitting"
                                        class="group relative bg-vscode-card border border-vscode-border hover:border-vscode-primary p-4 rounded-sm transition-all text-left flex items-center min-h-[5rem] h-auto active:bg-[#333]"
                                        :class="{
                                            'border-success-green bg-[#1e1e1e]': feedbackCorrect && selectedAnswer === option,
                                            'border-boss-red bg-[#1e1e1e] animate-shake': !feedbackCorrect && selectedAnswer === option && showFeedback,
                                            'opacity-50 cursor-not-allowed': isSubmitting && selectedAnswer !== option
                                        }">
                                    <div class="text-vscode-muted font-mono text-sm w-8 mr-3 group-hover:text-vscode-primary transition-colors shrink-0"
                                         x-text="option + '.'"></div>
                                    <span class="font-mono text-vscode-text group-hover:text-white text-sm break-words w-full leading-relaxed"
                                          x-text="currentQuestion['pilihan_'+option.toLowerCase()]"></span>
                                </button>
                            </template>
                        </div>
                    </template>

                    <!-- Short Answer -->
                    <template x-if="currentQuestion.tipe === 'short_answer'">
                        <div class="space-y-4">
                            <textarea x-model="shortAnswerInput"
                                      placeholder="// Tulis jawaban Anda di sini..."
                                      class="w-full h-32 bg-[#1e1e1e] border border-vscode-border text-vscode-text font-mono p-4 focus:border-vscode-primary focus:outline-none rounded-sm resize-none"></textarea>
                            <button @click="submitAnswer(shortAnswerInput)"
                                    :disabled="!shortAnswerInput || isSubmitting"
                                    class="bg-vscode-primary hover:bg-vscode-primary-dark text-white font-mono font-bold py-2 px-6 rounded-sm transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-play"></i> Submit
                            </button>
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="p-2 px-4 bg-vscode-primary flex justify-between items-center text-[10px] lg:text-xs text-white font-mono">
                <div class="flex gap-4">
                    <span><i class="fa-solid fa-code-branch"></i> main*</span>
                </div>
                <div>
                    <span>Solo Boss Battle</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div x-show="showErrorModal" 
         style="display: none;"
         class="fixed inset-0 z-[60] flex items-center justify-center bg-black/70 backdrop-blur-sm p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-vscode-card border border-bash-red rounded-lg shadow-2xl w-full max-w-sm overflow-hidden transform transition-all border-l-4 border-l-boss-red"
             @click.away="showErrorModal = false">
            <div class="p-4 flex items-start gap-4">
                <div class="text-2xl text-boss-red mt-1">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-white text-base mb-1">Runtime Error</h3>
                    <p class="text-sm text-vscode-muted mb-4" x-text="errorMessage"></p>
                    <button @click="showErrorModal = false" class="bg-[#3c3c3c] hover:bg-[#4c4c4c] text-white text-xs px-3 py-2 rounded-sm border border-vscode-border transition-colors">
                        Dismiss
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Modal (VS Code Style) -->
    <div x-show="showModal" 
         style="display: none;"
         class="absolute top-10 left-1/2 transform -translate-x-1/2 z-50 transition-all duration-300 w-full max-w-md px-4">
        <div class="bg-[#252526] text-vscode-text p-4 rounded shadow-2xl border border-[#454545] flex items-start gap-4 animate-bounce-short hover:bg-[#2a2a2a] transition-colors">
            <div class="text-2xl pt-1">
                <i x-show="feedbackCorrect" class="fa-solid fa-circle-check text-success-green"></i>
                <i x-show="!feedbackCorrect" class="fa-solid fa-circle-xmark text-boss-red"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-base mb-1" x-text="feedbackCorrect ? 'Compilation Success!' : 'Syntax Error!'"></h3>
                <p class="text-sm text-vscode-muted mb-1" x-text="feedbackMessage"></p>
            </div>
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
                deadline: new Date('{{ $deadline->toIso8601String() }}').getTime(),
                timeRemaining: 0,
                
                currentQuestionIndex: 0,
                selectedAnswer: '',
                shortAnswerInput: '',
                isSubmitting: false,
                
                // Visual States
                showModal: false,
                feedbackCorrect: false,
                feedbackMessage: '',
                showFeedback: false, // Used for shake effect trigger
                isHit: false,
                showDamage: false,
                showErrorModal: false,
                errorMessage: '',
                
                timerInterval: null,
                startTime: null,

                get currentQuestion() {
                    return this.questions[this.currentQuestionIndex];
                },

                init() {
                    this.updateTimer();
                    this.startTimer();
                    this.startTime = Date.now();
                    
                    // Find first unanswered
                    const firstUnanswered = this.questions.findIndex(q => !q.is_answered);
                    if (firstUnanswered !== -1) {
                        this.currentQuestionIndex = firstUnanswered;
                    } else if (this.questions.length > 0 && this.questions.every(q => q.is_answered)) {
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
                    
                    if (this.timeRemaining <= 0) {
                        this.finishQuiz();
                    }
                },

                formatTimer(seconds) {
                    if (seconds < 0) return "0:00";
                    const mins = Math.floor(seconds / 60);
                    const secs = seconds % 60;
                    return `${mins}:${secs.toString().padStart(2, '0')}`;
                },

                hpBarColor() {
                    const percentage = (this.bossHpCurrent / this.bossHpMax) * 100;
                    if (percentage > 50) return 'bg-boss-red'; // Keep red as per design, or change to green if desired
                    return 'bg-boss-red'; // Design uses red for boss HP always
                },

                shakeBoss() {
                    this.isHit = true;
                    setTimeout(() => this.isHit = false, 500);
                },

                submitAnswer(answer) {
                    if (this.isSubmitting) return;
                    
                    this.selectedAnswer = answer;
                    this.isSubmitting = true;
                    this.showFeedback = true; // Triggers shake on wrong answer
                    
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
                            jawaban_user: answer,
                            waktu_jawab_detik: timeSpent,
                            urutan_soal: this.currentQuestion.urutan
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        // Handle Errors (e.g. Time Expired)
                        if (data.error) {
                            if (data.error === 'Time expired') {
                                this.finishQuiz();
                                return;
                            }
                            this.errorMessage = data.error;
                            this.showErrorModal = true;
                            this.isSubmitting = false;
                            return;
                        }

                        this.feedbackCorrect = data.is_correct;
                        this.feedbackMessage = data.feedback_message;
                        
                        if (data.is_correct) {
                            // Hit Effect
                            this.bossHpCurrent = data.boss_hp_current;
                            this.isHit = true;
                            this.showDamage = true;
                            setTimeout(() => {
                                this.isHit = false;
                                this.showDamage = false;
                            }, 800);
                        }

                        // Show Modal & Auto Advance
                        setTimeout(() => {
                            this.showModal = true;
                            
                            // Auto close after 1.5 seconds
                            setTimeout(() => {
                                this.closeModal();
                            }, 1500);
                        }, 800);

                        // Mark answered
                        this.questions[this.currentQuestionIndex].is_answered = true;
                    })
                    .catch(err => {
                        console.error(err);
                        this.isSubmitting = false;
                        this.errorMessage = 'Connection Error: Failed to submit answer.';
                        this.showErrorModal = true;
                    });
                },

                closeModal() {
                    this.showModal = false;
                    this.isSubmitting = false;
                    this.showFeedback = false;
                    this.selectedAnswer = '';
                    this.shortAnswerInput = '';
                    
                    if (this.bossHpCurrent <= 0) {
                        // Boss Defeated
                        this.finishQuiz();
                    } else {
                        this.nextQuestion();
                    }
                },

                nextQuestion() {
                    if (this.currentQuestionIndex < this.questions.length - 1) {
                        this.currentQuestionIndex++;
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
                        const params = new URLSearchParams();
                        if (data.level_up && data.level_up.leveled_up) {
                            params.append('level_up', '1');
                            params.append('new_level', data.level_up.new_level);
                        }
                        if (data.new_badges && data.new_badges.length > 0) {
                            params.append('badges', JSON.stringify(data.new_badges));
                        }
                        window.location.href = `/solo/result/${this.sessionId}?${params.toString()}`;
                    });
                }
            }
        }
    </script>
</body>
</html>
