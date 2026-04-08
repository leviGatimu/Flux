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

// Fetch user data for the top right icon
$user_sql = "SELECT full_name FROM users WHERE id = $user_id";
$user_result = $conn->query($user_sql);
$user_data = $user_result->fetch_assoc();
$first_name = explode(' ', trim($user_data['full_name']))[0];

// Fetch all subjects for this user
$subjects_sql = "SELECT id, subject_name, created_at FROM subjects WHERE user_id = $user_id ORDER BY subject_name ASC";
$subjects_result = $conn->query($subjects_sql);

// Handle Subject Deletion (if they click a delete button)
if (isset($_GET['delete_id'])) {
    $delete_id = $conn->real_escape_string($_GET['delete_id']);
    // Ensure they can only delete THEIR subjects
    $conn->query("DELETE FROM subjects WHERE id = '$delete_id' AND user_id = '$user_id'");
    header("Location: subjects.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Subjects | Flux</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: { colors: { brand: { 50: '#ecfdf5', 100: '#d1fae5', 500: '#10b981', 600: '#059669', 900: '#064e3b' } } }
            }
        }
    </script>
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
                    class="flex items-center gap-3 px-3 py-2 text-slate-500 hover:bg-slate-50 hover:text-slate-900 rounded-lg font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    Overview
                </a>
                <a href="subjects.php"
                    class="flex items-center gap-3 px-3 py-2 bg-brand-50 text-brand-600 rounded-lg font-medium transition-colors">
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
            <a href="logout.php"
                class="flex items-center gap-3 px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg font-medium transition-colors mt-1 text-sm">
                Log Out
            </a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-y-auto bg-slate-50 relative z-10">

        <header
            class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 z-10 sticky top-0">
            <h1 class="text-xl font-bold text-slate-800">Subject Directory</h1>
            <div class="flex items-center gap-4">
                <a href="scan.php"
                    class="bg-brand-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-brand-600 transition-colors shadow-sm flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                        </path>
                    </svg>
                    Scan Assessment
                </a>
                <div
                    class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-600">
                    <?php echo substr($first_name, 0, 1); ?>
                </div>
            </div>
        </header>

        <div class="p-8 max-w-6xl mx-auto w-full">

            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Your Curriculum</h2>
                    <p class="text-slate-500">Manage your active classes and view detailed analytics.</p>
                </div>
                <a href="dashboard.php"
                    class="bg-white border border-slate-200 text-slate-700 px-4 py-2 rounded-lg font-medium hover:bg-slate-50 transition-colors shadow-sm text-sm">
                    + Add New Subject
                </a>
            </div>

            <?php if ($subjects_result->num_rows == 0): ?>
                <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-sm">
                    <p class="text-slate-500 mb-4">No subjects found in your directory.</p>
                    <a href="dashboard.php" class="text-brand-600 font-medium hover:underline">Go to Dashboard to add
                        one</a>
                </div>
            <?php else: ?>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php while ($subject = $subjects_result->fetch_assoc()): ?>
                        <div
                            class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow relative group">

                            <a href="subjects.php?delete_id=<?php echo $subject['id']; ?>"
                                onclick="return confirm('Are you sure you want to delete this subject? All grades associated with it will be lost.');"
                                class="absolute top-4 right-4 text-slate-300 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </a>

                            <div class="w-12 h-12 bg-brand-50 text-brand-500 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>

                            <h3 class="text-xl font-bold text-slate-900 mb-1">
                        
                                <?php echo htmlspecialchars($subject['subject_name']); ?>
                            </h3>
                                    <p class="text-sm text-slate-500 mb-6">0 Assessments Logged</p>
                            <a href="subject_detail.php?id=<?php echo $subject['id']; ?>"
                                class="block w-full text-center bg-slate-50 border border-slate-100 text-slate-700 py-2 rounded-lg font-medium hover:bg-brand-50 hover:text-brand-600 hover:border-brand-100 transition-colors">
                                View Analytics
                            </a>
                            </div>
                    <?php endwhile; ?>
                    </div>

            <?php endif; ?>

        </div>
    </main>
</body>

</html>
<?php $conn->close(); ?>