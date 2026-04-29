<?php
include_once 'koneksi.php';

$api_key = "7ec5fe9e9336419ab619234773e01783";

$kalori = $protein = $fat = $carbs = 0;
$food_name = '';
$user_gram = 0;
$search_done = false;
$error_msg = '';

$quick_foods = [
    ['label'=>'Nasi Putih',     'query'=>'white rice',      'gram'=>150, 'icon'=>'🍚'],
    ['label'=>'Ayam Rebus',     'query'=>'boiled chicken',  'gram'=>100, 'icon'=>'🍗'],
    ['label'=>'Telur Rebus',    'query'=>'boiled egg',      'gram'=>60,  'icon'=>'🥚'],
    ['label'=>'Tempe',          'query'=>'tempeh',          'gram'=>100, 'icon'=>'🟫'],
    ['label'=>'Tahu',           'query'=>'tofu',            'gram'=>100, 'icon'=>'⬜'],
    ['label'=>'Pisang',         'query'=>'banana',          'gram'=>100, 'icon'=>'🍌'],
    ['label'=>'Roti Tawar',     'query'=>'white bread',     'gram'=>60,  'icon'=>'🍞'],
    ['label'=>'Oatmeal',        'query'=>'oatmeal',         'gram'=>80,  'icon'=>'🥣'],
    ['label'=>'Susu Sapi',      'query'=>'whole milk',      'gram'=>250, 'icon'=>'🥛'],
    ['label'=>'Ubi Rebus',      'query'=>'sweet potato',    'gram'=>150, 'icon'=>'🍠'],
    ['label'=>'Salmon',         'query'=>'salmon',          'gram'=>100, 'icon'=>'🐟'],
    ['label'=>'Alpukat',        'query'=>'avocado',         'gram'=>80,  'icon'=>'🥑'],
];

$meal_types = [
    'sarapan'     => ['label'=>'Sarapan',     'icon'=>'🌅', 'color'=>'#fdcb6e'],
    'makan_siang' => ['label'=>'Makan Siang', 'icon'=>'☀️',  'color'=>'#00b894'],
    'makan_malam' => ['label'=>'Makan Malam', 'icon'=>'🌙', 'color'=>'#6c5ce7'],
    'camilan'     => ['label'=>'Camilan',     'icon'=>'🍎', 'color'=>'#e17055'],
];

if (isset($_POST['search'])) {
    $query     = urlencode($_POST['query']);
    $user_gram = (int)$_POST['gram'];

    $search_url = "https://api.spoonacular.com/food/ingredients/search?query=$query&number=1&apiKey=$api_key";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $search_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $search_res  = curl_exec($ch);
    $search_data = json_decode($search_res, true);

    if (!empty($search_data['results'])) {
        $food_id   = $search_data['results'][0]['id'];
        $food_name = ucfirst($search_data['results'][0]['name']);
        $info_url  = "https://api.spoonacular.com/food/ingredients/$food_id/information?amount=$user_gram&unit=grams&apiKey=$api_key";
        curl_setopt($ch, CURLOPT_URL, $info_url);
        $info_res  = curl_exec($ch);
        $info_data = json_decode($info_res, true);
        curl_close($ch);

        $nutrients = $info_data['nutrition']['nutrients'] ?? [];
        function getNutrient($list, $name) {
            foreach ($list as $n) if ($n['name'] === $name) return round($n['amount'], 1);
            return 0;
        }
        $kalori  = getNutrient($nutrients, 'Calories');
        $protein = getNutrient($nutrients, 'Protein');
        $fat     = getNutrient($nutrients, 'Fat');
        $carbs   = getNutrient($nutrients, 'Carbohydrates');
        $search_done = true;
    } else {
        curl_close($ch);
        $error_msg = "Makanan tidak ditemukan. Coba nama lain dalam Bahasa Inggris.";
    }
}

