<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We’ll be right back</title>
    <style>
        body {
            background: #f7f7f7;
            color: #333;
            font-family: 'Inter', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .box {
            text-align: center;
            max-width: 420px;
        }
        h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        p {
            font-size: 18px;
            color: #666;
        }
        .loader {
            margin: 25px auto;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 4px solid #ddd;
            border-top-color: #3498db;
            animation: spin 1.1s infinite linear;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
<div class="box">
    <div class="loader"></div>
    <h1>We’re performing updates</h1>
    <p>Please check back in a few minutes.<br>Thank you for your patience!</p>
</div>
</body>
</html>
