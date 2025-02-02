session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_auth";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle User Registration
if (isset($_POST["register"])) {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT); // Hash the password

    if (!empty($username) && !empty($email) && !empty($_POST["password"])) {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);
        
        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! You can now log in.');</script>";
        } else {
            echo "<script>alert('Error: Username or Email already exists.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Please fill all fields!');</script>";
    }
}

// Handle User Login
if (isset($_POST["login"])) {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $hashed_password);
        
        if ($stmt->num_rows > 0) {
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION["user_id"] = $id;
                $_SESSION["username"] = $username;
                header("Location: welcome.php");
                exit;
            } else {
                echo "<script>alert('Incorrect password!');</script>";
            }
        } else {
            echo "<script>alert('Username not found!');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Please fill all fields!');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Sign Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 50px;
        }
        .container {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        .toggle {
            margin-top: 10px;
            color: blue;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 id="form-title">Login</h2>
        
        <!-- Login Form -->
        <form method="POST" id="login-form">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>

        <!-- Sign Up Form -->
        <form method="POST" id="signup-form" style="display: none;">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Sign Up</button>
        </form>

        <p class="toggle" onclick="toggleForms()">Don't have an account? Sign Up</p>
    </div>

    <script>
        function toggleForms() {
            var loginForm = document.getElementById("login-form");
            var signupForm = document.getElementById("signup-form");
            var formTitle = document.getElementById("form-title");
            var toggleText = document.querySelector(".toggle");

            if (loginForm.style.display === "none") {
                loginForm.style.display = "block";
                signupForm.style.display = "none";
                formTitle.innerText = "Login";
                toggleText.innerText = "Don't have an account? Sign Up";
            } else {
                loginForm.style.display = "none";
                signupForm.style.display = "block";
                formTitle.innerText = "Sign Up";
                toggleText.innerText = "Already have an account? Login";
            }
        }
    </script>
</body>
</html>