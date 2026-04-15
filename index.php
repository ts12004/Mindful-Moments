<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daily Mindful Moments | Wellness Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
// Include PHP functions
require_once 'functions.php';

// Handle form submissions
$mood_message = handleMoodSubmission();
$history_cleared = handleClearHistory();

// If history was cleared, refresh to show empty state
if ($history_cleared) {
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Get random affirmation (for page load)
$random_affirmation = getRandomAffirmation();

// If user clicked new affirmation, get a new one
if (isset($_GET['new_affirmation'])) {
    $random_affirmation = getRandomAffirmation();
}

// Get mood history and stats
$mood_history = getMoodHistory(10);
$mood_stats = getMoodStatistics();
$current_mood = getCurrentMood();
?>

<div class="container">
  <!-- Header -->
  <div class="header">
    <h1>🌿 Daily Mindful Moments</h1>
    <p class="tagline">breathe • reflect • grow</p>
  </div>

  <div class="dashboard">
    <!-- Card 1: Daily Affirmation -->
    <div class="card">
      <h2>✨ Today's Affirmation</h2>
      <div class="affirmation-box">
        <div class="affirmation-text">"<?php echo htmlspecialchars($random_affirmation); ?>"</div>
        <form method="GET" action="">
          <button type="submit" name="new_affirmation" class="btn">🔄 New Affirmation</button>
        </form>
      </div>
      <p style="font-size: 0.85rem; color: #7c9a8f; text-align: center;">Start your day with a positive thought 💭</p>
    </div>

    <!-- Card 2: Mood Tracker -->
    <div class="card">
      <h2>😊 How are you feeling?</h2>
      
      <?php if ($mood_message): ?>
        <div class="alert alert-success"><?php echo $mood_message; ?></div>
      <?php endif; ?>
      
      <form method="POST" action="">
        <div class="mood-buttons">
          <?php foreach (getMoodOptions() as $mood_label => $mood_value): ?>
            <button type="submit" name="mood_value" value="<?php echo htmlspecialchars($mood_label); ?>" 
                    class="mood-btn <?php echo ($current_mood == $mood_label) ? 'mood-btn-active' : ''; ?>">
              <?php echo htmlspecialchars($mood_label); ?>
            </button>
          <?php endforeach; ?>
        </div>
      </form>
      
      <div class="mood-feedback">
        💡 Tracking your mood helps build emotional awareness.
      </div>
    </div>

    <!-- Card 3: Breathing Exercise (Pure CSS Animation - No JavaScript!) -->
    <div class="card">
      <h2>🌬️ Guided Breathing</h2>
      <div class="breathing-container">
        <div class="breathing-circle">
          Breathe
        </div>
        <div class="breath-text">
          <span style="animation: fadeInOut 4s infinite; display: inline-block;">🌸 Inhale slowly... (4 sec)</span>
          <span style="animation: fadeInOutExhale 4s infinite; display: inline-block; animation-delay: 4s;">🍃 Exhale gently... (4 sec)</span>
        </div>
        <p style="margin-top: 1rem; font-size: 0.8rem; color:#6b9080;">
          Follow the circle animation | Inhale → Hold → Exhale
        </p>
      </div>
    </div>
  </div>

  <!-- Card 4: Mood Statistics -->
  <div class="card" style="margin-top: 0; margin-bottom: 1.5rem;">
    <h2>📊 Your Mood Stats</h2>
    <div class="stats">
      <div class="stat-item">
        <div class="stat-number"><?php echo $mood_stats['total']; ?></div>
        <div class="stat-label">Total Entries</div>
      </div>
      <div class="stat-item">
        <div class="stat-number"><?php echo $mood_stats['😊 Great']; ?></div>
        <div class="stat-label">Great Days</div>
      </div>
      <div class="stat-item">
        <div class="stat-number"><?php echo $mood_stats['🙂 Okay']; ?></div>
        <div class="stat-label">Okay Days</div>
      </div>
      <div class="stat-item">
        <div class="stat-number"><?php echo $mood_stats['😐 Neutral']; ?></div>
        <div class="stat-label">Neutral</div>
      </div>
    </div>
  </div>

  <!-- Card 5: Mood History -->
  <div class="card" style="margin-top: 0;">
    <h2>📜 Your Mood Journal</h2>
    <?php if (count($mood_history) > 0): ?>
      <table class="history-table">
        <thead>
          <tr><th>Date & Time</th><th>Mood</th></tr>
        </thead>
        <tbody>
          <?php foreach ($mood_history as $entry): 
            $parts = explode(" | ", $entry, 2);
          ?>
            <tr>
              <td><?php echo htmlspecialchars($parts[0] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($parts[1] ?? ''); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (getTotalMoodCount() > 10): ?>
        <p style="margin-top: 0.5rem; font-size: 0.8rem; color:#6b9080;">Showing last 10 of <?php echo getTotalMoodCount(); ?> entries</p>
      <?php endif; ?>
    <?php else: ?>
      <div class="empty-history">
        📭 No moods logged yet. Click a mood button above to start your journal!
      </div>
    <?php endif; ?>
    
    <?php if (getTotalMoodCount() > 0): ?>
    <form method="POST" action="" style="margin-top: 1rem; text-align: right;" onsubmit="return confirm('Clear all mood history? This cannot be undone.')">
      <button type="submit" name="clear_history" class="btn btn-danger" style="background:#c9ada7; font-size:0.8rem;">🗑️ Clear All History</button>
    </form>
    <?php endif; ?>
  </div>

  <footer>
    <p>🧘‍♀️ Daily Mindful Moments — Take a pause, breathe deeply, and cherish the now.</p>
    <p>Built with HTML, CSS & PHP only | No JavaScript required | Pure CSS animations</p>
  </footer>
</div>

<style>
/* Additional CSS animations for breathing text */
@keyframes fadeInOut {
  0%, 45% { opacity: 1; display: inline-block; }
  46%, 100% { opacity: 0; display: none; }
}

@keyframes fadeInOutExhale {
  0%, 45% { opacity: 0; display: none; }
  46%, 100% { opacity: 1; display: inline-block; }
}

.breath-text span {
  position: absolute;
  width: 100%;
  text-align: center;
  left: 0;
}

.breath-text {
  position: relative;
  height: 50px;
  margin-top: 10px;
}
</style>

</body>
</html>