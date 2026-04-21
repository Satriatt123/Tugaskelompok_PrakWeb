<?php

$tdee  = isset($_SESSION['tdee'])  ? (int)$_SESSION['tdee']  : 0;
$bmr   = isset($_SESSION['bmr'])   ? (int)$_SESSION['bmr']   : 0;
$goal  = isset($_SESSION['goal'])  ? $_SESSION['goal']        : 'maintain';

$targets = [
    'lose_weight' => ['kcal' => $tdee - 500, 'label' => 'Menurunkan Berat',   'icon' => '📉', 'desc' => 'Defisit 500 kcal/hari — ideal untuk turun ~0.5 kg/minggu.'],
    'maintain'    => ['kcal' => $tdee,        'label' => 'Jaga Berat Badan',   'icon' => '⚖️', 'desc' => 'Konsumsi sesuai TDEE agar berat badan tetap stabil.'],
    'gain_weight' => ['kcal' => $tdee + 300,  'label' => 'Tambah Massa Otot',  'icon' => '💪', 'desc' => 'Surplus 300 kcal/hari untuk mendukung pembentukan otot.'],
];

$msg_ok = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ubah_goal'])) {
    $new_goal = $_POST['goal'];
    if (array_key_exists($new_goal, $targets)) {
        $_SESSION['goal'] = $new_goal;
        $goal = $new_goal;
        $msg_ok = "Goal berhasil diperbarui ke "" . $targets[$goal]['icon'] . " " . $targets[$goal]['label'] . ""!";
    }
}

$current = $targets[$goal];
$aksen_goal_colors = [
    'lose_weight' => '#e17055',
    'maintain'    => '#00b894',
    'gain_weight' => '#0984e3',
];
$goal_color = $aksen_goal_colors[$goal] ?? $aksen;
?>

