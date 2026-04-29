<?php
include_once 'koneksi.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$tdee    = isset($_SESSION['tdee'])    ? (int)$_SESSION['tdee'] : 2000;
$bmr     = isset($_SESSION['bmr'])     ? (int)$_SESSION['bmr']  : 1500;
$goal    = isset($_SESSION['goal'])    ? $_SESSION['goal']       : 'maintain';

$target_kalori = $tdee;
if ($goal === 'lose_weight') $target_kalori = $tdee - 500;
if ($goal === 'gain_weight') $target_kalori = $tdee + 300;

$goal_map = [
    'lose_weight' => ['label'=>'Menurunkan Berat', 'icon'=>'📉', 'color'=>'#e17055'],
    'maintain'    => ['label'=>'Jaga Berat Badan', 'icon'=>'⚖️', 'color'=>'#00b894'],
    'gain_weight' => ['label'=>'Tambah Massa Otot', 'icon'=>'💪', 'color'=>'#0984e3'],
];
$current_goal = $goal_map[$goal] ?? $goal_map['maintain'];

function getDailyLog($conn, $uid, $date) {
    if (!$uid) return ['total_kalori'=>0,'total_protein'=>0,'total_fat'=>0,'total_carbs'=>0];
    $date = mysqli_real_escape_string($conn, $date);
    $uid  = mysqli_real_escape_string($conn, $uid);
    $sql  = "SELECT COALESCE(SUM(kalori),0) AS total_kalori, COALESCE(SUM(protein),0) AS total_protein
             FROM food_logs WHERE user_id='$uid' AND tanggal='$date'";
    $res  = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($res) ?: ['total_kalori'=>0,'total_protein'=>0];
}
function getActivityLog($conn, $uid, $date) {
    if (!$uid) return 0;
    $date = mysqli_real_escape_string($conn, $date);
    $uid  = mysqli_real_escape_string($conn, $uid);
    $res  = mysqli_query($conn, "SELECT COALESCE(SUM(kalori_terbakar),0) AS total_burned FROM activity_logs WHERE user_id='$uid' AND tanggal='$date'");
    $row  = mysqli_fetch_assoc($res);
    return $row ? (int)$row['total_burned'] : 0;
}
function getRecentFoods($conn, $uid, $date) {
    if (!$uid) return [];
    $date = mysqli_real_escape_string($conn, $date);
    $uid  = mysqli_real_escape_string($conn, $uid);
    $res  = mysqli_query($conn, "SELECT id,nama_makanan,jumlah_gram,kalori,protein FROM food_logs WHERE user_id='$uid' AND tanggal='$date' ORDER BY id DESC LIMIT 6");
    $rows = [];
    while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
    return $rows;
}
function getRecentActivities($conn, $uid, $date) {
    if (!$uid) return [];
    $date = mysqli_real_escape_string($conn, $date);
    $uid  = mysqli_real_escape_string($conn, $uid);
    $res  = mysqli_query($conn, "SELECT jenis_aktivitas,durasi_menit,kalori_terbakar FROM activity_logs WHERE user_id='$uid' AND tanggal='$date' ORDER BY id DESC LIMIT 4");
    $rows = [];
    while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
    return $rows;
}

$hari_ini = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$kemarin  = date('Y-m-d', strtotime($hari_ini . ' -1 day'));

$data_today    = getDailyLog($conn, $user_id, $hari_ini);
$data_yest     = getDailyLog($conn, $user_id, $kemarin);
$burned_today  = getActivityLog($conn, $user_id, $hari_ini);
$recent_foods  = getRecentFoods($conn, $user_id, $hari_ini);
$recent_acts   = getRecentActivities($conn, $user_id, $hari_ini);

$kalori_masuk   = (int)($data_today['total_kalori'] ?? 0);
$protein_masuk  = round($data_today['total_protein'] ?? 0, 1);
$kalori_kemarin = (int)($data_yest['total_kalori'] ?? 0);
$net_kalori     = $kalori_masuk - $burned_today;
$sisa_kalori    = max($target_kalori - $net_kalori, 0);
$progress_pct   = $target_kalori > 0 ? min(round(($net_kalori / $target_kalori) * 100), 100) : 0;

$target_protein = isset($_SESSION['berat']) ? round($_SESSION['berat'] * 1.6) : 80;
$target_fat     = round($target_kalori * 0.27 / 9);
$target_carbs   = round(($target_kalori * 0.5) / 4);

$trend = $kalori_masuk - $kalori_kemarin;
$trend_sign = $trend >= 0 ? '+' : '';
?>

