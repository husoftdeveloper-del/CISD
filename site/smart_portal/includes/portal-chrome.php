<?php
if (!function_exists('portal_chrome_bar')) {
    function portal_chrome_bar(): void
    {
        static $rendered = false;
        if ($rendered) {
            return;
        }
        $rendered = true;
        ?>
<style>
.cisd-portal-bar {
    position: fixed;
    top: 12px;
    right: 12px;
    z-index: 10050;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    max-width: calc(100vw - 24px);
    justify-content: flex-end;
}
.cisd-portal-bar a {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 999px;
    font: 600 13px/1.2 'Segoe UI', system-ui, sans-serif;
    text-decoration: none;
    color: #fff;
    background: rgba(15, 23, 42, 0.92);
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.25);
    backdrop-filter: blur(8px);
    transition: transform 0.15s ease, background 0.15s ease;
}
.cisd-portal-bar a:hover {
    transform: translateY(-1px);
    background: #1e3a5a;
}
.cisd-portal-bar a.website {
    background: rgba(39, 174, 96, 0.95);
}
.cisd-portal-bar a.website:hover {
    background: #219a52;
}
@media (max-width: 640px) {
    .cisd-portal-bar {
        top: auto;
        bottom: 12px;
        left: 12px;
        right: 12px;
        justify-content: center;
    }
    .cisd-portal-bar a {
        font-size: 12px;
        padding: 7px 12px;
    }
}
</style>
<div class="cisd-portal-bar" role="navigation" aria-label="Portal shortcuts">
    <a class="website" href="../index.php" target="_blank" rel="noopener">🌐 Back to Website</a>
    <a href="dashboard.php">📊 Dashboard</a>
    <a href="../admin/dashboard.php">⚙️ Website Admin</a>
</div>
        <?php
    }
}