<style>
.gs-grid        { display:grid; grid-template-columns:1fr 1fr; gap:22px; }
.gs-card        { background:#fff; border-radius:18px; padding:26px; box-shadow:0 4px 14px rgba(0,0,0,.05); border:1.5px solid #f2f2f2; }
.metric-row     { display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #f5f5f5; font-size:.9rem; }
.metric-row:last-child{ border-bottom:none; }
.metric-val     { font-weight:700; color:#2d3436; font-size:1rem; }
.goal-option    { position:relative; margin-bottom:12px; }
.goal-option input[type="radio"]{ display:none; }
.goal-opt-label { display:flex; align-items:center; gap:14px; padding:15px 18px; background:#fff; border:1.5px solid #eee; border-radius:14px; cursor:pointer; transition:.25s; }
.goal-option input[type="radio"]:checked + .goal-opt-label { border-color:<?php echo $aksen; ?>; background:<?php echo $aksen; ?>08; }
.goal-opt-icon  { font-size:1.6rem; flex-shrink:0; }
.goal-opt-title { font-weight:700; font-size:.92rem; color:#2d3436; }
.goal-opt-kcal  { font-size:.8rem; color:#999; margin-top:2px; }
.goal-option input[type="radio"]:checked + .goal-opt-label .goal-opt-title { color:<?php echo $aksen; ?>; }
.btn-update     { background:<?php echo $aksen; ?>; color:white; border:none; border-radius:13px; padding:13px; font-weight:700; cursor:pointer; width:100%; transition:.25s; font-family:'Poppins',sans-serif; font-size:.92rem; }
.btn-update:hover{ transform:translateY(-2px); box-shadow:0 8px 20px <?php echo $aksen; ?>44; }
.current-goal-box { border-radius:18px; padding:24px; color:white; background:linear-gradient(135deg,<?php echo $goal_color; ?>,<?php echo $goal_color; ?>bb); margin-bottom:22px; }
.tip-box        { background:#f8f9fa; border-radius:14px; padding:18px; margin-top:18px; font-size:.85rem; color:#666; line-height:1.6; }
.tip-box strong { color:#2d3436; }
.msg-ok         { background:#00b89415; border:1.5px solid #00b89433; color:#00b894; border-radius:12px; padding:12px 16px; font-size:.88rem; font-weight:600; margin-bottom:18px; }
@media(max-width:800px){ .gs-grid{grid-template-columns:1fr;} }
</style>

<div style="margin-bottom:28px;">
  <h2 style="margin:0;font-weight:700;color:#2d3436;">Setting Goal ⚙️</h2>
  <p style="margin:4px 0 0;color:#aaa;font-size:.88rem;">Sesuaikan target kesehatanmu kapan saja.</p>
</div>

<?php if($msg_ok): ?>
<div class="msg-ok">✅ <?php echo htmlspecialchars($msg_ok); ?></div>
<?php endif; ?>

<div class="gs-grid">
  <div>
    <div class="current-goal-box">
      <div style="font-size:.75rem;opacity:.8;font-weight:600;letter-spacing:.5px;text-transform:uppercase;margin-bottom:8px;">Goal Saat Ini</div>
      <div style="font-size:1.8rem;font-weight:800;line-height:1.1;"><?php echo $current['icon'] . ' ' . $current['label']; ?></div>
      <div style="font-size:.88rem;opacity:.85;margin-top:8px;"><?php echo $current['desc']; ?></div>
      <div style="margin-top:14px;font-size:1.3rem;font-weight:700;"><?php echo number_format($current['kcal']); ?> <span style="font-size:.85rem;opacity:.8">kcal/hari</span></div>
    </div>

    <!-- Update Form -->
    <div class="gs-card">
      <p style="font-weight:700;color:#2d3436;font-size:1rem;margin:0 0 18px">🔄 Ubah Goal</p>
      <form method="POST">
        <?php foreach($targets as $key => $t): ?>
        <div class="goal-option">
          <input type="radio" name="goal" id="g_<?php echo $key; ?>" value="<?php echo $key; ?>" <?php echo ($goal === $key) ? 'checked' : ''; ?>>
          <label class="goal-opt-label" for="g_<?php echo $key; ?>">
            <span class="goal-opt-icon"><?php echo $t['icon']; ?></span>
            <div>
              <div class="goal-opt-title"><?php echo $t['label']; ?></div>
              <div class="goal-opt-kcal"><?php echo number_format($t['kcal']); ?> kcal/hari</div>
            </div>
          </label>
        </div>
        <?php endforeach; ?>
        <button type="submit" name="ubah_goal" class="btn-update" style="margin-top:8px;">Simpan Perubahan</button>
      </form>
    </div>
  </div>

  <div>
    <div class="gs-card" style="margin-bottom:22px;">
      <p style="font-weight:700;color:#2d3436;font-size:1rem;margin:0 0 16px">📊 Data Metabolisme</p>
      <div class="metric-row">
        <span style="color:#888">BMR</span>
        <span class="metric-val"><?php echo $bmr ? number_format($bmr) . ' kcal' : '—'; ?></span>
      </div>
      <div class="metric-row">
        <span style="color:#888">TDEE</span>
        <span class="metric-val"><?php echo $tdee ? number_format($tdee) . ' kcal' : '—'; ?></span>
      </div>
      <div class="metric-row">
        <span style="color:#888">Target Saat Ini</span>
        <span class="metric-val" style="color:<?php echo $goal_color; ?>"><?php echo number_format($current['kcal']); ?> kcal</span>
      </div>
      <?php if(!$bmr): ?>
      <div style="font-size:.82rem;color:#e17055;margin-top:10px;">⚠️ Data metabolisme belum dihitung. <a href="personalmatriks.php" style="color:<?php echo $aksen; ?>">Hitung sekarang →</a></div>
      <?php endif; ?>
    </div>

    <div class="gs-card" style="margin-bottom:22px;">
      <p style="font-weight:700;color:#2d3436;font-size:1rem;margin:0 0 10px">🔁 Hitung Ulang Kalori</p>
      <p style="font-size:.87rem;color:#888;margin:0 0 16px">Berat badanmu berubah? Perbarui data fisik untuk hasil yang lebih akurat.</p>
      <a href="personalmatriks.php" style="display:block;text-align:center;background:#f0f0f0;color:#2d3436;border-radius:12px;padding:12px;font-weight:700;font-size:.9rem;text-decoration:none;transition:.25s;" onmouseover="this.style.background='<?php echo $aksen; ?>';this.style.color='white'" onmouseout="this.style.background='#f0f0f0';this.style.color='#2d3436'">Perbarui Data Fisik →</a>
    </div>

    <div class="gs-card">
      <p style="font-weight:700;color:#2d3436;font-size:1rem;margin:0 0 14px">💡 Tips</p>
      <?php if($goal === 'lose_weight'): ?>
      <div class="tip-box">
        <strong>Menurunkan Berat Badan</strong><br>
        Defisit 500 kcal/hari menghasilkan penurunan sekitar <strong>0,5 kg/minggu</strong>.
        Kombinasikan dengan aktivitas fisik 3–5x/minggu dan tidur cukup (7–8 jam).
        Hindari defisit lebih dari 1000 kcal/hari agar massa otot tidak hilang.
      </div>
      <?php elseif($goal === 'gain_weight'): ?>
      <div class="tip-box">
        <strong>Menambah Massa Otot</strong><br>
        Surplus 300 kcal/hari cukup untuk mendukung pertumbuhan otot tanpa menumpuk terlalu banyak lemak.
        Prioritaskan <strong>protein 1,6–2,2 g/kg berat badan</strong> per hari, dan latihan beban rutin 3–5x/minggu.
      </div>
      <?php else: ?>
      <div class="tip-box">
        <strong>Menjaga Berat Badan</strong><br>
        Konsumsi kalori sesuai TDEE dan jaga pola makan bergizi seimbang.
        Olahraga ringan 2–3x/minggu cukup untuk menjaga kebugaran.
        Monitor berat badan secara berkala (1x/minggu).
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>