<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Change Password</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: Arial;
      background: #3d3d3d;
      color: white;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    form {
      background: #ffffff;
      width: 360px;
      padding: 40px 30px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      border: 1px solid #eaeaea;
      border-radius: 10px;
    }

    form:hover {
      transform: translateY(-4px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    h1 {
      text-align: center;
      color: #333;
      margin-bottom: 25px;
      font-weight: 600;
    }

    label {
      display: block;
      font-weight: 500;
      color: #444;
      margin-bottom: 6px;
    }

    input {
      width: 95%;
      padding: 12px 10px;
      margin-bottom: 18px;
      border: 1px solid #ccc;
      border-radius: 8px;
      outline: none;
      font-size: 15px;
      transition: all 0.2s ease;
    }

    input:focus {
      border-color: #4caf50;
      box-shadow: 0 0 6px rgba(76, 175, 80, 0.3);
    }

    .button-container {
        display: flex;
        justify-content: center;
        margin-top: 10px;
        gap: 30px;
    }

    .btn {
      font-size: 16px;
      font-weight: 600;
      border: none;
      width: 150px;
      height: 42px;
      border-radius: 21px;
      cursor: pointer;
      transition: background 0.3s ease, transform 0.2s ease;
    }

    .btn-submit {
      background: #4caf50;
      color: #fff;
    }

    .btn-submit:hover {
      background: #45a049;
      transform: scale(1.02);
    }

    .btn-cancel {
      background: #e74c3c;
      color: #fff;
    }

    .btn-cancel:hover {
      background: #c0392b;
      transform: scale(1.02);
    }

    .error { color: red; margin-top: 10px; text-align: center; display: none; }
    .success { color: green; margin-top: 10px; text-align: center; display: none; }
  </style>
</head>
<body>
  <form id="changePasswordForm">
    <h1>Change Password</h1>
    <div id="msg" class="error"></div>
    <label>Current Password</label>
    <input type="password" name="current_password" placeholder="Enter current password" required>

    <label>New Password</label>
    <input type="password" name="new_password" placeholder="Enter new password" required>

    <label>Confirm Password</label>
    <input type="password" name="confirm_password" placeholder="Confirm new password" required>

    <div class="button-container">
        <button class="btn btn-submit" type="submit">Submit</button>
        <button class="btn btn-cancel" type="button" onclick="window.history.back()">Cancel</button>
    </div>
  </form>

  <script>
    const form = document.getElementById('changePasswordForm');
    const msg = document.getElementById('msg');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      msg.style.display = 'none';

      const formData = new FormData(form);
      const res = await fetch('updatePassword.php', { method: 'POST', body: formData });
      const data = await res.json();

      if (data.success) {
        msg.className = 'success';
        msg.textContent = 'Password changed successfully!';
        msg.style.display = 'block';
        setTimeout(() => window.location.href = '../Common/profile.php', 1500);
      } else {
        msg.className = 'error';
        msg.textContent = data.message;
        msg.style.display = 'block';
        setTimeout(() => window.location.href = '../Common/profile.php', 3500);
      }
    });
  </script>
</body>
</html>