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
                                    if (isset($userNames[$role['id_users']])) {
                                        $ketua = esc($userNames[$role['id_users']]);
                                    }
                                    break;
                                }
                            }
                            echo $ketua;
                            ?>
                        </td>
                        <td>
                            <?php
                            $notulen = 'Tidak Ada';
                            $notulen_ids = []; // Store notulen user IDs for access checking
                            foreach ($roles[$rapat['id']] as $role) {
                                if ($role['id_roles'] == 2) { // ID untuk notulen
                                    if (isset($userNames[$role['id_users']])) {
                                        $notulen .= esc($userNames[$role['id_users']]) . ', ';
                                        $notulen_ids[] = $role['id_users']; // Collect notulen user IDs
                                    }
                                }
                            }
                            echo rtrim($notulen, ', ') ?: 'Tidak Ada';
                            ?>
                        </td>
                        <td>
                            <?php
                            $anggota = [];
                            foreach ($roles[$rapat['id']] as $role) {
                                if ($role['id_roles'] == 3) { // ID untuk anggota
                                    if (isset($userNames[$role['id_users']])) {
                                        $anggota[] = esc($userNames[$role['id_users']]);
                                    }
                                }
                            }
                            echo esc(implode(', ', $anggota)) ?: 'Tidak Ada';
                            ?>
                        </td>
                        <td>
                            <?php
                            $isMember = false;
                            foreach ($roles[$rapat['id']] as $role) {
                                if ($role['id_users'] == $userId) {
                                    $isMember = true;
                                    break;
                                }
                            }
                            ?>

                            <?php if ($isMember): ?>
                                <?php if ($has_attended[$rapat['id']]): ?>
                                    <button class="btn btn-secondary" disabled>Telah Absen</button>
                                <?php else: ?>
                                    <form action="<?= base_url('/beranda/absenHadir/' . $rapat['id']); ?>" method="post">
                                        <button type="submit" class="btn btn-success">Absen</button>
                                    </form>
                                <?php endif; ?>
                            <?php else: ?>
                                <span>Tidak dapat absen</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (in_array($userId, $notulen_ids)): ?>
                                <a href="<?= base_url('/attendance/list/' . $rapat['id']); ?>" class="btn btn-info">Lihat Absen</a>
                            <?php else: ?>
                                <span>Tidak ada akses untuk melihat absen</span>
                            <?php endif; ?>
                        </td>



                    </tr>
                <?php endforeach; ?>



            </tbody>
        </table>
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

</body>
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
                        tableContent += '<td>' + attendance.id_user + '</td>'; // Assuming this is the user ID
                        tableContent += '<td>' + attendance.nama + '</td>';
                        tableContent += '</tr>';
                    });
                    $('#attendanceTable tbody').html(tableContent);
                }
                $('#attendanceModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                console.error('Response:', xhr.responseText);
                $('#attendanceMessage').text('An error occurred while fetching the attendance data.').show();
                $('#attendanceModal').modal('show');
            }
        });

    }
</script>
<!-- Add these before the closing body tag -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>



</html>