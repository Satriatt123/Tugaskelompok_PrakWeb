<?php

$api_key = "7ec5fe9e9336419ab619234773e01783";

$kalori = $protein = $fat = $carbs = 0;
$food_name = '';
$user_gram = 0;
$search_done = false;
$error_msg = '';

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

        $info_url = "https://api.spoonacular.com/food/ingredients/$food_id/information?amount=$user_gram&unit=grams&apiKey=$api_key";
        curl_setopt($ch, CURLOPT_URL, $info_url);
        $info_res  = curl_exec($ch);
        $info_data = json_decode($info_res, true);
        curl_close($ch);

        $nutrients = $info_data['nutrition']['nutrients'] ?? [];

        function getNutrient($list, $name) {
            foreach ($list as $n) {
                if ($n['name'] === $name) return round($n['amount'], 1);
            }
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
        include_once 'koneksi.php';
        $nm   = mysqli_real_escape_string($conn, $_POST['nama']);
        $gr   = (int)$_POST['gram'];
        $kal  = (float)$_POST['kalori'];
        $prot = (float)$_POST['protein'];
        $tgl  = mysqli_real_escape_string($conn, $_POST['tanggal']);
        $uid  = mysqli_real_escape_string($conn, $user_id);

        $sql = "INSERT INTO food_logs (user_id, nama_makanan, jumlah_gram, kalori, protein, tanggal)
                VALUES ('$uid', '$nm', '$gr', '$kal', '$prot', '$tgl')";
        if (mysqli_query($conn, $sql)) {
            $save_msg = "✅ Berhasil disimpan ke jurnal!";
        } else {
            $save_msg = "⚠️ Gagal: " . mysqli_error($conn);
        }
    } else {
        $save_msg = "⚠️ Kamu belum login — data tidak tersimpan ke database.";
    }
}
?>

