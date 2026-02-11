<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up</title>
  <style>
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: 'Josefin Sans', sans-serif;
  background: linear-gradient(135deg, #165540 0%, #2ECC71 100%);
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

form {
  width: 400px;
  background: #E6EFE9;
  padding: 40px 30px;
  border-radius: 16px;
  box-shadow: 0 12px 28px rgba(22,85,64,0.35);
  border: 1px solid #B7DCC0;
  transition: all 0.3s ease;
}

form:hover {
  transform: translateY(-4px);
  box-shadow: 0 18px 36px rgba(22,85,64,0.45);
}

h1 {
  text-align: center;
  color: #165540;
  font-weight: 600;
  margin-bottom: 25px;
  letter-spacing: 1px;
}

label {
  display: block;
  font-weight: 600;
  color: #165540;
  margin-bottom: 6px;
  margin-left: 3px;
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

button {
  background: linear-gradient(135deg, #165540, #2ECC71);
  color: white;
  font-size: 16px;
  font-weight: 600;
  border: none;
  width: 100%;
  height: 45px;
  border-radius: 25px;
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  margin-top: 10px;
}

button:hover {
  box-shadow: 0 8px 18px rgba(22,85,64,0.4);
  transform: translateY(-2px);
}

.bottom-text {
  text-align: center;
  margin-top: 18px;
  font-size: 14px;
  color: #3d5f50;
}

.bottom-text a {
  color: #165540;
  text-decoration: none;
  font-weight: 600;
  margin-left: 5px;
  transition: color 0.2s;
}

.bottom-text a:hover {
  color: #1e7a5a;
  text-decoration: underline;
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


@media (max-width: 480px) {
  form {
    width: 90%;
    padding: 30px 20px;
  }

  h1 {
    font-size: 24px;
  }
}
</style>

</head>
<body>
  <form id="signupForm">
    <h1>Sign Up</h1>

    <label>Username</label>
    <input type="text" name="username" placeholder="Enter your username" />

    <label>Email</label>
    <input type="email" name="email" placeholder="Enter your email" />

    <label>Role</label>
    <select name="role" required style="width:100%; padding:12px; margin-bottom:18px; border:1px solid #ccc; border-radius:8px;">
      <option value="">-- Select Role --</option>
      <option value="user">User</option>
      <option value="food_donor">Food Donor</option>
      <option value="admin">Admin</option>
    </select>

    <label>Password</label>
    <input type="password" name="password" placeholder="Enter your password" />

    <label>Confirm Password</label>
    <input type="password" name="confirmPassword" placeholder="Confirm your password" />

    <button type="submit">Sign Up</button>

    <div id="msg" class="error"></div>
    <p class="bottom-text">
      Already have an account?
      <a href="/rwdd_assignment/LoginSignUp/login.php">Log In</a>
    </p>
  </form>

  <script>
    const form = document.getElementById('signupForm');
    const msg = document.getElementById('msg');
    form.addEventListener('submit', async (e) => {
          console.log("a");

      e.preventDefault();
      msg.style.display = 'none';

      const formData = new FormData(form);
      const res = await fetch('registerAccount.php', { method: 'POST', body: formData });
      const data = await res.json();

      if (data.success) {
        msg.className = 'success';
        msg.textContent = 'Registration successful! Redirecting to login...';
        msg.style.display = 'block';
        setTimeout(() => window.location.href = 'login.php', 1500);
      } else {
        msg.className = 'error';
        msg.textContent = data.message;
        msg.style.display = 'block';
      }
    });
  </script>
</body>
</html>
