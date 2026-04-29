<?php

$tdee  = isset($_SESSION['tdee'])  ? (int)$_SESSION['tdee']  : 0;
$bmr   = isset($_SESSION['bmr'])   ? (int)$_SESSION['bmr']   : 0;
$goal  = isset($_SESSION['goal'])  ? $_SESSION['goal']        : 'maintain';

$targets = [
    'lose_weight' => ['kcal'=>$tdee - 500, 'label'=>'Menurunkan Berat',  'icon'=>'📉', 'color'=>'#e17055',
                      'desc'=>'Defisit 500 kcal/hari — turun ~0.5 kg/minggu.',
                      'badges'=>['Defisit Kalori','Pembakaran Lemak']],
    'maintain'    => ['kcal'=>$tdee,        'label'=>'Jaga Berat Badan',  'icon'=>'⚖️', 'color'=>'#00b894',
                      'desc'=>'Konsumsi sesuai TDEE agar berat badan tetap stabil.',
                      'badges'=>['Seimbang','Kesehatan Optimal']],
    'gain_weight' => ['kcal'=>$tdee + 300,  'label'=>'Tambah Massa Otot', 'icon'=>'💪', 'color'=>'#0984e3',
                      'desc'=>'Surplus 300 kcal/hari untuk mendukung pembentukan otot.',
                      'badges'=>['Surplus Kalori','Bangun Otot']],
];

$msg_ok = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ubah_goal'])) {
    $new_goal = $_POST['goal'];
    if (array_key_exists($new_goal, $targets)) {
        $_SESSION['goal'] = $new_goal;
        $goal    = $new_goal;
        $msg_ok  = "Goal diperbarui ke " . $targets[$goal]['icon'] . " " . $targets[$goal]['label'] . "!";
    }
}

$current    = $targets[$goal];
$goal_color = $current['color'];

$tips = [
    'lose_weight' => [
        ['icon'=>'🥗','text'=>'Defisit 500 kcal/hari = turun ~0,5 kg/minggu secara sehat.'],
        ['icon'=>'🏃','text'=>'Kombinasikan dengan kardio 3–5x/minggu untuk hasil lebih cepat.'],
        ['icon'=>'😴','text'=>'Tidur cukup 7–8 jam. Kurang tidur meningkatkan hormon lapar.'],
        ['icon'=>'⚠️','text'=>'Hindari defisit >1000 kcal/hari agar massa otot tidak hilang.'],
    ],
    'maintain' => [
        ['icon'=>'⚖️','text'=>'Konsumsi kalori sesuai TDEE setiap hari untuk berat stabil.'],
        ['icon'=>'🥦','text'=>'Fokus pada kualitas makanan: protein cukup, sayur, karbohidrat kompleks.'],
        ['icon'=>'🏋️','text'=>'Olahraga ringan 2–3x/minggu cukup untuk menjaga kebugaran.'],
        ['icon'=>'📊','text'=>'Timbang badan 1x/minggu di waktu yang sama untuk monitoring.'],
    ],
    'gain_weight' => [
        ['icon'=>'💪','text'=>'Surplus 300 kcal/hari = penambahan otot tanpa lemak berlebih.'],
        ['icon'=>'🥩','text'=>'Targetkan protein 1,6–2,2 g/kg berat badan setiap hari.'],
        ['icon'=>'🏋️','text'=>'Latihan beban progresif 3–5x/minggu adalah kunci utamanya.'],
        ['icon'=>'⏰','text'=>'Makan protein dalam 2 jam setelah latihan untuk recovery optimal.'],
    ],
];
$current_tips = $tips[$goal] ?? $tips['maintain'];
?>

<style>
.gs-wrap * { box-sizing:border-box; }

.gs-grid { display:grid; grid-template-columns:1fr 1.1fr; gap:22px; }

