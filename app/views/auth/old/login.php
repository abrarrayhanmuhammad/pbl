<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - KickDropShoe</title>

    <link rel="stylesheet" href="/assets/css/login.css">
</head>

<body>

    <div class="login-container">

        <div class="login-card">

            <div class="brand">
                <h1>KICKDROPSHOE</h1>
                <p>Sistem Informasi Manajemen</p>
            </div>

            <div class="divider"></div>

            <?php if (isset($_SESSION['error'])): ?>

                <div class="error-message">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>

                <?php unset($_SESSION['error']); ?>

            <?php endif; ?>

            <form action="/login/process" method="POST">

                <div class="form-group">
                    <label for="username">
                        EMAIL/USERNAME
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="admin@kickdrop.com"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">
                        PASSWORD
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="******"
                        required
                    >
                </div>

                <button type="submit">login</button>

            </form>

            <div class="divider bottom-divider"></div>
            
            <div class="note">
                * Catatan: Gunakan akun Anda untuk login sebagai
                <br>
                Owner atau Karyawan.
            </div>

        </div>

    </div>

</body>
</html>