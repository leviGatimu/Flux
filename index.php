<?php
function renderStars(int $n = 5): string
{
    $star = '<svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>';
    return str_repeat($star, $n);
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flux | AI Academic Strategist</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7', 400: '#34d399',
                            500: '#10b981', 600: '#059669', 700: '#047857', 800: '#065f46', 900: '#064e3b', 950: '#022c22',
                        }
                    },
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'scan': 'scan 2.5s linear infinite',
                        'grow-1': 'grow 1s ease-out forwards 0.5s',
                        'grow-2': 'grow 1s ease-out forwards 0.7s',
                        'grow-3': 'grow 1s ease-out forwards 0.9s',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-18px)' },
                        },
                        scan: {
                            '0%': { top: '-5%' },
                            '100%': { top: '105%' },
                        },
                        grow: {
                            '0%': { transform: 'scaleY(0)', transformOrigin: 'bottom' },
                            '100%': { transform: 'scaleY(1)', transformOrigin: 'bottom' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Scroll reveal */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.7s ease-out, transform 0.7s ease-out;
        }

        .reveal.active {
            opacity: 1;
            transform: none;
        }

        .delay-100 {
            transition-delay: 100ms;
        }

        .delay-200 {
            transition-delay: 200ms;
        }

        .delay-300 {
            transition-delay: 300ms;
        }

        /* Stats divider */
        .stat-item+.stat-item {
            border-left: 1px solid #e2e8f0;
        }

        /* FAQ accordion */
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease-out;
        }

        .faq-answer.open {
            max-height: 300px;
        }

        .faq-icon {
            transition: transform 0.3s ease;
        }

        .faq-btn[aria-expanded="true"] .faq-icon {
            transform: rotate(45deg);
        }

        /* Mobile menu */
        #mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease-out;
        }

        #mobile-menu.open {
            max-height: 500px;
        }

        /* Big quote decoration */
        .quote-mark {
            font-family: Georgia, serif;
            font-size: 6rem;
            line-height: 0.75;
            color: #10b981;
            opacity: 0.15;
            user-select: none;
        }

        /* Background Aesthetics */
        .bg-aesthetic {
            position: fixed;
            inset: 0;
            z-index: -20;
            overflow: hidden;
            pointer-events: none;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            transition: opacity 0.5s ease;
        }

        .dark .blob {
            opacity: 0.1;
        }

        .blob-1 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, #10b981, transparent);
            top: -10%;
            left: -10%;
            animation: drift 20s infinite alternate;
        }

        .blob-2 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, #3b82f6, transparent);
            bottom: 10%;
            right: -5%;
            animation: drift 25s infinite alternate-reverse;
        }

        .blob-3 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, #f59e0b, transparent);
            top: 40%;
            left: 60%;
            animation: drift 18s infinite alternate;
        }

        @keyframes drift {
            from {
                transform: translate(0, 0) scale(1);
            }

            to {
                transform: translate(100px, 50px) scale(1.1);
            }
        }

        canvas#particleCanvas {
            position: fixed;
            inset: 0;
            z-index: -15;
            pointer-events: none;
            opacity: 0.4;
        }

        .dark canvas#particleCanvas {
            opacity: 0.2;
        }
    </style>
</head>

