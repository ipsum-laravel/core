<div class="messages messages-<?= $type ?>">
    <button type="button" class="messages-close" onclick="this.parentNode.classList.add('is-close');" data-dismiss="alert">&times;</button>
    <p class="messages-label"><?= $label ?> :</p>
    <ul class="messages-list">
        <?php foreach ($messages as $message) : ?>
        <li class="messages-items"><?= $message ?></li>
        <?php endforeach ?>
    </ul>
</div>