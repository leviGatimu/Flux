<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Flux | Create Your Account</title>
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
    <style>
        /* Smooth, subtle transitions for the form steps */
        .step-transition { transition: opacity 0.4s ease, transform 0.4s ease; }
        .step-hidden { opacity: 0; transform: translateX(20px); pointer-events: none; position: absolute; visibility: hidden; }
    </style>
</head>
<body class="bg-white text-slate-800 antialiased min-h-screen flex selection:bg-brand-100 selection:text-brand-900">

    <div class="hidden lg:flex lg:w-5/12 bg-slate-900 relative overflow-hidden flex-col justify-between p-12">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:20px_20px]"></div>
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-brand-500 rounded-full blur-[150px] opacity-20 -translate-y-1/2 translate-x-1/3"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-16">
                <div class="w-10 h-10 rounded-xl bg-brand-500 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-brand-500/30">F</div>
                <span class="font-bold text-2xl tracking-tight text-white">Flux</span>
            </div>
            
            <h1 class="text-4xl lg:text-5xl font-bold leading-tight text-white mb-6">
                Stop guessing.<br>
                <span class="text-brand-400">Start strategizing.</span>
            </h1>
            <p class="text-slate-400 text-lg leading-relaxed max-w-sm">
                Join the platform that turns your raw academic data into actionable, AI-driven study plans.
            </p>
        </div>

        <div class="relative z-10 bg-white/5 border border-white/10 p-6 rounded-2xl backdrop-blur-sm">
            <p class="text-slate-300 text-sm leading-relaxed mb-4">"Flux told me exactly what I was doing wrong in Advanced Math. I stopped wasting time studying what I already knew and focused strictly on the AI's recommendations. My grades jumped instantly."</p>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-brand-500 flex items-center justify-center text-white text-xs font-bold">M</div>
                <div>
                    <div class="text-white text-sm font-semibold">Malvyn T.</div>
                    <div class="text-slate-500 text-xs">Coding Academy Student</div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full lg:w-7/12 flex flex-col justify-center px-8 sm:px-16 lg:px-24 py-12 relative overflow-y-auto">
        
        <div class="w-full max-w-md mx-auto relative">
            
            <div class="mb-10">
                <div class="flex items-center justify-between text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">
                    <span>Step <span id="step-indicator">1</span> of 3</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5">
                    <div id="progress-bar" class="bg-brand-500 h-1.5 rounded-full transition-all duration-500 ease-out" style="width: 33%"></div>
                </div>
            </div>

            <form action="auth_process.php" method="POST" id="register-form" class="relative min-h-[400px]">
                
                <div id="step-1" class="step-transition w-full">
                    <h2 class="text-3xl font-bold text-slate-900 mb-2">Create your account</h2>
                    <p class="text-slate-500 mb-8">Let's get your workspace set up.</p>

                    <div class="mb-6">
                        <div id="g_id_onload"
                            data-client_id="YOUR_GOOGLE_CLIENT_ID_HERE.apps.googleusercontent.com"
                            data-context="signup" data-ux_mode="popup"
                            data-login_uri="http://localhost/flux/google_auth.php" data-auto_prompt="false">
                        </div>
                        <div class="g_id_signin flex justify-center" data-type="standard" data-shape="rectangular" data-theme="outline"
                            data-text="continue_with" data-size="large" data-logo_alignment="center" data-width="400">
                        </div>
                    </div>

                    <div class="relative flex py-4 items-center mb-4">
                        <div class="flex-grow border-t border-slate-200"></div>
                        <span class="flex-shrink-0 mx-4 text-xs font-medium text-slate-400">OR REGISTER WITH EMAIL</span>
                        <div class="flex-grow border-t border-slate-200"></div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Full Name</label>
                            <input type="text" name="full_name" required placeholder="Levi"
                                class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 outline-none transition-all text-slate-900 placeholder:text-slate-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                            <input type="email" name="email" required placeholder="you@school.edu"
                                class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 outline-none transition-all text-slate-900 placeholder:text-slate-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                            <input type="password" name="password" required placeholder="••••••••"
                                class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 outline-none transition-all text-slate-900 placeholder:text-slate-400">
                        </div>
                    </div>

                    <button type="button" onclick="nextStep(2)"
                        class="w-full mt-8 bg-slate-900 text-white font-semibold py-3.5 rounded-lg hover:bg-slate-800 transition-colors shadow-lg shadow-slate-900/20 active:scale-[0.98]">
                        Continue
                    </button>
                    <p class="text-center text-sm text-slate-500 mt-6">Already have an account? <a href="login.php" class="text-brand-600 font-medium hover:text-brand-700">Log in</a></p>
                </div>

                <div id="step-2" class="step-transition step-hidden w-full top-0">
                    <h2 class="text-3xl font-bold text-slate-900 mb-2">Academic Profile</h2>
                    <p class="text-slate-500 mb-8">How is your performance measured?</p>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Education Level</label>
                            <select name="education_level"
                                class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 outline-none transition-all text-slate-900 cursor-pointer">
                                <option value="high_school">High School</option>
                                <option value="university_yr1">University (Year 1-2)</option>
                                <option value="university_yr3">University (Year 3-4)</option>
                                <option value="academy" selected>Coding Academy / Bootcamp</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Grading System</label>
                                <select name="grading_system"
                                    class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 outline-none transition-all text-slate-900 cursor-pointer">
                                    <option value="percentage">Percentage (%)</option>
                                    <option value="gpa_4">GPA (4.0 Scale)</option>
                                    <option value="letter">Letter Grades</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Target Score</label>
                                <input type="text" name="target_score" placeholder="e.g., 90% or 3.8"
                                    class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 outline-none transition-all text-slate-900 placeholder:text-slate-400">
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-10">
                        <button type="button" onclick="nextStep(1)"
                            class="w-1/3 bg-white border border-slate-200 text-slate-700 font-semibold py-3.5 rounded-lg hover:bg-slate-50 transition-colors">
                            Back
                        </button>
                        <button type="button" onclick="nextStep(3)"
                            class="w-2/3 bg-slate-900 text-white font-semibold py-3.5 rounded-lg hover:bg-slate-800 transition-colors shadow-lg shadow-slate-900/20 active:scale-[0.98]">
                            Continue
                        </button>
                    </div>
                </div>

                <div id="step-3" class="step-transition step-hidden w-full top-0">
                    <h2 class="text-3xl font-bold text-slate-900 mb-2">Connect the AI</h2>
                    <p class="text-slate-500 mb-6">Flux operates on a B.Y.O.K. model to keep your data private and costs at zero.</p>

                    <div class="bg-brand-50 border border-brand-100 rounded-xl p-5 mb-6">
                        <ol class="text-sm text-brand-900 list-decimal pl-4 space-y-2 font-medium">
                            <li>Visit <a href="https://aistudio.google.com/app/apikey" target="_blank" class="text-brand-600 underline underline-offset-2 hover:text-brand-800">Google AI Studio</a>.</li>
                            <li>Click "Create API Key".</li>
                            <li>Paste your secure token below.</li>
                        </ol>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Google Gemini API Key</label>
                        <input type="password" name="api_key" required placeholder="AIzaSy..."
                            class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 outline-none font-mono text-sm transition-all text-slate-900 placeholder:text-slate-400">
                        <p class="text-xs text-slate-500 mt-2 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-brand-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" /></svg>
                            Key is encrypted prior to database insertion.
                        </p>
                    </div>

                    <div class="flex gap-3 mt-10">
                        <button type="button" onclick="nextStep(2)"
                            class="w-1/3 bg-white border border-slate-200 text-slate-700 font-semibold py-3.5 rounded-lg hover:bg-slate-50 transition-colors">
                            Back
                        </button>
                        <button type="submit"
                            class="w-2/3 bg-brand-500 text-white font-bold py-3.5 rounded-lg hover:bg-brand-600 transition-colors shadow-lg shadow-brand-500/30 active:scale-[0.98]">
                            Complete Setup
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function nextStep(targetStep) {
            // Find current active step
            const currentStepEl = document.querySelector('.step-transition:not(.step-hidden)');
            const nextStepEl = document.getElementById('step-' + targetStep);

            if (currentStepEl) {
                // If moving forward, validate inputs (Basic HTML5 validation)
                if (targetStep > parseInt(currentStepEl.id.split('-')[1])) {
                    let isValid = true;
                    currentStepEl.querySelectorAll('input[required]').forEach(input => {
                        if (!input.value) {
                            input.reportValidity();
                            isValid = false;
                        }
                    });
                    if (!isValid) return; // Stop if form is invalid
                }
                
                // Hide current step
                currentStepEl.classList.add('step-hidden');
                setTimeout(() => {
                    currentStepEl.style.position = 'absolute';
                }, 400); // Wait for transition
            }

            // Show next step
            setTimeout(() => {
                nextStepEl.style.position = 'relative';
                nextStepEl.classList.remove('step-hidden');
                
                // Update Progress Bar
                document.getElementById('step-indicator').innerText = targetStep;
                let progress = (targetStep / 3) * 100;
                document.getElementById('progress-bar').style.width = progress + '%';
            }, 50);
        }

        // Handle Google Auth Redirection
        window.onload = function () {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('step') === '2') {
                // Remove required flags from Step 1 since they used Google
                document.getElementById('step-1').querySelectorAll('input').forEach(el => el.required = false);
                nextStep(2);
            }
        };
    </script>
</body>
</html>