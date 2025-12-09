INSERT INTO "pengguna"
("username", "fullname", "password", "penjual")
VALUES
('rizki', 'Muhammad Rizki Fauzan', '$2y$12$DgZM6wbH9s4G8UzGCJPIU.QKkmGAlsOBG15jpwL8DtwMhUw2Qig0.', 0),
('singgih', 'Singgih Budi Utomo', '$2y$12$e7RuRGwn1rez3RhuTNZaNOAt4NpA9PqMktfizg4/hYN4HbBAllpXi', 1),
('ivan', 'Muhammad Ivan Afandi', '$2y$12$taZI/zqFytD0ko/d4KW//OnrQrbcfxD6OOG8sQ8G4ZTEtm3O8FXUe', 0);

INSERT INTO "barang"
("ID", "nama", "harga", "pemilik", "keterangan")
VALUES
('YauRtO3gxETi', 'Lenovo Legion Go S Z1 Extreme', '12700000', 'singgih', 'Perangkat gaming ini dirancang untuk para gamer yang mencari pengalaman portabel namun bertenaga. Dilengkapi dengan layar sentuh 8 inci yang cerah, akurasi warna yang menakjubkan, dan visual yang halus, membuat game terlihat luar biasa. Dengan penyimpanan yang luas dan memori yang cepat, perangkat ini memastikan waktu muat yang cepat dan multitasking yang lancar. Pengguna memuji desainnya yang elegan dan performa yang mengesankan; kualitas layar dan responsivitasnya menjadi fitur unggulan. Ideal untuk gamer yang menginginkan perangkat berperforma tinggi untuk bermain game di mana saja, perangkat ini juga cukup serbaguna untuk memenuhi kebutuhan siapa pun yang mencari perangkat hiburan kompak namun mumpuni.'),
('LTC1LEbnMkJ2', 'Steam Deck OLED 1 TB', '16500000', 'singgih', 'Komputer gaming yang kuat dan portabel, dirancang untuk kenyamanan dan pengalaman pengguna yang mirip konsol.');

INSERT INTO "pengikut"
("mengikuti", "diikuti")
VALUES
('singgih', 'rizki'),
('singgih', 'ivan');
