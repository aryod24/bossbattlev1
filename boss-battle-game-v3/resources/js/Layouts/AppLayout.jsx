import { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';

export default function AppLayout({ children }) {
    const { auth } = usePage().props;
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

    // Helper to check active route (simplified)
    const isRoute = (name) => {
        // In real app, might pass "route().current()" via props or use a helper
        // For now, simpler check or rely on Inertia's usePage().url
        return window.location.pathname.startsWith(name);
    };

    return (
        <div className="relative flex min-h-screen w-full flex-col group/design-root overflow-x-hidden bg-background-dark text-text-primary font-display">
            {/* TopNavBar Component */}
            <header className="flex items-center justify-between whitespace-nowrap border-b border-solid border-border px-6 sm:px-10 lg:px-20 py-3 bg-card/80 backdrop-blur-sm sticky top-0 z-50 text-white">
                {/* Logo & Title */}
                <div className="flex items-center gap-4 text-text-primary">
                    <div className="size-8">
                        {/* Ensure logo asset is handled correctly */}
                        <img src="/assets/logo.png" alt="CodeBossArena Logo" className="w-full h-full object-contain" />
                    </div>
                    <h2 className="text-text-primary text-lg font-bold leading-tight tracking-[-0.015em]">CodeBossArena</h2>
                </div>

                {/* Desktop Navigation */}
                <nav className="hidden lg:flex items-center gap-4">
                    <Link
                        href="/dashboard"
                        className={`flex h-10 shrink-0 cursor-pointer items-center justify-center gap-x-2 rounded-lg pl-4 pr-4 transition-colors ${isRoute('/dashboard')
                                ? 'bg-primary text-black shadow-sm font-bold'
                                : 'hover:bg-primary/20 text-text-muted hover:text-text-primary font-medium'
                            }`}
                    >
                        Dashboard
                    </Link>
                    <Link
                        href="/leaderboard"
                        className={`flex h-10 shrink-0 cursor-pointer items-center justify-center gap-x-2 rounded-lg pl-4 pr-4 transition-colors ${isRoute('/leaderboard')
                                ? 'bg-primary text-black shadow-sm font-bold'
                                : 'hover:bg-primary/20 text-text-muted hover:text-text-primary font-medium'
                            }`}
                    >
                        Leaderboard
                    </Link>
                    <Link
                        href="/solo"
                        className={`flex h-10 shrink-0 cursor-pointer items-center justify-center gap-x-2 rounded-lg pl-4 pr-4 transition-colors ${isRoute('/solo')
                                ? 'bg-primary text-black shadow-sm font-bold'
                                : 'hover:bg-primary/20 text-text-muted hover:text-text-primary font-medium'
                            }`}
                    >
                        Events
                    </Link>
                    <Link
                        href="/profile"
                        className={`flex h-10 shrink-0 cursor-pointer items-center justify-center gap-x-2 rounded-lg pl-4 pr-4 transition-colors ${isRoute('/profile')
                                ? 'bg-primary text-black shadow-sm font-bold'
                                : 'hover:bg-primary/20 text-text-muted hover:text-text-primary font-medium'
                            }`}
                    >
                        Profile
                    </Link>
                </nav>

                {/* Desktop: Logout + Avatar */}
                <div className="hidden lg:flex items-center gap-4">
                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        type="button"
                        className="flex items-center justify-center rounded-lg h-10 bg-primary text-black gap-2 text-sm font-bold px-5 shadow-sm hover:bg-accent-hover transition-transform duration-200 hover:-translate-y-0.5"
                    >
                        <span className="truncate">Log Out</span>
                    </Link>
                    <div
                        className="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
                        style={{
                            backgroundImage: `url("https://ui-avatars.com/api/?name=${encodeURIComponent(
                                auth?.user?.nama || 'User'
                            )}&background=random")`,
                        }}
                    ></div>
                </div>

                {/* Mobile/Tablet: Hamburger + Avatar */}
                <div className="lg:hidden flex items-center gap-4">
                    <button
                        onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                        type="button"
                        className="flex items-center justify-center size-10 rounded-lg hover:bg-primary/20 transition-colors"
                    >
                        <span className="material-symbols-outlined text-text-primary">menu</span>
                    </button>
                    <div
                        className="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
                        style={{
                            backgroundImage: `url("https://ui-avatars.com/api/?name=${encodeURIComponent(
                                auth?.user?.nama || 'User'
                            )}&background=random")`,
                        }}
                    ></div>
                </div>

                {/* Mobile Dropdown Menu */}
                {mobileMenuOpen && (
                    <div className="lg:hidden absolute top-full right-0 left-0 bg-card/95 backdrop-blur-sm border-b border-border shadow-lg">
                        <nav className="flex flex-col px-6 py-4 gap-2 text-white">
                            <Link href="/dashboard" className="flex h-12 items-center rounded-lg px-4 hover:bg-primary/20">
                                Dashboard
                            </Link>
                            <Link href="/leaderboard" className="flex h-12 items-center rounded-lg px-4 hover:bg-primary/20">
                                Leaderboard
                            </Link>
                            <Link href="/solo" className="flex h-12 items-center rounded-lg px-4 hover:bg-primary/20">
                                Events
                            </Link>
                            <Link href="/profile" className="flex h-12 items-center rounded-lg px-4 hover:bg-primary/20">
                                Profile
                            </Link>
                            <div className="border-t border-border my-2"></div>
                            <Link
                                href="/logout"
                                method="post"
                                as="button"
                                className="w-full flex h-12 items-center justify-center rounded-lg bg-primary text-black font-bold px-4 shadow-sm"
                            >
                                Log Out
                            </Link>
                        </nav>
                    </div>
                )}
            </header>

            <main className="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 lg:py-6">{children}</main>
        </div>
    );
}
