<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "loginmarcha"
);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}


// =========================
// PROSES LOGIN
// =========================

$loginMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {

    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {

        $loginMessage = "Username dan password wajib diisi.";

    } else {

        $stmt = $conn->prepare(
            "SELECT * FROM Login WHERE Username = ? AND Password = ?"
        );

        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            // Simpan username ke session
            $_SESSION["username"] = $username;

            // Pindah ke halaman Welcome
            header("Location: welcome.php");
            exit;

        } else {

            $loginMessage = "Username atau password salah.";

        }

        $stmt->close();
    }
}


// =========================
// PROSES SIGN UP
// =========================

$signupMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["signup"])) {

    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {

        $signupMessage = "Username dan password wajib diisi.";

    } else {

        // Cek apakah username sudah digunakan
        $check = $conn->prepare(
            "SELECT Username FROM Login WHERE Username = ?"
        );

        $check->bind_param("s", $username);
        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $signupMessage = "Username sudah digunakan.";

        } else {

            $stmt = $conn->prepare(
                "INSERT INTO Login (Username, Password) VALUES (?, ?)"
            );

            $stmt->bind_param("ss", $username, $password);

            if ($stmt->execute()) {

                $signupMessage = "Akun berhasil dibuat!";

            } else {

                $signupMessage = "Gagal membuat akun.";

            }

            $stmt->close();
        }

        $check->close();
    }
}

?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="UTF-8" />

    <meta
        http-equiv="X-UA-Compatible"
        content="IE=edge"
    />

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    />

    <title>Logins</title>

    <link
        rel="stylesheet"
        href="styles.css"
    />

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    />

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
        crossorigin
    />

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
        rel="stylesheet"
    />

    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,700,1,0"
    />

    <link
        href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
        rel="stylesheet"
    />

    <script src="https://unpkg.com/akar-icons-fonts"></script>

</head>

<body>

<div class="card">


    <!-- =========================
         NAVIGATION
    ========================== -->

    <ul class="card-nav">

        <li>
            <img src="logo.svg" />
            <span class="active-bar"></span>
        </li>

        <li>

            <button
                type="button"
                class="signin active"
                onclick="selectView('signin')"
            >

                <i class="ai-person-check"></i>

                <span>Sign In</span>

            </button>

        </li>

        <li>

            <button
                type="button"
                class="signup"
                onclick="selectView('signup')"
            >

                <i class="ai-person-add"></i>

                <span>Sign Up</span>

            </button>

        </li>

    </ul>


    <!-- =========================
         HERO
    ========================== -->

    <div class="card-hero">

        <div class="card-hero-inner">


            <div class="card-hero-content signin">

                <div>

                    <h2>Welcome Back.</h2>

                    <h3>
                        Please enter your credentials.
                    </h3>

                </div>

                <img src="signin.svg" />

            </div>


            <div class="card-hero-content signup">

                <div>

                    <h2>Sign Up Now.</h2>

                    <h3>
                        Join the crowd and get started.
                    </h3>

                </div>

                <img src="signup.svg" />

            </div>


        </div>

    </div>


    <!-- =========================
         FORM
    ========================== -->

    <div class="card-form">

        <div class="forms">


            <!-- =========================
                 SIGN IN
            ========================== -->

            <form
                id="signin"
                class="active"
                method="POST"
            >

                <p>

                    Don't have an account?

                    <a onclick="selectView('signup')">
                        Sign Up
                    </a>.

                </p>


                <label>
                    Username
                </label>


                <div class="control">

                    <input
                        type="text"
                        name="username"
                        autocomplete="off"
                        placeholder="myusername"
                        required
                    />

                    <i class="ai-person"></i>

                </div>


                <label>
                    Password
                </label>


                <div class="control">

                    <input
                        type="password"
                        name="password"
                        placeholder="●●●●●●●●●●●●●●●"
                        required
                    />

                    <i class="ai-lock-on"></i>

                </div>


                <p class="footer">

                    By clicking Sign In you agree to our
                    terms and conditions, privacy policy
                    and reusability rules and whatever our
                    CEO says is true.

                </p>


                <button
                    type="submit"
                    name="login"
                >
                    Sign In
                </button>


                <?php if ($loginMessage !== ""): ?>

                    <p>
                        <?= htmlspecialchars($loginMessage) ?>
                    </p>

                <?php endif; ?>


            </form>


            <!-- =========================
                 SIGN UP
            ========================== -->

            <form
                id="signup"
                method="POST"
            >

                <p>

                    Already have an account?

                    <a onclick="selectView('signin')">
                        Sign In
                    </a>.

                </p>


                <label>
                    Username
                </label>


                <div class="control">

                    <input
                        type="text"
                        name="username"
                        placeholder="myusername"
                        required
                    />

                    <i class="ai-person"></i>

                </div>


                <label>
                    Password
                </label>


                <div class="control">

                    <input
                        type="password"
                        name="password"
                        placeholder="●●●●●●●●●●●●●●●"
                        required
                    />

                    <i class="ai-lock-on"></i>

                </div>


                <button
                    type="submit"
                    name="signup"
                >
                    Sign Up
                </button>


                <?php if ($signupMessage !== ""): ?>

                    <p>
                        <?= htmlspecialchars($signupMessage) ?>
                    </p>

                <?php endif; ?>


            </form>


        </div>

    </div>

</div>


<script src="main.js"></script>

</body>
</html>