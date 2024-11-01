<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Peserta</title>
</head>

<body>
    <h1>Daftar Peserta</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Alamat</th>
            <th>Waktu Daftar</th>
        </tr>
        <?php foreach ($peserta as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= $p['nama'] ?></td>
                <td><?= $p['email'] ?></td>
                <td><?= $p['alamat'] ?></td>
                <td><?= $p['created_at'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>