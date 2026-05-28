<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pre-test Penempatan</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        @keyframes pop { 0% { transform: scale(0.9); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
        @keyframes bounce-short { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }
        .animate-pop { animation: pop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
        .animate-bounce-short { animation: bounce-short 0.5s ease-in-out 1; }
        .animate-shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
        
        body {
            background-color: #1e1e1e; 
            background-image: linear-gradient(#1e293b 1px, transparent 1px), linear-gradient(90deg, #1e293b 1px, transparent 1px);
            background-size: 50px 50px;
        }
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #1e1e1e; }
        ::-webkit-scrollbar-thumb { background: #424242; border-radius: 0px; }
        ::-webkit-scrollbar-thumb:hover { background: #4f4f4f; }
        .prose pre { 
            background-color: #1e1e1e !important; 
            border: 1px solid #333; 
            border-radius: 0.125rem; 
            padding: 0.75rem;
            overflow-x: auto;
        }
        .prose code { 
            color: #ce9178; 
            background-color: #2d2d2d;
            padding: 0.125rem 0.375rem;
            border-radius: 0.125rem;
            font-family: 'Fira Code', 'Consolas', monospace;
            font-size: 0.9em;
        }
        .prose pre code {
            background-color: transparent;
            padding: 0;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 lg:p-8 text-vscode-text font-sans"
      x-data="pretestQuiz()" 
      x-init="init()">

    <!-- MAIN CONTAINER -->
    <div class="w-full max-w-4xl bg-vscode-card rounded-xl shadow-2xl border border-vscode-border overflow-hidden relative animate-pop flex flex-col h-auto lg:h-[85vh]">
        
        <!-- STATUS BAR -->
        <div class="absolute top-0 left-0 w-full h-1 bg-purple-500 z-50"></div>

        <!-- HEADER -->
        <div class="bg-vscode-card border-b border-vscode-border">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="bg-purple-500/20 text-purple-400 text-xs font-mono font-bold px-3 py-1 rounded-sm uppercase tracking-wider border border-purple-500/30">
                        PRE-TEST
                    </div>
                    <span class="text-vscode-muted text-sm font-mono">Penempatan Section</span>
                </div>
                <div class="flex items-center gap-2 bg-[#1e1e1e] px-3 py-1.5 rounded-sm border border-vscode-border">
                    <i class="fa-solid fa-clock text-purple-400" :class="timeRemaining < 60 ? 'animate-pulse text-red-400' : ''"></i>
                    <span class="font-bold text-vscode-text font-mono text-lg" x-text="formatTimer(timeRemaining)"></span>
                </div>
            </div>

            <!-- Progress Bar (Segmented) -->
            <div class="px-6 pb-3">
                <div class="flex items-center justify-between mb-1 font-mono text-xs">
                    <span class="text-vscode-muted">PROGRESS</span>
                    <span class="text-purple-400" x-text="`${currentQuestionIndex + 1} / ${questions.length}`"></span>
                </div>
                <div class="w-full flex gap-[2px] h-2 rounded-sm overflow-hidden">
                    <template x-for="(q, idx) in questions" :key="idx">
                        <div class="flex-1 rounded-[1px] transition-all duration-300"
                             :style="idx < currentQuestionIndex || (answerResults[idx] !== null) ? 'background-color: #3b82f6; box-shadow: 0 0 4px #3b82f6;' : (idx === currentQuestionIndex && answerResults[idx] === null ? 'background-color: #60a5fa; box-shadow: 0 0 6px #60a5fa;' : 'background-color: #333;')"></div>
                    </template>
                </div>
            </div>
        </div>

        <!-- SCROLLABLE CONTENT -->
        <div class="flex-1 overflow-y-auto px-8 py-6 flex flex-col">
            
            <!-- Level Badge -->
            <div class="mb-4">
                <span class="text-xs font-mono px-2 py-1 rounded-sm border"
                      :class="{
                          'bg-green-500/10 text-green-400 border-green-500/30': currentQuestion.level === 'Easy',
                          'bg-yellow-500/10 text-yellow-400 border-yellow-500/30': currentQuestion.level === 'Medium',
                          'bg-red-500/10 text-red-400 border-red-500/30': currentQuestion.level === 'Hard'
                      }"
                      x-text="currentQuestion.level"></span>
            </div>

            <!-- Question -->
            <div class="mb-6">
                <div class="font-mono text-vscode-muted mb-2 text-sm">// Pertanyaan:</div>
                <div class="font-medium text-lg lg:text-xl leading-relaxed text-[#d4d4d4] prose prose-invert max-w-none"
                     x-html="currentQuestion.soal_text"></div>
            </div>

            <!-- Answer Grid -->
            <div class="mt-auto pb-4">
                <!-- Multiple Choice -->
                <template x-if="currentQuestion.tipe === 'multiple_choice'">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <template x-for="option in ['A','B','C','D']">
                            <button @click="submitAnswer(option)"
                                    :disabled="isSubmitting"
                                    class="group relative bg-vscode-card border border-vscode-border hover:border-purple-400 p-4 rounded-sm transition-all text-left flex items-center min-h-[5rem] h-auto active:bg-[#333]"
                                    :class="{
                                        'border-green-500 bg-[#1e1e1e]': feedbackCorrect && selectedAnswer === option,
                                        'border-red-500 bg-[#1e1e1e] animate-shake': !feedbackCorrect && selectedAnswer === option && showFeedback,
                                        'opacity-50 cursor-not-allowed': isSubmitting && selectedAnswer !== option
                                    }">
                                <div class="text-vscode-muted font-mono text-sm w-8 mr-3 group-hover:text-purple-400 transition-colors shrink-0"
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
                                  class="w-full h-32 bg-[#1e1e1e] border border-vscode-border text-vscode-text font-mono p-4 focus:border-purple-400 focus:outline-none rounded-sm resize-none"></textarea>
                        <button @click="submitAnswer(shortAnswerInput)"
                                :disabled="!shortAnswerInput || isSubmitting"
                                class="bg-purple-500 hover:bg-purple-600 text-white font-mono font-bold py-2 px-6 rounded-sm transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-play"></i> Submit
                        </button>
                    </div>
                </template>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="p-2 px-4 bg-purple-600 flex justify-between items-center text-[10px] lg:text-xs text-white font-mono">
            <div class="flex gap-4">
                <span><i class="fa-solid fa-code-branch"></i> pretest</span>
            </div>
            <div>
                <span>Pre-test Penempatan • 30 Soal</span>
            </div>
        </div>
    </div>

    <!-- Notification Modal -->
    <div x-show="showModal" 
         style="display: none;"
         class="fixed top-10 left-1/2 transform -translate-x-1/2 z-50 transition-all duration-300 w-full max-w-md px-4">
        <div class="bg-[#252526] text-vscode-text p-4 rounded shadow-2xl border border-[#454545] flex items-start gap-4 animate-bounce-short">
            <div class="text-2xl pt-1">
                <i x-show="feedbackCorrect" class="fa-solid fa-circle-check text-green-400"></i>
                <i x-show="!feedbackCorrect" class="fa-solid fa-circle-xmark text-red-400"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-base mb-1" x-text="feedbackCorrect ? 'Benar!' : 'Salah!'"></h3>
                <p class="text-sm text-vscode-muted" x-text="feedbackMessage"></p>
            </div>
        </div>
    </div>

    <script>
        function pretestQuiz() {
            return {
                questions: @json($questions),
                sessionId: {{ $session->id }},
                deadline: new Date('{{ $deadline->toIso8601String() }}').getTime(),
                timeRemaining: 0,
                
                currentQuestionIndex: 0,
                answerResults: [],
                selectedAnswer: '',
                shortAnswerInput: '',
                isSubmitting: false,
                
                showModal: false,
                feedbackCorrect: false,
                feedbackMessage: '',
                showFeedback: false,
                
                timerInterval: null,
                startTime: null,

                get currentQuestion() {
                    const question = this.questions[this.currentQuestionIndex];
                    // Process question text to wrap code snippets
                    if (question && question.soal_text) {
                        question.soal_text = this.formatCodeInText(question.soal_text);
                    }
                    return question;
                },

                formatCodeInText(text) {
                    // Skip if already has HTML tags
                    if (/<[^>]+>/.test(text)) {
                        return text;
                    }
                    
                    // Wrap code-like patterns in <code> tags
                    // Pattern 1: PHP variables ($variable)
                    text = text.replace(/(\$[a-zA-Z_][a-zA-Z0-9_]*(?:\[[^\]]+\])*)/g, '<code>$1</code>');
                    
                    // Pattern 2: Function calls (function_name())
                    text = text.replace(/\b([a-zA-Z_][a-zA-Z0-9_]*\(\))/g, '<code>$1</code>');
                    
                    // Pattern 3: Operators and symbols (==, ===, !=, &&, ||, etc.)
                    text = text.replace(/\b(===|!==|==|!=|&&|\|\||<=|>=|<>|\+=|-=|\*=|\/=|%=|\+\+|--|\*\*)\b/g, '<code>$1</code>');
                    
                    // Pattern 4: Numbers in quotes or standalone
                    text = text.replace(/"([^"]+)"/g, '<code>"$1"</code>');
                    text = text.replace(/'([^']+)'/g, "<code>'$1'</code>");
                    
                    // Pattern 5: PHP tags
                    text = text.replace(/(&lt;\?php|<\?php|\?&gt;|\?>)/g, '<code>$1</code>');
                    
                    // Pattern 6: Common keywords
                    const keywords = ['true', 'false', 'null', 'return', 'if', 'else', 'elseif', 'switch', 'case', 'break', 'continue', 'for', 'while', 'foreach', 'do', 'function', 'class', 'new', 'extends', 'public', 'private', 'protected', 'static', 'const'];
                    keywords.forEach(keyword => {
                        const regex = new RegExp(`\\b(${keyword})\\b(?![^<]*>)`, 'gi');
                        text = text.replace(regex, '<code>$1</code>');
                    });
                    
                    return text;
                },

                init() {
                    // Initialize answer results array (null = unanswered)
                    this.answerResults = this.questions.map(q => q.is_answered ? 'answered' : null);
                    
                    this.updateTimer();
                    this.startTimer();
                    this.startTime = Date.now();
                    
                    const firstUnanswered = this.questions.findIndex(q => !q.is_answered);
                    if (firstUnanswered !== -1) {
                        this.currentQuestionIndex = firstUnanswered;
                    } else if (this.questions.length > 0 && this.questions.every(q => q.is_answered)) {
                        this.finishQuiz();
                    }
                },

                startTimer() {
                    this.timerInterval = setInterval(() => this.updateTimer(), 1000);
                },

                updateTimer() {
                    const diff = this.deadline - new Date().getTime();
                    this.timeRemaining = Math.max(0, Math.floor(diff / 1000));
                    if (this.timeRemaining <= 0) this.finishQuiz();
                },

                formatTimer(seconds) {
                    if (seconds < 0) return "0:00";
                    const mins = Math.floor(seconds / 60);
                    const secs = seconds % 60;
                    return `${mins}:${secs.toString().padStart(2, '0')}`;
                },

                submitAnswer(answer) {
                    if (this.isSubmitting) return;
                    
                    this.selectedAnswer = answer;
                    this.isSubmitting = true;
                    this.showFeedback = true;
                    
                    const timeSpent = Math.floor((Date.now() - this.startTime) / 1000);

                    fetch('/pretest/action', {
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
                        if (data.error) {
                            if (data.error === 'Time expired') {
                                this.finishQuiz();
                                return;
                            }
                            alert(data.error);
                            this.isSubmitting = false;
                            return;
                        }

                        this.feedbackCorrect = data.is_correct;
                        this.feedbackMessage = data.is_correct ? 'Jawaban benar!' : 'Jawaban salah.';
                        this.answerResults[this.currentQuestionIndex] = data.is_correct ? 'correct' : 'incorrect';

                        this.showModal = true;
                        setTimeout(() => this.closeModal(), 600);

                        this.questions[this.currentQuestionIndex].is_answered = true;
                    })
                    .catch(err => {
                        console.error(err);
                        this.isSubmitting = false;
                        alert('Connection error. Please try again.');
                    });
                },

                closeModal() {
                    this.showModal = false;
                    this.isSubmitting = false;
                    this.showFeedback = false;
                    this.selectedAnswer = '';
                    this.shortAnswerInput = '';
                    this.nextQuestion();
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
                    fetch(`/pretest/finish/${this.sessionId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        window.location.href = `/pretest/result/${this.sessionId}`;
                    });
                }
            }
        }
    </script>
</body>
</html>
