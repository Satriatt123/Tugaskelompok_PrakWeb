<?php
@var
$aksen = isset($aksen) ? $aksen : '#e17055';
$user_id     = isset($_SESSION['user_id'])  ? $_SESSION['user_id']     : null;
$berat_badan = isset($_SESSION['berat']) ? (float)$_SESSION['berat'] : 60;
$api_key     = "dD5k8UAKA18Lkwwc5VQ7uSC3gdsSzm2MeJxT4luP"; 

$aktivitas_data = [
    'Lari (sedang, 9km/h)' => ['met'=>9.8,  'icon'=>'🏃‍♂️', 'cat'=>'cardio'],
    'HIIT / Kardio Intens' => ['met'=>10.0, 'icon'=>'🔥',  'cat'=>'cardio'],
    'Bersepeda (sedang)'   => ['met'=>8.0,  'icon'=>'🚵',  'cat'=>'cardio'],
    'Berenang (santai)'    => ['met'=>7.0,  'icon'=>'🏊',  'cat'=>'olahraga'],
    'Jalan Kaki (santai)'  => ['met'=>2.8,  'icon'=>'🚶',  'cat'=>'ringan'],
    'Yoga / Stretching'    => ['met'=>2.5,  'icon'=>'🧘',  'cat'=>'ringan'],
];

$search_query = "";
$msg_err = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_search_api'])) {
    $search_query = trim($_POST['search_query']);
    
    $weight_lb = $berat_badan * 2.20462; 
    
    $url = "https://api.api-ninjas.com/v1/caloriesburned?activity=" . urlencode($search_query) . "&weight=" . $weight_lb;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-Api-Key: " . $api_key]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $res = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200 && $res) {
        $data = json_decode($res, true);
        if (!empty($data)) {
            $hasil_pencarian = [];
            foreach ($data as $act) {
                $met_kalkulasi = round($act['calories_per_hour'] / $berat_badan, 1);
                
                $nama_act = ucwords($act['name']);
                $hasil_pencarian[$nama_act] = [
                    'met'  => $met_kalkulasi > 0 ? $met_kalkulasi : 1.0,
                    'icon' => '⚡',
                    'cat'  => 'pencarian'
                ];
            }
            $aktivitas_data = array_merge($hasil_pencarian, $aktivitas_data);
        } else {
            $msg_err = "Tidak ada aktivitas yang cocok dengan kata kunci tersebut.";
        }
    } else {
        if ($http_code == 400 || $http_code == 401 || $http_code == 403) {
            $msg_err = "API Key tidak valid atau limit habis. Pastikan API Key sudah benar.";
        } else {
            $msg_err = "Gagal menghubungi server API Ninjas. Kode: $http_code";
        }
    }
}

$msg_ok  = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_aktivitas'])) {
    if ($user_id) {
        $jenis        = mysqli_real_escape_string($conn, $_POST['jenis_aktivitas']);
        $durasi       = (int)$_POST['durasi'];
        $kalori_bakar = (int)$_POST['kalori_bakar'];
        $tanggal      = mysqli_real_escape_string($conn, $_POST['tanggal']);
        $sql = "INSERT INTO activity_logs (user_id, jenis_aktivitas, durasi_menit, kalori_terbakar, tanggal)
                VALUES ('$user_id', '$jenis', '$durasi', '$kalori_bakar', '$tanggal')";
        if (mysqli_query($conn, $sql)) {
            $msg_ok  = "Aktivitas berhasil disimpan! 🎉";
        } else {
            $msg_err = "Gagal menyimpan: " . mysqli_error($conn);
        }
    } else {
        $msg_err = "Belum login — data tidak tersimpan.";
    }
}

$hari_ini   = date('Y-m-d');
$logs_today = [];
$total_burn = 0;
if ($user_id) {
    $uid = mysqli_real_escape_string($conn, $user_id);
    $res = mysqli_query($conn, "SELECT * FROM activity_logs WHERE user_id='$uid' AND tanggal='$hari_ini' ORDER BY id DESC");
    while ($r = mysqli_fetch_assoc($res)) { $logs_today[] = $r; $total_burn += (int)$r['kalori_terbakar']; }
}

