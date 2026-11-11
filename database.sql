-- Pilih database yang telah dibuat di InfinityFree sebelum menjalankan.
CREATE TABLE users (
 id_user INT AUTO_INCREMENT PRIMARY KEY,
 nama VARCHAR(100) NOT NULL,
 email VARCHAR(100) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 role ENUM('admin','pembeli') DEFAULT 'pembeli',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE kategori (
 id_kategori INT AUTO_INCREMENT PRIMARY KEY,
 nama_kategori VARCHAR(100) NOT NULL
) ENGINE=InnoDB;
CREATE TABLE produk (
 id_produk INT AUTO_INCREMENT PRIMARY KEY,
 id_kategori INT NOT NULL,
 nama_produk VARCHAR(150) NOT NULL,
 deskripsi TEXT,
 harga DECIMAL(12,2) NOT NULL,
 stok INT NOT NULL DEFAULT 0,
 gambar VARCHAR(255) DEFAULT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori)
 ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;
CREATE TABLE keranjang (
 id_keranjang INT AUTO_INCREMENT PRIMARY KEY,
 id_user INT NOT NULL,
 id_produk INT NOT NULL,
 jumlah INT NOT NULL DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY unik_keranjang (id_user,id_produk),
 FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
 FOREIGN KEY (id_produk) REFERENCES produk(id_produk) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE pesanan (
 id_pesanan INT AUTO_INCREMENT PRIMARY KEY,
 id_user INT NOT NULL,
 tanggal_pesanan DATETIME DEFAULT CURRENT_TIMESTAMP,
 total_harga DECIMAL(12,2) NOT NULL,
 alamat_pengiriman TEXT NOT NULL,
 status ENUM('Menunggu','Diproses','Dikirim','Selesai','Dibatalkan') DEFAULT 'Menunggu',
 FOREIGN KEY (id_user) REFERENCES users(id_user)
) ENGINE=InnoDB;
CREATE TABLE detail_pesanan (
 id_detail INT AUTO_INCREMENT PRIMARY KEY,
 id_pesanan INT NOT NULL,
 id_produk INT NOT NULL,
 jumlah INT NOT NULL,
 harga DECIMAL(12,2) NOT NULL,
 subtotal DECIMAL(12,2) NOT NULL,
 FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan) ON DELETE CASCADE,
 FOREIGN KEY (id_produk) REFERENCES produk(id_produk) ON DELETE RESTRICT
) ENGINE=InnoDB;
INSERT INTO kategori(nama_kategori) VALUES
('Elektronik'),('Fashion'),('Makanan'),('Aksesoris'),('Peralatan Rumah');
INSERT INTO produk(id_kategori,nama_produk,deskripsi,harga,stok) VALUES
(1,'Headphone Bluetooth','Headphone wireless dengan kualitas suara jernih.',150000,20),
(2,'Tas Wanita','Tas wanita model modern dan elegan.',125000,15),
(3,'Keripik Pisang','Keripik pisang renyah khas Bengkulu.',25000,50),
(4,'Jam Tangan','Jam tangan casual untuk pria dan wanita.',95000,10),
(5,'Botol Minum','Botol minum praktis untuk aktivitas sehari-hari.',35000,30);
