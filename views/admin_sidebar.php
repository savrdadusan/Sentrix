<?php $current = basename($_SERVER['PHP_SELF']); ?>

<div class="sidebar">
<h2>⚡ Sentrix</h2>

    <a class="<?= $current === 'admin.php' ? 'active' : '' ?>" href="/public/admin/admin.php">Challenges</a>
    <a class="<?= $current === 'users.php' ? 'active' : '' ?>" href="/public/admin/users.php">Users</a>

    <hr style="margin:15px 0; border-color:#0f172a;">

</div>