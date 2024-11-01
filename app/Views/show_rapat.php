<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Rapat</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Daftar Rapat</h2>
        <a href="<?= base_url('/rapat/create') ?>" class="btn btn-primary mb-3">Buat Rapat Baru</a>
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
                        <td><?= $rapat['nama_rapat'] ?></td>
                        <td><?= $rapat['ruangan'] ?></td>
                        <td><?= $rapat['waktu_mulai'] ?></td>
                        <td><?= $rapat['waktu_selesai'] ?></td>
                        <td>
                            <?php
                            $ketua = '';
                            foreach ($roles[$rapat['id']] as $role) {
                                if ($role['id_roles'] == 1) { // ID untuk ketua
                                    $ketua = $role['id_users'];
                                    break;
                                }
                            }
                            echo $ketua; // Tampilkan nama ketua
                            ?>
                        </td>
                        <td>
                            <?php
                            $notulen = '';
                            foreach ($roles[$rapat['id']] as $role) {
                                if ($role['id_roles'] == 2) { // ID untuk notulen
                                    $notulen = $role['id_users'];
                                    break;
                                }
                            }
                            echo $notulen; // Tampilkan nama notulen
                            ?>
                        </td>
                        <td>
                            <?php
                            $anggota = [];
                            foreach ($roles[$rapat['id']] as $role) {
                                if ($role['id_roles'] == 3) { // ID untuk anggota
                                    $anggota[] = $role['id_users'];
                                }
                            }
                            echo implode(', ', $anggota); // Tampilkan semua anggota
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>