<style>
.food-grid     { display:grid; grid-template-columns:1fr 1fr; gap:22px; }
.food-card     { background:#fff; border-radius:18px; padding:26px; box-shadow:0 4px 14px rgba(0,0,0,.05); border:1.5px solid #f2f2f2; }
.fd-label      { font-size:.78rem; color:#aaa; font-weight:600; text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px; display:block; }
.fd-input      { width:100%; padding:11px 14px; border:1.5px solid #eee; border-radius:12px; font-family:'Poppins',sans-serif; font-size:.9rem; outline:none; transition:.25s; box-sizing:border-box; }
.fd-input:focus{ border-color:<?php echo $aksen; ?>; box-shadow:0 0 0 3px <?php echo $aksen; ?>18; }
.btn-search    { background:<?php echo $aksen; ?>; color:white; border:none; border-radius:12px; padding:12px 22px; font-weight:700; cursor:pointer; transition:.25s; font-family:'Poppins',sans-serif; white-space:nowrap; font-size:.9rem; }
.btn-search:hover{ transform:translateY(-2px); box-shadow:0 8px 20px <?php echo $aksen; ?>44; }
.result-card   { background:white; border-radius:16px; border-left:4px solid <?php echo $aksen; ?>; padding:20px; margin-top:18px; }
.macro-grid    { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; margin:16px 0; }
.macro-box     { text-align:center; background:#f8f9fa; border-radius:12px; padding:12px 8px; }
.macro-val     { font-size:1.2rem; font-weight:700; color:<?php echo $aksen; ?>; }
.macro-lbl     { font-size:.7rem; color:#aaa; font-weight:600; text-transform:uppercase; margin-top:3px; letter-spacing:.3px; }
.btn-simpan    { background:#2d3436; color:white; border:none; border-radius:12px; padding:12px; font-weight:700; cursor:pointer; width:100%; transition:.25s; font-family:'Poppins',sans-serif; font-size:.9rem; }
.btn-simpan:hover{ background:<?php echo $aksen; ?>; transform:translateY(-2px); }
.fd-select     { appearance:none; background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23aaa' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E") no-repeat right 14px center white; }
.save-msg      { border-radius:12px; padding:11px 16px; font-size:.88rem; font-weight:600; margin-bottom:14px; }
.tips-box      { background:#f8f9fa; border-radius:14px; padding:18px; font-size:.84rem; color:#666; line-height:1.7; }
.tips-box b    { color:#2d3436; }
.err-box       { background:#e1705518; border:1.5px solid #e1705533; color:#c0392b; border-radius:12px; padding:12px 16px; font-size:.88rem; font-weight:600; margin-top:14px; }
@media(max-width:800px){ .food-grid{grid-template-columns:1fr;} .macro-grid{grid-template-columns:repeat(2,1fr);} }
</style>

<div style="margin-bottom:28px;">
  <h2 style="margin:0;font-weight:700;color:#2d3436;">Food Tracking 🍽️</h2>
  <p style="margin:4px 0 0;color:#aaa;font-size:.88rem;">Cari makanan dan catat asupan kalorimu hari ini.</p>
</div>

<?php if($save_msg): ?>
<div class="save-msg" style="background:<?php echo strpos($save_msg,'✅')!==false ? '#00b89415' : '#e1705515'; ?>; border:1.5px solid <?php echo strpos($save_msg,'✅')!==false ? '#00b89433' : '#e1705533'; ?>; color:<?php echo strpos($save_msg,'✅')!==false ? '#00b894' : '#e17055'; ?>">
  <?php echo htmlspecialchars($save_msg); ?>
</div>
<?php endif; ?>

<div class="food-grid">
  <div>
    <div class="food-card">
      <p style="font-weight:700;color:#2d3436;font-size:1rem;margin:0 0 18px">🔍 Cari Nutrisi Makanan</p>
      <p style="font-size:.83rem;color:#aaa;margin:0 0 18px;">Gunakan nama makanan dalam Bahasa Inggris untuk hasil terbaik.</p>

      <form method="POST">
        <div style="margin-bottom:14px;">
          <span class="fd-label">Nama Makanan</span>
          <input type="text" name="query" class="fd-input" placeholder="Contoh: rice, chicken breast, banana..." 
                 value="<?php echo isset($_POST['query']) ? htmlspecialchars($_POST['query']) : ''; ?>" required>
        </div>
        <div style="display:flex;gap:10px;align-items:flex-end;">
          <div style="flex:1;">
            <span class="fd-label">Berat (gram)</span>
            <input type="number" name="gram" class="fd-input" placeholder="100" min="1" max="5000"
                   value="<?php echo $user_gram ?: ''; ?>" required>
          </div>
          <button type="submit" name="search" class="btn-search">Cari</button>
        </div>
      </form>

      <?php if($error_msg): ?>
        <div class="err-box">❌ <?php echo htmlspecialchars($error_msg); ?></div>
      <?php endif; ?>

      <?php if($search_done): ?>
      <div class="result-card">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:8px;">
          <div>
            <div style="font-weight:700;font-size:1rem;color:#2d3436;">🍎 <?php echo htmlspecialchars($food_name); ?></div>
            <div style="font-size:.8rem;color:#aaa;">Per <?php echo $user_gram; ?> gram</div>
          </div>
          <div style="background:<?php echo $aksen; ?>15;border:1.5px solid <?php echo $aksen; ?>33;border-radius:10px;padding:5px 14px;font-weight:700;color:<?php echo $aksen; ?>;font-size:.88rem;"><?php echo $kalori; ?> kcal</div>
        </div>

        <div class="macro-grid">
          <div class="macro-box">
            <div class="macro-val"><?php echo $kalori; ?></div>
            <div class="macro-lbl">🔥 Kalori</div>
          </div>
          <div class="macro-box">
            <div class="macro-val"><?php echo $protein; ?>g</div>
            <div class="macro-lbl">💪 Protein</div>
          </div>
          <div class="macro-box">
            <div class="macro-val"><?php echo $fat; ?>g</div>
            <div class="macro-lbl">🥑 Lemak</div>
          </div>
          <div class="macro-box">
            <div class="macro-val"><?php echo $carbs; ?>g</div>
            <div class="macro-lbl">🍞 Karbo</div>
          </div>
        </div>

        <form method="POST">
          <input type="hidden" name="simpan_makan" value="1">
          <input type="hidden" name="nama"    value="<?php echo htmlspecialchars($food_name); ?>">
          <input type="hidden" name="kalori"  value="<?php echo $kalori; ?>">
          <input type="hidden" name="protein" value="<?php echo $protein; ?>">
          <input type="hidden" name="gram"    value="<?php echo $user_gram; ?>">

          <div style="margin-bottom:12px;">
            <span class="fd-label">Catat untuk</span>
            <select name="tanggal" class="fd-input fd-select">
              <option value="<?php echo date('Y-m-d'); ?>">Hari Ini (<?php echo date('d M Y'); ?>)</option>
              <option value="<?php echo date('Y-m-d', strtotime('-1 day')); ?>">Kemarin (<?php echo date('d M Y', strtotime('-1 day')); ?>)</option>
            </select>
          </div>

          <button type="submit" class="btn-simpan">💾 Simpan ke Jurnal</button>
        </form>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <div>
    <div class="food-card" style="margin-bottom:20px;">
      <p style="font-weight:700;color:#2d3436;font-size:1rem;margin:0 0 14px">💡 Contoh Pencarian</p>
      <div style="display:flex;flex-wrap:wrap;gap:8px;">
        <?php
        $examples = ['Rice','Chicken breast','Egg','Banana','Milk','Tempeh','Tofu','Avocado','Oatmeal','Salmon'];
        foreach($examples as $ex):
        ?>
        <span onclick="document.querySelector('[name=query]').value='<?php echo $ex; ?>';document.querySelector('[name=gram]').value=100;" 
              style="background:#f0f0f0;border-radius:50px;padding:5px 14px;font-size:.8rem;cursor:pointer;transition:.2s;font-weight:500;"
              onmouseover="this.style.background='<?php echo $aksen; ?>20';this.style.color='<?php echo $aksen; ?>'"
              onmouseout="this.style.background='#f0f0f0';this.style.color='inherit'">
          <?php echo $ex; ?>
        </span>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="food-card">
      <p style="font-weight:700;color:#2d3436;font-size:1rem;margin:0 0 14px">📚 Panduan Nutrisi</p>
      <div class="tips-box">
        <b>🔥 Kalori</b> — energi total dari makanan.<br>
        <b>💪 Protein</b> — untuk membangun dan memperbaiki otot. Kebutuhan umum: <b>1,2–2g/kg</b> berat badan.<br>
        <b>🥑 Lemak</b> — lemak sehat mendukung hormon dan penyerapan vitamin. Targetkan <b>20–35%</b> dari total kalori.<br>
        <b>🍞 Karbohidrat</b> — sumber energi utama. Pilih karbohidrat kompleks (nasi merah, oat, ubi).
      </div>
    </div>
  </div>
</div>