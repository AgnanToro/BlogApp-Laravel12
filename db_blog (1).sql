-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 21, 2025 at 09:57 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `nama_komentator` varchar(255) NOT NULL,
  `isi_komentar` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `nama_komentator`, `isi_komentar`, `created_at`, `updated_at`) VALUES
(5, 4, 'John Doe', 'Artikel yang sangat menarik dan informatif!', '2025-08-20 11:38:20', '2025-08-20 11:38:20'),
(7, 5, 'Agnan', 'Nice Insight', '2025-08-20 11:42:44', '2025-08-20 11:42:44'),
(8, 5, 'step', 'nice info', '2025-08-20 17:56:41', '2025-08-20 17:56:41'),
(10, 9, 'Windah Basudara', 'Mantap, menambah wawasan baru', '2025-08-21 04:16:53', '2025-08-21 04:16:53'),
(11, 8, 'Vinicius Junior', 'agree', '2025-08-21 04:18:44', '2025-08-21 04:18:44'),
(15, 10, 'Fahri', 'mantap fiturnya sangat membantu', '2025-08-21 09:28:58', '2025-08-21 09:28:58');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(6, '0001_01_01_000000_create_users_table', 1),
(9, '2025_08_20_163406_create_posts_table', 1),
(10, '2025_08_20_164342_create_comments_table', 1),
(11, '2025_08_21_013945_add_role_to_users_table', 2),
(12, '2025_08_21_100000_create_password_reset_tokens_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('agnanpes1@gmail.com', '$2y$12$LKKlxeseHitQc5toMqeK5OCS7tDJwS6A8mldbutXf9hNxXc/6KNzm', '2025-08-21 09:44:24');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `konten` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `tanggal_post` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `judul`, `konten`, `foto`, `tanggal_post`, `created_at`, `updated_at`) VALUES
(4, 'Fitur Baru di PHP 8 yang Wajib Kamu Tahu!', 'PHP 8 dirilis dengan banyak peningkatan besar yang bikin performa lebih cepat, sintaks lebih rapi, dan fitur modern yang memudahkan developer. Berikut adalah fitur-fitur utama yang paling menonjol.\r\n\r\n1. JIT (Just In Time Compilation)\r\n\r\nJIT mempercepat eksekusi kode PHP dengan cara meng-compile sebagian kode menjadi machine code.\r\n\r\nPerforma aplikasi yang intensif komputasi (misalnya perhitungan matematis atau grafik) jadi lebih cepat.\r\n\r\nUntuk aplikasi web biasa, peningkatannya tidak terlalu signifikan, tapi tetap memberi stabilitas.\r\n\r\n2. Union Types\r\n\r\nSebelumnya PHP hanya mendukung satu tipe data di deklarasi.\r\n\r\nPHP 8 memperbolehkan union type misalnya:\r\n\r\nfunction foo(int|float $number) {\r\n    return $number * 2;\r\n}\r\n\r\n\r\nJadi parameter bisa menerima lebih dari satu tipe dengan jelas dan aman.\r\n\r\n3. Named Arguments\r\n\r\nSekarang kita bisa memanggil fungsi dengan menyebutkan nama argumen.\r\n\r\nContoh:\r\n\r\nfunction createUser($name, $age, $isAdmin = false) {}\r\ncreateUser(age: 25, name: \"Budi\");\r\n\r\n\r\nLebih fleksibel dan mudah dibaca, terutama untuk fungsi dengan banyak parameter opsional.\r\n\r\n4. Attributes (Annotations Native)\r\n\r\nPHP 8 mendukung atribut untuk menambahkan metadata ke class, method, atau property.\r\n\r\nContoh:\r\n\r\n#[Route(\"/home\", methods: [\"GET\"])]\r\nfunction home() {}\r\n\r\n\r\nFitur ini mirip annotations di framework modern seperti Symfony atau Laravel.\r\n\r\n5. Match Expression\r\n\r\nAlternatif yang lebih ringkas dari switch.\r\n\r\nContoh:\r\n\r\n$status = 200;\r\necho match($status) {\r\n    200 => \"OK\",\r\n    404 => \"Not Found\",\r\n    500 => \"Error\",\r\n};\r\n\r\n\r\nLebih aman karena match mengevaluasi strict (tipe data juga dicek).\r\n\r\n6. Nullsafe Operator\r\n\r\nMenghindari error saat mengakses properti dari objek null.\r\n\r\nContoh:\r\n\r\n$country = $user?->getAddress()?->getCountry();\r\n\r\n\r\nJadi tidak perlu if berlapis untuk cek null.\r\n\r\n7. Constructor Property Promotion\r\n\r\nMenyingkat penulisan konstruktor di class.\r\n\r\nContoh:\r\n\r\nclass User {\r\n    public function __construct(\r\n        private string $name,\r\n        private int $age\r\n    ) {}\r\n}\r\n\r\n\r\nTidak perlu deklarasi property + assign manual, langsung di constructor.\r\n\r\n8. Improvements Lain\r\n\r\nKonsistensi error handling (TypeError dan ValueError lebih jelas).\r\n\r\nPerformance overall meningkat dibanding PHP 7.4.\r\n\r\nSyntax lebih modern dan bersih, mendekati bahasa seperti Java atau C#.', 'posts/i76om2aHGfzjuSVNp4Eg41PMSqargceQXn7ijixK.png', '2025-08-20 11:38:20', '2025-08-20 11:38:20', '2025-08-20 19:43:23'),
(5, 'Apa itu Claude Code?', 'Claude Code adalah alat berbasis AI yang dikembangkan oleh Anthropic yang membantu pengembang dalam menulis, memahami, dan menyempurnakan kode. Alat ini beroperasi melalui terminal dan menggunakan perintah bahasa alami untuk berinteraksi dengan model Claude, memungkinkan tugas-tugas coding yang kompleks dilakukan lebih cepat dan efisien. \r\nBerikut adalah beberapa poin penting tentang Claude Code:\r\nAsisten Pengkodean Berbasis AI:\r\nClaude Code dirancang untuk membantu pengembang dalam berbagai tugas coding, mulai dari penulisan kode hingga debugging. \r\nBerbasis Terminal:\r\nBerbeda dengan beberapa alat pengkodean AI lainnya, Claude Code beroperasi melalui terminal, memungkinkannya terintegrasi dengan berbagai alur kerja pengembangan. \r\nInteraksi Bahasa Alami:\r\nPengguna dapat berinteraksi dengan Claude Code menggunakan bahasa alami untuk meminta bantuan dalam penulisan, penjelasan, atau perbaikan kode. \r\nAkses ke Model Claude:\r\nClaude Code terhubung ke server Anthropic melalui API, memungkinkan akses ke model bahasa Claude yang kuat, termasuk Claude 3.7 Sonnet, tanpa perlu menjalankan model secara lokal. \r\nAgen Pengkodean Terawasi:\r\nClaude Code dianggap sebagai agen pengkodean terawasi, yang berarti ia dapat melakukan tugas-tugas coding yang kompleks secara mandiri, meskipun dalam beberapa kasus mungkin memerlukan intervensi pengguna. \r\nManfaat Penggunaan:\r\nClaude Code dapat membantu pengembang meningkatkan produktivitas, mempercepat alur kerja, dan memahami kode dengan lebih baik. \r\nDengan kata lain, Claude Code adalah alat yang kuat yang memberdayakan pengembang dengan kecerdasan AI untuk mempermudah dan mempercepat proses pengembangan perangkat lunak', 'posts/j1i3MAfdVsGPcKDf5QcNJ0b6nx5bIZeSPfP4CqhY.png', '2025-08-20 11:42:18', '2025-08-20 11:42:18', '2025-08-20 11:42:18'),
(6, 'AI Tools untuk Programmer di 2025 yang Bikin Ngoding Lebih Produktif', 'AI Tools untuk Programmer di 2025 yang Bikin Ngoding Lebih Produktif\r\n\r\nTahun 2025 jadi era di mana AI makin erat dengan dunia programming. Sekarang, banyak developer memanfaatkan AI bukan buat gantiin, tapi buat bantu kerjaan biar lebih cepat dan efisien. Berikut beberapa AI tools yang populer dipakai programmer di 2025.\r\n\r\n1. GitHub Copilot\r\n\r\nDikembangkan oleh GitHub dan OpenAI.\r\n\r\nBisa auto-complete kode, kasih saran fungsi, sampai generate unit test.\r\n\r\nCocok untuk yang pakai VSCode, JetBrains, atau Neovim.\r\n\r\n2. Cursor AI\r\n\r\nEditor mirip VSCode tapi lebih “AI-first”.\r\n\r\nBisa ngobrol langsung dengan AI di dalam editor buat refactor, debugging, atau generate kode dari requirement.\r\n\r\nBanyak dipakai startup karena praktis dan cepat.\r\n\r\n3. ChatGPT (GPT-5)\r\n\r\nBukan cuma buat tanya teori, tapi juga bisa generate kode, jelasin error, atau kasih best practice.\r\n\r\nDengan plugin coding, bisa dipakai kayak personal mentor.\r\n\r\nBanyak developer pakai buat brainstorming arsitektur sistem.\r\n\r\n4. Codeium\r\n\r\nAlternatif gratis GitHub Copilot.\r\n\r\nSupport banyak bahasa pemrograman, ringan, dan cepat.\r\n\r\nFitur mirip auto-complete + chat AI untuk ngebantu debug.\r\n\r\n5. Tabnine\r\n\r\nAI assistant fokus di auto-completion.\r\n\r\nBagus buat tim karena bisa belajar dari codebase internal.\r\n\r\nPrivasi lebih terjaga dibanding beberapa tools lain.\r\n\r\n6. Replit Ghostwriter\r\n\r\nCocok buat pemula yang suka coding di browser.\r\n\r\nBisa bikin project cepat tanpa setup ribet.\r\n\r\nBanyak dipakai buat belajar coding interaktif.\r\n\r\n7. Copilot for Docs & Test\r\n\r\nAI yang bantu nyusun dokumentasi dan testing otomatis.\r\n\r\nBerguna buat tim besar biar standar project lebih rapi.\r\n\r\nMenghemat waktu karena dokumentasi sering jadi bagian yang paling malesin.', 'posts/kWbnKpubTNNoIvhP0y7sOALEkZmbif13lA3zCgmp.png', '2025-08-20 20:04:35', '2025-08-20 20:04:35', '2025-08-20 20:21:26'),
(8, 'Apa itu Cyber Crime? Pengertian, Jenis, dan Contoh Kasusnya', 'Teknologi telah menjadi bagian integral dari kehidupan manusia modern. Sayangnya, seiring dengan perkembangan teknologi, ancaman digital seperti cyber crime juga terus meningkat. Cyber crime adalah jenis kejahatan yang dilakukan melalui internet dengan memanfaatkan perangkat digital sebagai alat atau sasaran kejahatan. Artikel ini akan membahas secara rinci pengertian cyber crime, jenis-jenisnya, dampaknya, serta cara mencegahnya.\r\n\r\nPengertian Cyber Crime\r\nApa itu cyber crime? Secara sederhana, cyber crime adalah kejahatan yang dilakukan di dunia maya menggunakan teknologi komputer dan jaringan internet. Menurut para ahli, pengertian cyber crime adalah segala bentuk tindakan ilegal yang melibatkan komputer, jaringan, atau data digital untuk mencuri, merusak, atau menyalahgunakan informasi.\r\n\r\nCybercrime adalah ancaman serius karena dapat merugikan individu, organisasi, dan bahkan negara. Mulai dari pencurian data pribadi hingga peretasan sistem perusahaan besar, kejahatan siber ini berkembang pesat dengan berbagai modus operandi.\r\n\r\nJenis-Jenis Cyber Crime\r\nBerikut adalah beberapa jenis cyber crime yang umum terjadi:\r\n\r\n1. Phishing\r\nPhishing adalah metode cyber crime di mana pelaku mencoba mencuri informasi sensitif seperti kata sandi, nomor kartu kredit, atau data pribadi dengan menyamar sebagai entitas tepercaya melalui email atau situs web palsu.\r\n\r\nContoh kasus phishing adalah email yang tampak berasal dari bank, meminta Anda untuk memasukkan detail akun ke dalam situs palsu.\r\n\r\n2. Serangan Ransomware\r\nRansomware adalah jenis malware yang mengenkripsi data pengguna dan meminta tebusan agar data tersebut dapat diakses kembali. Serangan ini sering menargetkan perusahaan atau institusi yang memiliki data sensitif.\r\n\r\nBaca lebih lanjut tentang ransomware dan bagaimana melindungi diri dari ancaman ini di artikel berikut: Apa itu Ransomware?\r\n\r\n3. Carding\r\nCarding adalah aktivitas mencuri informasi kartu kredit seseorang untuk melakukan transaksi tanpa izin. Biasanya, pelaku mendapatkan data kartu kredit melalui situs ilegal atau phishing.\r\n\r\n4. Cracking\r\nCracking adalah tindakan meretas sistem atau perangkat lunak dengan tujuan merusak atau mencuri informasi. Berbeda dengan hacking, cracking lebih condong pada niat kriminal, seperti mencuri data rahasia perusahaan.\r\n\r\n5. OTP Fraud\r\nOTP (One-Time Password) fraud adalah kejahatan di mana pelaku memanfaatkan kode OTP untuk mengakses akun korban secara ilegal. Pelaku sering kali menipu korban agar menyerahkan kode OTP dengan alasan palsu.\r\n\r\n6. Cyberbullying\r\nCyberbullying adalah intimidasi atau pelecehan yang dilakukan di platform online seperti media sosial, forum, atau aplikasi pesan instan. Dampaknya sangat merugikan korban, baik secara emosional maupun psikologis.\r\n\r\n7. Kejahatan Konten\r\nKejahatan konten mencakup penyebaran informasi palsu (hoaks), ujaran kebencian, dan materi ilegal seperti pornografi atau kekerasan. Jenis cybercrime ini sering kali menimbulkan kekacauan di masyarakat.\r\n\r\nDampak Cyber Crime\r\nCyber crime adalah ancaman yang dapat memberikan dampak serius, baik bagi individu maupun organisasi. Berikut adalah beberapa dampaknya:\r\n\r\n1. Kerugian Finansial\r\nCyber crime seperti carding atau ransomware dapat menyebabkan kerugian materi yang besar.\r\n\r\n2. Kehilangan Privasi\r\nPencurian data pribadi dapat menyebabkan penyalahgunaan identitas atau informasi sensitif lainnya.\r\n\r\n3. Kerusakan Reputasi\r\nPerusahaan yang menjadi korban peretasan sering kali kehilangan kepercayaan pelanggan.\r\n\r\n4. Gangguan Psikologis\r\nKorban cyberbullying atau penipuan online sering kali mengalami tekanan emosional yang berat.\r\n\r\n5. Ancaman terhadap Keamanan Nasional\r\nSerangan cyber pada infrastruktur penting seperti listrik atau transportasi dapat berdampak pada stabilitas nasional.\r\n\r\nCara Mencegah dan Mengatasi Cyber Crime\r\n\r\nBerikut adalah langkah-langkah untuk mencegah dan mengatasi cyber crime:\r\n\r\n1. Gunakan Perangkat Keamanan\r\nPasang antivirus dan firewall untuk melindungi perangkat dari malware atau serangan.\r\n\r\n2. Hindari Membuka Tautan Mencurigakan\r\nJangan sembarangan mengklik tautan atau mengunduh file dari sumber yang tidak dikenal.\r\n\r\n3. Perbarui Sistem Secara Berkala\r\nPastikan sistem operasi, perangkat lunak, dan browser selalu diperbarui untuk mengurangi risiko kerentanan.\r\n\r\n4. Gunakan Kata Sandi yang Kuat\r\nKombinasikan huruf, angka, dan simbol untuk membuat kata sandi yang sulit ditebak.\r\n\r\n5. Pendidikan Cyber Security\r\nTingkatkan kesadaran tentang ancaman siber dan cara menghadapinya melalui pelatihan atau seminar.\r\n\r\n6. Laporkan Kejahatan Siber\r\nJika menjadi korban cyber crime, segera laporkan ke pihak berwajib atau lembaga yang berwenang.\r\n\r\nKesimpulan\r\nCyber crime adalah kejahatan digital yang semakin berkembang seiring dengan pesatnya teknologi. Dengan memahami pengertian cyber crime, jenis-jenisnya, dan cara mencegahnya, kita dapat melindungi diri dari ancaman ini.\r\n\r\nDari phishing hingga ransomware, ancaman cyber crime tidak hanya merugikan secara finansial tetapi juga dapat berdampak serius pada privasi dan keamanan. Oleh karena itu, meningkatkan kesadaran dan menggunakan teknologi dengan bijak adalah kunci untuk melindungi diri dari kejahatan di dunia maya.Selalu waspada, dan pastikan Anda memahami cara melindungi diri Anda dari cyber crime!', 'posts/JBZkIjgIwIExDJWv0hOczx74GJsleF4bjVn9BXGi.jpg', '2025-08-21 00:21:00', '2025-08-21 00:21:00', '2025-08-21 00:27:47'),
(9, 'Tips Belajar Programming', '🔥 7 Tips Belajar Programming Biar Gak Cepet Menyerah\r\n\r\nBelajar programming itu mirip banget kayak belajar main alat musik atau olahraga: awalnya bikin pusing, tapi kalau konsisten lama-lama jadi nagih. Nah, biar perjalanan belajarmu lebih smooth, ini ada beberapa tips yang bisa dicoba :\r\n\r\n1. Mulai dari Dasar, Jangan Keburu Pengen Jadi Hacker\r\n\r\nSering banget orang langsung pengen bikin website canggih atau jadi pro hacker, padahal dasar HTML, CSS, atau logika algoritma aja masih bolong. Pelan-pelan aja bro, kuasai basic dulu, nanti advance-nya nyusul.\r\n\r\n2. Bikin Target Kecil tapi Konsisten\r\n\r\nLebih baik coding 30 menit tiap hari daripada maraton 8 jam sehari terus seminggu ngilang. Target kecil bikin progress terasa dan lebih gampang dipantau.\r\n\r\n3. Jangan Takut Error (Error = Guru Terbaik)\r\n\r\nKalau layar udah penuh pesan error, jangan panik dulu. Baca pelan-pelan, biasanya jawabannya udah ada di error message. Kalau masih buntu, coba googling atau cek StackOverflow.\r\n\r\n4. Praktik, Praktik, Praktik\r\n\r\nBaca teori doang tanpa praktik = cepet lupa. Bikin project kecil: to-do list, kalkulator sederhana, atau CRUD dengan Laravel. Dari project nyata biasanya ilmu nempel lebih lama.\r\n\r\n5. Gunakan Dokumentasi & Sumber Resmi\r\n\r\nDokumentasi bukan buat developer senior aja. Biasakan baca docs resmi framework/library yang dipakai, biar terbiasa mandiri.\r\n\r\n6. Cari Komunitas / Teman Belajar\r\n\r\nBelajar bareng temen itu lebih seru. Kalau stuck, ada yang bisa bantu. Bisa gabung di forum, Discord, atau komunitas lokal kampus.\r\n\r\n7. Nikmati Prosesnya\r\n\r\nJangan cuma fokus ke hasil akhir. Nikmati tiap progress kecil yang berhasil. Ingat, semua programmer jago dulunya juga pernah bingung gara-gara titik koma 😅.', 'posts/Rn2S1FaqXk8mzMtJnZlzMiWgMcmLZSJ3mCJilSYv.jpg', '2025-08-21 00:38:12', '2025-08-21 00:38:12', '2025-08-21 00:38:12'),
(10, 'Veo 3 — AI Video Generation Tingkat Berikutnya dari Google', 'Sejak dirilis di Google I/O 2025, Veo 3 langsung jadi sorotan karena kemampuannya menciptakan video pendek berdurasi sekitar 8 detik lengkap dengan audio—mulai dari dialog, efek suara, hingga musik latar. Semua ini cukup dihasilkan hanya dari deskripsi teks atau gambar saja.\r\n\r\n1. Fitur Unggulan Veo 3\r\n\r\nAudio-native: Tidak hanya video, tapi juga menyertakan audio yang terintegrasi langsung seperti dialog, suara ambient, dan efek spesial.\r\n\r\nKualitas sinematik: Visual realistis dengan kontrol fisika yang akurat, hasilnya mirip sinema mini.\r\n\r\nPlatform fleksibel: Bisa diakses lewat aplikasi Gemini, Flow, dan Vertex AI. Google juga meluncurkan Veo 3 Fast, versi lebih cepat untuk developer.\r\n\r\n2. Dampak dan Penggunaan Nyata\r\n\r\nDipakai oleh agensi iklan seperti BarkleyOKRP untuk kampanye kreatif.\r\n\r\nTerintegrasi dengan Canva untuk mempermudah kreator membuat konten visual.\r\n\r\nRamai digunakan di sosial media untuk membuat konten kreatif yang unik dan viral.\r\n\r\n3. Tantangan dan Kritik\r\n\r\nMasih ada hasil yang gagal, misalnya video musik yang glitch atau wajah aneh saat prompt rumit digunakan.\r\n\r\nDisalahgunakan untuk membuat konten rasis dan hoax yang sempat viral di TikTok.\r\n\r\nGoogle menambahkan watermark (visible + SynthID) agar mudah dikenali sebagai konten AI, serta menjanjikan evaluasi keamanan berkelanjutan.', 'posts/DUmxb7YkkkOoapwoLFd0iOKJbIrtqMOKKhg5QyiG.jpg', '2025-08-21 00:45:00', '2025-08-21 00:45:00', '2025-08-21 00:45:00'),
(15, 'Apa Itu Machine Learning?', 'Machine Learning (ML) atau pembelajaran mesin adalah cabang dari kecerdasan buatan (Artificial Intelligence/AI) yang memungkinkan komputer belajar dari data dan membuat keputusan atau prediksi tanpa harus diprogram secara eksplisit.\r\n\r\nKalau di pemrograman biasa, kita kasih aturan → komputer eksekusi → keluar hasil.\r\nNah, di Machine Learning, kita kasih data → komputer belajar pola → komputer bisa bikin prediksi/keputusan sendiri.\r\n\r\nBagaimana Cara Kerja Machine Learning?\r\nSecara sederhana, prosesnya bisa dijelaskan dalam 3 tahap utama:\r\nInput Data\r\nData bisa berupa angka, teks, gambar, atau bahkan suara.\r\nContoh: foto kucing dan anjing untuk dilatih dalam model klasifikasi hewan.\r\n\r\nTraining (Pelatihan Model)\r\nAlgoritma ML belajar dari data untuk menemukan pola.\r\nMisalnya: mengenali bahwa kucing biasanya punya telinga runcing, sementara anjing tidak selalu.\r\n\r\nPrediction (Prediksi)\r\nSetelah dilatih, model digunakan untuk memprediksi data baru.\r\nContoh: ketika diberi foto baru, model bisa menebak apakah itu kucing atau anjing.\r\n\r\nJenis-Jenis Machine Learning\r\nAda beberapa pendekatan utama dalam ML:\r\n\r\n1. Supervised Learning\r\nModel belajar dari data yang sudah diberi label.\r\nContoh: dataset email (label: spam / bukan spam).\r\nAlgoritma populer: Linear Regression, Decision Tree, Random Forest.\r\n\r\n2. Unsupervised Learning\r\nModel belajar dari data tanpa label, hanya mencari pola tersembunyi.\r\nContoh: mengelompokkan pelanggan berdasarkan kebiasaan belanja.\r\nAlgoritma populer: K-Means Clustering, PCA.\r\n\r\n3. Reinforcement Learning\r\nModel belajar lewat trial and error, mirip seperti manusia belajar dari pengalaman.\r\nContoh: AI yang belajar main game atau mengendalikan robot.\r\nKonsep: ada agent, environment, reward, dan action.\r\n\r\nContoh Penerapan Machine Learning\r\nML sudah banyak dipakai di kehidupan sehari-hari tanpa kita sadari:\r\nRekomendasi Film/Musik → Netflix, Spotify, YouTube.\r\nPengenalan Wajah → Face Unlock di smartphone.\r\nSelf-driving Car → Mobil Tesla belajar mengenali jalan.\r\nChatbot & Virtual Assistant → Siri, Alexa, dan juga ChatGPT (gabungan ML + NLP).\r\nDeteksi Penipuan → Bank mendeteksi transaksi mencurigakan.\r\n\r\nTantangan dalam Machine Learning\r\nWalaupun keren, ML juga punya tantangan:\r\nData Bias → Jika datanya bias, hasilnya juga ikut bias.\r\nOverfitting → Model terlalu fokus pada data training, gagal pada data baru.\r\nKebutuhan Data Besar → Butuh banyak data agar hasil akurat.\r\nKomputasi Mahal → Training model kompleks butuh hardware canggih (GPU/TPU).\r\n\r\nMasa Depan Machine Learning\r\nMachine Learning akan semakin terintegrasi dalam berbagai bidang:\r\nKesehatan → Diagnosa penyakit lebih cepat.\r\nPendidikan → Pembelajaran adaptif sesuai kemampuan siswa.\r\nBisnis → Prediksi pasar dan perilaku konsumen.\r\nKeamanan Siber → Deteksi serangan lebih cepat.\r\nBisa dibilang, ML adalah salah satu teknologi yang akan membentuk era digital masa depan.\r\n\r\n👉 Jadi, singkatnya: Machine Learning adalah otak dari banyak teknologi modern saat ini, mulai dari aplikasi sederhana sampai inovasi masa depan seperti mobil tanpa sopir dan AI super cerdas.', 'posts/3PZgu4b7q7M7tlq0OSy29FShiFBu8vGdzuJzo85X.jpg', '2025-08-21 08:36:17', '2025-08-21 08:36:17', '2025-08-21 08:36:17');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('YC45txMevdpcSWKvissLDcuNtTovvyJfAPRQLeOg', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRG5aYXFCR21BTzZHRTY0NlpJMXlyalNETWtCbWFBY2pYaEYxMXpzRiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1755799410);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(1, 'Admin', 'agnanpes1@gmail.com', '2025-08-20 11:37:49', '$2y$12$6I0Uv8B97UFDZaGWHEuNM.QVPv87lOIx5Tj6GL2oSmHpH547S/V.G', NULL, '2025-08-20 11:37:49', '2025-08-21 09:44:05', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_post_id_foreign` (`post_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD KEY `password_reset_tokens_email_index` (`email`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
