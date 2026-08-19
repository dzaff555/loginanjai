<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Welcome <?= htmlspecialchars($username) ?></title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;

            background: #000000;
            color: white;

            font-family: Poppins, sans-serif;

            overflow: hidden;
        }

        .welcome {
            text-align: center;

            opacity: 0;
            transform: translateY(25px) scale(0.96);

            animation: welcomeIn 1.2s cubic-bezier(.16, 1, .3, 1) forwards;
        }

        .welcome h1 {
            font-size: 64px;
            font-weight: 600;
            letter-spacing: -2px;
        }

        .welcome p {
            margin-top: 10px;
            font-size: 18px;
            opacity: 0;

            animation: subtitleIn 1s ease forwards;
            animation-delay: .5s;
        }

        @keyframes welcomeIn {
            0% {
                opacity: 0;
                transform: translateY(25px) scale(0.96);
                filter: blur(10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
                filter: blur(0);
            }
        }

        @keyframes subtitleIn {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }

            100% {
                opacity: .6;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <div class="welcome">
        <h1>Welcome, <?= htmlspecialchars($username) ?>!</h1>
        <p>You have successfully signed in.</p>
    </div>

</body>
</html>