$categories = [
    'all'       => ['label'=>'Semua',      'icon'=>'✨', 'color'=>'#636e72'],
    'pencarian' => ['label'=>'Hasil Cari', 'icon'=>'🔍', 'color'=>'#d63031'],
    'cardio'    => ['label'=>'Cardio',     'icon'=>'❤️', 'color'=>'#e17055'],
    'olahraga'  => ['label'=>'Olahraga',   'icon'=>'🏅', 'color'=>'#0984e3'],
    'ringan'    => ['label'=>'Ringan',     'icon'=>'🌿', 'color'=>'#00b894'],
];

function intensityLabel($met) {
    if ($met <= 3.5)  return ['label'=>'Ringan',   'color'=>'#00b894'];
    if ($met <= 6.5)  return ['label'=>'Sedang',   'color'=>'#fdcb6e'];
    if ($met <= 9.0)  return ['label'=>'Tinggi',   'color'=>'#e17055'];
    return                  ['label'=>'Intens',   'color'=>'#d63031'];
}
?>

<style>
.at-wrap * { box-sizing:border-box; }
.at-grid { display:grid; grid-template-columns:1.4fr 1fr; gap:22px; }

.at-card { background:#fff; border-radius:18px; padding:24px; box-shadow:0 4px 18px rgba(0,0,0,.05); border:1.5px solid #f0f0f0; margin-bottom:16px; }
.at-card:last-child { margin-bottom:0; }
.at-card-title { font-weight:700; color:#1a1a2e; font-size:.97rem; margin:0 0 16px; }

.search-row { display:flex; gap:8px; margin-bottom:18px; }
.search-input { flex:1; padding:12px 16px; border:1.5px solid #eee; border-radius:12px; font-family:'Poppins',sans-serif; font-size:.9rem; outline:none; transition:.25s; background:#fafafa; }
.search-input:focus { border-color:<?php echo $aksen; ?>; background:#fff; box-shadow:0 0 0 3px <?php echo $aksen; ?>15; }
.search-btn { background:<?php echo $aksen; ?>; color:#fff; border:none; border-radius:12px; padding:0 20px; font-weight:700; cursor:pointer; transition:.2s; font-family:'Poppins',sans-serif; }
.search-btn:hover { background:#d63031; transform:translateY(-2px); }

.cat-tabs  { display:flex; gap:7px; margin-bottom:18px; flex-wrap:wrap; }
.cat-tab   { display:inline-flex; align-items:center; gap:5px; padding:7px 14px; border-radius:50px; border:1.5px solid #eee; background:#f8f9fa; cursor:pointer; font-weight:600; font-size:.78rem; transition:.2s; color:#666; }
.cat-tab:hover { border-color:#ddd; transform:translateY(-1px); }
.cat-tab.active { color:#fff; border-color:transparent; box-shadow:0 3px 10px rgba(0,0,0,.15); }

.act-cards-grid { display:grid; grid-template-columns:repeat(4, 1fr); gap:9px; max-height:360px; overflow-y:auto; padding-right:4px; }
.act-cards-grid::-webkit-scrollbar { width:4px; }
.act-cards-grid::-webkit-scrollbar-thumb { background:#eee; border-radius:10px; }

.act-item   { display:flex; flex-direction:column; align-items:center; gap:5px; padding:12px 6px; background:#fafafa; border:1.5px solid #f0f0f0; border-radius:14px; cursor:pointer; transition:.2s; text-align:center; position:relative; }
.act-item:hover { transform:translateY(-2px); box-shadow:0 5px 16px rgba(0,0,0,.09); border-color:#ddd; }
.act-item.selected { border-color:<?php echo $aksen; ?>; background:<?php echo $aksen; ?>08; box-shadow:0 0 0 3px <?php echo $aksen; ?>18; }
.act-item.hidden    { display:none; }
.act-item-icon  { font-size:1.5rem; line-height:1; }
.act-item-name  { font-size:.68rem; font-weight:700; color:#555; line-height:1.2; }
.act-item-met   { font-size:.62rem; font-weight:700; padding:2px 7px; border-radius:6px; }

.configurator { background:linear-gradient(135deg,<?php echo $aksen; ?>0a,<?php echo $aksen; ?>18); border:1.5px solid <?php echo $aksen; ?>30; border-radius:16px; padding:20px; margin-top:16px; animation:slideUp .3s ease; display:none; }
@keyframes slideUp { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.selected-act-header { display:flex; align-items:center; gap:12px; margin-bottom:16px; }
.selected-act-icon   { font-size:2rem; }
.selected-act-name   { font-weight:800; color:#1a1a2e; font-size:1rem; }
.selected-act-met    { font-size:.78rem; color:#888; }

.dur-presets { display:flex; gap:7px; flex-wrap:wrap; margin-bottom:14px; }
.dur-btn     { padding:7px 14px; border:1.5px solid #ddd; border-radius:9px; background:#fff; cursor:pointer; font-size:.8rem; font-weight:700; color:#555; transition:.2s; }
.dur-btn:hover,.dur-btn.active { background:<?php echo $aksen; ?>; color:#fff; border-color:<?php echo $aksen; ?>; }

.dur-custom-row { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:16px; }
.at-input    { width:100%; padding:10px 14px; border:1.5px solid #eee; border-radius:11px; font-family:'Poppins',sans-serif; font-size:.9rem; outline:none; transition:.25s; background:#fff; }
.at-input:focus { border-color:<?php echo $aksen; ?>; box-shadow:0 0 0 3px <?php echo $aksen; ?>15; }
.at-label    { font-size:.74rem; color:#aaa; font-weight:700; text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px; display:block; }

.kal-result  { display:flex; justify-content:space-between; align-items:center; background:#fff; border-radius:13px; padding:14px 18px; margin-bottom:16px; box-shadow:0 2px 8px rgba(0,0,0,.06); }
.kal-big     { font-size:2rem; font-weight:800; color:<?php echo $aksen; ?>; line-height:1; }
.kal-sub     { font-size:.75rem; color:#aaa; font-weight:600; margin-top:3px; }

.btn-save-act { background:#1a1a2e; color:#fff; border:none; border-radius:12px; padding:12px; font-weight:700; cursor:pointer; width:100%; transition:.25s; font-family:'Poppins',sans-serif; font-size:.9rem; }
.btn-save-act:hover { background:<?php echo $aksen; ?>; transform:translateY(-2px); }

.burn-box { background:linear-gradient(135deg,#e17055,#d63031); color:#fff; border-radius:18px; padding:22px; display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; }
.burn-num { font-size:2rem; font-weight:800; line-height:1; }
.burn-lbl { font-size:.72rem; opacity:.8; font-weight:600; text-transform:uppercase; letter-spacing:.5px; margin-top:4px; }

.log-row     { display:flex; align-items:center; gap:10px; padding:10px 0; border-bottom:1px solid #f5f5f5; }
.log-row:last-child { border-bottom:none; }
.log-icon    { font-size:1.3rem; flex-shrink:0; }
.log-name    { font-weight:600; color:#2d3436; font-size:.87rem; flex:1; }
.log-dur     { font-size:.74rem; color:#aaa; font-weight:600; }
.log-kcal-chip { background:#fff3f0; color:#e17055; padding:3px 11px; border-radius:7px; font-weight:700; font-size:.78rem; flex-shrink:0; }
.log-empty   { text-align:center; padding:28px 0; color:#ccc; font-size:.87rem; }

.msg-ok  { background:#00b89412; border:1.5px solid #00b89433; color:#00b894; border-radius:12px; padding:11px 16px; font-size:.87rem; font-weight:600; margin-bottom:16px; }
.msg-err { background:#e1705512; border:1.5px solid #e1705533; color:#e17055; border-radius:12px; padding:11px 16px; font-size:.87rem; font-weight:600; margin-bottom:16px; }

.intensity-legend { display:flex; gap:10px; margin-top:8px; flex-wrap:wrap; }
.il-dot { display:inline-flex; align-items:center; gap:5px; font-size:.72rem; color:#888; font-weight:600; }
.il-badge { width:8px; height:8px; border-radius:50%; display:inline-block; }

@media(max-width:950px) { .at-grid { grid-template-columns:1fr; } .act-cards-grid { grid-template-columns:repeat(5,1fr); } }
@media(max-width:600px) { .act-cards-grid { grid-template-columns:repeat(4,1fr); } }
@media(max-width:420px) { .act-cards-grid { grid-template-columns:repeat(3,1fr); } }
</style>

<div style="margin-bottom:24px;">
  <h2 style="margin:0;font-weight:800;color:#1a1a2e;font-size:1.5rem;letter-spacing:-.3px;">Activity Tracking ⚡</h2>
  <p style="margin:3px 0 0;color:#aaa;font-size:.84rem;">Cari olahraga (bahasa Inggris) & hitung kalori dengan akurat.</p>
</div>

<?php if($msg_ok): ?><div class="msg-ok">✅ <?php echo $msg_ok; ?></div><?php endif; ?>
<?php if($msg_err): ?><div class="msg-err">⚠️ <?php echo $msg_err; ?></div><?php endif; ?>

<div class="at-grid">
  <div>
    <div class="at-card">
      
      <form method="POST" class="search-row">
          <input type="text" name="search_query" class="search-input" placeholder="Cari: running, yoga, weightlifting..." value="<?php echo htmlspecialchars($search_query); ?>" required>
          <button type="submit" name="btn_search_api" class="search-btn">🔍 Cari</button>
      </form>

      <div class="cat-tabs" id="catTabs">
        <?php foreach($categories as $ckey => $cat): ?>
        <div class="cat-tab <?php echo ($ckey==='all' && empty($search_query)) || ($ckey==='pencarian' && !empty($search_query)) ?'active':''; ?>"
             id="cat_<?php echo $ckey; ?>"
             data-color="<?php echo $cat['color']; ?>"
             onclick="filterCat('<?php echo $ckey; ?>','<?php echo $cat['color']; ?>')">
          <?php echo $cat['icon'] . ' ' . $cat['label']; ?>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="intensity-legend" style="margin-bottom:12px;">
        <?php foreach([['Ringan','#00b894'],['Sedang','#fdcb6e'],['Tinggi','#e17055'],['Intens','#d63031']] as $il): ?>
        <span class="il-dot"><span class="il-badge" style="background:<?php echo $il[1]; ?>"></span><?php echo $il[0]; ?></span>
        <?php endforeach; ?>
      </div>

      <div class="act-cards-grid" id="actGrid">
        <?php foreach($aktivitas_data as $nama => $data):
          $int = intensityLabel($data['met']);
          $slug = 'act_' . md5($nama);
        ?>
        <div class="act-item" id="<?php echo $slug; ?>"
             data-name="<?php echo htmlspecialchars($nama); ?>"
             data-met="<?php echo $data['met']; ?>"
             data-icon="<?php echo $data['icon']; ?>"
             data-cat="<?php echo $data['cat']; ?>"
             onclick="selectActivity(this)">
          <span class="act-item-icon"><?php echo $data['icon']; ?></span>
          <span class="act-item-name"><?php echo htmlspecialchars(explode('(',$nama)[0]); ?></span>
          <span class="act-item-met" style="background:<?php echo $int['color']; ?>20;color:<?php echo $int['color']; ?>"><?php echo $int['label']; ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="at-card" id="configurator" style="display:none;">
      <div class="selected-act-header">
        <span class="selected-act-icon" id="selIcon">🏃</span>
        <div>
          <div class="selected-act-name" id="selName">—</div>
          <div class="selected-act-met" id="selMet">MET: —</div>
        </div>
      </div>

      <p style="font-size:.78rem;color:#aaa;font-weight:700;text-transform:uppercase;letter-spacing:.5px;margin:0 0 10px;">⏱ Durasi</p>
      <div class="dur-presets" id="durBtns">
        <?php foreach([10,15,20,30,45,60,90] as $d): ?>
        <button type="button" class="dur-btn" onclick="setDur(<?php echo $d; ?>,this)"><?php echo $d; ?> min</button>
        <?php endforeach; ?>
      </div>

      <div class="dur-custom-row">
        <div>
          <span class="at-label">Durasi (menit)</span>
          <input type="number" id="durasi" class="at-input" placeholder="30" min="1" max="600" oninput="clearDurActive();hitungKalori()">
        </div>
        <div>
          <span class="at-label">Berat Badan (kg)</span>
          <input type="number" id="berat" class="at-input" value="<?php echo $berat_badan; ?>" min="30" max="200" oninput="hitungKalori()">
        </div>
      </div>

      <div class="kal-result" id="kalResult" style="display:none;">
        <div>
          <div class="kal-big" id="kalNum">0</div>
          <div class="kal-sub" id="kalSub">kalori diperkirakan terbakar</div>
        </div>
        <div style="font-size:2.5rem;">🔥</div>
      </div>

      <form method="POST" id="saveActForm" style="display:none;">
        <input type="hidden" name="simpan_aktivitas" value="1">
        <input type="hidden" name="jenis_aktivitas" id="hidden_jenis">
        <input type="hidden" name="durasi"           id="hidden_durasi">
        <input type="hidden" name="kalori_bakar"     id="hidden_kalori">
        <div style="margin-bottom:12px;">
          <span class="at-label">Tanggal</span>
          <input type="date" name="tanggal" class="at-input" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" required>
        </div>
        <button type="submit" class="btn-save-act">💾 Simpan ke Jurnal</button>
      </form>
    </div>
  </div>

  <div>
    <div class="burn-box">
      <div>
        <div class="burn-lbl">Kalori Terbakar Hari Ini</div>
        <div class="burn-num"><?php echo number_format($total_burn); ?> <span style="font-size:1rem;opacity:.8">kcal</span></div>
      </div>
      <div style="font-size:2.8rem;">🔥</div>
    </div>

    <div class="at-card">
      <p class="at-card-title">📋 Aktivitas Hari Ini</p>
      <?php if(empty($logs_today)): ?>
      <div class="log-empty">
        <div style="font-size:2rem;margin-bottom:8px;">🏋️</div>
        Belum ada aktivitas tercatat hari ini.<br>
        <span style="font-size:.78rem;">Yuk mulai gerak! 💪</span>
      </div>
      <?php else: ?>
      <?php foreach($logs_today as $log):
        $act_icon = isset($aktivitas_data[$log['jenis_aktivitas']]) ? $aktivitas_data[$log['jenis_aktivitas']]['icon'] : '⚡';
      ?>
      <div class="log-row">
        <span class="log-icon"><?php echo $act_icon; ?></span>
        <div style="flex:1;min-width:0;">
          <div class="log-name"><?php echo htmlspecialchars($log['jenis_aktivitas']); ?></div>
          <span class="log-dur"><?php echo $log['durasi_menit']; ?> menit</span>
        </div>
        <span class="log-kcal-chip">-<?php echo number_format($log['kalori_terbakar']); ?> kcal</span>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
let selectedMet  = 0;
let selectedName = '';

function filterCat(cat, color) {
    document.querySelectorAll('.cat-tab').forEach(t => { t.classList.remove('active'); t.style.background=''; t.style.color=''; });
    const tab = document.getElementById('cat_' + cat);
    if(tab) {
        tab.classList.add('active');
        tab.style.background = color;
        tab.style.color = '#fff';
    }

    document.querySelectorAll('.act-item').forEach(card => {
        const match = (cat === 'all') || (card.dataset.cat === cat);
        card.classList.toggle('hidden', !match);
    });
}

function selectActivity(el) {
    document.querySelectorAll('.act-item').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    selectedMet  = parseFloat(el.dataset.met);
    selectedName = el.dataset.name;
    const icon   = el.dataset.icon;

    document.getElementById('selIcon').textContent = icon;
    document.getElementById('selName').textContent = selectedName;
    document.getElementById('selMet').textContent  = 'MET: ' + selectedMet + ' · ' + intensityText(selectedMet);
    document.getElementById('configurator').style.display = 'block';
    document.getElementById('configurator').scrollIntoView({behavior:'smooth', block:'nearest'});
    hitungKalori();
}

function intensityText(met) {
    if (met <= 3.5)  return '🟢 Ringan';
    if (met <= 6.5)  return '🟡 Sedang';
    if (met <= 9.0)  return '🔴 Tinggi';
    return '🔴 Sangat Intens';
}

function setDur(min, btn) {
    document.getElementById('durasi').value = min;
    clearDurActive();
    if(btn) btn.classList.add('active');
    hitungKalori();
}
function clearDurActive() {
    document.querySelectorAll('.dur-btn').forEach(b => b.classList.remove('active'));
    hitungKalori();
}

function hitungKalori() {
    const durasi = parseFloat(document.getElementById('durasi').value) || 0;
    const berat  = parseFloat(document.getElementById('berat').value)  || 65;
    if (!selectedMet || durasi < 1) {
        document.getElementById('kalResult').style.display  = 'none';
        document.getElementById('saveActForm').style.display = 'none';
        return;
    }
    const kalori = Math.round(selectedMet * berat * (durasi / 60));
    document.getElementById('kalNum').textContent  = kalori.toLocaleString('id-ID');
    document.getElementById('kalSub').textContent  = `kalori terbakar dalam ${durasi} menit`;
    document.getElementById('kalResult').style.display = 'flex';

    document.getElementById('hidden_jenis').value  = selectedName;
    document.getElementById('hidden_durasi').value = durasi;
    document.getElementById('hidden_kalori').value = kalori;
    document.getElementById('saveActForm').style.display = 'block';
}

<?php if(!empty($search_query)): ?>
    filterCat('pencarian', '<?php echo $categories["pencarian"]["color"]; ?>');
<?php else: ?>
    filterCat('all', '<?php echo $categories["all"]["color"]; ?>');
<?php endif; ?>
</script>