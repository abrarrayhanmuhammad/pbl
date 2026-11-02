<?php
/**
 * Header Component - KickDropShoe MIS
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
$user_role = $user_role ?? ($_SESSION['role'] ?? 'superadmin');
$user_name = $user_name ?? ($_SESSION['nama'] ?? ($user_role === 'karyawan' ? 'Bagas (Karyawan)' : 'Super Admin'));
$user_initials = $user_initials ?? ($_SESSION['user_initials'] ?? ($user_role === 'karyawan' ? 'BK' : 'SA'));

$page_title = $page_title ?? 'KickDropShoe';
$body_class = $body_class ?? 'font-sans antialiased bg-brand-slateBg';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title><?= htmlspecialchars($page_title) ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="/assets/js/tailwind.js"></script>
    <script src="/assets/js/sweet.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        brand: {
                            navy: '#0B1325',
                            darkCard: '#0F172A',
                            darkInput: '#1E293B',
                            yellow: '#FFFF00',
                            yellowHover: '#CCCC00',
                            slateBg: '#F8FAFC'
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="<?= htmlspecialchars($body_class) ?>">
