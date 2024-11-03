<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Attendance List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Attendance List for Meeting</h2>

        <?php if (!empty($attendanceData)) : ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attendanceData as $attendance): ?>
                        <tr>
                            <td><?= esc($attendance['nama']); ?></td>
                            <td>
                                <?= isset($attendance['status_kehadiran']) && $attendance['status_kehadiran'] === 'hadir'
                                    ? 'Present'
                                    : 'Absent'; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <div class="alert alert-warning">No attendance data found for this meeting.</div>
        <?php endif; ?>

        <a href="<?= base_url('/beranda'); ?>" class="btn btn-secondary">Back to Beranda</a>
    </div>
</body>

</html>