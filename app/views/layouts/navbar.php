<header class="header">

    <div class="header-left">

        <button
            type="button"
            class="menu-toggle"
            id="menu-toggle"
            aria-label="Buka menu"
        >
            <span></span>
            <span></span>
            <span></span>
        </button>

        <h1>
            <?= htmlspecialchars($pageTitle ?? 'Dashboard') ?>
        </h1>

    </div>

    <div class="user-info">
        <span>
            User: <?= htmlspecialchars($_SESSION['nama']) ?>
        </span>
        <span>|</span>
        <span>
            <?= date('d-m-Y') ?>
        </span>
    </div>
    
</header>