<body
    class="bg-slate-50 text-slate-800 antialiased selection:bg-brand-100 selection:text-brand-900 overflow-x-hidden dark:bg-slate-950 dark:text-slate-200">

    <!-- Global Background Elements -->
    <div class="bg-aesthetic">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>
    <canvas id="particleCanvas"></canvas>

    <!-- ===== NAVBAR ===== -->
    <nav class="fixed w-full bg-white/85 backdrop-blur-md z-50 border-b border-slate-100 transition-all duration-300 dark:bg-slate-900/85 dark:border-slate-800"
        id="navbar">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2">
                <div
                    class="w-8 h-8 rounded-lg bg-brand-500 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-brand-500/30 dark:text-slate-900">
                    F</div>
                <span class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Flux</span>
            </a>
            <div class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-500 dark:text-slate-400">
                <a href="#how-it-works" class="hover:text-brand-600 transition-colors">How it Works</a>
                <a href="#features" class="hover:text-brand-600 transition-colors">Features</a>
                <a href="#faq" class="hover:text-brand-600 transition-colors">FAQ</a>
                <a href="#testimonials" class="hover:text-brand-600 transition-colors">Wall of Love</a>
            </div>
            <div class="flex items-center gap-3">
                <a href="login.php"
                    class="hidden sm:block text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors dark:text-slate-400 dark:hover:text-white">Log
                    in</a>
                <a href="register.php"
                    class="text-sm font-semibold bg-slate-900 text-white px-5 py-2.5 rounded-full hover:bg-slate-700 shadow-md transition-all hover:scale-105 active:scale-95 dark:bg-slate-800 dark:text-white dark:text-slate-900">Get
                    Started</a>

                <button id="themeToggleBtn"
                    class="p-2 rounded-full text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors ml-4"
                    aria-label="Toggle dark mode">
                    <svg id="themeIconSun" class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    <svg id="themeIconMoon" class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                        </path>
                    </svg>
                </button>

                <button id="menuBtn"
                    class="md:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors dark:text-slate-400 dark:hover:bg-slate-800"
                    aria-label="Toggle menu" aria-expanded="false">
                    <svg id="iconHamburger" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg id="iconClose" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <!-- Mobile menu -->
        <div id="mobile-menu"
            class="md:hidden border-t border-slate-100 bg-white dark:border-slate-800 dark:bg-slate-900">
            <div class="px-6 py-4 space-y-1">
                <a href="#how-it-works"
                    class="mobile-link block py-2.5 px-3 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-brand-600 transition-colors dark:text-slate-300 dark:hover:bg-slate-800">How
                    it Works</a>
                <a href="#features"
                    class="mobile-link block py-2.5 px-3 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-brand-600 transition-colors dark:text-slate-300 dark:hover:bg-slate-800">Features</a>
                <a href="#faq"
                    class="mobile-link block py-2.5 px-3 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-brand-600 transition-colors dark:text-slate-300 dark:hover:bg-slate-800">FAQ</a>
                <a href="#testimonials"
                    class="mobile-link block py-2.5 px-3 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-brand-600 transition-colors dark:text-slate-300 dark:hover:bg-slate-800">Wall
                    of Love</a>
                <div class="pt-3 mt-3 border-t border-slate-100 flex flex-col gap-2 dark:border-slate-800">
                    <a href="login.php"
                        class="block py-2.5 px-3 text-sm font-medium text-slate-600 hover:bg-slate-50 rounded-lg transition-colors dark:text-slate-400 dark:hover:bg-slate-800">Log
                        in</a>
                    <a href="register.php"
                        class="block py-2.5 px-3 text-sm font-semibold bg-brand-500 text-white rounded-lg hover:bg-brand-600 transition-colors text-center dark:text-slate-900">Get
                        Started Free</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ===== HERO ===== -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-28 overflow-hidden bg-transparent">

        <div class="max-w-7xl mx-auto px-6 text-center">
            <!-- Badge -->
            <div
                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-brand-50 border border-brand-100 text-brand-700 text-sm font-medium mb-8 cursor-default">
                <span class="relative flex h-2 w-2">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
                </span>
                Flux Core AI is now available
            </div>

            <!-- Headline -->
            <h1
                class="text-5xl lg:text-7xl font-extrabold tracking-tight text-slate-900 mb-6 leading-[1.06] dark:text-white">
                Stop Guessing Why You Got a&nbsp;B.<br>
                <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-brand-500 via-emerald-400 to-teal-400">Let
                    AI Map Your A.</span>
            </h1>

            <p class="max-w-2xl mx-auto text-lg text-slate-500 mb-10 leading-relaxed dark:text-slate-400">
                Upload your test scores and let our AI engine diagnose exactly where you are losing marks. Get
                personalized study guides and actionable growth plans — instantly.
            </p>

            <!-- CTAs -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-8">
                <a href="register.php"
                    class="w-full sm:w-auto px-8 py-4 bg-brand-500 text-white font-semibold rounded-full hover:bg-brand-600 shadow-xl shadow-brand-500/25 transition-all hover:-translate-y-1 active:scale-95 text-base flex items-center justify-center gap-2 group dark:text-slate-900">
                    Start Your Free Analysis
                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
                <a href="#how-it-works"
                    class="w-full sm:w-auto px-8 py-4 border border-slate-200 bg-white text-slate-700 font-semibold rounded-full hover:border-slate-300 hover:bg-slate-50 transition-all text-base flex items-center justify-center gap-2 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">
                    See How It Works
                </a>
            </div>

            <!-- Trust signals -->
            <div
                class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm text-slate-400 mb-20 dark:text-slate-500">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-brand-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    No credit card required
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-brand-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    BYOK — your data stays yours
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-brand-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    First analysis in under 2 min
                </span>
            </div>

            <!-- Dashboard mockup -->
            <div class="relative max-w-5xl mx-auto animate-float">
                <div
                    class="bg-white rounded-2xl shadow-2xl shadow-slate-300/25 border border-slate-100 p-2 relative z-10 dark:bg-slate-900 dark:shadow-slate-900/50 dark:border-slate-800">
                    <!-- Browser chrome bar -->
                    <div
                        class="bg-slate-50 border-b border-slate-100 px-4 py-3 flex items-center gap-2 rounded-t-xl dark:bg-slate-950 dark:border-slate-800">
                        <div class="w-3 h-3 rounded-full bg-red-400"></div>
                        <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                        <div class="w-3 h-3 rounded-full bg-brand-400"></div>
                        <div
                            class="ml-3 flex-1 bg-white border border-slate-200 rounded-md px-3 py-1 text-xs text-slate-400 font-mono text-left max-w-xs dark:bg-slate-900 dark:border-slate-700 dark:text-slate-500">
                            dashboard.flux.app/overview</div>
                    </div>
                    <!-- Dashboard body -->
                    <div class="p-5 md:p-6 bg-slate-50/60 rounded-b-xl grid grid-cols-1 md:grid-cols-3 gap-4 text-left">

                        <!-- Left col: Chart + subject bars -->
                        <div class="md:col-span-2 space-y-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p
                                        class="text-slate-400 text-[11px] font-semibold uppercase tracking-widest dark:text-slate-500">
                                        Overall Trajectory</p>
                                    <div class="text-3xl font-bold text-slate-900 mt-1 dark:text-white">3.8 <span
                                            class="text-base font-medium text-slate-400 dark:text-slate-500">GPA</span>
                                        <span
                                            class="text-brand-500 text-sm ml-2 bg-brand-50 border border-brand-100 px-2 py-0.5 rounded-lg font-semibold">↑
                                            +0.2</span>
                                    </div>
                                </div>
                                <span
                                    class="text-xs text-slate-400 bg-white border border-slate-100 px-2 py-1 rounded-lg shrink-0 dark:text-slate-500 dark:bg-slate-900 dark:border-slate-800">This
                                    Semester</span>
                            </div>

                            <!-- Bar chart -->
                            <div
                                class="bg-white rounded-xl p-4 border border-slate-100 dark:bg-slate-900 dark:border-slate-800">
                                <div class="h-28 w-full flex items-end gap-2">
                                    <?php
                                    $bars = [
                                        ['h' => '40%', 'cls' => 'bg-slate-200', 'anim' => 'animate-grow-1', 'label' => 'Jan', 'lc' => 'text-slate-400'],
                                        ['h' => '60%', 'cls' => 'bg-slate-200', 'anim' => 'animate-grow-2', 'label' => 'Feb', 'lc' => 'text-slate-400'],
                                        ['h' => '55%', 'cls' => 'bg-slate-200', 'anim' => 'animate-grow-3', 'label' => 'Mar', 'lc' => 'text-slate-400'],
                                        ['h' => '80%', 'cls' => 'bg-brand-200', 'anim' => 'animate-grow-1', 'label' => 'Apr', 'lc' => 'text-slate-400'],
                                        ['h' => '100%', 'cls' => 'bg-brand-500', 'anim' => 'animate-grow-2', 'label' => 'May', 'lc' => 'text-brand-600', 'tooltip' => 'Current: 94%'],
                                    ];
                                    foreach ($bars as $bar): ?>
                                        <div
                                            class="flex-1 flex flex-col items-center gap-1 <?= isset($bar['tooltip']) ? 'relative group cursor-pointer' : '' ?>">
                                            <?php if (isset($bar['tooltip'])): ?>
                                                <div
                                                    class="absolute -top-9 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-xs py-1.5 px-2.5 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10 pointer-events-none dark:bg-slate-800 dark:text-white dark:text-slate-900">
                                                    <?= $bar['tooltip'] ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="w-full <?= $bar['cls'] ?> rounded-t-md <?= $bar['anim'] ?> <?= $bar['cls'] === 'bg-brand-500' ? 'shadow-lg shadow-brand-500/25' : '' ?>"
                                                style="height:<?= $bar['h'] ?>"></div>
                                            <span
                                                class="text-[10px] font-medium <?= $bar['lc'] ?>"><?= $bar['label'] ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Subject health bars -->
                            <div
                                class="bg-white rounded-xl p-4 border border-slate-100 space-y-3 dark:bg-slate-900 dark:border-slate-800">
                                <p
                                    class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest dark:text-slate-500">
                                    Subject
                                    Health</p>
                                <?php
                                $subjects = [
                                    ['name' => 'Mathematics', 'score' => 94, 'bar' => 'bg-brand-500'],
                                    ['name' => 'Physics', 'score' => 67, 'bar' => 'bg-amber-400'],
                                    ['name' => 'English', 'score' => 88, 'bar' => 'bg-blue-400'],
                                ];
                                foreach ($subjects as $sub): ?>
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="text-xs text-slate-500 w-24 shrink-0 dark:text-slate-400"><?= $sub['name'] ?></span>
                                        <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full <?= $sub['bar'] ?> rounded-full"
                                                style="width:<?= $sub['score'] ?>%"></div>
                                        </div>
                                        <span
                                            class="text-xs font-bold text-slate-700 w-8 text-right dark:text-slate-300"><?= $sub['score'] ?>%</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Right col: AI briefing + next action -->
                        <div class="flex flex-col gap-4">
                            <div
                                class="bg-white p-5 rounded-xl border border-brand-100 shadow-sm relative overflow-hidden flex-1 hover:-translate-y-0.5 transition-transform duration-200 dark:bg-slate-900">
                                <div
                                    class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-brand-400 to-brand-600 rounded-l-xl">
                                </div>
                                <div class="flex items-center gap-2 mb-3">
                                    <div
                                        class="w-7 h-7 rounded-full bg-brand-50 flex items-center justify-center text-brand-600 animate-pulse">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">Live AI
                                        Briefing</span>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed dark:text-slate-400">
                                    <strong class="text-slate-900 dark:text-white">Math Alert:</strong> Scores
                                    stabilized after
                                    factoring review. Shift focus to the upcoming <span
                                        class="text-brand-600 font-medium bg-brand-50 px-1 rounded border border-brand-100">System
                                        Design</span> module to maintain trajectory.
                                </p>
                            </div>

                            <div class="bg-slate-900 p-5 rounded-xl border border-slate-800 dark:bg-slate-800">
                                <p
                                    class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest mb-3 dark:text-slate-400">
                                    Next
                                    Action</p>
                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-amber-500/15 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-white text-xs font-semibold dark:text-slate-900">Physics:
                                            Thermodynamics</p>
                                        <p class="text-slate-400 text-xs mt-0.5 dark:text-slate-500">Weak area — 3
                                            curated videos ready</p>
                                    </div>
                                </div>
                                <button
                                    class="mt-4 w-full py-2 bg-brand-500/15 border border-brand-500/20 text-brand-400 text-xs font-semibold rounded-lg hover:bg-brand-500/25 transition-colors">View
                                    Study Plan →</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Glow beneath mockup -->
                <div
                    class="absolute -bottom-5 left-1/2 -translate-x-1/2 w-2/3 h-10 bg-brand-500/10 blur-2xl rounded-full pointer-events-none">
                </div>
            </div>
        </div>
    </section>

    <!-- ===== STATS STRIP ===== -->
    <section class="border-y border-slate-100 bg-white dark:border-slate-800 dark:bg-slate-900">
        <div class="max-w-4xl mx-auto px-6 py-8 grid grid-cols-2 md:grid-cols-4 text-center">
            <?php
            $stats = [
                ['value' => '500+', 'label' => 'Early Adopters'],
                ['value' => '4.9 ★', 'label' => 'Average Rating'],
                ['value' => '12+', 'label' => 'Subjects Covered'],
                ['value' => '< 2 min', 'label' => 'First Analysis'],
            ];
            foreach ($stats as $stat): ?>
                <div class="stat-item py-4 px-6">
                    <div class="text-2xl font-extrabold text-slate-900 dark:text-white"><?= $stat['value'] ?></div>
                    <div class="text-xs text-slate-400 mt-1 font-medium dark:text-slate-500"><?= $stat['label'] ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ===== FEATURES ===== -->
    <section id="features" class="py-24 bg-slate-50 dark:bg-slate-950">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16 reveal">
                <p class="text-brand-600 font-semibold text-xs uppercase tracking-widest mb-3">Why Flux?</p>
                <h2 class="text-4xl font-bold text-slate-900 mb-4 dark:text-white">More Than a Calculator.</h2>
                <p class="text-slate-500 max-w-xl mx-auto dark:text-slate-400">Flux bridges the gap between knowing your
                    score and knowing
                    exactly how to improve it.</p>
            </div>

            <!-- Bento grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                <!-- Wide card: OCR -->
                <div
                    class="md:col-span-2 bg-white rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 relative overflow-hidden group reveal dark:bg-slate-900 dark:border-slate-800">
                    <div
                        class="absolute top-0 right-0 w-64 h-64 bg-brand-50 rounded-full blur-3xl opacity-0 group-hover:opacity-70 transition-opacity duration-500 pointer-events-none">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 bg-brand-50 rounded-2xl flex items-center justify-center text-brand-600 mb-5 border border-brand-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-3 dark:text-white">Frictionless Data Entry</h3>
                        <p class="text-slate-500 mb-8 max-w-md leading-relaxed dark:text-slate-400">Don't type your
                            grades. Just snap a
                            photo of your test paper. Our OCR engine extracts scores, topics, and even reads teacher
                            annotations in the margins.</p>
                        <div
                            class="relative w-full max-w-xs bg-slate-50 rounded-xl border border-slate-200 h-44 overflow-hidden dark:bg-slate-950 dark:border-slate-700">
                            <div
                                class="absolute w-full h-0.5 bg-gradient-to-r from-transparent via-brand-400 to-transparent shadow-[0_0_14px_rgba(16,185,129,0.7)] animate-scan z-20">
                            </div>
                            <div
                                class="p-5 font-mono text-xs text-slate-400 space-y-2 leading-loose opacity-50 dark:text-slate-500">
                                <div class="flex justify-between"><span>Q1: Quadratic Eq...</span><span
                                        class="text-slate-600 dark:text-slate-400">8/10</span></div>
                                <div class="flex justify-between"><span>Q2: Thermodynamics...</span><span
                                        class="text-red-500 font-bold">2/15 ✗</span></div>
                                <div class="flex justify-between"><span>Q3: System Design...</span><span
                                        class="text-brand-600 font-bold">20/20 ✓</span></div>
                                <div
                                    class="pt-2 border-t border-slate-200 text-[10px] text-brand-500 font-semibold dark:border-slate-700">
                                    AI
                                    detected 1 weak topic →</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Recovery Guides -->
                <div
                    class="bg-slate-900 rounded-3xl p-8 border border-slate-800 relative overflow-hidden reveal delay-100 group dark:bg-slate-800">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-900 to-brand-950/50 pointer-events-none">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center text-brand-400 mb-5 border border-white/10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3 dark:text-slate-900">Custom Recovery Guides</h3>
                        <p class="text-slate-400 text-sm mb-6 leading-relaxed dark:text-slate-500">Flux ignores what you
                            know and generates
                            study sheets strictly for what you failed.</p>
                        <div
                            class="bg-slate-800/70 rounded-xl p-4 border border-slate-700/50 space-y-3 dark:border-slate-700">
                            <div class="h-2 w-1/3 bg-brand-500 rounded-full"></div>
                            <div class="space-y-1.5">
                                <div class="h-1.5 w-full bg-slate-600/50 rounded-full"></div>
                                <div class="h-1.5 w-5/6 bg-slate-600/50 rounded-full"></div>
                                <div class="h-1.5 w-4/6 bg-slate-600/50 rounded-full"></div>
                            </div>
                            <div
                                class="flex items-center gap-2.5 p-2.5 bg-red-500/10 rounded-lg border border-red-500/20 mt-2">
                                <div class="w-6 h-6 rounded-full bg-red-500 flex items-center justify-center text-white shrink-0 dark:text-slate-900"
                                    style="font-size:9px">▶</div>
                                <div>
                                    <p class="text-xs text-white font-semibold dark:text-slate-900">Factoring
                                        Polynomials</p>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500">Tutorial · 5 min</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Smart Gap Detection -->
                <div
                    class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 reveal dark:bg-slate-900 dark:border-slate-800">
                    <div
                        class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 mb-5 border border-blue-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 dark:text-white">Smart Gap Detection</h3>
                    <p class="text-slate-500 text-sm leading-relaxed dark:text-slate-400">Pinpoints the exact sub-topic
                        causing your mark
                        loss — not just the broad subject.</p>
                </div>

                <!-- Card: YouTube -->
                <div
                    class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 reveal delay-100 dark:bg-slate-900 dark:border-slate-800">
                    <div
                        class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center text-red-500 mb-5 border border-red-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 dark:text-white">Curated Video Links</h3>
                    <p class="text-slate-500 text-sm leading-relaxed dark:text-slate-400">Matched YouTube tutorials for
                        every weak topic. No
                        more sifting through hours of irrelevant content.</p>
                </div>

                <!-- Card: BYOK Privacy -->
                <div
                    class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 reveal delay-200 dark:bg-slate-900 dark:border-slate-800">
                    <div
                        class="w-12 h-12 bg-violet-50 rounded-2xl flex items-center justify-center text-violet-500 mb-5 border border-violet-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 dark:text-white">BYOK Privacy Model</h3>
                    <p class="text-slate-500 text-sm leading-relaxed dark:text-slate-400">Bring Your Own Key. Your API
                        key, your data. Zero
                        vendor lock-in, total privacy.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- ===== HOW IT WORKS ===== -->
    <section id="how-it-works" class="py-24 bg-white border-t border-slate-100 dark:bg-slate-900 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20 reveal">
                <p class="text-brand-600 font-semibold text-xs uppercase tracking-widest mb-3">The Process</p>
                <h2 class="text-4xl font-bold text-slate-900 dark:text-white">Three Steps. Infinite Clarity.</h2>
            </div>

            <div class="relative grid md:grid-cols-3 gap-12">
                <!-- Connecting dashed line (desktop) -->
                <div class="hidden md:block absolute border-t-2 border-dashed border-slate-200 pointer-events-none dark:border-slate-700"
                    style="top:2.5rem; left:22%; right:22%;"></div>

                <?php
                $steps = [
                    [
                        'num' => '01',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>',
                        'title' => 'Upload Your Data',
                        'desc' => 'Snap a photo of your graded test or input marks manually in under 10 seconds. Our OCR handles the rest.',
                        'active' => false,
                        'delay' => '',
                    ],
                    [
                        'num' => '02',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>',
                        'title' => 'AI Diagnosis',
                        'desc' => 'Our engine bypasses the final grade to isolate the exact sub-topics where you lost points.',
                        'active' => true,
                        'delay' => 'delay-100',
                    ],
                    [
                        'num' => '03',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
                        'title' => 'Execute Your Plan',
                        'desc' => 'Receive a customized cheat sheet and curated YouTube tutorials targeted precisely at your weak spots.',
                        'active' => false,
                        'delay' => 'delay-200',
                    ],
                ];
                foreach ($steps as $step): ?>
                    <div class="text-center space-y-5 relative reveal <?= $step['delay'] ?>">
                        <div class="relative inline-block">
                            <div
                                class="w-20 h-20 mx-auto <?= $step['active'] ? 'bg-brand-500 shadow-xl shadow-brand-500/30' : 'bg-white border-2 border-slate-200' ?> rounded-2xl flex items-center justify-center <?= $step['active'] ? 'text-white' : 'text-slate-400' ?>">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24"><?= $step['icon'] ?></svg>
                            </div>
                            <span
                                class="absolute -top-2 -right-2 w-6 h-6 rounded-full text-[10px] font-bold flex items-center justify-center <?= $step['active'] ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-500' ?>"><?= $step['num'] ?></span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white"><?= $step['title'] ?></h3>
                        <p class="text-slate-500 text-sm leading-relaxed max-w-xs mx-auto dark:text-slate-400">
                            <?= $step['desc'] ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ===== TESTIMONIALS ===== -->
    <section id="testimonials"
        class="py-24 bg-slate-900 text-white overflow-hidden dark:bg-slate-800 dark:text-white dark:text-slate-900">
        <div class="max-w-7xl mx-auto px-6 mb-16 text-center reveal">
            <p class="text-brand-400 font-semibold text-xs uppercase tracking-widest mb-3">Wall of Love</p>
            <h2 class="text-4xl font-bold mb-4">Loved by Students</h2>
            <p class="text-slate-400 dark:text-slate-500">Join early adopters who are hacking their study time.</p>
        </div>

        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-6">
            <?php
            $testimonials = [
                [
                    'text' => "I used to spend 3 hours re-reading physics chapters before tests. Flux told me to just study Kinematics formulas. I got an A- and saved 2 hours of my life.",
                    'name' => 'Malvyn T.',
                    'role' => 'Year 1, Academy',
                    'initial' => 'M',
                    'grad' => 'from-blue-500 to-blue-700',
                    'delay' => '',
                ],
                [
                    'text' => "The BYOK model is genius. I control my own API key, so I know my data is private. Plus the dashboard looks incredible.",
                    'name' => 'Jason R.',
                    'role' => 'Computer Science',
                    'initial' => 'J',
                    'grad' => 'from-emerald-500 to-teal-600',
                    'delay' => 'delay-100',
                ],
                [
                    'text' => "The YouTube integration is flawless. It gave me the exact 8-minute video I needed to understand Matrix transformations before finals.",
                    'name' => 'Sarah K.',
                    'role' => 'High School Junior',
                    'initial' => 'S',
                    'grad' => 'from-pink-500 to-rose-600',
                    'delay' => 'delay-200',
                ],
            ];
            foreach ($testimonials as $t): ?>
                <div
                    class="bg-slate-800/50 p-7 rounded-2xl border border-slate-700/50 hover:border-brand-500/30 transition-all duration-300 hover:-translate-y-1 reveal <?= $t['delay'] ?> relative overflow-hidden dark:bg-slate-800/80 dark:border-slate-700">
                    <div class="quote-mark absolute -top-2 left-2 leading-none">"</div>
                    <div class="flex gap-0.5 mb-5 text-brand-400 relative"><?= renderStars() ?></div>
                    <p class="text-slate-300 text-sm mb-6 leading-relaxed relative"><?= $t['text'] ?></p>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-9 h-9 rounded-full bg-gradient-to-br <?= $t['grad'] ?> flex items-center justify-center font-bold text-xs text-white shadow-md dark:text-slate-900">
                            <?= $t['initial'] ?>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-white dark:text-slate-900"><?= $t['name'] ?></div>
                            <div class="text-xs text-slate-500 dark:text-slate-400"><?= $t['role'] ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ===== FAQ ===== -->
    <section id="faq" class="py-24 bg-white border-t border-slate-100 dark:bg-slate-900 dark:border-slate-800">
        <div class="max-w-2xl mx-auto px-6">
            <div class="text-center mb-16 reveal">
                <p class="text-brand-600 font-semibold text-xs uppercase tracking-widest mb-3">Got Questions?</p>
                <h2 class="text-4xl font-bold text-slate-900 dark:text-white">Frequently Asked</h2>
            </div>

            <?php
            $faqs = [
                [
                    'q' => 'What does BYOK mean?',
                    'a' => 'BYOK stands for Bring Your Own Key. Instead of routing data through shared servers, you provide your own OpenAI-compatible API key. Your academic data is processed directly between you and the AI provider — we never store or see it.',
                ],
                [
                    'q' => 'Which subjects does Flux support?',
                    'a' => 'Flux currently supports 12+ subjects including Mathematics, Physics, Chemistry, Biology, English, Computer Science, History, and Economics. If your subject isn\'t listed, you can still input custom topics manually.',
                ],
                [
                    'q' => 'Is Flux really free?',
                    'a' => 'Yes. The core analysis feature is completely free. You only need your own API key from OpenAI (which has a free tier). You pay your AI provider directly for API usage — we don\'t charge for Flux itself.',
                ],
                [
                    'q' => 'How accurate is the OCR scan?',
                    'a' => 'Our OCR engine achieves 95%+ accuracy on clearly photographed test papers. It handles printed text, handwritten scores, and teacher annotations. You can always review and correct extracted data before the analysis runs.',
                ],
            ];
            foreach ($faqs as $i => $faq): ?>
                <div
                    class="border-b border-slate-100 reveal <?= $i > 0 ? 'delay-' . min($i * 100, 300) : '' ?> dark:border-slate-800">
                    <button class="faq-btn w-full flex items-center justify-between py-5 text-left gap-4 group"
                        aria-expanded="false">
                        <span
                            class="font-semibold text-slate-900 group-hover:text-brand-600 transition-colors text-sm dark:text-white"><?= $faq['q'] ?></span>
                        <span
                            class="faq-icon shrink-0 w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-brand-50 group-hover:text-brand-600 transition-colors dark:text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </span>
                    </button>
                    <div class="faq-answer">
                        <p class="text-slate-500 text-sm leading-relaxed pb-5 dark:text-slate-400"><?= $faq['a'] ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ===== CTA ===== -->
    <section class="py-28 relative overflow-hidden bg-brand-500 text-white text-center dark:text-slate-900">
        <!-- Decorative shapes -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-brand-400/30 rounded-full blur-3xl pointer-events-none">
        </div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-brand-700/40 rounded-full blur-3xl pointer-events-none">
        </div>
        <div class="absolute top-10 right-1/4 w-24 h-24 border-2 border-white/10 rounded-full pointer-events-none">
        </div>
        <div class="absolute bottom-10 left-1/4 w-14 h-14 border-2 border-white/10 rounded-full pointer-events-none">
        </div>
        <div
            class="absolute top-1/2 left-10 w-8 h-8 border-2 border-white/10 rounded-full -translate-y-1/2 pointer-events-none">
        </div>

        <div class="max-w-3xl mx-auto px-6 relative z-10 reveal">
            <p class="text-brand-100 font-semibold text-xs uppercase tracking-widest mb-4">Start Today</p>
            <h2 class="text-5xl font-extrabold mb-6 leading-tight">Ready to Stop Guessing?</h2>
            <p class="text-brand-100 text-lg mb-10 leading-relaxed">Create your account, connect your key, and generate
                your first strategy in under 2 minutes.</p>
            <a href="register.php"
                class="inline-block px-10 py-4 bg-white text-brand-600 font-bold rounded-full hover:bg-slate-50 shadow-2xl shadow-brand-800/30 transition-all hover:-translate-y-1 active:scale-95 text-base dark:bg-slate-900 dark:hover:bg-slate-800">
                Create Free Account
            </a>
            <p class="mt-5 text-brand-200 text-sm">No credit card. No commitment. Just results.</p>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="bg-slate-950 pt-16 pb-10 border-t border-slate-800 text-slate-400 dark:text-slate-500">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-10 mb-12">
            <!-- Brand -->
            <div class="col-span-2 md:col-span-1">
                <div class="flex items-center gap-2 mb-4">
                    <div
                        class="w-7 h-7 rounded-lg bg-brand-500 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-brand-500/20 dark:text-slate-900">
                        F</div>
                    <span class="text-lg font-bold text-white dark:text-slate-900">Flux</span>
                </div>
                <p class="text-sm leading-relaxed mb-5">Built for academic excellence. Powered by AI.</p>
                <p class="text-xs text-slate-600 dark:text-slate-400">© <?= date('Y') ?> Flux. All rights reserved.</p>
            </div>
            <!-- Product -->
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm dark:text-slate-900">Product</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#features" class="hover:text-brand-400 transition-colors">Features</a></li>
                    <li><a href="#how-it-works" class="hover:text-brand-400 transition-colors">How it Works</a></li>
                    <li><a href="#" class="hover:text-brand-400 transition-colors">BYOK Guide</a></li>
                    <li><a href="#" class="hover:text-brand-400 transition-colors">Changelog</a></li>
                </ul>
            </div>
            <!-- Legal -->
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm dark:text-slate-900">Legal</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="hover:text-brand-400 transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-brand-400 transition-colors">Terms of Service</a></li>
                    <li><a href="#" class="hover:text-brand-400 transition-colors">Cookie Policy</a></li>
                </ul>
            </div>
            <!-- Connect -->
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm dark:text-slate-900">Connect</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="hover:text-brand-400 transition-colors">Community</a></li>
                    <li><a href="#" class="hover:text-brand-400 transition-colors">Contact Us</a></li>
                    <li><a href="#faq" class="hover:text-brand-400 transition-colors">FAQ</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // ── Scroll reveal ──────────────────────────────────────────
            const reveals = document.querySelectorAll('.reveal');
            const revealOnScroll = () => {
                const threshold = window.innerHeight - 90;
                reveals.forEach(el => {
                    if (el.getBoundingClientRect().top < threshold) el.classList.add('active');
                });
            };
            window.addEventListener('scroll', revealOnScroll, { passive: true });
            revealOnScroll();

            // ── Navbar elevation on scroll ─────────────────────────────
            const navbar = document.getElementById('navbar');
            window.addEventListener('scroll', () => {
                navbar.classList.toggle('shadow-sm', window.scrollY > 50);
            }, { passive: true });


            // ── Theme toggle ───────────────────────────────────────────
            const themeBtn = document.getElementById('themeToggleBtn');
            const htmlEl = document.documentElement;

            // Check local storage or system preference
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                htmlEl.classList.add('dark');
            } else {
                htmlEl.classList.remove('dark');
            }

            themeBtn.addEventListener('click', () => {
                htmlEl.classList.toggle('dark');
                if (htmlEl.classList.contains('dark')) {
                    localStorage.theme = 'dark';
                } else {
                    localStorage.theme = 'light';
                }
            });

            // ── Mobile menu toggle ─────────────────────────────────────
            const menuBtn = document.getElementById('menuBtn');
            const mobileMenu = document.getElementById('mobile-menu');
            const iconHamburger = document.getElementById('iconHamburger');
            const iconClose = document.getElementById('iconClose');

            menuBtn.addEventListener('click', () => {
                const open = mobileMenu.classList.toggle('open');
                menuBtn.setAttribute('aria-expanded', open);
                iconHamburger.classList.toggle('hidden', open);
                iconClose.classList.toggle('hidden', !open);
            });

            document.querySelectorAll('.mobile-link').forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.remove('open');
                    menuBtn.setAttribute('aria-expanded', 'false');
                    iconHamburger.classList.remove('hidden');
                    iconClose.classList.add('hidden');
                });
            });

            // ── FAQ accordion ──────────────────────────────────────────
            document.querySelectorAll('.faq-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const answer = btn.nextElementSibling;
                    const isOpen = answer.classList.contains('open');

                    // Close all
                    document.querySelectorAll('.faq-answer').forEach(a => a.classList.remove('open'));
                    document.querySelectorAll('.faq-btn').forEach(b => b.setAttribute('aria-expanded', 'false'));

                    // Open if it was closed
                    if (!isOpen) {
                        answer.classList.add('open');
                        btn.setAttribute('aria-expanded', 'true');
                    }
                });
            });

            // ── Particle Engine ────────────────────────────────────────
            class Particle {
                constructor(canvas) {
                    this.canvas = canvas;
                    this.ctx = canvas.getContext('2d');
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.vx = (Math.random() - 0.5) * 0.5;
                    this.vy = (Math.random() - 0.5) * 0.5;
                    this.radius = Math.random() * 1.5 + 1;
                }

                update() {
                    this.x += this.vx;
                    this.y += this.vy;

                    if (this.x < 0 || this.x > this.canvas.width) this.vx *= -1;
                    if (this.y < 0 || this.y > this.canvas.height) this.vy *= -1;
                }

                draw() {
                    this.ctx.beginPath();
                    this.ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                    this.ctx.fillStyle = document.documentElement.classList.contains('dark') ? '#10b981' : '#64748b';
                    this.ctx.fill();
                }
            }

            const canvas = document.getElementById('particleCanvas');
            const ctx = canvas.getContext('2d');
            let particles = [];
            const particleCount = Math.min(Math.floor(window.innerWidth / 15), 100);

            // Mouse tracking
            const mouse = { x: null, y: null, radius: 150 };
            window.addEventListener('mousemove', (e) => {
                mouse.x = e.x;
                mouse.y = e.y;
            });

            // Reset mouse pos on leave
            window.addEventListener('mouseout', () => {
                mouse.x = null;
                mouse.y = null;
            });

            const resize = () => {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
                initParticles();
            };

            const initParticles = () => {
                particles = [];
                for (let i = 0; i < particleCount; i++) {
                    particles.push(new Particle(canvas));
                }
            };

            const animate = () => {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                particles.forEach((p, index) => {
                    p.update();
                    p.draw();

                    // Connect to mouse
                    if (mouse.x && mouse.y) {
                        const dx = p.x - mouse.x;
                        const dy = p.y - mouse.y;
                        const dist = Math.sqrt(dx * dx + dy * dy);
                        if (dist < mouse.radius) {
                            ctx.beginPath();
                            ctx.strokeStyle = document.documentElement.classList.contains('dark') ? 
                                `rgba(16, 185, 129, ${1 - dist/mouse.radius})` : 
                                `rgba(148, 163, 184, ${1 - dist/mouse.radius})`;
                            ctx.lineWidth = 1;
                            ctx.moveTo(p.x, p.y);
                            ctx.lineTo(mouse.x, mouse.y);
                            ctx.stroke();
                        }
                    }

                    // Connect to other particles
                    for (let j = index + 1; j < particles.length; j++) {
                        const p2 = particles[j];
                        const dx = p.x - p2.x;
                        const dy = p.y - p2.y;
                        const dist = Math.sqrt(dx * dx + dy * dy);

                        if (dist < 120) {
                            ctx.beginPath();
                            ctx.strokeStyle = document.documentElement.classList.contains('dark') ?
                                `rgba(16, 185, 129, ${1 - dist / 120})` :
                                `rgba(148, 163, 184, ${1 - dist / 120})`;
                            ctx.lineWidth = 0.5;
                            ctx.moveTo(p.x, p.y);
                            ctx.lineTo(p2.x, p2.y);
                            ctx.stroke();
                        }
                    }
                });
                requestAnimationFrame(animate);
            };

            window.addEventListener('resize', resize);
            resize();
            animate();
        });
    </script>
</body>

</html>