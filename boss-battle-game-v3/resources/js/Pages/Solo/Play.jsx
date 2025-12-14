import { useState, useEffect, useMemo, useRef } from 'react';
import { Head, router } from '@inertiajs/react';
import '../../css/battle.css';

export default function SoloPlay({ soloRaid, session, questions, timeRemaining: initialTimeRemaining, deadline, bossName }) {
    // Game State
    const [currentTimeRemaining, setCurrentTimeRemaining] = useState(initialTimeRemaining);
    const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
    const [bossHpCurrent, setBossHpCurrent] = useState(session.boss_hp_akhir);
    const bossHpMax = session.boss_hp_awal;

    // UI State
    const [selectedAnswer, setSelectedAnswer] = useState('');
    const [shortAnswerInput, setShortAnswerInput] = useState('');
    const [isSubmitting, setIsSubmitting] = useState(false);

    // Visual Effects State
    const [isHit, setIsHit] = useState(false);
    const [showDamage, setShowDamage] = useState(false);
    const [showFeedbackModal, setShowFeedbackModal] = useState(false);
    const [feedbackCorrect, setFeedbackCorrect] = useState(false);
    const [feedbackMessage, setFeedbackMessage] = useState('');
    const [showErrorModal, setShowErrorModal] = useState(false);
    const [errorMessage, setErrorMessage] = useState('');
    const [showFeedbackShake, setShowFeedbackShake] = useState(false);

    // Refs
    // UseRef to track updated state in intervals if needed, though dependency array usually suffices
    const startTimeRef = useRef(Date.now());
    const deadlineMs = useMemo(() => new Date(deadline).getTime(), [deadline]);

    // Memoized Current Question
    const currentQuestion = useMemo(() => questions[currentQuestionIndex] || {}, [questions, currentQuestionIndex]);

    // Initial Setup to find first unanswered question
    useEffect(() => {
        const firstUnanswered = questions.findIndex(q => !q.is_answered);
        if (firstUnanswered !== -1) {
            setCurrentQuestionIndex(firstUnanswered);
        } else if (questions.length > 0 && questions.every(q => q.is_answered)) {
            // All answered, redirect to result
            router.visit(`/solo/result/${session.id}`);
        }
    }, []); // Run once on mount

    // Timer Logic
    useEffect(() => {
        const interval = setInterval(() => {
            const now = Date.now();
            const diff = deadlineMs - now;
            const remaining = Math.max(0, Math.floor(diff / 1000));

            setCurrentTimeRemaining(remaining);

            if (remaining <= 0) {
                clearInterval(interval);
                finishQuiz();
            }
        }, 1000);

        return () => clearInterval(interval);
    }, [deadlineMs]);

    // Format Timer
    const formatTimer = (seconds) => {
        if (seconds < 0) return "0:00";
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    };

    // HP Bar Color
    const getHpBarColor = () => 'bg-red-600'; // Boss Red

    // Actions
    const shakeBoss = () => {
        setIsHit(true);
        setTimeout(() => setIsHit(false), 500);
    };

    const submitAnswer = async (answer) => {
        if (isSubmitting) return;

        setSelectedAnswer(answer);
        setIsSubmitting(true);
        setShowFeedbackShake(true);

        const timeSpent = Math.floor((Date.now() - startTimeRef.current) / 1000);

        try {
            const response = await window.axios.post(`/solo/${soloRaid.id}/battle/action`, {
                session_id: session.id,
                question_id: currentQuestion.id,
                jawaban_user: answer,
                waktu_jawab_detik: timeSpent,
                urutan_soal: currentQuestion.urutan
            });

            const data = response.data;

            if (data.error) {
                if (data.error === 'Time expired') {
                    finishQuiz();
                    return;
                }
                throw new Error(data.error);
            }

            setFeedbackCorrect(data.is_correct);
            setFeedbackMessage(data.feedback_message);

            if (data.is_correct) {
                setBossHpCurrent(data.boss_hp_current);
                setIsHit(true);
                setShowDamage(true);
                setTimeout(() => {
                    setIsHit(false);
                    setShowDamage(false);
                }, 800);
            }

            // Show Feedback Modal
            setTimeout(() => {
                setShowFeedbackModal(true);
                // Auto Close
                setTimeout(() => {
                    closeModal(data.boss_hp_current);
                }, 1500);
            }, 800);

            // Mark locally as answered
            questions[currentQuestionIndex].is_answered = true;

        } catch (error) {
            console.error(error);
            setIsSubmitting(false);
            setErrorMessage(error.response?.data?.message || error.message || 'Failed to submit answer');
            setShowErrorModal(true);
        }
    };

    const closeModal = (currentHp) => {
        setShowFeedbackModal(false);
        setIsSubmitting(false);
        setShowFeedbackShake(false);
        setSelectedAnswer('');
        setShortAnswerInput('');

        if (currentHp <= 0) {
            finishQuiz();
        } else {
            nextQuestion();
        }
    };

    const nextQuestion = () => {
        if (currentQuestionIndex < questions.length - 1) {
            setCurrentQuestionIndex(prev => prev + 1);
            startTimeRef.current = Date.now();
        } else {
            finishQuiz();
        }
    };

    const finishQuiz = async () => {
        try {
            const response = await window.axios.post(`/solo/${soloRaid.id}/battle/finish/${session.id}`);
            const data = response.data;

            const params = new URLSearchParams();
            if (data.level_up && data.level_up.leveled_up) {
                params.append('level_up', '1');
                params.append('new_level', data.level_up.new_level);
            }
            if (data.new_badges && data.new_badges.length > 0) {
                params.append('badges', JSON.stringify(data.new_badges));
            }

            router.visit(`/solo/result/${session.id}?${params.toString()}`);
        } catch (error) {
            console.error('Finish quiz error:', error);
            // Force redirect anyway
            router.visit(`/solo/result/${session.id}`);
        }
    };

    return (
        <div className="min-h-screen flex items-center justify-center p-4 lg:p-8 text-vscode-text font-sans bg-[#1e1e1e] bg-[linear-gradient(#1e293b_1px,transparent_1px),linear-gradient(90deg,#1e293b_1px,transparent_1px)] bg-[size:50px_50px]">
            <Head title={`Battle - ${bossName}`} />

            {/* MAIN DASHBOARD CONTAINER */}
            <div className="w-full max-w-6xl bg-vscode-card rounded-xl shadow-2xl border border-vscode-border overflow-hidden relative animate-pop flex flex-col lg:flex-row h-auto lg:h-[85vh]">

                {/* STATUS BAR */}
                <div className="absolute top-0 left-0 w-full h-1 bg-vscode-primary z-50"></div>

                {/* LEFT PANEL: BATTLE ARENA */}
                <div className="lg:w-2/5 w-full bg-[#1e1e1e] relative flex flex-col border-b lg:border-b-0 lg:border-r border-vscode-border">

                    {/* Header Info */}
                    <div className="absolute top-0 left-0 w-full p-6 flex justify-between items-start z-10">
                        <div className="flex flex-col gap-2">
                            <span className="bg-vscode-primary/20 text-vscode-primary border border-vscode-primary/30 text-xs font-mono font-bold px-3 py-1 rounded-sm uppercase tracking-wider">
                                Level: {session.level}
                            </span>
                        </div>

                        {/* Timer Badge */}
                        <div className="flex items-center gap-2 bg-vscode-card px-3 py-1.5 rounded-sm border border-vscode-border">
                            <span className={`material-symbols-outlined text-vscode-primary ${currentTimeRemaining < 30 ? 'animate-pulse text-red-500' : ''}`}>schedule</span>
                            <span className="font-bold text-vscode-text font-mono text-lg">{formatTimer(currentTimeRemaining)}</span>
                        </div>
                    </div>

                    {/* Main Boss Stage */}
                    <div className="flex-1 flex flex-col items-center justify-center p-8 relative mt-12 lg:mt-0">
                        {/* Floating Damage Text */}
                        {showDamage && (
                            <div className="absolute top-24 right-10 lg:right-20 bg-red-600 text-white font-black text-2xl px-4 py-2 rounded shadow-xl z-30 font-mono border border-red-400 animate-bounce-short">
                                -1 HP!
                            </div>
                        )}

                        {/* Boss Avatar */}
                        <div
                            className={`relative group cursor-pointer ${isHit ? 'animate-shake' : 'animate-float'}`}
                            onClick={shakeBoss}
                        >
                            {/* Aura */}
                            <div className="absolute inset-0 bg-vscode-primary rounded-full blur-3xl opacity-20 group-hover:opacity-40 transition-opacity duration-500"></div>

                            {/* Avatar Image */}
                            <div className={`w-48 h-48 lg:w-64 lg:h-64 bg-[#2d2d2d] rounded-full border-4 border-vscode-border shadow-2xl flex items-center justify-center overflow-hidden relative z-20 transition-all duration-200 ${isHit ? 'boss-hit' : ''}`}>
                                <img
                                    src={`https://api.dicebear.com/9.x/bottts-neutral/svg?seed=${encodeURIComponent(bossName)}&backgroundColor=1e1e1e`}
                                    alt="Boss"
                                    className="w-full h-full object-cover opacity-90"
                                />
                            </div>

                            {/* Boss Badge */}
                            <div className="absolute bottom-4 right-4 bg-vscode-card text-vscode-text text-xs font-mono px-3 py-1 rounded border border-vscode-border z-30 shadow-lg">
                                <span className="text-vscode-primary">const</span> boss = <span className="text-[#ce9178]">{`'${bossName}'`}</span>;
                            </div>
                        </div>

                        <div className="text-center mt-8 z-20">
                            <h2 className="text-2xl font-bold text-vscode-text tracking-tight mb-2 font-mono">{bossName}</h2>

                            {/* HP Bar */}
                            <div className="w-64 lg:w-72 mx-auto">
                                <div className="flex justify-between text-xs font-bold text-vscode-muted mb-1 uppercase tracking-wider font-mono">
                                    <span>HP Status</span>
                                    <span>{bossHpCurrent}/{bossHpMax}</span>
                                </div>
                                <div className="w-full bg-[#333333] h-4 rounded-sm border border-[#444] p-[1px] relative overflow-hidden">
                                    <div
                                        className={`h-full rounded-sm transition-all duration-500 ease-out w-full opacity-90 ${getHpBarColor()}`}
                                        style={{ width: `${(bossHpCurrent / bossHpMax) * 100}%` }}
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Decoration Lines */}
                    <div className="absolute bottom-4 left-4 text-vscode-border text-[10px] font-mono opacity-50">
                        Ln {currentQuestionIndex + 1}, Col 12 &nbsp;&nbsp; UTF-8 &nbsp;&nbsp; JavaScript
                    </div>
                </div>

                {/* RIGHT PANEL: CONSOLE */}
                <div className="lg:w-3/5 w-full bg-[#1e1e1e] flex flex-col relative z-0">
                    {/* Tabs */}
                    <div className="flex items-center bg-vscode-card border-b border-vscode-border">
                        <div className="px-4 py-3 bg-[#1e1e1e] border-t-2 border-t-vscode-primary text-sm font-mono text-vscode-text flex items-center gap-2">
                            <span className="text-yellow-500 font-bold">JS</span>
                            soal_{(currentQuestionIndex + 1).toString().padStart(2, '0')}
                            <span className="ml-2 text-vscode-muted hover:text-white cursor-pointer">✕</span>
                        </div>
                    </div>

                    {/* Progress Bar */}
                    <div className="px-8 pt-6 pb-2">
                        <div className="flex items-center justify-between mb-2 font-mono text-xs">
                            <span className="text-vscode-muted">PROGRESS_SESI</span>
                            <span className="text-vscode-primary">{currentQuestionIndex + 1} / {questions.length} Completed</span>
                        </div>
                        <div className="w-full bg-[#333] h-1 rounded-full overflow-hidden">
                            <div
                                className="bg-vscode-primary h-full shadow-[0_0_10px_#007acc] transition-all duration-300"
                                style={{ width: `${((currentQuestionIndex + 1) / questions.length) * 100}%` }}
                            ></div>
                        </div>
                    </div>

                    {/* Scrollable Content */}
                    <div className="flex-1 overflow-y-auto px-8 py-4 custom-scrollbar flex flex-col">
                        {/* Question Block */}
                        <div className="mb-6">
                            <div className="font-mono text-vscode-muted mb-2 text-sm">// Pertanyaan:</div>
                            <div
                                className="font-medium text-lg lg:text-xl leading-relaxed text-[#d4d4d4] prose prose-invert max-w-none"
                                dangerouslySetInnerHTML={{ __html: currentQuestion.soal_text }}
                            ></div>

                            {/* Debug / Cheat Sheet */}
                            <div className="mt-2 p-2 bg-[#2d2d2d] border border-dashed border-[#444] rounded text-xs text-[#858585] font-mono inline-block">
                                <span className="text-yellow-500 mr-1">🐛</span>
                                Using Cheat: Answer is <span className="font-bold text-[#ce9178]">{currentQuestion.jawaban_benar}</span>
                            </div>
                        </div>

                        {/* Answer Grid */}
                        <div className="mt-auto pb-8">
                            {currentQuestion.tipe === 'multiple_choice' ? (
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    {['A', 'B', 'C', 'D'].map((option) => (
                                        <button
                                            key={option}
                                            onClick={() => submitAnswer(option)}
                                            disabled={isSubmitting}
                                            className={`group relative bg-vscode-card border border-vscode-border hover:border-vscode-primary p-4 rounded-sm transition-all text-left flex items-center min-h-[5rem] h-auto active:bg-[#333]
                                                ${feedbackCorrect && selectedAnswer === option ? 'border-green-500 bg-[#1e1e1e]' : ''}
                                                ${!feedbackCorrect && selectedAnswer === option && showFeedbackShake ? 'border-red-500 bg-[#1e1e1e] animate-shake' : ''}
                                                ${isSubmitting && selectedAnswer !== option ? 'opacity-50 cursor-not-allowed' : ''}
                                            `}
                                        >
                                            <div className="text-vscode-muted font-mono text-sm w-8 mr-3 group-hover:text-vscode-primary transition-colors shrink-0">
                                                {option}.
                                            </div>
                                            <span className="font-mono text-vscode-text group-hover:text-white text-sm break-words w-full leading-relaxed">
                                                {currentQuestion[`pilihan_${option.toLowerCase()}`]}
                                            </span>
                                        </button>
                                    ))}
                                </div>
                            ) : (
                                <div className="space-y-4">
                                    <textarea
                                        value={shortAnswerInput}
                                        onChange={(e) => setShortAnswerInput(e.target.value)}
                                        placeholder="// Tulis jawaban Anda di sini..."
                                        className="w-full h-32 bg-[#1e1e1e] border border-vscode-border text-vscode-text font-mono p-4 focus:border-vscode-primary focus:outline-none rounded-sm resize-none"
                                    ></textarea>
                                    <button
                                        onClick={() => submitAnswer(shortAnswerInput)}
                                        disabled={!shortAnswerInput || isSubmitting}
                                        className="bg-vscode-primary hover:bg-yellow-600 text-black font-mono font-bold py-2 px-6 rounded-sm transition-colors flex items-center gap-2"
                                    >
                                        <span>▶</span> Submit
                                    </button>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Footer */}
                    <div className="p-2 px-4 bg-vscode-primary flex justify-between items-center text-[10px] lg:text-xs text-black font-mono">
                        <div className="flex gap-4">
                            <span>main*</span>
                        </div>
                        <div>
                            <span>Solo Boss Battle</span>
                        </div>
                    </div>
                </div>
            </div>

            {/* Error Modal */}
            {showErrorModal && (
                <div className="fixed inset-0 z-[60] flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
                    <div className="bg-vscode-card border border-red-500 rounded-lg shadow-2xl w-full max-w-sm overflow-hidden transform transition-all border-l-4 border-l-red-600">
                        <div className="p-4 flex items-start gap-4">
                            <div className="text-2xl text-red-600 mt-1">⚠️</div>
                            <div className="flex-1">
                                <h3 className="font-bold text-white text-base mb-1">Runtime Error</h3>
                                <p className="text-sm text-vscode-muted mb-4">{errorMessage}</p>
                                <button onClick={() => setShowErrorModal(false)} className="bg-[#3c3c3c] hover:bg-[#4c4c4c] text-white text-xs px-3 py-2 rounded-sm border border-vscode-border transition-colors">
                                    Dismiss
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            )}

            {/* Feedback Modal */}
            {showFeedbackModal && (
                <div className="absolute top-10 left-1/2 transform -translate-x-1/2 z-50 transition-all duration-300 w-full max-w-md px-4">
                    <div className="bg-[#252526] text-vscode-text p-4 rounded shadow-2xl border border-[#454545] flex items-start gap-4 animate-bounce-short hover:bg-[#2a2a2a] transition-colors">
                        <div className="text-2xl pt-1">
                            {feedbackCorrect ? (
                                <span className="text-green-500">✅</span>
                            ) : (
                                <span className="text-red-500">❌</span>
                            )}
                        </div>
                        <div className="flex-1">
                            <h3 className="font-bold text-base mb-1">{feedbackCorrect ? 'Compilation Success!' : 'Syntax Error!'}</h3>
                            <p className="text-sm text-vscode-muted mb-1">{feedbackMessage}</p>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
