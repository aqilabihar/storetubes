<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Rapat</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        <h2>Welcome, <?= esc($user['nama']); ?>!</h2>
        <p>Now lets dive into new meeting.</p>

        <!-- Logout Button -->




        <div class="content-wrapper">
            <!-- Responsive Dashboard Cards -->
            <div class="card-container">
                <div class="card">
                    <div class="card-title">Ketua Rapat</div>
                    <div class="card-number"><?= esc($jumlah_rapat['ketua']); ?></div>
                </div>
                <div class="card">
                    <div class="card-title">Notulensi</div>
                    <div class="card-number"><?= esc($jumlah_rapat['notulensi']); ?></div>
                </div>
                <div class="card">
                    <div class="card-title">Anggota</div>
                    <div class="card-number"><?= esc($jumlah_rapat['anggota']); ?></div>
                </div>
            </div>
        </div>
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