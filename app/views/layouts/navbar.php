<header class="header">

    <h1>
        <?= htmlspecialchars($pageTitle ?? 'Dashboard') ?>
    </h1>

    <div class="user-info">

        User :
        <span>
            <?= htmlspecialchars($_SESSION['nama']) ?>
        </span>

        |

        <span>
            <?= date('d-m-Y') ?>
        </span>

    </div>

</header>