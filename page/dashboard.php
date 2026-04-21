<?php
// Included inside tracking.php — session & $aksen & $jk are available from parent scope
include_once 'koneksi.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$tdee    = isset($_SESSION['tdee'])    ? (int)$_SESSION['tdee'] : 2000;
$bmr     = isset($_SESSION['bmr'])     ? (int)$_SESSION['bmr']  : 1500;
$goal    = isset($_SESSION['goal'])    ? $_SESSION['goal']       : 'maintain';

// Compute target kalori
$target_kalori = $tdee;
if ($goal === 'lose_weight') $target_kalori = $tdee - 500;
if ($goal === 'gain_weight') $target_kalori = $tdee + 300;

// Goal meta
$goal_map = [
    'lose_weight' => ['label' => 'Menurunkan Berat', 'icon' => '📉', 'color' => '#e17055'],
    'maintain'    => ['label' => 'Jaga Berat Badan', 'icon' => '⚖️', 'color' => '#00b894'],
    'gain_weight' => ['label' => 'Tambah Massa Otot', 'icon' => '💪', 'color' => '#0984e3'],
];
$current_goal = $goal_map[$goal] ?? $goal_map['maintain'];

// DB query helpers
function getDailyLog($conn, $uid, $date) {
    if (!$uid) return ['total_kalori' => 0, 'total_protein' => 0, 'total_fat' => 0, 'total_carbs' => 0];
    $date = mysqli_real_escape_string($conn, $date);
    $uid  = mysqli_real_escape_string($conn, $uid);
    $sql  = "SELECT 
                COALESCE(SUM(kalori), 0)  AS total_kalori,
                COALESCE(SUM(protein), 0) AS total_protein
             FROM food_logs 
             WHERE user_id = '$uid' AND tanggal = '$date'";
    $res  = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($res) ?: ['total_kalori' => 0, 'total_protein' => 0];
}

function getActivityLog($conn, $uid, $date) {
    if (!$uid) return 0;
    $date = mysqli_real_escape_string($conn, $date);
    $uid  = mysqli_real_escape_string($conn, $uid);
    $sql  = "SELECT COALESCE(SUM(kalori_terbakar), 0) AS total_burned
             FROM activity_logs 
             WHERE user_id = '$uid' AND tanggal = '$date'";
    $res  = mysqli_query($conn, $sql);
    $row  = mysqli_fetch_assoc($res);
    return $row ? (int)$row['total_burned'] : 0;
}

function getRecentFoods($conn, $uid, $date) {
    if (!$uid) return [];
    $date = mysqli_real_escape_string($conn, $date);
    $uid  = mysqli_real_escape_string($conn, $uid);
    $sql  = "SELECT nama_makanan, jumlah_gram, kalori, protein
             FROM food_logs 
             WHERE user_id = '$uid' AND tanggal = '$date'
             ORDER BY id DESC LIMIT 5";
    $res  = mysqli_query($conn, $sql);
    $rows = [];
    while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
    return $rows;
}

$hari_ini     = date('Y-m-d');
$kemarin      = date('Y-m-d', strtotime('-1 day'));

$data_today   = getDailyLog($conn, $user_id, $hari_ini);
$data_yest    = getDailyLog($conn, $user_id, $kemarin);
$burned_today = getActivityLog($conn, $user_id, $hari_ini);
$recent_foods = getRecentFoods($conn, $user_id, $hari_ini);

$kalori_masuk   = (int)($data_today['total_kalori'] ?? 0);
$protein_masuk  = round($data_today['total_protein'] ?? 0, 1);
$kalori_kemarin = (int)($data_yest['total_kalori'] ?? 0);
$net_kalori     = $kalori_masuk - $burned_today;
$sisa_kalori    = max($target_kalori - $net_kalori, 0);
$progress_pct   = $target_kalori > 0 ? min(round(($net_kalori / $target_kalori) * 100), 100) : 0;
?>

