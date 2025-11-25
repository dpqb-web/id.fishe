CREATE TABLE IF NOT EXISTS 'pengguna' (
  'username' VARCHAR(255) PRIMARY KEY,
  'fullname' TEXT,
  'password' TEXT,
  'penjual' BOOLEAN DEFAULT FALSE
);
CREATE TABLE IF NOT EXISTS 'pengikut' (
  -- 'ID' INT PRIMARY KEY AUTO_INCREMENT, -- MySQL/MariaDB
  'mengikuti' VARCHAR(255),
  'diikuti' VARCHAR(255),
  FOREIGN KEY ('mengikuti') REFERENCES 'pengguna'('username'),
  FOREIGN KEY ('diikuti') REFERENCES 'pengguna'('username')
);
CREATE TABLE IF NOT EXISTS 'barang' (
  'ID' VARCHAR(255) PRIMARY KEY,
  'judul' TEXT,
  'harga' UNSIGNED BIGINT DEFAULT 0,
  'pemilik' VARCHAR(255),
  'keterangan' LONGTEXT,
  FOREIGN KEY ('pemilik') REFERENCES 'pengguna'('username')
);
CREATE TABLE IF NOT EXISTS 'keranjang' (
  -- 'ID' INT PRIMARY KEY AUTO_INCREMENT, -- MySQL/MariaDB
  'pembeli' VARCHAR(255),
  'barang' VARCHAR(255),
  'jumlah' UNSIGNED INT DEFAULT 1,
  FOREIGN KEY ('pembeli') REFERENCES 'pengguna'('username'),
  FOREIGN KEY ('barang') REFERENCES 'barang'('ID')
);
CREATE TABLE IF NOT EXISTS 'transaksi' (
  -- 'ID' INT PRIMARY KEY AUTO_INCREMENT, -- MySQL/MariaDB
  'pembeli' VARCHAR(255),
  'barang' VARCHAR(255),
  'jumlah' UNSIGNED INT DEFAULT 1,
  'tanggal' DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY ('pembeli') REFERENCES 'pengguna'('username'),
  FOREIGN KEY ('barang') REFERENCES 'barang'('ID')
);
CREATE TABLE IF NOT EXISTS 'ulasan' (
  -- 'ID' INT PRIMARY KEY AUTO_INCREMENT, -- MySQL/MariaDB
  'pembeli' VARCHAR(255),
  'barang' VARCHAR(255),
  'bintang' UNSIGNED TINYINT,
  'komentar' TEXT,
  FOREIGN KEY ('pembeli') REFERENCES 'pengguna'('username'),
  FOREIGN KEY ('barang') REFERENCES 'barang'('ID')
);
