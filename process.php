<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $file = $_POST['file'];
    $logoUrl = $_POST['logoUrl'];
    $domain = $_POST['domain'];
    $mac = $_POST['mac'];
    $d1 = $_POST['d1'];
    $d2 = $_POST['d2'];
    $sn = $_POST['sn'];
    $model = $_POST['model'];

    $data = "<?php\n\$file = \"$file\";\n\$logoUrl = \"$logoUrl\";\n\$domain = \"$domain\";\n\$mac = \"$mac\";\n\$d1 = \"$d1\";\n\$d2 = \"$d2\";\n\$sn = \"$sn\";\n\$model = \"$model\";\n?>";
    file_put_contents('config.php', $data);

    header('Location: index.php?success=true&showProfile=true');
    exit();
}

include 'config.php';
include 'functions.php';

if (isset($_GET['profile'])) {
    header('Content-Type: application/json');
    echo getProfile();
    exit();
}

if (isset($_GET['m3u']) && $_GET['m3u'] == "1") {
    getPlaylist(true);
    exit();
}

if (isset($_GET['id'])) {
    header("Location: " . getTvdata($_GET['id']));
    exit();
}