<style>
.db-wrap * { box-sizing:border-box; }
.db-header  { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
.goal-chip  { display:inline-flex; align-items:center; gap:8px; padding:8px 20px; border-radius:50px; font-weight:700; font-size:.84rem; }

.stats-row  { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:20px; }
.stat-card  { background:#fff; border-radius:18px; padding:20px; box-shadow:0 4px 14px rgba(0,0,0,.05); border:1.5px solid #f2f2f2; position:relative; overflow:hidden; }
.stat-card::before { content:''; position:absolute; top:-20px; right:-20px; width:70px; height:70px; border-radius:50%; opacity:.07; }
.stat-num   { font-size:1.65rem; font-weight:800; line-height:1.1; letter-spacing:-.5px; }
.stat-lbl   { font-size:.71rem; color:#aaa; font-weight:700; text-transform:uppercase; letter-spacing:.5px; margin-top:5px; }
.stat-trend { font-size:.75rem; font-weight:700; margin-top:6px; }

.prog-card  { background:#fff; border-radius:18px; padding:26px; box-shadow:0 4px 14px rgba(0,0,0,.05); border:1.5px solid #f2f2f2; margin-bottom:20px; }
.prog-bar-wrap { background:#f0f0f0; border-radius:50px; height:12px; overflow:hidden; margin:14px 0 8px; }
.prog-bar-fill { height:100%; border-radius:50px; transition:width 1.4s cubic-bezier(.4,0,.2,1); }

.macro-section { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-top:18px; }
.macro-pill    { background:#f8f9fa; border-radius:14px; padding:14px; }
.macro-pill-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#aaa; margin-bottom:8px; }
.macro-pill-val   { font-size:1.1rem; font-weight:800; color:#1a1a2e; margin-bottom:8px; }
.macro-pill-bar   { height:5px; background:#eee; border-radius:50px; overflow:hidden; }
.macro-pill-fill  { height:100%; border-radius:50px; transition:width 1s ease; }

.bottom-grid { display:grid; grid-template-columns:1.4fr 1fr; gap:18px; }
.db-card     { background:#fff; border-radius:18px; padding:22px; box-shadow:0 4px 14px rgba(0,0,0,.05); border:1.5px solid #f2f2f2; margin-bottom:16px; }
.db-card:last-child { margin-bottom:0; }
.db-card-title { font-weight:700; color:#1a1a2e; font-size:.95rem; margin:0 0 16px; }

.food-tbl   { width:100%; border-collapse:collapse; font-size:.86rem; }
.food-tbl th { text-align:left; font-size:.7rem; color:#aaa; font-weight:700; text-transform:uppercase; letter-spacing:.5px; padding:0 0 11px; border-bottom:1.5px solid #f0f0f0; }
.food-tbl td { padding:10px 0; border-bottom:1px solid #f8f8f8; color:#444; vertical-align:middle; }
.food-tbl tr:last-child td { border-bottom:none; }
.kal-badge  { background:#f0f0f0; border-radius:7px; padding:3px 10px; font-weight:700; font-size:.8rem; }

.act-row    { display:flex; align-items:center; gap:10px; padding:9px 0; border-bottom:1px solid #f5f5f5; }
.act-row:last-child { border-bottom:none; }
.act-row-name { font-weight:600; color:#2d3436; font-size:.87rem; flex:1; }
.act-row-burn { background:#fff3f0; color:#e17055; padding:3px 10px; border-radius:7px; font-weight:700; font-size:.78rem; }

.qa-btn     { display:block; text-align:center; padding:13px; border-radius:13px; font-weight:700; font-size:.88rem; text-decoration:none; transition:.25s; margin-bottom:10px; }
.qa-btn:hover { transform:translateY(-2px); box-shadow:0 6px 16px rgba(0,0,0,.12); }

.sum-row    { display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f5f5f5; font-size:.88rem; }
.sum-row:last-child { border-bottom:none; }

.empty-state { text-align:center; padding:26px 0; color:#ccc; font-size:.87rem; }

@media(max-width:900px){ .stats-row{grid-template-columns:1fr 1fr;} .bottom-grid{grid-template-columns:1fr;} .macro-section{grid-template-columns:repeat(3,1fr);} }
@media(max-width:500px){ .stats-row{grid-template-columns:1fr 1fr;} .macro-section{grid-template-columns:1fr 1fr;} }
</style>

<div class="db-header">
  <div>
    <h2 style="margin:0;font-weight:800;color:#1a1a2e;font-size:1.5rem;letter-spacing:-.3px;">Dashboard 👋</h2>
    <form method="GET" action="tracking.php" style="margin-top:8px;display:flex;gap:8px;align-items:center;">
      <input type="hidden" name="page" value="dashboard">
      <input type="date" name="date" value="<?php echo htmlspecialchars($hari_ini); ?>"
             max="<?php echo date('Y-m-d'); ?>"
             style="padding:6px 12px;border-radius:10px;border:1.5px solid #eee;outline:none;font-family:'Poppins',sans-serif;font-size:.84rem;color:#444;background:#fff;"
             onchange="this.form.submit()">
      <span style="font-size:.82rem;color:#aaa;"><?php echo date('l, d M Y', strtotime($hari_ini)); ?></span>
    </form>
  </div>
  <div class="goal-chip" style="background:<?php echo $current_goal['color']; ?>15;border:1.5px solid <?php echo $current_goal['color']; ?>30;color:<?php echo $current_goal['color']; ?>">
    <?php echo $current_goal['icon'] . ' ' . $current_goal['label']; ?>
  </div>
</div>

<div class="stats-row">
  <div class="stat-card">
    <div style="position:absolute;top:-20px;right:-20px;width:70px;height:70px;border-radius:50%;background:<?php echo $aksen; ?>;opacity:.08;"></div>
    <div class="stat-num" style="color:<?php echo $aksen; ?>"><?php echo number_format($kalori_masuk); ?></div>
    <div class="stat-lbl">🍽️ Kalori Masuk</div>
    <?php if($kalori_kemarin > 0): ?>
    <div class="stat-trend" style="color:<?php echo $trend > 0 ? '#e17055' : '#00b894'; ?>"><?php echo $trend_sign . number_format($trend); ?> vs kemarin</div>
    <?php endif; ?>
  </div>
  <div class="stat-card">
    <div style="position:absolute;top:-20px;right:-20px;width:70px;height:70px;border-radius:50%;background:#e17055;opacity:.08;"></div>
    <div class="stat-num" style="color:#e17055"><?php echo number_format($burned_today); ?></div>
    <div class="stat-lbl">⚡ Kalori Terbakar</div>
    <div class="stat-trend" style="color:#aaa"><?php echo count($recent_acts); ?> aktivitas</div>
  </div>
  <div class="stat-card">
    <div style="position:absolute;top:-20px;right:-20px;width:70px;height:70px;border-radius:50%;background:#00b894;opacity:.08;"></div>
    <div class="stat-num" style="color:#00b894"><?php echo number_format($sisa_kalori); ?></div>
    <div class="stat-lbl">🎯 Sisa Kalori</div>
    <div class="stat-trend" style="color:#aaa">dari <?php echo number_format($target_kalori); ?> target</div>
  </div>
  <div class="stat-card">
    <div style="position:absolute;top:-20px;right:-20px;width:70px;height:70px;border-radius:50%;background:#6c5ce7;opacity:.08;"></div>
    <div class="stat-num" style="color:#6c5ce7"><?php echo $protein_masuk; ?>g</div>
    <div class="stat-lbl">💪 Protein</div>
    <div class="stat-trend" style="color:#aaa">target <?php echo $target_protein; ?>g</div>
  </div>
</div>

<div class="prog-card">
  <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
    <p style="font-weight:700;color:#1a1a2e;font-size:.97rem;margin:0">📊 Progress Kalori Bersih — <?php echo date('d M Y', strtotime($hari_ini)); ?></p>
    <span style="font-size:.85rem;color:#888;font-weight:600"><b style="color:#1a1a2e;font-size:1rem"><?php echo number_format($net_kalori); ?></b> / <?php echo number_format($target_kalori); ?> kcal</span>
  </div>

  <div class="prog-bar-wrap">
    <div class="prog-bar-fill" style="width:<?php echo $progress_pct; ?>%;background:linear-gradient(90deg,<?php echo $aksen; ?>,<?php echo $aksen; ?>99)"></div>
  </div>
  <div style="display:flex;justify-content:space-between;font-size:.8rem;color:#aaa;">
    <span style="font-weight:600"><?php echo $progress_pct; ?>% tercapai</span>
    <span>
      <?php if($progress_pct >= 100): ?>✅ Target terpenuhi!
      <?php elseif($net_kalori < 0): ?>⚠️ Kalori negatif
      <?php else: ?>Butuh <b><?php echo number_format($sisa_kalori); ?> kcal</b> lagi<?php endif; ?>
    </span>
  </div>

  <div class="macro-section">
    <?php
    $macros = [
      ['emoji'=>'💪','label'=>'Protein', 'val'=>$protein_masuk,'unit'=>'g','target'=>$target_protein,'color'=>'#6c5ce7'],
      ['emoji'=>'🍞','label'=>'Karbo',   'val'=>0,             'unit'=>'g','target'=>$target_carbs,  'color'=>'#fdcb6e'],
      ['emoji'=>'⚡','label'=>'Terbakar','val'=>$burned_today, 'unit'=>'kcal','target'=>500,          'color'=>'#e17055'],
    ];
    foreach($macros as $m):
      $pct = $m['target'] > 0 ? min(round(($m['val']/$m['target'])*100), 100) : 0;
    ?>
    <div class="macro-pill">
      <div class="macro-pill-label"><?php echo $m['emoji'] . ' ' . $m['label']; ?></div>
      <div class="macro-pill-val"><?php echo $m['val']; ?><span style="font-size:.75rem;color:#aaa;font-weight:600"> /<?php echo $m['target'].$m['unit']; ?></span></div>
      <div class="macro-pill-bar"><div class="macro-pill-fill" style="width:<?php echo $pct; ?>%;background:<?php echo $m['color']; ?>;"></div></div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<div class="bottom-grid">
  <div>
    <div class="db-card">
      <p class="db-card-title">🍽️ Makanan Tercatat</p>
      <?php if(empty($recent_foods)): ?>
      <div class="empty-state"><div style="font-size:2rem;margin-bottom:8px;">🍽️</div>Belum ada makanan pada tanggal ini.</div>
      <?php else: ?>
      <table class="food-tbl">
        <thead><tr><th>Makanan</th><th>Gram</th><th>Kalori</th><th>Protein</th><th></th></tr></thead>
        <tbody>
          <?php foreach($recent_foods as $f): ?>
          <tr>
            <td style="font-weight:700;color:#1a1a2e"><?php echo htmlspecialchars(ucfirst($f['nama_makanan'])); ?></td>
            <td style="color:#aaa"><?php echo $f['jumlah_gram']; ?>g</td>
            <td><span class="kal-badge"><?php echo number_format($f['kalori']); ?></span></td>
            <td style="color:#6c5ce7;font-weight:600"><?php echo round($f['protein'],1); ?>g</td>
            <td><a href="delete_log.php?id=<?php echo $f['id']; ?>&type=food" onclick="return confirm('Hapus?')" style="color:#ddd;text-decoration:none;font-size:1rem;transition:.2s;" onmouseover="this.style.color='#e17055'" onmouseout="this.style.color='#ddd'">🗑️</a></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>

    <?php if(!empty($recent_acts)): ?>
    <div class="db-card">
      <p class="db-card-title">⚡ Aktivitas Hari Ini</p>
      <?php foreach($recent_acts as $a): ?>
      <div class="act-row">
        <div style="flex:1;">
          <div class="act-row-name"><?php echo htmlspecialchars($a['jenis_aktivitas']); ?></div>
          <div style="font-size:.74rem;color:#aaa;"><?php echo $a['durasi_menit']; ?> menit</div>
        </div>
        <span class="act-row-burn">-<?php echo number_format($a['kalori_terbakar']); ?> kcal</span>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

  <div>
    <div class="db-card">
      <p class="db-card-title">⚡ Aksi Cepat</p>
      <a href="tracking.php?page=food"        class="qa-btn" style="background:<?php echo $aksen; ?>;color:#fff">🍽️ + Tambah Makanan</a>
      <a href="tracking.php?page=activity"    class="qa-btn" style="background:#1a1a2e;color:#fff">⚡ + Catat Aktivitas</a>
      <a href="tracking.php?page=goalsetting" class="qa-btn" style="background:#f0f0f0;color:#444">⚙️ Ubah Goal</a>
    </div>

    <div class="db-card">
      <p class="db-card-title">📋 Ringkasan</p>
      <div>
        <div class="sum-row"><span style="color:#888">BMR</span><b><?php echo number_format($bmr); ?> kcal</b></div>
        <div class="sum-row"><span style="color:#888">TDEE</span><b><?php echo number_format($tdee); ?> kcal</b></div>
        <div class="sum-row"><span style="color:#888">Target Harian</span><b style="color:<?php echo $aksen; ?>"><?php echo number_format($target_kalori); ?> kcal</b></div>
        <div class="sum-row"><span style="color:#888">Kalori Bersih</span><b style="color:<?php echo $net_kalori > $target_kalori ? '#e17055' : '#00b894'; ?>"><?php echo number_format($net_kalori); ?> kcal</b></div>
        <div class="sum-row"><span style="color:#888">Kemarin</span><b><?php echo number_format($kalori_kemarin); ?> kcal</b></div>
      </div>
    </div>
  </div>
</div>