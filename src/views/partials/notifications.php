<div class="ph20">
    <div class="alert alert-<?= $type == "error" ? 'danger' : $type ?>" role="alert">
        <p><?= $label ?> :</p>
        <ul>
            <?php foreach ($messages as $message) : ?>
                <li><?= $message ?></li>
            <?php endforeach ?>
        </ul>
    </div>
</div>