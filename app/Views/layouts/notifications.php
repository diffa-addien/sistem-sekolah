<!-- File ini berisi templating notifikasi otomatis dengan library Notyf -->

<?php if (session()->getFlashdata('success') || session()->getFlashdata('error')) : ?>
    <script>
        // Pastikan DOM sudah siap
        document.addEventListener('DOMContentLoaded', function() {
            // Buat instance Notyf
            const notyf = new Notyf({
                duration: 5000, // Durasi notifikasi 5 detik
                position: {
                    x: 'right',
                    y: 'top',
                },
                dismissible: false // Bisa ditutup manual
            });

            // Cek dan tampilkan notifikasi sukses
            <?php if ($message = session()->getFlashdata('success')) : ?>
                notyf.success(<?= json_encode($message) ?>);
            <?php endif; ?>
            
            // Cek dan tampilkan notifikasi error
            <?php if ($message = session()->getFlashdata('error')) : ?>
                notyf.error(<?= json_encode($message) ?>);
            <?php endif; ?>
        });
    </script>
<?php endif; ?>