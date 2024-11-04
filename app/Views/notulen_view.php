<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Rapat</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
        <h3>Daftar Rapat</h3>
        <div class="table-container">
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
                        <th>Aksi</th>
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
                                $notulen = '';
                                $notulen_ids = [];
                                foreach ($roles[$rapat['id']] as $role) {
                                    if ($role['id_roles'] == 2 && isset($userNames[$role['id_users']])) {
                                        $notulen .= esc($userNames[$role['id_users']]) . ', ';
                                        $notulen_ids[] = $role['id_users'];
                                    }
                                }
                                echo rtrim($notulen, ', ') ?: 'Tidak Ada';
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
                                echo esc(implode(', ', $anggota)) ?: 'Tidak Ada';
                                ?>
                            </td>
                            <td>
                                <?php if (in_array($userId, $notulen_ids)): ?>
                                    <a href="<?= base_url('/transcription/view/' . $rapat['id']); ?>" class="btn btn-primary">Rekam Transkip</a>
                                <?php else: ?>
                                    <span>No access to view transcription</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Attendance Modal -->
    <div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendanceModalLabel">Attendance List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="attendanceMessage" class="alert alert-warning" style="display:none;"></div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceListBody">
                            <!-- Attendance data will be inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add these before the closing body tag -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script>
        function fetchAttendance(meetingId) {
            $.ajax({
                url: '<?= base_url('/beranda/getAttendance/') ?>' + meetingId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        $('#attendanceMessage').text(response.error).show();
                    } else {
                        let tableContent = '';
                        $.each(response, function(index, attendance) {
                            tableContent += '<tr>';
                            tableContent += '<td>' + attendance.id_user + '</td>';
                            tableContent += '<td>' + attendance.nama + '</td>';
                            tableContent += '</tr>';
                        });
                        $('#attendanceListBody').html(tableContent);
                    }
                    $('#attendanceModal').modal('show');
                },
                error: function(xhr, status, error) {
                    $('#attendanceMessage').text('An error occurred while fetching the attendance data.').show();
                    $('#attendanceModal').modal('show');
                }
            });
        }
    </script>
</body>

</html>