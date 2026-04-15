<?php
// ========== CONFIGURATION ==========
define('MOOD_FILE', 'mood_history.txt');

// ========== AFFIRMATIONS ARRAY ==========
function getAffirmations() {
    return [
        "I am capable of achieving anything I set my mind to.",
        "Today, I choose peace and positivity.",
        "I embrace my journey and trust the process.",
        "My potential is limitless, and every step counts.",
        "I radiate kindness and attract good energy.",
        "I am enough, just as I am in this moment.",
        "Challenges help me grow stronger and wiser.",
        "I deserve happiness and beautiful experiences.",
        "Every day is a fresh start.",
        "I am proud of who I am becoming."
    ];
}

// ========== MOOD OPTIONS ==========
function getMoodOptions() {
    return [
        '😊 Great' => 'great',
        '🙂 Okay' => 'okay',
        '😐 Neutral' => 'neutral',
        '😔 Down' => 'down',
        '😴 Tired' => 'tired'
    ];
}

// ========== SAVE MOOD TO FILE ==========
function saveMood($mood) {
    $date = date("Y-m-d H:i:s");
    $entry = $date . " | " . htmlspecialchars($mood) . PHP_EOL;
    return file_put_contents(MOOD_FILE, $entry, FILE_APPEND | LOCK_EX);
}

// ========== GET MOOD HISTORY ==========
function getMoodHistory($limit = 10) {
    $history = [];
    if (file_exists(MOOD_FILE)) {
        $lines = file(MOOD_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $history = array_reverse($lines);
        if ($limit) {
            $history = array_slice($history, 0, $limit);
        }
    }
    return $history;
}

// ========== GET TOTAL MOOD COUNT ==========
function getTotalMoodCount() {
    if (file_exists(MOOD_FILE)) {
        $lines = file(MOOD_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return count($lines);
    }
    return 0;
}

// ========== CLEAR MOOD HISTORY ==========
function clearMoodHistory() {
    if (file_exists(MOOD_FILE)) {
        return unlink(MOOD_FILE);
    }
    return true;
}

// ========== GET RANDOM AFFIRMATION ==========
function getRandomAffirmation() {
    $affirmations = getAffirmations();
    return $affirmations[array_rand($affirmations)];
}

// ========== GET MOOD STATISTICS ==========
function getMoodStatistics() {
    $stats = [
        '😊 Great' => 0,
        '🙂 Okay' => 0,
        '😐 Neutral' => 0,
        '😔 Down' => 0,
        '😴 Tired' => 0,
        'total' => 0
    ];
    
    if (file_exists(MOOD_FILE)) {
        $lines = file(MOOD_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $parts = explode(" | ", $line, 2);
            if (isset($parts[1])) {
                $mood = trim($parts[1]);
                if (isset($stats[$mood])) {
                    $stats[$mood]++;
                    $stats['total']++;
                }
            }
        }
    }
    return $stats;
}

// ========== HANDLE FORM SUBMISSIONS ==========
function handleMoodSubmission() {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['mood_value'])) {
        $selected_mood = htmlspecialchars($_POST['mood_value']);
        saveMood($selected_mood);
        return "✨ Thanks for sharing! Your mood has been recorded.";
    }
    return null;
}

function handleClearHistory() {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['clear_history'])) {
        clearMoodHistory();
        return true;
    }
    return false;
}

// ========== GET CURRENT MOOD FOR ACTIVE STATE ==========
function getCurrentMood() {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['mood_value'])) {
        return $_POST['mood_value'];
    }
    return null;
}
?>