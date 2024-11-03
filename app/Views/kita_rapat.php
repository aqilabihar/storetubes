<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Rapat</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Bootstrap CSS for Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <style>
        body {
            padding-top: 70px;
            background-color: #f4f6f9;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .content-wrapper {
            display: flex;
            justify-content: center;
            padding: 20px;
            margin-top: 80px;
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            width: 100%;
            max-width: 1200px;
            padding: 20px;
        }

        .card {
            background-color: #c3f0f4;
            color: #055a63;
            border-radius: 8px;
            text-align: center;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .card-number {
            font-size: 2rem;
            font-weight: bold;
        }

        .table-container {
            margin-top: 20px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .table th,
        .table td {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <!-- Include Navbar -->
    <?= $this->include('navbar/navbar'); ?>



    <div class="container mt-5">


        <!-- Logout and Tambah Rapat Buttons -->


        <!-- Daftar Rapat Table -->
        <div class="table-container">
            <h3>Daftar Rapat Anda</h3>
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
                                    if ($role['id_roles'] == 1 && isset($userNames[$role['id_users']])) {
                                        $ketua = esc($userNames[$role['id_users']]);
                                        break;
                                    }
                                }
                                echo $ketua;
                                ?>
                            </td>
                            <td>
                                <?php
                                $notulen = 'Tidak Ada';
                                foreach ($roles[$rapat['id']] as $role) {
                                    if ($role['id_roles'] == 2 && isset($userNames[$role['id_users']])) {
                                        $notulen = esc($userNames[$role['id_users']]);
                                        break;
                                    }
                                }
                                echo $notulen;
                                ?>
                            </td>
                            <td>
                                <?php
                                $anggota = [];
                                foreach ($roles[$rapat['id']] as $role) {
                                    if ($role['id_roles'] == 3 && isset($userNames[$role['id_users']])) {
                                        $anggota[] = esc($userNames[$role['id_users']]);
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
    </div>
</body>

</html>