<style>
.dash-header     { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:28px; flex-wrap:wrap; gap:12px; }
.goal-chip       { display:inline-flex; align-items:center; gap:8px; padding:8px 20px; border-radius:50px; font-weight:600; font-size:.85rem; }
.stats-grid      { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }
.stat-card       { background:#fff; border-radius:18px; padding:22px; box-shadow:0 4px 14px rgba(0,0,0,.05); border:1.5px solid #f2f2f2; }
.stat-card .num  { font-size:1.7rem; font-weight:700; line-height:1.1; }
.stat-card .lbl  { font-size:.72rem; color:#aaa; font-weight:600; text-transform:uppercase; letter-spacing:.5px; margin-top:5px; }
.progress-card   { background:#fff; border-radius:18px; padding:28px; box-shadow:0 4px 14px rgba(0,0,0,.05); border:1.5px solid #f2f2f2; margin-bottom:24px; }
.prog-bar-wrap   { background:#f0f0f0; border-radius:50px; height:13px; overflow:hidden; margin:14px 0 8px; }
.prog-bar-fill   { height:100%; border-radius:50px; transition:width 1.2s ease; }
.bottom-grid     { display:grid; grid-template-columns:1.5fr 1fr; gap:20px; }
.table-card      { background:#fff; border-radius:18px; padding:24px; box-shadow:0 4px 14px rgba(0,0,0,.05); border:1.5px solid #f2f2f2; }
.food-table      { width:100%; border-collapse:collapse; font-size:.87rem; }
.food-table th   { text-align:left; font-size:.72rem; color:#aaa; font-weight:600; text-transform:uppercase; letter-spacing:.5px; padding:0 0 12px; border-bottom:1.5px solid #f0f0f0; }
.food-table td   { padding:10px 0; border-bottom:1px solid #f8f8f8; color:#444; vertical-align:middle; }
.food-table tr:last-child td { border-bottom:none; }
.kal-badge       { background:#f0f0f0; border-radius:8px; padding:3px 10px; font-weight:600; font-size:.82rem; }
.quick-btn       { display:block; text-align:center; padding:12px; border-radius:13px; font-weight:700; font-size:.87rem; text-decoration:none; transition:.25s; margin-bottom:10px; }
.quick-btn:hover { transform:translateY(-2px); box-shadow:0 6px 16px rgba(0,0,0,.12); }
.section-ttl     { font-weight:700; color:#2d3436; font-size:1rem; margin:0 0 16px; }
@media(max-width:900px){ .stats-grid{grid-template-columns:1fr 1fr;} .bottom-grid{grid-template-columns:1fr;} }
@media(max-width:500px){ .stats-grid{grid-template-columns:1fr 1fr;} }
</style>

<div class="dash-header">
  <div>
    <h2 style="margin:0;font-weight:700;color:#2d3436;">Dashboard 👋</h2>
    <p style="margin:4px 0 0;color:#aaa;font-size:.88rem;"><?php echo date('l, d F Y'); ?></p>
  </div>
  <div class="goal-chip" style="background:<?php echo $current_goal['color']; ?>18; border:1.5px solid <?php echo $current_goal['color']; ?>33; color:<?php echo $current_goal['color']; ?>">
    <?php echo $current_goal['icon'] . ' ' . $current_goal['label']; ?>
  </div>
</div>

<!-- Stats Row -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="num" style="color:<?php echo $aksen; ?>"><?php echo number_format($kalori_masuk); ?></div>
    <div class="lbl">🔥 Kalori Masuk</div>
  </div>
  <div class="stat-card">
    <div class="num" style="color:#e17055"><?php echo number_format($burned_today); ?></div>
    <div class="lbl">⚡ Kalori Terbakar</div>
  </div>
  <div class="stat-card">
    <div class="num" style="color:#00b894"><?php echo number_format($sisa_kalori); ?></div>
    <div class="lbl">🎯 Sisa Kalori</div>
  </div>
  <div class="stat-card">
    <div class="num" style="color:#6c5ce7"><?php echo $protein_masuk; ?>g</div>
    <div class="lbl">💪 Protein</div>
  </div>
</div>

<!-- Progress -->
<div class="progress-card">
  <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
    <p class="section-ttl" style="margin:0">📊 Progress Kalori Harian</p>
    <span style="font-size:.85rem;color:#888"><b style="color:#2d3436"><?php echo number_format($net_kalori); ?></b> / <?php echo number_format($target_kalori); ?> kcal</span>
  </div>
  <div class="prog-bar-wrap">
    <div class="prog-bar-fill" style="width:<?php echo $progress_pct; ?>%;background:linear-gradient(90deg,<?php echo $aksen;?>,<?php echo $aksen;?>99)"></div>
  </div>
  <div style="display:flex;justify-content:space-between;font-size:.8rem;color:#aaa;">
    <span><?php echo $progress_pct; ?>% tercapai</span>
    <span>
      <?php if($progress_pct >= 100): ?>
        ✅ Target terpenuhi!
      <?php elseif($net_kalori < 0): ?>
        ⚠️ Kalori negatif — pertimbangkan makan lebih banyak.
      <?php else: ?>
        Butuh <?php echo number_format($sisa_kalori); ?> kcal lagi
      <?php endif; ?>
    </span>
  </div>
</div>

<!-- Bottom Grid -->
<div class="bottom-grid">
  <!-- Recent Foods -->
  <div class="table-card">
    <p class="section-ttl">🍽️ Makanan Hari Ini</p>
    <?php if(empty($recent_foods)): ?>
      <div style="text-align:center;padding:30px 0;color:#ccc;">
        <div style="font-size:2rem;">🍽️</div>
        <p style="margin:8px 0 0;font-size:.88rem;">Belum ada makanan tercatat hari ini.</p>
      </div>
    <?php else: ?>
    <table class="food-table">
      <thead><tr>
        <th>Makanan</th><th>Gram</th><th>Kalori</th><th>Protein</th>
      </tr></thead>
      <tbody>
        <?php foreach($recent_foods as $f): ?>
        <tr>
          <td style="font-weight:600;color:#2d3436"><?php echo htmlspecialchars(ucfirst($f['nama_makanan'])); ?></td>
          <td style="color:#999"><?php echo $f['jumlah_gram']; ?>g</td>
          <td><span class="kal-badge"><?php echo number_format($f['kalori']); ?></span></td>
          <td style="color:#6c5ce7"><?php echo round($f['protein'],1); ?>g</td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>

  <!-- Quick Actions + Summary -->
  <div>
    <div class="table-card" style="margin-bottom:20px;">
      <p class="section-ttl">⚡ Quick Actions</p>
      <a href="tracking.php?page=food" class="quick-btn" style="background:<?php echo $aksen; ?>;color:white">+ Tambah Makanan</a>
      <a href="tracking.php?page=activity" class="quick-btn" style="background:#2d3436;color:white">+ Catat Aktivitas</a>
      <a href="tracking.php?page=goalsetting" class="quick-btn" style="background:#f0f0f0;color:#444">⚙️ Ubah Goal</a>
    </div>
    <div class="table-card">
      <p class="section-ttl">📋 Ringkasan</p>
      <div style="display:flex;flex-direction:column;gap:10px;font-size:.88rem;">
        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f5f5f5;"><span style="color:#888">BMR</span><b><?php echo number_format($bmr); ?> kcal</b></div>
        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f5f5f5;"><span style="color:#888">TDEE</span><b><?php echo number_format($tdee); ?> kcal</b></div>
        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f5f5f5;"><span style="color:#888">Target</span><b style="color:<?php echo $aksen; ?>"><?php echo number_format($target_kalori); ?> kcal</b></div>
        <div style="display:flex;justify-content:space-between;padding:8px 0;"><span style="color:#888">Kemarin</span><b><?php echo number_format($kalori_kemarin); ?> kcal</b></div>
      </div>
    </div>
  </div>
</div>