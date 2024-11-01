<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Rapat</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #a8e0e3;
        }

        .card {
            background-color: #d4f1f4;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Welcome, <?= esc($user['nama']); ?>!</h2>
        <p>You are now logged in.</p>

        <!-- Logout Button -->
        <div class="mb-3">
            <a href="<?= base_url('/auth/logout'); ?>" class="btn btn-danger">Logout</a>
            <a href="<?= base_url('/rapat/create'); ?>" class="btn btn-primary ml-2">Tambah Rapat</a>
        </div>


        <div class="card">
            <h4>Total Rapat yang Diikuti:</h4>
            <p>Ketua Rapat: <?= esc($jumlah_rapat['ketua']); ?></p>
            <p>Notulensi: <?= esc($jumlah_rapat['notulensi']); ?></p>
            <p>Anggota: <?= esc($jumlah_rapat['anggota']); ?></p>
        </div>

        <h3>Daftar Rapat</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Rapat</th>
                    <th>Ruangan</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Ketua</th>
                    <th>Notulen</th>
                    <th>Anggota</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rapats as $rapat): ?>
                    <tr>
                        <td><?= esc($rapat['nama_rapat']) ?></td>
                        <td><?= esc($rapat['ruangan']) ?></td>
                        <td><?= esc($rapat['waktu_mulai']) ?></td>
                        <td><?= esc($rapat['waktu_selesai']) ?></td>
                        <td>
                            <?php
                            $ketua = 'Tidak Ada';
                            foreach ($roles[$rapat['id']] as $role) {
                                if ($role['id_roles'] == 1) { // ID untuk ketua
                                    if (isset($userNames[$role['id_users']])) { // Pastikan ID ada di userNames
                                        $ketua = esc($userNames[$role['id_users']]); // Ambil nama ketua
                                    }
                                    break; // Tidak perlu melanjutkan setelah menemukan ketua
                                }
                            }
                            echo $ketua;
                            ?>
                        </td>
                        <td>
                            <?php
                            $notulen = 'Tidak Ada';
                            foreach ($roles[$rapat['id']] as $role) {
                                if ($role['id_roles'] == 2) { // ID untuk notulen
                                    if (isset($userNames[$role['id_users']])) { // Pastikan ID ada di userNames
                                        $notulen = esc($userNames[$role['id_users']]); // Ambil nama notulen
                                    }
                                    break; // Tidak perlu melanjutkan setelah menemukan notulen
                                }
                            }
                            echo $notulen;
                            ?>
                        </td>
                        <td>
                            <?php
                            $anggota = [];
                            foreach ($roles[$rapat['id']] as $role) {
                                if ($role['id_roles'] == 3) { // ID untuk anggota
                                    if (isset($userNames[$role['id_users']])) { // Pastikan ID ada di userNames
                                        $anggota[] = esc($userNames[$role['id_users']]); // Ambil nama anggota
                                    }
                                }
                            }
                            echo esc(implode(', ', $anggota));
                            ?>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>