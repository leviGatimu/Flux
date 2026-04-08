<?php
session_start();

// 1. THE BOUNCER
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. DATABASE CONNECTION
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'flux_app';
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// 3. FETCH USER DATA
$user_sql = "SELECT full_name, target_score, grading_system, has_seen_tutorial FROM users WHERE id = $user_id";
$user_result = $conn->query($user_sql);
$user_data = $user_result->fetch_assoc();

$first_name = explode(' ', trim($user_data['full_name']))[0];
$show_tutorial = $user_data['has_seen_tutorial'] == 0 ? 'true' : 'false';

// 4. FETCH SUBJECTS
$subjects_sql = "SELECT id, subject_name FROM subjects WHERE user_id = $user_id ORDER BY created_at DESC";
$subjects_result = $conn->query($subjects_sql);
$has_subjects = $subjects_result->num_rows > 0;

// 5. CALCULATE ACTUAL OVERALL SCORE
// This pulls the real average of every grade you've ever logged across all subjects
$score_sql = "
    SELECT AVG(a.score) as avg_score 
    FROM assessments a
    JOIN subjects s ON a.subject_id = s.id
    WHERE s.user_id = $user_id
";
$score_result = $conn->query($score_sql);
$score_data = $score_result->fetch_assoc();

if ($score_data['avg_score'] !== null) {
    // Format the real score based on what they chose in onboarding
    if ($user_data['grading_system'] == 'gpa_4') {
        $current_score = number_format($score_data['avg_score'], 2); // e.g., 3.85
    } else {
        $current_score = round($score_data['avg_score']) . '%'; // e.g., 92%
    }
} else {
    // If they have subjects but haven't scanned any tests yet
    $current_score = "--";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Flux</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { brand: { 50: '#ecfdf5', 100: '#d1fae5', 500: '#10b981', 600: '#059669', 900: '#064e3b' } }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://unpkg.com/intro.js/minified/introjs.min.css">
    <style>
        .introjs-tooltip {
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            font-family: 'Inter', sans-serif;
        }

        .introjs-button {
            border-radius: 6px;
            text-shadow: none;
            background-image: none;
        }

        .introjs-nextbutton,
        .introjs-donebutton {
            background-color: #10b981 !important;
            color: white !important;
            border: none;
            text-shadow: none;
        }

        .introjs-nextbutton:hover,
        .introjs-donebutton:hover {
            background-color: #059669 !important;
        }

        .introjs-skipbutton {
            color: #64748b;
            font-weight: 500;
        }

        /* Modal Transition Styles */
        #subject-modal {
            transition: opacity 0.3s ease;
        }

        #subject-modal-panel {
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .modal-hidden {
            opacity: 0;
            pointer-events: none;
        }

        .modal-panel-hidden {
            transform: scale(0.95);
            opacity: 0;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased h-screen flex overflow-hidden">

    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between hidden md:flex z-20">
        <div>
            <div class="h-16 flex items-center px-6 border-b border-slate-100">
                <div
                    class="w-8 h-8 rounded-lg bg-brand-500 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-brand-500/30">
                    F</div>
                <span class="ml-3 text-xl font-bold tracking-tight text-slate-900">Flux</span>
            </div>

            <nav class="p-4 space-y-1">
                <a href="dashboard.php"
                    class="flex items-center gap-3 px-3 py-2 bg-brand-50 text-brand-600 rounded-lg font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    Overview
                </a>
                <a href="subjects.php"
                    class="flex items-center gap-3 px-3 py-2 text-slate-500 hover:bg-slate-50 hover:text-slate-900 rounded-lg font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    Subjects
                </a>
            </nav>
        </div>
        <div class="p-4 border-t border-slate-100">
            <a href="settings.php"
                class="flex items-center gap-3 px-3 py-2 text-slate-500 hover:bg-slate-50 hover:text-slate-900 rounded-lg font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Settings
            </a>
            <a href="logout.php"
                class="flex items-center gap-3 px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg font-medium transition-colors mt-1 text-sm">
                Log Out
            </a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-y-auto bg-slate-50 relative z-10">

        <header
            class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 z-10 sticky top-0">
            <h1 class="text-xl font-bold text-slate-800">Overview</h1>
            <div class="flex items-center gap-4">
                <a href="scan.php" data-step="2"
                    data-intro="This is the core engine. Click here anytime to snap a photo of a test or input your marks manually."
                    class="bg-brand-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-brand-600 transition-colors shadow-sm flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Scan Assessment
                </a>
                <div data-step="3"
                    data-intro="Access your profile settings, update your target score, or manage your Google API key right here."
                    class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-600">
                    <?php echo substr($first_name, 0, 1); ?>
                </div>
            </div>
        </header>

        <div class="p-8 max-w-6xl mx-auto w-full">

            <div class="mb-8" data-step="1"
                data-intro="Welcome to Flux! Let's take a quick 15-second tour of your new AI academic command center.">
                <h2 class="text-2xl font-bold text-slate-900">Welcome back,
                    <?php echo htmlspecialchars($first_name); ?>!
                </h2>
                <p class="text-slate-500">Here is your academic trajectory today.</p>
            </div>

            <?php if (!$has_subjects): ?>

                <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-sm">
                    <div
                        class="w-20 h-20 bg-brand-50 text-brand-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Let's build your dashboard</h3>
                    <p class="text-slate-500 max-w-md mx-auto mb-8">You haven't added any subjects yet. Add your classes to
                        start tracking grades and generating AI study strategies.</p>
                    <button onclick="openSubjectModal()"
                        class="bg-slate-900 text-white px-6 py-3 rounded-xl font-medium hover:bg-slate-800 transition-colors shadow-lg active:scale-95">
                        + Add First Subject
                    </button>
                </div>

            <?php else: ?>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-8">

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white p-6 border border-slate-200 rounded-2xl shadow-sm">
                                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">Overall Score
                                </h3>
                                <div class="flex items-end gap-3">
                                    <span class="text-4xl font-bold text-slate-900"><?php echo $current_score; ?></span>
                                </div>
                            </div>
                            <div class="bg-white p-6 border border-slate-200 rounded-2xl shadow-sm">
                                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">Target Score
                                </h3>
                                <div class="flex items-end gap-3">
                                    <span
                                        class="text-4xl font-bold text-slate-400"><?php echo htmlspecialchars($user_data['target_score'] ?? '--'); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                                <h3 class="font-bold text-slate-900">Active Subjects</h3>
                                <button onclick="openSubjectModal()"
                                    class="text-sm text-brand-600 font-medium hover:text-brand-700 hover:underline">
                                    + Add Subject
                                </button>
                            </div>
                            <div class="divide-y divide-slate-100">
                                <?php while ($subject = $subjects_result->fetch_assoc()): ?>
                                    <div
                                        class="p-6 hover:bg-slate-50 transition-colors flex items-center justify-between cursor-pointer group">
                                        <div>
                                            <h4 class="font-bold text-slate-900 group-hover:text-brand-600 transition-colors">
                                                <?php echo htmlspecialchars($subject['subject_name']); ?>
                                            </h4>
                                            <p class="text-xs text-slate-400 mt-1">Ready for data</p>
                                        </div>
                                        <svg class="w-5 h-5 text-slate-300 group-hover:text-brand-500 transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>

                    </div>
                </div>

            <?php endif; ?>

        </div>
    </main>

    <div id="subject-modal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm modal-hidden">
        <div id="subject-modal-panel"
            class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden modal-panel-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-slate-900">Add New Subject</h3>
                <button onclick="closeSubjectModal()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form action="process_subject.php" method="POST" class="p-6">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Subject Name</label>
                    <input type="text" name="subject_name" required placeholder="e.g. Advanced Math, System Design"
                        class="w-full px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/10 outline-none transition-all text-slate-900">
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeSubjectModal()"
                        class="w-1/3 py-3 rounded-lg font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Cancel</button>
                    <button type="submit"
                        class="w-2/3 py-3 rounded-lg font-medium text-white bg-brand-500 hover:bg-brand-600 shadow-lg shadow-brand-500/30 transition-all active:scale-95">Save
                        Subject</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/intro.js/minified/introjs.min.js"></script>
    <script>
        // Modal Logic
        const modal = document.getElementById('subject-modal');
        const modalPanel = document.getElementById('subject-modal-panel');

        function openSubjectModal() {
            modal.classList.remove('modal-hidden');
            modalPanel.classList.remove('modal-panel-hidden');
            document.querySelector('input[name="subject_name"]').focus();
        }

        function closeSubjectModal() {
            modal.classList.add('modal-hidden');
            modalPanel.classList.add('modal-panel-hidden');
        }

        // Close modal if user clicks outside the panel
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                closeSubjectModal();
            }
        });

        // Tutorial Logic
        const shouldShowTutorial = <?php echo $show_tutorial; ?>;
        if (shouldShowTutorial) {
            const tour = introJs();
            tour.setOptions({
                showProgress: true, showBullets: false, exitOnOverlayClick: false, doneLabel: 'Got it!'
            });
            const markTutorialComplete = () => { fetch('complete_tutorial.php', { method: 'POST' }); };
            tour.onexit(markTutorialComplete);
            tour.oncomplete(markTutorialComplete);
            setTimeout(() => { tour.start(); }, 500);
        }
    </script>
</body>

</html>