$save_msg = '';
if (isset($_POST['simpan_makan'])) {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    if ($user_id) {
        $nm   = mysqli_real_escape_string($conn, $_POST['nama']);
        $gr   = (int)$_POST['gram'];
        $kal  = (float)$_POST['kalori'];
        $prot = (float)$_POST['protein'];
        $tgl  = mysqli_real_escape_string($conn, $_POST['tanggal']);
        $uid  = mysqli_real_escape_string($conn, $user_id);
        $sql  = "INSERT INTO food_logs (user_id, nama_makanan, jumlah_gram, kalori, protein, tanggal)
                 VALUES ('$uid', '$nm', '$gr', '$kal', '$prot', '$tgl')";
        $save_msg = mysqli_query($conn, $sql) ? "✅ Berhasil disimpan!" : "⚠️ Gagal: " . mysqli_error($conn);
    } else {
        $save_msg = "⚠️ Belum login — data tidak tersimpan.";
    }
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$today_foods = [];
$today_kcal = 0; $today_protein = 0;
if ($user_id) {
    $uid  = mysqli_real_escape_string($conn, $user_id);
    $today = date('Y-m-d');
    $res  = mysqli_query($conn, "SELECT * FROM food_logs WHERE user_id='$uid' AND tanggal='$today' ORDER BY id DESC");
    while ($r = mysqli_fetch_assoc($res)) { $today_foods[] = $r; $today_kcal += $r['kalori']; $today_protein += $r['protein']; }
}
$target_kcal = isset($_SESSION['tdee']) ? (int)$_SESSION['tdee'] : 2000;
if (isset($_SESSION['goal'])) {
    if ($_SESSION['goal'] === 'lose_weight') $target_kcal -= 500;
    if ($_SESSION['goal'] === 'gain_weight') $target_kcal += 300;
}
$log_pct = $target_kcal > 0 ? min(round(($today_kcal / $target_kcal) * 100), 100) : 0;
?>

<style>
.ft-wrap *{ box-sizing:border-box; }

.ft-header  { margin-bottom:20px; }
.ft-title   { margin:0; font-weight:800; color:#1a1a2e; font-size:1.5rem; letter-spacing:-.3px; }
.ft-sub     { margin:3px 0 0; color:#aaa; font-size:.84rem; }

/* ── Meal type tabs ── */
.meal-tabs  { display:flex; gap:8px; margin-bottom:22px; flex-wrap:wrap; }
.meal-tab   { display:inline-flex; align-items:center; gap:7px; padding:9px 18px; border-radius:50px;
              border:1.5px solid #eee; background:#fff; cursor:pointer; font-weight:600;
              font-size:.83rem; transition:all .2s; color:#777; white-space:nowrap; }
.meal-tab:hover { border-color:#ddd; transform:translateY(-1px); }
.meal-tab.active { color:#fff; border-color:transparent; box-shadow:0 4px 14px rgba(0,0,0,.15); }

.ft-grid    { display:grid; grid-template-columns:1.15fr 1fr; gap:20px; }

.ft-card    { background:#fff; border-radius:18px; padding:24px; box-shadow:0 4px 18px rgba(0,0,0,.05); border:1.5px solid #f0f0f0; margin-bottom:16px; }
.ft-card:last-child { margin-bottom:0; }
.ft-card-title { font-weight:700; color:#1a1a2e; font-size:.97rem; margin:0 0 18px; display:flex; align-items:center; gap:8px; }

.fd-label   { font-size:.74rem; color:#aaa; font-weight:700; text-transform:uppercase; letter-spacing:.6px; margin-bottom:7px; display:block; }
.fd-input   { width:100%; padding:11px 14px; border:1.5px solid #eee; border-radius:12px;
              font-family:'Poppins',sans-serif; font-size:.9rem; outline:none; transition:.25s; background:#fafafa; }
.fd-input:focus { border-color:<?php echo $aksen; ?>; box-shadow:0 0 0 3px <?php echo $aksen; ?>18; background:#fff; }

.portion-row { display:flex; gap:7px; flex-wrap:wrap; margin-bottom:14px; }
.portion-btn { padding:6px 14px; border:1.5px solid #eee; border-radius:8px; background:#f8f9fa;
               cursor:pointer; font-size:.8rem; font-weight:700; color:#666; transition:.2s; }
.portion-btn:hover, .portion-btn.active { background:<?php echo $aksen; ?>; color:#fff; border-color:<?php echo $aksen; ?>; }

.btn-search { background:<?php echo $aksen; ?>; color:#fff; border:none; border-radius:12px; padding:12px 22px;
              font-weight:700; cursor:pointer; transition:.25s; font-family:'Poppins',sans-serif; font-size:.9rem; width:100%; margin-top:6px; }
.btn-search:hover { transform:translateY(-2px); box-shadow:0 8px 20px <?php echo $aksen; ?>44; }
.btn-search:active { transform:translateY(0); }

.result-reveal { animation:slideUp .35s ease; }
@keyframes slideUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }

.result-food-name { font-weight:800; font-size:1.05rem; color:#1a1a2e; }
.result-sub       { font-size:.78rem; color:#aaa; margin-top:2px; }
.result-kcal-badge { background:<?php echo $aksen; ?>15; border:1.5px solid <?php echo $aksen; ?>33;
                     border-radius:10px; padding:5px 14px; font-weight:800; color:<?php echo $aksen; ?>; font-size:.9rem; }

.macro-bars  { margin:16px 0; display:flex; flex-direction:column; gap:10px; }
.macro-row   { display:flex; align-items:center; gap:10px; }
.macro-label { width:80px; font-size:.76rem; font-weight:700; color:#888; text-transform:uppercase; flex-shrink:0; }
.macro-track { flex:1; height:7px; background:#f0f0f0; border-radius:50px; overflow:hidden; }
.macro-fill  { height:100%; border-radius:50px; transition:width 1s ease; }
.macro-value { width:55px; text-align:right; font-size:.82rem; font-weight:700; color:#444; flex-shrink:0; }

.save-section { margin-top:16px; padding-top:16px; border-top:1.5px dashed #f0f0f0; }
.btn-save    { background:#1a1a2e; color:#fff; border:none; border-radius:12px; padding:12px;
               font-weight:700; cursor:pointer; width:100%; transition:.25s; font-family:'Poppins',sans-serif; font-size:.9rem; }
.btn-save:hover { background:<?php echo $aksen; ?>; transform:translateY(-2px); }

.msg-ok  { background:#00b89412; border:1.5px solid #00b89433; color:#00b894; border-radius:12px; padding:11px 16px; font-size:.87rem; font-weight:600; margin-bottom:16px; }
.msg-err { background:#e1705512; border:1.5px solid #e1705533; color:#e17055; border-radius:12px; padding:11px 16px; font-size:.87rem; font-weight:600; margin-bottom:16px; }
.err-inline { background:#e1705512; border:1.5px solid #e1705533; color:#c0392b; border-radius:12px; padding:12px 16px; font-size:.87rem; font-weight:600; margin-top:14px; }

.qf-grid    { display:grid; grid-template-columns:repeat(3, 1fr); gap:8px; }
.qf-chip    { display:flex; flex-direction:column; align-items:center; gap:4px; padding:11px 6px;
              background:#f8f9fa; border:1.5px solid #f0f0f0; border-radius:13px; cursor:pointer;
              transition:.2s; text-align:center; }
.qf-chip:hover { background:<?php echo $aksen; ?>10; border-color:<?php echo $aksen; ?>; transform:translateY(-2px); box-shadow:0 4px 12px <?php echo $aksen; ?>20; }
.qf-chip:hover .qf-name { color:<?php echo $aksen; ?>; }
.qf-icon    { font-size:1.4rem; line-height:1; }
.qf-name    { font-size:.72rem; font-weight:700; color:#555; line-height:1.2; }
.qf-gram    { font-size:.66rem; color:#aaa; font-weight:600; }

.log-progress-bar { background:#f0f0f0; border-radius:50px; height:6px; margin:8px 0 14px; overflow:hidden; }
.log-progress-fill { height:100%; border-radius:50px; background:linear-gradient(90deg,<?php echo $aksen; ?>,<?php echo $aksen; ?>99); }
.log-item   { display:flex; align-items:center; gap:10px; padding:9px 0; border-bottom:1px solid #f5f5f5; }
.log-item:last-child { border-bottom:none; }
.log-dot    { width:8px; height:8px; border-radius:50%; background:<?php echo $aksen; ?>; flex-shrink:0; }
.log-food-name { font-weight:600; color:#2d3436; font-size:.86rem; flex:1; }
.log-gram   { font-size:.75rem; color:#aaa; font-weight:600; }
.log-kcal   { background:#fff3f0; color:#e17055; padding:3px 10px; border-radius:7px; font-weight:700; font-size:.78rem; flex-shrink:0; }
.log-delete { color:#ddd; font-size:.95rem; text-decoration:none; transition:.2s; flex-shrink:0; }
.log-delete:hover { color:#e17055; }

.log-empty  { text-align:center; padding:24px 0; color:#ccc; font-size:.87rem; }

.day-total  { display:flex; justify-content:space-between; align-items:center; padding:12px 16px;
              background:linear-gradient(135deg,<?php echo $aksen; ?>,<?php echo $aksen; ?>bb);
              border-radius:13px; color:#fff; margin-bottom:14px; }
.day-total-num { font-size:1.5rem; font-weight:800; line-height:1; }
.day-total-lbl { font-size:.72rem; opacity:.8; font-weight:600; text-transform:uppercase; margin-top:3px; }

.tips-card  { background:linear-gradient(135deg,#f8f9fa,#fff); }
.tip-row    { display:flex; gap:10px; align-items:flex-start; padding:8px 0; border-bottom:1px solid #f0f0f0; font-size:.84rem; color:#555; }
.tip-row:last-child { border-bottom:none; }
.tip-emoji  { font-size:1rem; flex-shrink:0; margin-top:1px; }

@media(max-width:850px){
  .ft-grid  { grid-template-columns:1fr; }
  .qf-grid  { grid-template-columns:repeat(4,1fr); }
}
@media(max-width:500px){
  .meal-tabs { gap:6px; }
  .meal-tab  { padding:7px 12px; font-size:.78rem; }
  .qf-grid   { grid-template-columns:repeat(3,1fr); }
}
</style>

<form id="quickForm" method="POST" style="display:none;">
  <input type="hidden" id="qf_query" name="query">
  <input type="hidden" id="qf_gram"  name="gram">
  <button type="submit" name="search"></button>
</form>

<div class="ft-wrap">
  <div class="ft-header">
    <h2 class="ft-title">Food Tracking 🍽️</h2>
    <p class="ft-sub">Cari makanan & catat asupan harianmu dengan mudah.</p>
  </div>

  <?php if($save_msg): ?>
  <div class="<?php echo strpos($save_msg,'✅')!==false ? 'msg-ok' : 'msg-err'; ?>"><?php echo htmlspecialchars($save_msg); ?></div>
  <?php endif; ?>

  <div class="meal-tabs" id="mealTabs">
    <?php foreach($meal_types as $key => $mt): ?>
    <div class="meal-tab" id="tab_<?php echo $key; ?>" data-color="<?php echo $mt['color']; ?>"
         onclick="selectMeal('<?php echo $key; ?>','<?php echo $mt['color']; ?>')">
      <?php echo $mt['icon'] . ' ' . $mt['label']; ?>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="ft-grid">
    <div>
      <div class="ft-card">
        <p class="ft-card-title">🔍 Cari Nutrisi Makanan</p>
        <p style="font-size:.82rem;color:#aaa;margin:-10px 0 16px">Gunakan nama dalam Bahasa Inggris untuk hasil terbaik.</p>

        <form method="POST" id="searchForm">
          <div style="margin-bottom:14px;">
            <span class="fd-label">Nama Makanan</span>
            <input type="text" name="query" id="queryInput" class="fd-input"
                   placeholder="Contoh: chicken breast, white rice..."
                   value="<?php echo isset($_POST['query']) ? htmlspecialchars($_POST['query']) : ''; ?>" required>
          </div>

          <div style="margin-bottom:14px;">
            <span class="fd-label">Porsi (gram)</span>
            <div class="portion-row" id="portionBtns">
              <?php foreach([50,100,150,200,250] as $pg): ?>
              <button type="button" class="portion-btn <?php echo ($user_gram==$pg)?'active':''; ?>"
                      onclick="setPortion(<?php echo $pg; ?>, this)"><?php echo $pg; ?>g</button>
              <?php endforeach; ?>
              <span style="font-size:.78rem;color:#aaa;align-self:center;font-weight:600;">Custom:</span>
            </div>
            <input type="number" name="gram" id="gramInput" class="fd-input" placeholder="Masukkan gram..."
                   min="1" max="5000" value="<?php echo $user_gram ?: ''; ?>" required
                   oninput="clearPortionActive()">
          </div>

          <button type="submit" name="search" class="btn-search">🔍 Cari Nutrisi</button>
        </form>

        <?php if($error_msg): ?>
        <div class="err-inline">❌ <?php echo htmlspecialchars($error_msg); ?></div>
        <?php endif; ?>

        <?php if($search_done): ?>
        <div style="margin-top:20px;padding-top:18px;border-top:1.5px solid #f0f0f0;" class="result-reveal">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:8px;margin-bottom:4px;">
            <div>
              <div class="result-food-name">🍎 <?php echo htmlspecialchars($food_name); ?></div>
              <div class="result-sub">Per <?php echo $user_gram; ?>g · data dari Spoonacular</div>
            </div>
            <div class="result-kcal-badge"><?php echo $kalori; ?> kcal</div>
          </div>

          <div class="macro-bars">
            <?php
            $macros = [
              ['label'=>'🔥 Kalori', 'val'=>$kalori,  'unit'=>'kcal', 'max'=>800,  'color'=>'#e17055'],
              ['label'=>'💪 Protein','val'=>$protein, 'unit'=>'g',    'max'=>100,  'color'=>'#6c5ce7'],
              ['label'=>'🥑 Lemak',  'val'=>$fat,     'unit'=>'g',    'max'=>80,   'color'=>'#00b894'],
              ['label'=>'🍞 Karbo',  'val'=>$carbs,   'unit'=>'g',    'max'=>150,  'color'=>'#fdcb6e'],
            ];
            foreach($macros as $m):
              $pct = min(round(($m['val']/$m['max'])*100), 100);
            ?>
            <div class="macro-row">
              <span class="macro-label"><?php echo $m['label']; ?></span>
              <div class="macro-track"><div class="macro-fill" style="width:<?php echo $pct; ?>%;background:<?php echo $m['color']; ?>;"></div></div>
              <span class="macro-value"><?php echo $m['val']; ?><?php echo $m['unit']; ?></span>
            </div>
            <?php endforeach; ?>
          </div>

          <div class="save-section">
            <form method="POST">
              <input type="hidden" name="simpan_makan" value="1">
              <input type="hidden" name="nama"    value="<?php echo htmlspecialchars($food_name); ?>">
              <input type="hidden" name="kalori"  value="<?php echo $kalori; ?>">
              <input type="hidden" name="protein" value="<?php echo $protein; ?>">
              <input type="hidden" name="gram"    value="<?php echo $user_gram; ?>">
              <div style="margin-bottom:12px;">
                <span class="fd-label">Tanggal</span>
                <input type="date" name="tanggal" class="fd-input"
                       value="<?php echo date('Y-m-d'); ?>"
                       max="<?php echo date('Y-m-d'); ?>" required>
              </div>
              <button type="submit" class="btn-save">💾 Simpan ke Jurnal</button>
            </form>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <div>
      <div class="ft-card">
        <p class="ft-card-title">⚡ Makanan Populer</p>
        <p style="font-size:.8rem;color:#aaa;margin:-10px 0 14px">Klik untuk langsung mencari nutrisi.</p>
        <div class="qf-grid">
          <?php foreach($quick_foods as $qf): ?>
          <div class="qf-chip" onclick="quickSearch('<?php echo $qf['query']; ?>',<?php echo $qf['gram']; ?>)" title="<?php echo $qf['query']; ?> · <?php echo $qf['gram']; ?>g">
            <span class="qf-icon"><?php echo $qf['icon']; ?></span>
            <span class="qf-name"><?php echo $qf['label']; ?></span>
            <span class="qf-gram"><?php echo $qf['gram']; ?>g</span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="ft-card">
        <p class="ft-card-title">📋 Log Hari Ini</p>

        <div class="day-total">
          <div>
            <div class="day-total-num"><?php echo number_format($today_kcal); ?> <span style="font-size:.85rem;opacity:.8">kcal</span></div>
            <div class="day-total-lbl">dari target <?php echo number_format($target_kcal); ?> kcal</div>
          </div>
          <div style="text-align:right;">
            <div style="font-size:1.3rem;font-weight:800;"><?php echo $log_pct; ?>%</div>
            <div style="font-size:.72rem;opacity:.8;">Protein: <?php echo round($today_protein,1); ?>g</div>
          </div>
        </div>
        <div class="log-progress-bar"><div class="log-progress-fill" style="width:<?php echo $log_pct; ?>%"></div></div>

        <?php if(empty($today_foods)): ?>
        <div class="log-empty">
          <div style="font-size:2rem;margin-bottom:8px;">🍽️</div>
          Belum ada makanan tercatat hari ini.<br>
          <span style="font-size:.78rem;">Gunakan form di kiri untuk menambahkan!</span>
        </div>
        <?php else: ?>
        <div style="max-height:240px;overflow-y:auto;">
          <?php foreach($today_foods as $f): ?>
          <div class="log-item">
            <div class="log-dot"></div>
            <div class="log-food-name"><?php echo htmlspecialchars(ucfirst($f['nama_makanan'])); ?></div>
            <span class="log-gram"><?php echo $f['jumlah_gram']; ?>g</span>
            <span class="log-kcal"><?php echo number_format($f['kalori']); ?></span>
            <a href="delete_log.php?id=<?php echo $f['id']; ?>&type=food"
               onclick="return confirm('Hapus item ini?')" class="log-delete" title="Hapus">🗑️</a>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

      <div class="ft-card tips-card">
        <p class="ft-card-title">💡 Panduan Nutrisi</p>
        <div>
          <div class="tip-row"><span class="tip-emoji">🔥</span><span><b>Kalori</b> — energi total dari makanan.</span></div>
          <div class="tip-row"><span class="tip-emoji">💪</span><span><b>Protein</b> — bangun otot. Target: <b>1.2–2g/kg</b> berat badan.</span></div>
          <div class="tip-row"><span class="tip-emoji">🥑</span><span><b>Lemak sehat</b> — dukung hormon & vitamin. Targetkan <b>20–35%</b> kalori.</span></div>
          <div class="tip-row"><span class="tip-emoji">🍞</span><span><b>Karbohidrat</b> — sumber energi. Pilih yang kompleks (nasi merah, oat, ubi).</span></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
let activeMealColor = null;
function selectMeal(key, color) {
    document.querySelectorAll('.meal-tab').forEach(t => {
        t.classList.remove('active');
        t.style.background = '';
        t.style.color = '';
    });
    const tab = document.getElementById('tab_' + key);
    tab.classList.add('active');
    tab.style.background = color;
    activeMealColor = color;
    document.querySelectorAll('.btn-search,.btn-save').forEach(b => b.style.background = color);
}

function setPortion(gram, btn) {
    document.getElementById('gramInput').value = gram;
    document.querySelectorAll('.portion-btn').forEach(b => b.classList.remove('active'));
    if(btn) btn.classList.add('active');
}
function clearPortionActive() {
    document.querySelectorAll('.portion-btn').forEach(b => b.classList.remove('active'));
}

function quickSearch(name, gram) {
    document.getElementById('qf_query').value = name;
    document.getElementById('qf_gram').value  = gram;
    document.getElementById('quickForm').submit();
}

(function() {
    const h = new Date().getHours();
    if      (h < 10) selectMeal('sarapan',     '<?php echo $meal_types["sarapan"]["color"]; ?>');
    else if (h < 14) selectMeal('makan_siang', '<?php echo $meal_types["makan_siang"]["color"]; ?>');
    else if (h < 19) selectMeal('makan_malam', '<?php echo $meal_types["makan_malam"]["color"]; ?>');
    else             selectMeal('camilan',      '<?php echo $meal_types["camilan"]["color"]; ?>');
})();
</script>