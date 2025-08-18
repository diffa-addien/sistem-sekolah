<?php
$role = session()->get('role');
$config = config('App');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | <?= $config->appName ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
</head>

<body class="bg-gray-100">

    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
            <div class="text-center">
                <img src="<?= base_url('assets/logo.png') ?>" alt="Logo" class="w-16 h-16 mx-auto mb-4 border border-gray-500 rounded-full">
                <h2 class="text-2xl font-bold text-gray-800"><?= $config->appName ?></h2>
                <p class="mt-2 text-sm text-gray-600">Silakan login ke akun Anda</p>
            </div>

            <form class="space-y-6" action="<?= site_url('login') ?>" method="POST">
                <?= csrf_field() ?>

                <div>
                    <label for="username" class="text-sm font-medium text-gray-700">Username</label>
                    <input id="username" name="username" type="text" required
                        class="block w-full px-3 py-2 mt-1 text-gray-900 placeholder-gray-500 bg-gray-50 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                        placeholder="Masukkan username Anda">
                </div>

                <div>
                    <label for="password" class="text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" required
                        class="block w-full px-3 py-2 mt-1 text-gray-900 placeholder-gray-500 bg-gray-50 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                        placeholder="Masukkan password Anda">
                </div>

                <div>
                    <button type="submit"
                        class="w-full px-4 py-3 my-2 text-sm font-medium text-white rounded-md bg-sky-600 rounded-lg hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    <script>
        const notyf = new Notyf({ duration: 5000, position: { x: 'right', y: 'top' }, dismissible: true });

        <?php if (session()->getFlashdata('success')): ?>
            notyf.success(<?= json_encode(session()->getFlashdata('success')) ?>);
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            notyf.error(<?= json_encode(session()->getFlashdata('error')) ?>);
        <?php endif; ?>
    </script>

</body>

</html>