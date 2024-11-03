<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .fade-in {
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .custom-input {
            background-color: #f7fafc;
            border: 1px solid #cbd5e0;
            padding: 12px 16px;
            border-radius: 8px;
            width: 100%;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .custom-input::placeholder {
            color: #a0aec0;
        }

        .custom-input:focus {
            border-color: #3182ce;
            outline: none;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.3);
        }

        .container {
            max-width: 400px;
            width: 90%;
        }

        .circular-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto;
        }

        .circular-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>

<body class="bg-teal-500 flex items-center justify-center min-h-screen">
    <div class="container bg-white p-8 rounded-lg shadow-lg fade-in">
        <!-- Circular Image -->
        <div class="circular-image mb-4">
            <img src="<?= base_url('images/Ellipse 2.png') ?>" alt="Logo">
        </div>

        <!-- Title -->
        <h2 class="text-center text-3xl font-bold text-blue-800 mb-6">THE NOTES</h2>

        <!-- Display Error Message -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger text-center mb-4">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="<?= base_url('/auth/login') ?>" method="post" class="space-y-4">
            <!-- Username Input -->
            <div>
                <input type="text" name="username" id="username" placeholder="Username" class="custom-input" required>
            </div>

            <!-- Password Input -->
            <div>
                <input type="password" name="password" id="password" placeholder="Password" class="custom-input" required>
            </div>

            <!-- Login Button -->
            <div>
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">Login</button>
            </div>

            <!-- Links -->
            <div class="text-center mt-4">
                <a href="#" class="text-gray-600 hover:underline">Lupa Password</a> |
                <a href="<?= base_url('/auth/register') ?>" class="text-blue-600 hover:underline">Registrasi</a>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>