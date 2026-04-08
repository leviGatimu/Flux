<?php
session_start();

// 1. BOUNCER & DATABASE
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'flux_app';
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

$user_id = $_SESSION['user_id'];
$subject_id = $conn->real_escape_string($_GET['id']);

// 2. FETCH SUBJECT INFO
$subject_sql = "SELECT subject_name FROM subjects WHERE id = $subject_id AND user_id = $user_id";
$subject_res = $conn->query($subject_sql);
$subject = $subject_res->fetch_assoc();

if (!$subject) {
    header("Location: dashboard.php");
    exit();
}

// 3. FETCH LATEST ASSESSMENT & AI STRATEGY
$latest_sql = "
    SELECT a.*, s.diagnosis, s.study_notes, s.curated_links 
    FROM assessments a
    LEFT JOIN ai_strategies s ON a.id = s.assessment_id
    WHERE a.subject_id = $subject_id
    ORDER BY a.date_added DESC LIMIT 1
";
$latest_res = $conn->query($latest_sql);
$data = $latest_res->fetch_assoc();

// 4. FETCH ALL SCORES FOR THE CHART
$history_sql = "SELECT score, date_added FROM assessments WHERE subject_id = $subject_id ORDER BY date_added ASC";
$history_res = $conn->query($history_sql);
$scores = [];
while ($row = $history_res->fetch_assoc()) {
    $scores[] = (float) $row['score'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $subject['subject_name']; ?> Analytics | Flux
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { brand: { 50: '#ecfdf5', 500: '#10b981', 600: '#059669', 900: '#064e3b' } }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">

    <nav class="bg-white border-b border-slate-200 px-8 py-4 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-4">
            <a href="subjects.php" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold text-slate-900">
                <?php echo htmlspecialchars($subject['subject_name']); ?>
            </h1>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Powered by Gemini 1.5 Pro</span>
            <div class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-8">

        <?php if (!$data): ?>
            <div class="bg-white rounded-[2rem] p-20 text-center border border-slate-200 shadow-sm">
                <h2 class="text-2xl font-bold mb-4">No assessments found.</h2>
                <a href="scan.php"
                    class="bg-brand-500 text-white px-8 py-4 rounded-xl font-bold hover:bg-brand-600 transition-all shadow-lg">Scan
                    Your First Test</a>
            </div>
        <?php else: ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-1 space-y-8">
                    <div class="bg-white rounded-[2rem] p-8 border border-slate-200 shadow-sm">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Latest Result</p>
                        <h2 class="text-5xl font-black text-slate-900 mb-2">
                            <?php echo $data['score']; ?>%
                        </h2>
                        <div
                            class="flex items-center gap-2 text-brand-600 font-bold text-sm bg-brand-50 px-3 py-1 rounded-full w-fit">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Subject Status: Growing
                        </div>
                    </div>

                    <div class="bg-white rounded-[2rem] p-8 border border-slate-200 shadow-sm">
                        <h3 class="font-bold text-slate-900 mb-6">Historical Trajectory</h3>
                        <canvas id="scoreChart" height="200"></canvas>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-8">

                    <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white shadow-2xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-brand-500 rounded-full blur-[120px] opacity-20">
                        </div>

                        <div class="relative z-10">
                            <div class="flex items-center gap-3 mb-8">
                                <div class="w-10 h-10 rounded-xl bg-brand-500 flex items-center justify-center text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold">AI Recovery Strategy</h2>
                            </div>

                            <div class="grid md:grid-cols-2 gap-10">
                                <div>
                                    <h4 class="text-brand-400 text-xs font-black uppercase tracking-widest mb-3">Topic
                                        Diagnosis:
                                        <?php echo htmlspecialchars($data['topic_name']); ?>
                                    </h4>
                                    <p class="text-slate-300 leading-relaxed italic">"
                                        <?php echo htmlspecialchars($data['diagnosis']); ?>"
                                    </p>
                                </div>

                                <div>
                                    <h4 class="text-brand-400 text-xs font-black uppercase tracking-widest mb-3">Custom
                                        Cheat Sheet</h4>
                                    <div
                                        class="bg-white/5 border border-white/10 rounded-2xl p-6 text-sm text-slate-300 leading-relaxed">
                                        <?php echo nl2br(htmlspecialchars($data['study_notes'])); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-10 pt-10 border-t border-white/10">
                                <h4 class="text-white text-xs font-black uppercase tracking-widest mb-6">Recommended
                                    Resources</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php
                                    $queries = json_decode($data['curated_links'], true);
                                    foreach ($queries as $query):
                                        $yt_url = "https://www.youtube.com/results?search_query=" . urlencode($query);
                                        ?>
                                        <a href="<?php echo $yt_url; ?>" target="_blank"
                                            class="flex items-center gap-4 bg-white/5 border border-white/10 p-4 rounded-2xl hover:bg-white/10 transition-all group">
                                            <div
                                                class="w-10 h-10 rounded-full bg-red-500/20 flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-slate-500 font-bold uppercase tracking-tight">Watch
                                                    Tutorial</p>
                                                <p class="text-sm font-semibold text-white">
                                                    <?php echo htmlspecialchars($query); ?>
                                                </p>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        <?php endif; ?>

    </main>

    <script>
        // Trajectory Chart Logic
        const ctx = document.getElementById('scoreChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_fill(0, count($scores), '')); ?>,
                    datasets: [{
                        label: 'Score Trajectory',
                        data: <?php echo json_encode($scores); ?>,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#10b981'
                }]
            },
        options: {
            responsive: true,
                plugins: { legend: { display: false } },
            scales: {
                y: { min: 0, max: 100, ticks: { color: '#94a3b8' }, grid: { borderDash: [5, 5] } },
                x: { display: false }
            }
        }
        });
    </script>
</body>

</html>