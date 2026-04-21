<?php
// Included inside tracking.php — $aksen, $jk, $conn are available from parent scope
include_once 'koneksi.php';

$user_id   = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$berat_badan = isset($_SESSION['berat']) ? (float)$_SESSION['berat'] : 65; // kg dari session

// MET values untuk berbagai aktivitas
$aktivitas_list = [
    'Lari (ringan, 6km/h)'       => 7.0,
    'Lari (sedang, 9km/h)'       => 9.8,
    'Lari (cepat, 12km/h)'       => 12.3,
    'Bersepeda (santai)'         => 4.0,
    'Bersepeda (sedang)'         => 8.0,
    'Angkat Beban'               => 5.0,
    'HIIT / Kardio Intensitas Tinggi' => 10.0,
    'Renang'                     => 7.0,
    'Yoga / Stretching'          => 2.5,
    'Jalan Kaki (santai)'        => 2.8,
    'Jalan Kaki (cepat)'         => 4.3,
    'Badminton'                  => 5.5,
    'Sepak Bola'                 => 7.0,
    'Basket'                     => 6.5,
    'Zumba / Aerobik'            => 7.5,
    'Pilates'                    => 3.5,
    'Push-up / Sit-up (calisthenics)' => 4.0,
    'Mendaki / Hiking'           => 6.0,
    'Berenang (cepat)'           => 10.0,
    'Lainnya (aktivitas umum)'   => 4.0,
];

// Proses simpan aktivitas
$msg_ok  = '';
$msg_err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_aktivitas'])) {
    if ($user_id) {
        $jenis        = mysqli_real_escape_string($conn, $_POST['jenis_aktivitas']);
        $durasi       = (int)$_POST['durasi'];
        $kalori_bakar = (int)$_POST['kalori_bakar'];
        $tanggal      = mysqli_real_escape_string($conn, $_POST['tanggal']);

        $sql = "INSERT INTO activity_logs (user_id, jenis_aktivitas, durasi_menit, kalori_terbakar, tanggal)
                VALUES ('$user_id', '$jenis', '$durasi', '$kalori_bakar', '$tanggal')";

        if (mysqli_query($conn, $sql)) {
            $msg_ok = "Aktivitas berhasil disimpan! 🎉";
        } else {
            $msg_err = "Gagal menyimpan: " . mysqli_error($conn);
        }
    } else {
        $msg_err = "Kamu belum login. Data tidak tersimpan ke database, tapi kalori sudah dihitung!";
    }
}

// Ambil log aktivitas hari ini
$hari_ini   = date('Y-m-d');
$logs_today = [];
$total_burn = 0;

if ($user_id) {
    $uid = mysqli_real_escape_string($conn, $user_id);
    $res = mysqli_query($conn, "SELECT * FROM activity_logs WHERE user_id='$uid' AND tanggal='$hari_ini' ORDER BY id DESC");
    while ($r = mysqli_fetch_assoc($res)) {
        $logs_today[] = $r;
        $total_burn  += (int)$r['kalori_terbakar'];
    }
}
?>

