<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Sign Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 350px;
        }
        h2 {
            text-align: center;
            color: #0078d7;
        }
        .input-field {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }
        .input-field:focus {
            border-color: #0078d7;
            outline: none;
        }
        .submit-btn {
            width: 100%;
            padding: 10px;
            background-color: #0078d7;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #005bb5;
        }
        .toggle-buttons {
            text-align: center;
            margin-top: 20px;
        }
        .toggle-buttons a {
            color: #0078d7;
            text-decoration: none;
            font-size: 1.1rem;
        }
        .toggle-buttons a:hover {
            text-decoration: underline;
        }
        .form-container {
            display: none;
        }
        .show-login .login-form {
            display: block;
        }
        .show-signup .signup-form {
            display: block;
        }
    </style>
</head>
<body>

    <div class="container show-login" id="auth-container">
        <h2>Login</h2>
        <!-- Login Form -->
        <div class="login-form form-container">
            <form action="login-action.php" method="POST">
                <input type="text" class="input-field" name="username" placeholder="Username" required><br>
                <input type="password" class="input-field" name="password" placeholder="Password" required><br>
                <button type="submit" class="submit-btn">Login</button>
            </form>
        </div>

        <!-- Sign Up Form -->
        <div class="signup-form form-container">
            <form action="signup-action.php" method="POST">
                <input type="text" class="input-field" name="username" placeholder="Username" required><br>
                <input type="email" class="input-field" name="email" placeholder="Email" required><br>
                <input type="password" class="input-field" name="password" placeholder="Password" required><br>
                <button type="submit" class="submit-btn">Sign Up</button>
            </form>
        </div>

        <div class="toggle-buttons">
            <a href="#" id="toggle-login">Already have an account? Login</a><br>
            <a href="#" id="toggle-signup">Don't have an account? Sign Up</a>
        </div>
    </div>

    <script>
        const loginForm = document.querySelector('.login-form');
        const signupForm = document.querySelector('.signup-form');
        const authContainer = document.getElementById('auth-container');
        const toggleLogin = document.getElementById('toggle-login');
        const toggleSignup = document.getElementById('toggle-signup');

        toggleLogin.addEventListener('click', (e) => {
            e.preventDefault();
            authContainer.classList.remove('show-signup');
            authContainer.classList.add('show-login');
        });

        toggleSignup.addEventListener('click', (e) => {
            e.preventDefault();
            authContainer.classList.remove('show-login');
            authContainer.classList.add('show-signup');
        });
    </script>
</body>
</html>