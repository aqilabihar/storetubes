<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Buat Rapat Baru</title>
</head>

<body>
    <div class="container mt-5">
        <h2>Buat Rapat Baru</h2>
        <form action="<?= base_url('/rapat/save') ?>" method="post">
            <div class="form-group">
                <label for="nama_rapat">Nama Rapat:</label>
                <input type="text" name="nama_rapat" id="nama_rapat" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="ruangan">Ruangan:</label>
                <input type="text" name="ruangan" id="ruangan" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="waktu_mulai">Waktu Mulai:</label>
                <input type="datetime-local" name="waktu_mulai" id="waktu_mulai" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="waktu_selesai">Waktu Selesai:</label>
                <input type="datetime-local" name="waktu_selesai" id="waktu_selesai" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="ketua">Ketua:</label>
                <select name="ketua" id="ketua" class="form-control" required>
                    <option value="">Pilih Ketua</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['id'] ?>"><?= $user['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="anggota">Anggota:</label>
                <select name="anggota[]" id="anggota" class="form-control" multiple required>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['id'] ?>"><?= $user['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Rapat</button>
        </form>

    </div>
</body>

</html>