<style>
.act-grid       { display:grid; grid-template-columns:1.2fr 1fr; gap:22px; }
.act-card       { background:#fff; border-radius:18px; padding:26px; box-shadow:0 4px 14px rgba(0,0,0,.05); border:1.5px solid #f2f2f2; }
.act-label      { font-size:.78rem; color:#aaa; font-weight:600; text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px; display:block; }
.act-input      { width:100%; padding:11px 14px; border:1.5px solid #eee; border-radius:12px; font-family:'Poppins',sans-serif; font-size:.9rem; outline:none; transition:.25s; box-sizing:border-box; }
.act-input:focus{ border-color:<?php echo $aksen; ?>; box-shadow:0 0 0 3px <?php echo $aksen; ?>18; }
.act-select     { appearance:none; background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23aaa' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E") no-repeat right 14px center white; }
.result-box     { background:<?php echo $aksen; ?>0f; border:1.5px solid <?php echo $aksen; ?>33; border-radius:15px; padding:18px; text-align:center; margin:16px 0; display:none; }
.result-box .num{ font-size:2.4rem; font-weight:700; color:<?php echo $aksen; ?>; line-height:1; }
.result-box .sub{ font-size:.82rem; color:#888; margin-top:4px; }
.btn-hitung     { background:<?php echo $aksen; ?>; color:white; border:none; border-radius:12px; padding:12px 24px; font-weight:700; cursor:pointer; transition:.25s; font-family:'Poppins',sans-serif; width:100%; font-size:.9rem; }
.btn-hitung:hover{ transform:translateY(-2px); box-shadow:0 8px 20px <?php echo $aksen; ?>44; }
.btn-simpan     { background:#2d3436; color:white; border:none; border-radius:12px; padding:12px 24px; font-weight:700; cursor:pointer; transition:.25s; font-family:'Poppins',sans-serif; width:100%; font-size:.9rem; display:none; }
.btn-simpan:hover{ background:<?php echo $aksen; ?>; transform:translateY(-2px); }
.log-row        { display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #f5f5f5; font-size:.88rem; }
.log-row:last-child{ border-bottom:none; }
.kal-chip       { background:#fff3f0; color:#e17055; padding:3px 12px; border-radius:8px; font-weight:700; font-size:.82rem; }
.msg-ok         { background:#00b89415; border:1.5px solid #00b89433; color:#00b894; border-radius:12px; padding:12px 16px; font-size:.88rem; font-weight:600; margin-bottom:16px; }
.msg-err        { background:#e1705515; border:1.5px solid #e1705533; color:#e17055; border-radius:12px; padding:12px 16px; font-size:.88rem; font-weight:600; margin-bottom:16px; }
.total-burn-box { background:linear-gradient(135deg,#e17055,#d63031); color:white; border-radius:15px; padding:18px 22px; display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; }
@media(max-width:850px){ .act-grid{grid-template-columns:1fr;} }
</style>

<div style="margin-bottom:28px;">
  <h2 style="margin:0;font-weight:700;color:#2d3436;">Activity Tracking ⚡</h2>
  <p style="margin:4px 0 0;color:#aaa;font-size:.88rem;">Catat olahraga dan hitung kalori yang terbakar.</p>
</div>

<?php if($msg_ok): ?><div class="msg-ok">✅ <?php echo $msg_ok; ?></div><?php endif; ?>
<?php if($msg_err): ?><div class="msg-err">⚠️ <?php echo $msg_err; ?></div><?php endif; ?>

<div class="act-grid">
  <!-- Form -->
  <div class="act-card">
    <p style="font-weight:700;color:#2d3436;font-size:1rem;margin:0 0 20px">🏃 Catat Aktivitas</p>

    <div style="margin-bottom:14px;">
      <span class="act-label">Jenis Aktivitas</span>
      <select id="jenis" class="act-input act-select" onchange="hitungKalori()">
        <option value="">-- Pilih Aktivitas --</option>
        <?php foreach($aktivitas_list as $nama => $met): ?>
          <option value="<?php echo htmlspecialchars($nama); ?>" data-met="<?php echo $met; ?>">
            <?php echo htmlspecialchars($nama); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
      <div>
        <span class="act-label">Durasi (menit)</span>
        <input type="number" id="durasi" class="act-input" placeholder="Contoh: 30" min="1" max="600" oninput="hitungKalori()">
      </div>
      <div>
        <span class="act-label">Berat Badan (kg)</span>
        <input type="number" id="berat" class="act-input" value="<?php echo $berat_badan; ?>" min="30" max="200" oninput="hitungKalori()">
      </div>
    </div>

    <button type="button" class="btn-hitung" onclick="hitungKalori()">🧮 Hitung Kalori</button>

    <!-- Result Box -->
    <div class="result-box" id="resultBox">
      <div class="num" id="resultNum">0</div>
      <div class="sub" id="resultSub">kalori diperkirakan terbakar</div>
    </div>

    <!-- Save Form -->
    <form method="POST" id="saveForm" style="display:none;margin-top:12px;">
      <input type="hidden" name="simpan_aktivitas" value="1">
      <input type="hidden" name="jenis_aktivitas" id="hidden_jenis">
      <input type="hidden" name="durasi" id="hidden_durasi">
      <input type="hidden" name="kalori_bakar" id="hidden_kalori">
      
      <div style="margin-bottom:14px;">
        <span class="act-label">Waktu</span>
        <select name="tanggal" class="act-input act-select">
          <option value="<?php echo date('Y-m-d'); ?>">Hari Ini (<?php echo date('d M'); ?>)</option>
          <option value="<?php echo date('Y-m-d', strtotime('-1 day')); ?>">Kemarin (<?php echo date('d M', strtotime('-1 day')); ?>)</option>
        </select>
      </div>

      <button type="submit" class="btn-simpan" id="btnSimpan">💾 Simpan ke Jurnal</button>
    </form>
  </div>

  <!-- Log Today -->
  <div>
    <!-- Total Burn Card -->
    <div class="total-burn-box">
      <div>
        <div style="font-size:.78rem;opacity:.8;font-weight:600;letter-spacing:.5px;text-transform:uppercase;">Kalori Terbakar Hari Ini</div>
        <div style="font-size:2rem;font-weight:700;line-height:1.1;margin-top:4px;"><?php echo number_format($total_burn); ?> <span style="font-size:1rem;opacity:.8">kcal</span></div>
      </div>
      <div style="font-size:2.5rem;">🔥</div>
    </div>

    <div class="act-card">
      <p style="font-weight:700;color:#2d3436;font-size:1rem;margin:0 0 16px">📋 Aktivitas Hari Ini</p>

      <?php if(empty($logs_today)): ?>
        <div style="text-align:center;padding:30px 0;color:#ccc;">
          <div style="font-size:2.5rem;">🏋️</div>
          <p style="margin:10px 0 0;font-size:.88rem;">Belum ada aktivitas tercatat hari ini.</p>
        </div>
      <?php else: ?>
        <?php foreach($logs_today as $log): ?>
        <div class="log-row">
          <div>
            <div style="font-weight:600;color:#2d3436;font-size:.9rem;"><?php echo htmlspecialchars($log['jenis_aktivitas']); ?></div>
            <div style="color:#aaa;font-size:.78rem;margin-top:2px;"><?php echo $log['durasi_menit']; ?> menit</div>
          </div>
          <span class="kal-chip">-<?php echo number_format($log['kalori_terbakar']); ?> kcal</span>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
function hitungKalori() {
    const jenisEl  = document.getElementById('jenis');
    const durasi   = parseFloat(document.getElementById('durasi').value) || 0;
    const berat    = parseFloat(document.getElementById('berat').value) || 65;
    const met      = parseFloat(jenisEl.selectedOptions[0]?.dataset.met || 0);
    const jenis    = jenisEl.value;

    const box    = document.getElementById('resultBox');
    const form   = document.getElementById('saveForm');
    const btnS   = document.getElementById('btnSimpan');

    if (!jenis || durasi < 1 || !met) { box.style.display='none'; form.style.display='none'; return; }

    // Kalori = MET × berat (kg) × durasi (jam)
    const kalori = Math.round(met * berat * (durasi / 60));

    document.getElementById('resultNum').textContent = kalori.toLocaleString('id-ID');
    document.getElementById('resultSub').textContent = `kalori diperkirakan terbakar dalam ${durasi} menit`;
    box.style.display = 'block';

    // Populate hidden fields
    document.getElementById('hidden_jenis').value  = jenis;
    document.getElementById('hidden_durasi').value = durasi;
    document.getElementById('hidden_kalori').value = kalori;

    form.style.display = 'block';
    btnS.style.display = 'block';
}
</script>