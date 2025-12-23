<?php
require_once __DIR__ . '/../../koneksi.php';

// jika sudah login, tendang ke dashboard
if (isset($_SESSION['id_user'])) {
    header('Location: index.php?page=dashboard');
    exit;
}

// initialize variabel error sweet alert
$loginError = null;
$loginSukses = false;

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Validasi CSRF Gagal!');
    }
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {

            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['is_validator'] = $user['is_validator'];
            $_SESSION['login_success'] = 'Login berhasil!';

            $loginSukses = true;
        } else {
            // jika password salah
            $loginError = "Username atau password salah!";
        }
    }

    // jika gagal
    $loginError = "Username atau password salah!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link href=" https://cdn.jsdelivr.net/npm/sweetalert2@11.23.0/dist/sweetalert2.min.css " rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-6">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT4lL4rvcMPRD1eJYg3ktdqF7i3O4IKO83T8w&s"
                    alt="">
            </div>
            <div class="col-6">

                <form action="" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="mb-3">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="" required
                            autofocus>
                    </div>
                    <div class="mb-1">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="show-password ">
                        <p>
                            <input type="checkbox" onclick="showHide()" />
                            Tampilkan Password
                        </p>
                    </div>
                    <input type="submit" class="btn btn-primary" name="submit" value="Login">
                </form>
            </div>
        </div>
    </div>

    <!-- SCRIPT FOR SHOW PASSWORD -->
    <script type="text/javascript">
        function showHide() {
            let inputan = document.getElementById("password");
            if (inputan.type === "password") {
                inputan.type = "text";
            } else {
                inputan.type = "password";
            }
        } 
    </script>

    <?php if ($loginError): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: "Loading",
                timer: 1500,
                didOpen: () => {
                    Swal.showLoading();
                }
            }).then(() => {
                Swal.fire({
                    icon: "error",
                    title: "Login Gagal",
                    text: "<?= $loginError ?>",
                    showConfirmButton: true,
                    timer: 3000
                });
            });
        </script>
    <?php endif; ?>

    <?php if ($loginSukses): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: "Loading",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                timer: 1500
            }).then(() => {
                Swal.fire({
                    icon: "success",
                    title: "Login Berhasil",
                    text: "Selamat datang, <?= $_SESSION['nama'] ?>",
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = "index.php?page=dashboard";
                });
            });
        </script>
    <?php endif; ?>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>

</html>