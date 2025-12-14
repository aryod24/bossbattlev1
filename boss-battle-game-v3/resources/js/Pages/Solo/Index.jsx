import { useState } from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';

dayjs.extend(relativeTime);

export default function SoloIndex({ raids }) {
    const { auth } = usePage().props;
    const [currentFilter, setCurrentFilter] = useState('all');
    const [searchQuery, setSearchQuery] = useState('');

    const filterEvents = (status) => {
        setCurrentFilter(status);
    };

    const isVisible = (eventStatus, isExpired) => {
        if (currentFilter === 'all') return true;
        if (currentFilter === 'active') return eventStatus === 'active' && !isExpired;
        if (currentFilter === 'closed') return isExpired;
        return true;
    };

    const isExpired = (date) => dayjs().isAfter(dayjs(date));

    // Handle pagination links manually or use Inertia Link
    // For now simple rendering of items
    const raidItems = raids.data || [];

    return (
        <AppLayout>
            <Head title="Daftar Event" />

            <div className="flex flex-col gap-6">
                {/* Page Heading */}
                <div className="flex flex-wrap justify-between items-center gap-4">
                    <div className="flex flex-col gap-2">
                        <p className="text-4xl font-black leading-tight tracking-tight text-text-primary">Daftar Event</p>
                        <p className="text-text-muted text-base font-normal leading-normal">Bergabunglah dengan teman & kompetisi</p>
                    </div>
                    {/* Admin Create Button */}
                    {auth.user.role === 'admin' && (
                        <Link
                            href="/admin/solo-raids/create"
                            className="flex items-center justify-center rounded-lg h-12 bg-primary text-black gap-2 text-sm font-bold leading-normal tracking-wide min-w-0 px-6 shadow-sm hover:bg-accent-hover transition-transform duration-200 hover:-translate-y-0.5"
                        >
                            <span className="material-symbols-outlined">add_circle</span>
                            <span className="truncate">Buat Event Baru</span>
                        </Link>
                    )}
                </div>

                {/* Toolbar: Filters and Search */}
                <div className="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div className="flex gap-2 p-1 bg-card border border-border rounded-lg overflow-x-auto">
                        <div
                            onClick={() => filterEvents('all')}
                            className={`flex h-10 shrink-0 cursor-pointer items-center justify-center gap-x-2 rounded-lg pl-4 pr-4 transition-colors ${currentFilter === 'all' ? 'bg-primary shadow-sm' : 'hover:bg-primary/20'
                                }`}
                        >
                            <p className={`text-sm leading-normal ${currentFilter === 'all' ? 'text-black font-bold' : 'text-text-primary font-medium'}`}>Semua</p>
                        </div>
                        <div
                            onClick={() => filterEvents('active')}
                            className={`flex h-10 shrink-0 cursor-pointer items-center justify-center gap-x-2 rounded-lg pl-4 pr-4 transition-colors ${currentFilter === 'active' ? 'bg-primary shadow-sm' : 'hover:bg-primary/20'
                                }`}
                        >
                            <p className={`text-sm leading-normal ${currentFilter === 'active' ? 'text-black font-bold' : 'text-text-primary font-medium'}`}>Aktif</p>
                        </div>
                        <div
                            onClick={() => filterEvents('closed')}
                            className={`flex h-10 shrink-0 cursor-pointer items-center justify-center gap-x-2 rounded-lg pl-4 pr-4 transition-colors ${currentFilter === 'closed' ? 'bg-primary shadow-sm' : 'hover:bg-primary/20'
                                }`}
                        >
                            <p className={`text-sm leading-normal ${currentFilter === 'closed' ? 'text-black font-bold' : 'text-text-primary font-medium'}`}>Selesai</p>
                        </div>
                    </div>
                    <div className="w-full md:max-w-xs">
                        <label className="flex flex-col min-w-40 h-12 w-full">
                            <div className="flex w-full flex-1 items-stretch rounded-lg h-full shadow-sm border border-border bg-card focus-within:ring-2 focus-within:ring-primary">
                                <div className="text-text-muted flex items-center justify-center pl-4 rounded-l-lg border-r-0">
                                    <span className="material-symbols-outlined">search</span>
                                </div>
                                <input
                                    value={searchQuery}
                                    onChange={(e) => setSearchQuery(e.target.value)}
                                    className="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-base font-normal h-full placeholder:text-text-muted px-4 pl-2 focus:outline-none focus:ring-0 border-none bg-transparent text-text-primary"
                                    placeholder="Cari event..."
                                />
                            </div>
                        </label>
                    </div>
                </div>

                {/* Event Cards Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {raidItems.length > 0 ? (
                        raidItems.map((raid) => {
                            const expired = isExpired(raid.tanggal_selesai);
                            const visible = isVisible(raid.status, expired) && (searchQuery === '' || raid.nama.toLowerCase().includes(searchQuery.toLowerCase()));

                            if (!visible) return null;

                            return (
                                <div
                                    key={raid.id}
                                    className={`flex flex-col bg-card rounded-lg border border-border shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 cursor-pointer overflow-hidden h-full ${raid.status === 'draft' ? 'opacity-70' : ''
                                        }`}
                                >
                                    <div className="p-5 flex-1 flex flex-col">
                                        <div className="flex justify-between items-start gap-4 mb-2">
                                            <div className="flex-1">
                                                <h3 className="text-lg font-bold text-text-primary mb-1">{raid.nama}</h3>

                                                {/* Deadline / Countdown Section */}
                                                {raid.status === 'active' ? (
                                                    !expired ? (
                                                        <div className="flex items-center text-error animate-pulse">
                                                            <span className="material-symbols-outlined text-sm mr-1">timer</span>
                                                            <p className="text-xs font-bold">Berakhir {dayjs(raid.tanggal_selesai).fromNow()}</p>
                                                        </div>
                                                    ) : (
                                                        <div className="flex items-center text-text-muted">
                                                            <span className="material-symbols-outlined text-sm mr-1">event_busy</span>
                                                            <p className="text-xs font-bold">Event Berakhir</p>
                                                        </div>
                                                    )
                                                ) : raid.status === 'selesai' ? (
                                                    <div className="flex items-center text-success">
                                                        <span className="material-symbols-outlined text-sm mr-1">check_circle</span>
                                                        <p className="text-xs font-bold">Telah Diselesaikan</p>
                                                    </div>
                                                ) : (
                                                    <div className="flex items-center text-text-muted">
                                                        <span className="material-symbols-outlined text-sm mr-1">edit_note</span>
                                                        <p className="text-xs font-bold">Mode Draft</p>
                                                    </div>
                                                )}
                                            </div>
                                            {/* Placeholder Boss Image */}
                                            <img
                                                className="w-12 h-12 rounded-full border-2 border-border object-cover shrink-0"
                                                src={`https://ui-avatars.com/api/?name=${encodeURIComponent(raid.boss_easy_name || 'Boss')}&background=random`}
                                                alt="Boss"
                                            />
                                        </div>

                                        <p className="text-xs text-text-muted mb-4 line-clamp-2 mt-2">{raid.deskripsi}</p>

                                        <div className="mt-auto">
                                            {/* Progress Bar Placeholder */}
                                            <div className="text-xs text-text-muted mb-1 flex justify-between">
                                                <span>XP Reward</span>
                                                <span className="font-bold text-text-primary">{(raid.question_count || 10) * 10} XP</span>
                                            </div>
                                            <div className="w-full bg-border rounded-full h-1.5 mb-4">
                                                <div className="bg-primary h-1.5 rounded-full" style={{ width: '0%' }}></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="px-5 py-4 border-t border-border bg-background-dark/50">
                                        <div className="flex justify-between items-center mb-4">
                                            <div className="flex items-center gap-2 text-sm text-text-muted">
                                                <span className="material-symbols-outlined text-lg">calendar_today</span>
                                                <span className="font-medium">{dayjs(raid.tanggal_mulai).format('D MMM YYYY')}</span>
                                            </div>

                                            {/* Difficulty Badge */}
                                            {raid.hard_enabled ? (
                                                <span className="text-[10px] font-bold px-2 py-0.5 bg-red-500/10 text-red-500 border border-red-500/20 rounded uppercase tracking-wider">
                                                    Hard
                                                </span>
                                            ) : raid.medium_enabled ? (
                                                <span className="text-[10px] font-bold px-2 py-0.5 bg-orange-500/10 text-orange-500 border border-orange-500/20 rounded uppercase tracking-wider">
                                                    Medium
                                                </span>
                                            ) : (
                                                <span className="text-[10px] font-bold px-2 py-0.5 bg-green-500/10 text-green-500 border border-green-500/20 rounded uppercase tracking-wider">
                                                    Easy
                                                </span>
                                            )}
                                        </div>

                                        <div className="flex justify-between items-center">
                                            {raid.status === 'active' && !expired ? (
                                                <>
                                                    <span className="animate-pulse text-xs font-bold px-3 py-1 bg-red-600 text-white rounded-md uppercase tracking-wider">Ongoing</span>
                                                    <Link
                                                        href={`/solo/${raid.id}`}
                                                        className="flex items-center justify-center rounded-lg h-10 bg-primary text-black gap-2 text-sm font-bold px-5 shadow-sm hover:bg-accent-hover transition-transform duration-200 hover:scale-105"
                                                    >
                                                        Bergabung
                                                    </Link>
                                                </>
                                            ) : raid.status === 'active' && expired ? (
                                                <>
                                                    <span className="text-xs font-bold px-3 py-1 bg-text-muted text-white rounded-md uppercase tracking-wider">Tutup</span>
                                                    <button className="flex items-center justify-center rounded-lg h-10 bg-border text-text-muted gap-2 text-sm font-bold px-5 cursor-not-allowed" disabled>
                                                        Tutup
                                                    </button>
                                                </>
                                            ) : raid.status === 'selesai' ? (
                                                <>
                                                    <span className="text-xs font-bold px-3 py-1 bg-green-600 text-white rounded-md uppercase tracking-wider">Selesai</span>
                                                    <button className="flex items-center justify-center rounded-lg h-10 bg-blue-500 text-white gap-2 text-sm font-bold px-5 shadow-sm hover:brightness-110 transition-colors duration-200">
                                                        Leaderboard
                                                    </button>
                                                </>
                                            ) : (
                                                <>
                                                    <span className="text-xs font-bold px-3 py-1 bg-text-muted text-white rounded-md uppercase tracking-wider">Draft</span>
                                                    <button className="flex items-center justify-center rounded-lg h-10 bg-border text-text-muted gap-2 text-sm font-bold px-5 cursor-not-allowed" disabled>
                                                        Daftar
                                                    </button>
                                                </>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            );
                        })
                    ) : (
                        <div className="col-span-3 flex flex-col items-center justify-center text-center p-16 rounded-lg bg-card border border-border mt-4">
                            <span className="material-symbols-outlined text-6xl text-text-muted mb-4">sentiment_dissatisfied</span>
                            <h3 className="text-2xl font-bold mb-2 text-text-primary">Tidak ada event saat ini.</h3>
                            <p className="text-text-muted mb-4">Kembali lagi nanti untuk melihat keseruan baru!</p>
                        </div>
                    )}
                </div>

                {/* Pagination (Simplified) */}
                {raids.links && raids.links.length > 3 && (
                    <div className="mt-8 flex justify-center gap-2">
                        {raids.links.map((link, i) => (
                            <Link
                                key={i}
                                href={link.url || '#'}
                                className={`px-4 py-2 rounded-lg ${link.active ? 'bg-primary text-black font-bold' : 'bg-card text-text-muted border border-border'} ${!link.url ? 'opacity-50 cursor-not-allowed' : ''}`}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        ))}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
