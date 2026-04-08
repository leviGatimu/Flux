<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'flux_app';
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
$user_id = $_SESSION['user_id'];

// Fetch user's subjects for the dropdown
$subjects_sql = "SELECT id, subject_name FROM subjects WHERE user_id = $user_id";
$subjects_result = $conn->query($subjects_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Assessment | Flux</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { brand: { 50: '#ecfdf5', 100: '#d1fae5', 500: '#10b981', 600: '#059669', 900: '#064e3b' } },
                    animation: { 'spin-slow': 'spin 3s linear infinite', 'pulse-fast': 'pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite' }
                }
            }
        }
    </script>
    <style>
        .step-hidden {
            display: none;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6 antialiased">

    <div class="w-full max-w-2xl">

        <div id="loading-overlay"
            class="fixed inset-0 z-50 bg-slate-900/90 backdrop-blur-md flex flex-col items-center justify-center text-white hidden">
            <div class="relative mb-8">
                <div class="w-24 h-24 rounded-full border-4 border-brand-500/20 border-t-brand-500 animate-spin"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-12 h-12 bg-brand-500 rounded-full animate-pulse"></div>
                </div>
            </div>
            <h2 class="text-2xl font-bold mb-2">Analyzing Assessment...</h2>
            <p class="text-slate-400 animate-pulse">Gemini 1.5 Pro is diagnosing weak spots</p>
        </div>

        <div class="glass-panel rounded-[2.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden">

            <div class="px-10 py-6 border-b border-slate-100 flex justify-between items-center bg-white/50">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-lg bg-brand-500 flex items-center justify-center text-white font-bold shadow-lg shadow-brand-500/20">
                        S</div>
                    <span class="font-bold text-slate-900 uppercase tracking-widest text-xs">Diagnostic Engine</span>
                </div>
                <a href="dashboard.php" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </a>
            </div>

            <form action="process_scan.php" method="POST" enctype="multipart/form-data" id="scan-form" class="p-10">

                <div id="step-1" class="space-y-6">
                    <div>
                        <h2 class="text-3xl font-black text-slate-900 mb-2">Basic Context</h2>
                        <p class="text-slate-500 mb-8">Which curriculum segment are we analyzing?</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Subject</label>
                            <select name="subject_id" required
                                class="w-full px-5 py-4 rounded-2xl bg-white border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none transition-all appearance-none cursor-pointer">
                                <?php while ($row = $subjects_result->fetch_assoc()): ?>
                                    <option value="<?php echo $row['id']; ?>">
                                        <?php echo htmlspecialchars($row['subject_name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Type</label>
                            <select name="assessment_type"
                                class="w-full px-5 py-4 rounded-2xl bg-white border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none transition-all appearance-none cursor-pointer">
                                <option value="test">Test / Exam</option>
                                <option value="quiz">Pop Quiz</option>
                                <option value="assignment">Assignment / Project</option>
                            </select>
                        </div>
                    </div>

                    <button type="button" onclick="changeStep(2)"
                        class="w-full bg-slate-900 text-white font-bold py-5 rounded-2xl hover:bg-slate-800 transition-all shadow-xl active:scale-95 uppercase tracking-widest text-xs mt-4">Next:
                        Upload Result</button>
                </div>

                <div id="step-2" class="step-hidden space-y-6">
                    <div>
                        <h2 class="text-3xl font-black text-slate-900 mb-2">Visual Input</h2>
                        <p class="text-slate-500 mb-8">Upload a clear photo of your graded paper.</p>
                    </div>

                    <div class="relative group">
                        <input type="file" name="assessment_image" id="file-input" accept="image/*" required
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div id="drop-zone"
                            class="border-2 border-dashed border-slate-200 rounded-[2rem] p-12 text-center group-hover:border-brand-500 group-hover:bg-brand-50/30 transition-all">
                            <div
                                class="w-16 h-16 bg-brand-50 text-brand-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-slate-600 font-bold" id="file-name">Click to browse or drop image</p>
                            <p class="text-slate-400 text-xs mt-2 uppercase tracking-tighter">JPEG, PNG allowed up to
                                10MB</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="button" onclick="changeStep(1)"
                            class="w-1/3 bg-slate-100 text-slate-500 font-bold py-5 rounded-2xl hover:bg-slate-200 transition-all uppercase tracking-widest text-xs">Back</button>
                        <button type="button" onclick="changeStep(3)"
                            class="w-2/3 bg-slate-900 text-white font-bold py-5 rounded-2xl hover:bg-slate-800 transition-all shadow-xl uppercase tracking-widest text-xs">Final
                            Step</button>
                    </div>
                </div>

                <div id="step-3" class="step-hidden space-y-6">
                    <div>
                        <h2 class="text-3xl font-black text-slate-900 mb-2">Subject Notes</h2>
                        <p class="text-slate-500 mb-8">Any specific topics you struggled with or teacher comments?</p>
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Contextual
                            Insights</label>
                        <textarea name="user_notes" rows="4"
                            placeholder="I struggled with quadratic equations and ran out of time on the last section..."
                            class="w-full px-5 py-4 rounded-2xl bg-white border border-slate-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none transition-all text-slate-900 resize-none"></textarea>
                    </div>

                    <div class="flex gap-4">
                        <button type="button" onclick="changeStep(2)"
                            class="w-1/3 bg-slate-100 text-slate-500 font-bold py-5 rounded-2xl hover:bg-slate-200 transition-all uppercase tracking-widest text-xs">Back</button>
                        <button type="submit"
                            class="w-2/3 bg-brand-500 text-white font-bold py-5 rounded-2xl hover:bg-brand-600 shadow-xl shadow-brand-500/30 transition-all active:scale-95 uppercase tracking-widest text-xs">Analyze
                            Now</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        function changeStep(step) {
            document.getElementById('step-1').classList.add('step-hidden');
            document.getElementById('step-2').classList.add('step-hidden');
            document.getElementById('step-3').classList.add('step-hidden');
            document.getElementById('step-' + step).classList.remove('step-hidden');
        }

        // File name update logic
        const fileInput = document.getElementById('file-input');
        const fileNameDisplay = document.getElementById('file-name');
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                fileNameDisplay.innerText = fileInput.files[0].name;
                fileNameDisplay.classList.add('text-brand-600');
            }
        });

        // Form Submit -> Show Loading
        document.getElementById('scan-form').onsubmit = function () {
            document.getElementById('loading-overlay').classList.remove('hidden');
        };
    </script>
</body>

</html>