<!DOCTYPE html>
<html>
<head>
    <title>Welcome to User Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
        }
        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 450px;
            margin: auto;
        }
        h2 {
            color: #333;
            margin-bottom: 10px;
        }
        p {
            color: #555;
            font-size: 16px;
            line-height: 1.5;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Welcome, {{ $user->first_name }}!</h2>

        <p>We’re excited to have you on board at <strong>User Management System</strong>. Explore and manage your account seamlessly.</p>

        <p class="footer">Best Regards,<br><strong>User Management System Team</strong></p>
    </div>

</body>
</html>
