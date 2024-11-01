<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Register</title>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Daftar Pengguna Baru</h2>
        <form action="<?= base_url('/auth/save') ?>" method="post">
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" name="nama" id="nama" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="jenis_kelamin">Jenis Kelamin:</label>
                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="male">Laki-laki</option>
                    <option value="female">Perempuan</option>
                    <option value="other">Lainnya</option>
                </select>
            </div>
            <div class="form-group">
                <label for="nip_nim">NIP/NIM:</label>
                <input type="text" name="nip_nim" id="nip_nim" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="telepon">Telepon:</label>
                <input type="tel" name="telepon" id="telepon" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir:</label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="pertanyaan_keamanan">Pertanyaan Keamanan:</label>
                <select name="pertanyaan_keamanan" id="pertanyaan_keamanan" class="form-control" required>
                    <option value="">Pilih Pertanyaan</option>
                    <option value="q1">Apa nama hewan peliharaan Anda?</option>
                    <option value="q2">Apa sekolah pertama Anda?</option>
                    <option value="q3">Apa nama gadis ibu Anda?</option>
                    <option value="q4">Apa julukan masa kecil Anda?</option>
                    <option value="q5">Apa warna favorit Anda?</option>
                </select>
            </div>

            <div class="form-group">
                <label for="jawaban_keamanan">Jawaban:</label>
                <input type="text" name="jawaban_keamanan" id="jawaban_keamanan" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Daftar</button>
        </form>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>