
<?php
require_once 'config/functions.php';

// Mengambil pencarian dan kategori dari URL
$q = trim($_GET['q'] ?? '');
$kategori = (int)($_GET['kategori'] ?? 0);

// Mengambil daftar kategori
$cats = $conn->query(
    "SELECT id_kategori, nama_kategori
     FROM kategori
     ORDER BY nama_kategori ASC"
);

// Query produk
$sql = "SELECT p.*, k.nama_kategori
        FROM produk p
        LEFT JOIN kategori k ON p.id_kategori = k.id_kategori
        WHERE p.stok > 0";

$params = [];
$types = '';

// Filter pencarian
if ($q !== '') {
    $sql .= " AND (p.nama_produk LIKE ? OR p.deskripsi LIKE ?)";

    $like = "%{$q}%";

    $params[] = $like;
    $params[] = $like;
    $types .= 'ss';
}

// Filter kategori
if ($kategori > 0) {
    $sql .= " AND p.id_kategori = ?";

    $params[] = $kategori;
    $types .= 'i';
}

// Urutkan produk terbaru
$sql .= " ORDER BY p.id_produk DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Query produk gagal: " . $conn->error);
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$products = $stmt->get_result();
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>MarketKu - Marketplace Online</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="assets/css/style.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }

        /* AREA GAMBAR PRODUK */
        .product-image-box {
            width: 100%;
            height: 220px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            overflow: hidden;
        }

        /* FOTO PRODUK TIDAK TERPOTONG */
        .product-image-box img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain !important;
            object-position: center !important;
            transform: none !important;
        }

        /* KARTU PRODUK */
        .product-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            height: 100%;
            background: #ffffff;
            transition: .2s;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px #1e3a8a18;
        }

        .product-card .card-body {
            padding: 16px;
        }

        .product-card h6 {
            min-height: 38px;
        }

        /* HARGA */
        .price {
            color: #2457d6;
            font-weight: 800;
        }

        /* FORM PENCARIAN */
        .form-select,
        .form-control {
            min-height: 42px;
        }

        /* TAMPILAN HP */
        @media (max-width: 576px) {
            .product-image-box {
                height: 180px;
                padding: 10px;
            }

            .product-card .card-body {
                padding: 12px;
            }

            .hero {
                padding: 30px 20px !important;
            }
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-market">

    <div class="container">

        <a class="navbar-brand brand" href="index.php">
            MarketKu
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#nav">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="nav">

            <div class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

                <a class="nav-link" href="index.php">
                    Produk
                </a>

                <a class="nav-link" href="keranjang.php">
                    Keranjang (<?= cart_count() ?>)
                </a>

                <?php if (current_user()): ?>

                    <span class="navbar-text text-white">
                        <?= e(current_user()['nama']) ?>
                    </span>

                    <?php if (current_user()['role'] === 'admin'): ?>

                        <a class="btn btn-light btn-sm" href="admin/index.php">
                            Admin
                        </a>

                    <?php endif; ?>

                    <a class="btn btn-outline-light btn-sm" href="logout.php">
                        Keluar
                    </a>

                <?php else: ?>

                    <a class="btn btn-light btn-sm" href="login.php">
                        Login
                    </a>

                    <a class="btn btn-outline-light btn-sm" href="register.php">
                        Daftar
                    </a>

                <?php endif; ?>

            </div>
        </div>
    </div>

</nav>

<!-- KONTEN UTAMA -->
<main class="container py-4">

    <?php show_flash(); ?>

    <!-- HERO -->
    <section class="hero mb-4">

        <h1 class="display-6 fw-bold">
            Belanja mudah di MarketKu
        </h1>

        <p class="mb-0">
            Temukan produk pilihan dengan harga bersahabat.
        </p>

    </section>

    <!-- FORM PENCARIAN DAN KATEGORI -->
    <form action="index.php" method="GET" class="row g-2 mb-4">

        <!-- PENCARIAN PRODUK -->
        <div class="col-md-7">

            <input
                type="text"
                class="form-control"
                name="q"
                value="<?= e($q) ?>"
                placeholder="Cari nama produk..."
            >

        </div>

        <!-- DROPDOWN KATEGORI -->
        <div class="col-md-3">

            <select
                class="form-select"
                name="kategori"
                onchange="this.form.submit()"
            >

                <!-- SEMUA KATEGORI -->
                <option value="0"
                    <?= $kategori === 0 ? 'selected' : '' ?>>

                    Semua kategori

                </option>

                <!-- DAFTAR KATEGORI DATABASE -->
                <?php if ($cats): ?>

                    <?php while ($c = $cats->fetch_assoc()): ?>

                        <option
                            value="<?= (int)$c['id_kategori'] ?>"
                            <?= $kategori === (int)$c['id_kategori'] ? 'selected' : '' ?>
                        >

                            <?= e($c['nama_kategori']) ?>

                        </option>

                    <?php endwhile; ?>

                <?php endif; ?>

            </select>

        </div>

        <!-- TOMBOL CARI -->
        <div class="col-md-2 d-grid">

            <button type="submit" class="btn btn-primary">
                Cari Produk
            </button>

        </div>

    </form>

    <!-- JUDUL PRODUK -->
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h3 class="fw-bold">
            Daftar Produk
        </h3>

        <span class="text-secondary">
            MarketKu Online
        </span>

    </div>

    <!-- DAFTAR PRODUK -->
    <div class="row g-4">

        <?php if ($products && $products->num_rows > 0): ?>

            <?php while ($p = $products->fetch_assoc()): ?>

                <div class="col-6 col-md-4 col-lg-3">

                    <div class="card product-card shadow-sm">

                        <!-- GAMBAR PRODUK -->
                        <div class="product-image-box">

                            <img
                                src="<?= e(image_url($p['gambar'])) ?>"
                                alt="<?= e($p['nama_produk']) ?>"
                                loading="lazy"
                            >

                        </div>

                        <!-- INFORMASI PRODUK -->
                        <div class="card-body">

                            <small class="text-secondary">
                                <?= e($p['nama_kategori'] ?? 'Umum') ?>
                            </small>

                            <h6 class="fw-bold mt-1">
                                <?= e($p['nama_produk']) ?>
                            </h6>

                            <div class="price mb-1">
                                <?= rupiah($p['harga']) ?>
                            </div>

                            <small class="text-secondary">
                                Stok: <?= (int)$p['stok'] ?>
                            </small>

                            <a
                                class="btn btn-primary btn-sm w-100 mt-3"
                                href="detail.php?id=<?= (int)$p['id_produk'] ?>"
                            >
                                Lihat Detail
                            </a>

                        </div>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="col-12">

                <div class="alert alert-info">
                    Produk belum tersedia atau tidak ditemukan.
                </div>

            </div>

        <?php endif; ?>

    </div>

</main>

<!-- FOOTER -->
<footer class="text-center py-4">

    © <?= date('Y') ?> MarketKu - Marketplace Online

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>