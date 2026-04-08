<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Flux Intelligence</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7', 400: '#34d399',
                            500: '#10b981', 600: '#059669', 700: '#047857', 800: '#065f46', 900: '#064e3b',
                        }
                    },
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                }
            }
        }
    </script>
</head>

<body class="bg-white text-slate-800 antialiased min-h-screen flex selection:bg-brand-100 selection:text-brand-900">

    <!-- Left Panel: Brand / Marketing -->
    <div class="hidden lg:flex lg:w-5/12 bg-slate-900 relative overflow-hidden flex-col justify-between p-12">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:20px_20px]"></div>
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-brand-500 rounded-full blur-[150px] opacity-20 -translate-y-1/2 translate-x-1/3"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-16">
                <div class="w-10 h-10 rounded-xl bg-brand-500 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-brand-500/30">F</div>
                <span class="font-bold text-2xl tracking-tight text-white">Flux</span>
            </div>
            
            <h1 class="text-4xl lg:text-5xl font-bold leading-tight text-white mb-6">
                Welcome back to<br>
                <span class="text-brand-400">Intelligence.</span>
            </h1>
            <p class="text-slate-400 text-lg leading-relaxed max-w-sm">
                Log in to resume your strategic academic journey and access your AI-optimized workspace.
            </p>
        </div>

        <div class="relative z-10 bg-white/5 border border-white/10 p-6 rounded-2xl backdrop-blur-sm">
            <p class="text-slate-300 text-sm leading-relaxed mb-4">"Returning to Flux is a seamless experience. The workspace remembers exactly where I left off in my curriculum strategy, making every study session hyper-focused."</p>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-white text-xs font-bold">A</div>
                <div>
                    <div class="text-white text-sm font-semibold">Alex R.</div>
                    <div class="text-slate-500 text-xs">University Student</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Panel: Login Form -->
    <div class="w-full lg:w-7/12 flex flex-col justify-center px-8 sm:px-16 lg:px-24 py-12 relative overflow-y-auto">
        <div class="w-full max-w-md mx-auto relative">
            
            <div class="mb-10">
                <h2 class="text-3xl font-bold text-slate-900 mb-2">Log in</h2>
                <p class="text-slate-500">Pick up where you left off.</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-lg text-sm text-red-600 font-medium">
                    <?php 
                        if ($_GET['error'] == 'invalid') echo "Invalid email or password. Please try again.";
                        else echo "An error occurred. Please try again.";
                    ?>
                </div>
            <?php endif; ?>

            <form action="login_process.php" method="POST" class="space-y-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                        <input type="email" name="email" required placeholder="you@school.edu"
                            class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 outline-none transition-all text-slate-900 placeholder:text-slate-400">
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-sm font-medium text-slate-700">Password</label>
                            <a href="#" class="text-xs font-semibold text-brand-600 hover:text-brand-700">Forgot password?</a>
                        </div>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 outline-none transition-all text-slate-900 placeholder:text-slate-400">
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-brand-500 focus:ring-brand-500 border-slate-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-slate-600">Remember me for 30 days</label>
                </div>

                <button type="submit"
                    class="w-full bg-slate-900 text-white font-semibold py-3.5 rounded-lg hover:bg-slate-800 transition-colors shadow-lg shadow-slate-900/20 active:scale-[0.98]">
                    Log in
                </button>

                <div class="relative flex py-4 items-center">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="flex-shrink-0 mx-4 text-xs font-medium text-slate-400">OR CONTINUE WITH</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                <div class="mb-6">
                    <div id="g_id_onload"
                        data-client_id="YOUR_GOOGLE_CLIENT_ID_HERE.apps.googleusercontent.com"
                        data-context="signin" data-ux_mode="popup"
                        data-login_uri="http://localhost/flux/google_auth.php" data-auto_prompt="false">
                    </div>
                    <div class="g_id_signin flex justify-center" data-type="standard" data-shape="rectangular" data-theme="outline"
                        data-text="signin_with" data-size="large" data-logo_alignment="center" data-width="400">
                    </div>
                </div>

                <p class="text-center text-sm text-slate-500">
                    Don't have an account? <a href="register.php" class="text-brand-600 font-medium hover:text-brand-700">Join Flux</a>
                </p>
            </form>
        </div>
    </div>

</body>
</html>
