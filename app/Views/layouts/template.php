<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php
    // Tangkap isi dari section 'title'. Parameter 'true' membuat fungsi ini me-return nilainya.
    $pageTitle = $this->renderSection('title', true);
    echo ($pageTitle) ? $pageTitle . ' | Baitul Jannah' : 'Dashboard | Baitul Jannah';
    ?>
  </title>

  <script src="https://cdn.tailwindcss.com"></script>

  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.tailwindcss.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />

  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.dataTables.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

  <style>
    /* * {outline: 1px solid blue} */
    /* Simple styling for scrollbar if needed */
    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 4px;
    }
  </style>
</head>

<body class="bg-gray-100" x-data="{ isSideMenuOpen: false }" @keydown.escape="isSideMenuOpen = false">

  <div class="flex h-screen bg-gray-100">
    <?= $this->include('layouts/sidebar') ?>

    <div class="flex flex-col flex-1">
      <?= $this->include('layouts/header') ?>

      <main class="h-full pb-16 overflow-y-auto">
        <div class="container p-1 md:p-4 mx-auto grid">
          <?= $this->renderSection('content') ?>
        </div>
      </main>
    </div>
  </div>


  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.0.8/js/dataTables.tailwindcss.js"></script>
  <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
  <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.dataTables.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

  <?= $this->renderSection('scripts') ?>

  <?= $this->include('layouts/notifications') ?>

</body>

</html>