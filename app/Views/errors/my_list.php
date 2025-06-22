<div class="px-4 py-3 mb-4 text-red-800 bg-red-200 border border-red-500 rounded-lg" role="alert">
    <strong class="font-bold">Terdapat kesalahan validasi:</strong>
    <ul class="mt-2 list-disc list-inside">
        <?php foreach ($errors as $error) : ?>
            <li><?= esc($error) ?></li>
        <?php endforeach ?>
    </ul>
</div>