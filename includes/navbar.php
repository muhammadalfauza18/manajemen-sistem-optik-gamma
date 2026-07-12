<main class="main">

    <header class="topbar">

        <div class="search-box">

            <i class="fa-solid fa-search"></i>

            <input type="text" placeholder="Search...">

        </div>

        <div class="top-actions">

            <button class="icon-btn">

                <i class="fa-solid fa-bell"></i>

            </button>

            <div class="divider"></div>

            <div class="admin-chip">

                <img src="/optik-gamma/assets/img/default.png">

                <span>

                    <?= isset($_SESSION['username']) ? $_SESSION['username'] : 'Administrator'; ?>

                </span>

            </div>

        </div>

    </header>