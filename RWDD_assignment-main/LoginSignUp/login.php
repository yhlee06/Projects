<?php
session_start();

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: ../Admin/userManagement.php");
        exit;
    }

    if ($_SESSION['role'] === 'food_donor') {
        header("Location: ../FoodDonor/dashboard.php");
        exit;
    }

    if ($_SESSION['role'] === 'user') {
        if (($_SESSION['user_type'] ?? '') === 'staff') {
            header("Location: ../Users/Staff/staffDashboard.php");
        } else {
            header("Location: ../Users/Student/studentDashboard.php");
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <style>
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: 'Josefin Sans', sans-serif;
  background: linear-gradient(135deg, #165540 0%, #2ECC71 100%);
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

form {
  background: #E6EFE9;
  width: 360px;
  padding: 40px 30px;
  border-radius: 16px;
  box-shadow: 0 12px 28px rgba(22,85,64,0.35);
  transition: all 0.3s ease;
  border: 1px solid #B7DCC0;
}

form:hover {
  transform: translateY(-4px);
  box-shadow: 0 18px 36px rgba(22,85,64,0.45);
}

h1 {
  text-align: center;
  color: #165540;
  margin-bottom: 25px;
  font-weight: 600;
}

label {
  display: block;
  font-weight: 600;
  color: #165540;
  margin-bottom: 6px;
}

input {
  width: 100%;
  padding: 12px 12px;
  margin-bottom: 18px;
  border: 1.5px solid #B7DCC0;
  border-radius: 8px;
  outline: none;
  font-size: 15px;
  background: #F3F8F5;
  color: #1E2D24;
  transition: border 0.2s ease, box-shadow 0.2s ease;
}

input:focus {
  border-color: #165540;
  box-shadow: 0 0 0 2px rgba(22,85,64,0.25);
  background: #ffffff;
}

#submit-button {
  background: linear-gradient(135deg, #165540, #2ECC71);
  color: #ffffff;
  font-size: 16px;
  font-weight: 600;
  border: none;
  width: 100%;
  height: 42px;
  border-radius: 21px;
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  margin-top: 10px;
}

#submit-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 18px rgba(22,85,64,0.4);
}

.sign-in-container {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 20px;
  font-size: 14px;
}

.sign-in-question {
  color: #3d5f50;
}

.sign-in-button {
  color: #165540;
  text-decoration: none;
  font-weight: 600;
  margin-left: 6px;
  transition: color 0.2s;
}

.sign-in-button:hover {
  color: #1e7a5a;
  text-decoration: underline;
}

#back-button {
  background: #B7DCC0;
  color: #1E2D24;
  font-size: 14px;
  font-weight: 600;
  border: none;
  width: 100%;
  height: 40px;
  border-radius: 20px;
  cursor: pointer;
  margin-top: 10px;
  transition: background 0.2s ease;
}

#back-button:hover {
  background: #A3CFAE;
}

.error {
  color: #c31432;
  margin-top: 10px;
  text-align: center;
  display: none;
}

.success {
  color: #165540;
  margin-top: 10px;
  text-align: center;
  display: none;
}
</style>

</head>
<body>
  <form id="loginForm">
    <h1>Login</h1>
    <label>Username</label>
    <input type="text" name="username" placeholder="Enter your username">

    <label>Password</label>
    <input type="password" name="password" placeholder="Enter your password">

    <button id="submit-button" type="submit">Login</button>
    <div id="msg" class="error"></div>

    <button type="button" id="back-button" onclick="window.location.href='/rwdd_assignment/index.php'">
    Back
    </button>

    <div class="sign-in-container">
      <p class="sign-in-question">Don't have an account?</p>
      <a class="sign-in-button" href="/rwdd_assignment/LoginSignUp/signUp.php">Register</a>
    </div>
  </form>

  <script>
    const form = document.getElementById('loginForm');
    const msg = document.getElementById('msg');
    form.addEventListener('submit', async (e) => {
          console.log("a");

      e.preventDefault();
      msg.style.display = 'none';

      const formData = new FormData(form);
      console.log("b");
      const res = await fetch('enterAccount.php', { method: 'POST', body: formData });
      console.log("c");
      const data = await res.json();

      if (data.success) {
        msg.className = 'success';
        msg.textContent = 'Login successful!';
        msg.style.display = 'block';
        setTimeout(() => window.location.href = data.redirect, 1500);
      } else {
        msg.className = 'error';
        msg.textContent = data.message;
        msg.style.display = 'block';
      }
    });
  </script>
</body>
</html>