.gs-card { background:#fff; border-radius:18px; padding:24px; box-shadow:0 4px 18px rgba(0,0,0,.05); border:1.5px solid #f0f0f0; margin-bottom:16px; }
.gs-card:last-child { margin-bottom:0; }
.gs-card-title { font-weight:700; color:#1a1a2e; font-size:.97rem; margin:0 0 18px; }

.current-banner { border-radius:20px; padding:26px; color:#fff;
                  background:linear-gradient(135deg,<?php echo $goal_color; ?>,<?php echo $goal_color; ?>bb);
                  margin-bottom:16px; position:relative; overflow:hidden; }
.current-banner::after { content:'<?php echo $current['icon']; ?>'; position:absolute; right:20px; bottom:-10px;
                          font-size:5rem; opacity:.15; line-height:1; }
.current-banner-tag { font-size:.72rem; opacity:.8; font-weight:700; text-transform:uppercase; letter-spacing:.8px; margin-bottom:10px; }
.current-banner-title { font-size:1.6rem; font-weight:800; line-height:1.15; margin-bottom:8px; }
.current-banner-desc { font-size:.86rem; opacity:.85; line-height:1.5; margin-bottom:16px; }
.current-banner-kcal { font-size:2rem; font-weight:800; }
.current-banner-kcal span { font-size:.9rem; opacity:.8; }
.badge-row { display:flex; gap:7px; flex-wrap:wrap; margin-top:12px; }
.banner-badge { padding:4px 12px; border-radius:50px; background:rgba(255,255,255,.2); font-size:.75rem; font-weight:700; backdrop-filter:blur(4px); }

.goal-opt-wrap { margin-bottom:12px; }
.goal-opt-wrap input[type="radio"] { display:none; }
.goal-opt-card { display:flex; align-items:center; gap:14px; padding:16px 18px;
                 background:#fff; border:2px solid #eee; border-radius:16px; cursor:pointer;
                 transition:.25s; position:relative; overflow:hidden; }
.goal-opt-card:hover { border-color:#ddd; transform:translateY(-1px); box-shadow:0 4px 14px rgba(0,0,0,.07); }
.goal-opt-wrap input[type="radio"]:checked + .goal-opt-card { border-color:var(--opt-color); background:var(--opt-color-bg); }
.goal-opt-wrap input[type="radio"]:checked + .goal-opt-card .goal-opt-name { color:var(--opt-color); }
.goal-opt-icon-wrap { width:44px; height:44px; border-radius:13px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0; }
.goal-opt-name { font-weight:800; font-size:.92rem; color:#1a1a2e; }
.goal-opt-kcal { font-size:.78rem; color:#aaa; margin-top:2px; font-weight:600; }
.goal-opt-check { position:absolute; right:16px; width:22px; height:22px; border-radius:50%; border:2px solid #eee; display:flex; align-items:center; justify-content:center; transition:.25s; }
.goal-opt-wrap input[type="radio"]:checked + .goal-opt-card .goal-opt-check { background:var(--opt-color); border-color:var(--opt-color); color:#fff; font-size:.7rem; font-weight:700; }
.goal-opt-wrap input[type="radio"]:checked + .goal-opt-card .goal-opt-check::after { content:'✓'; }

.btn-update { background:<?php echo $aksen; ?>; color:#fff; border:none; border-radius:13px; padding:14px;
              font-weight:800; cursor:pointer; width:100%; transition:.25s; font-family:'Poppins',sans-serif; font-size:.93rem; margin-top:6px; }
.btn-update:hover { transform:translateY(-2px); box-shadow:0 8px 20px <?php echo $aksen; ?>44; }

.met-row    { display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #f5f5f5; }
.met-row:last-child { border-bottom:none; }
.met-val    { font-weight:800; color:#1a1a2e; font-size:1rem; }

.tip-item   { display:flex; gap:12px; align-items:flex-start; padding:10px 0; border-bottom:1px solid #f5f5f5; }
.tip-item:last-child { border-bottom:none; }
.tip-icon   { width:32px; height:32px; border-radius:10px; display:flex; align-items:center; justify-content:center;
              font-size:1rem; flex-shrink:0; background:<?php echo $goal_color; ?>15; }
.tip-text   { font-size:.85rem; color:#555; line-height:1.5; }

.recalc-card { background:linear-gradient(135deg,#f8f9fa,#fff); }
.recalc-link { display:block; text-align:center; background:#f0f0f0; color:#2d3436; border-radius:12px;
               padding:13px; font-weight:700; font-size:.9rem; text-decoration:none; transition:.25s; }
.recalc-link:hover { background:<?php echo $aksen; ?>; color:#fff; transform:translateY(-2px); }

.msg-ok { background:#00b89412; border:1.5px solid #00b89433; color:#00b894; border-radius:12px; padding:12px 16px; font-size:.87rem; font-weight:600; margin-bottom:18px; }

@media(max-width:820px){ .gs-grid{grid-template-columns:1fr;} }
</style>

<div style="margin-bottom:24px;">
  <h2 style="margin:0;font-weight:800;color:#1a1a2e;font-size:1.5rem;letter-spacing:-.3px;">Setting Goal ⚙️</h2>
  <p style="margin:3px 0 0;color:#aaa;font-size:.84rem;">Sesuaikan target kesehatanmu kapan saja.</p>
</div>

<?php if($msg_ok): ?>
<div class="msg-ok">✅ <?php echo htmlspecialchars($msg_ok); ?></div>
<?php endif; ?>

<div class="gs-grid">
  <div>
    <div class="current-banner">
      <div class="current-banner-tag">Goal Saat Ini</div>
      <div class="current-banner-title"><?php echo $current['icon'] . ' ' . $current['label']; ?></div>
      <div class="current-banner-desc"><?php echo $current['desc']; ?></div>
      <div class="current-banner-kcal"><?php echo number_format($current['kcal']); ?> <span>kcal/hari</span></div>
      <div class="badge-row">
        <?php foreach($current['badges'] as $b): ?>
        <span class="banner-badge"><?php echo $b; ?></span>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="gs-card">
      <p class="gs-card-title">🔄 Ubah Goal</p>
      <form method="POST">
        <?php
        $opt_colors = ['lose_weight'=>'#e17055','maintain'=>'#00b894','gain_weight'=>'#0984e3'];
        foreach($targets as $key => $t):
          $c = $opt_colors[$key];
        ?>
        <div class="goal-opt-wrap">
          <input type="radio" name="goal" id="g_<?php echo $key; ?>" value="<?php echo $key; ?>" <?php echo ($goal===$key)?'checked':''; ?>>
          <label class="goal-opt-card" for="g_<?php echo $key; ?>"
                 style="--opt-color:<?php echo $c; ?>;--opt-color-bg:<?php echo $c; ?>0d;">
            <div class="goal-opt-icon-wrap" style="background:<?php echo $c; ?>18;"><?php echo $t['icon']; ?></div>
            <div style="flex:1;">
              <div class="goal-opt-name"><?php echo $t['label']; ?></div>
              <div class="goal-opt-kcal"><?php echo number_format($t['kcal']); ?> kcal/hari</div>
            </div>
            <div class="goal-opt-check"></div>
          </label>
        </div>
        <?php endforeach; ?>
        <button type="submit" name="ubah_goal" class="btn-update">Simpan Goal</button>
      </form>
    </div>
  </div>

  <div>
    <div class="gs-card">
      <p class="gs-card-title">📊 Data Metabolisme</p>
      <?php if(!$bmr): ?>
      <div style="background:#e1705510;border:1.5px solid #e1705530;color:#e17055;border-radius:12px;padding:12px 16px;font-size:.87rem;font-weight:600;margin-bottom:16px;">
        ⚠️ Data belum dihitung. <a href="personalmatriks.php" style="color:<?php echo $aksen; ?>;font-weight:700;">Hitung sekarang →</a>
      </div>
      <?php endif; ?>
      <div class="met-row">
        <span style="color:#888;font-size:.88rem;">BMR (Basal Metabolic Rate)</span>
        <span class="met-val"><?php echo $bmr ? number_format($bmr).' kcal' : '—'; ?></span>
      </div>
      <div class="met-row">
        <span style="color:#888;font-size:.88rem;">TDEE (Total Daily Energy)</span>
        <span class="met-val"><?php echo $tdee ? number_format($tdee).' kcal' : '—'; ?></span>
      </div>
      <div class="met-row">
        <span style="color:#888;font-size:.88rem;">Target Saat Ini</span>
        <span class="met-val" style="color:<?php echo $goal_color; ?>"><?php echo number_format($current['kcal']); ?> kcal</span>
      </div>

      <?php if($tdee > 0): ?>
      <div style="margin-top:16px;">
        <?php
        $bars = [
          ['label'=>'BMR',    'val'=>$bmr,             'color'=>'#aaa'],
          ['label'=>'TDEE',   'val'=>$tdee,            'color'=>'#6c5ce7'],
          ['label'=>'Target', 'val'=>$current['kcal'], 'color'=>$goal_color],
        ];
        $max = max(array_column($bars,'val'));
        foreach($bars as $b):
          $pct = $max > 0 ? round(($b['val']/$max)*100) : 0;
        ?>
        <div style="margin-bottom:10px;">
          <div style="display:flex;justify-content:space-between;font-size:.76rem;font-weight:700;color:#888;margin-bottom:4px;">
            <span><?php echo $b['label']; ?></span><span style="color:#1a1a2e;"><?php echo number_format($b['val']); ?> kcal</span>
          </div>
          <div style="background:#f0f0f0;border-radius:50px;height:7px;overflow:hidden;">
            <div style="width:<?php echo $pct; ?>%;height:100%;background:<?php echo $b['color']; ?>;border-radius:50px;transition:width 1.2s ease;"></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <div class="gs-card">
      <p class="gs-card-title">💡 Tips untuk Goalmu</p>
      <?php foreach($current_tips as $tip): ?>
      <div class="tip-item">
        <div class="tip-icon"><?php echo $tip['icon']; ?></div>
        <p class="tip-text"><?php echo $tip['text']; ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="gs-card recalc-card">
      <p class="gs-card-title">🔁 Perbarui Data Fisik</p>
      <p style="font-size:.86rem;color:#888;margin:0 0 14px;line-height:1.5;">Berat badanmu berubah? Perbarui data untuk kalkulasi yang lebih akurat.</p>
      <a href="personalmatriks.php" class="recalc-link">Perbarui Data Fisik →</a>
    </div>
  </div>
</div>