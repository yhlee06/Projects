<?php
session_start();
require '../Common/db.php';

$stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>
  <link href="../Common/sidebar.css" rel="stylesheet" type="text/css"/>
<style>
  body {
    background: #F3EAD7;
    color: #1E2D24;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
  }

  .main {
    margin-left: 280px;
    padding: 40px;
  }

  .profile-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
  }

  .profile-left {
    display: flex;
    align-items: center;
    gap: 15px;
  }

  .profile-pic {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: white;
    object-fit: cover;
  }

  .profile-info h3 {
    margin: 0;
    font-size: 20px;
    color: #165540;
  }

  .profile-info p {
    margin: 5px 0 0;
    font-size: 14px;
    color: #1E2D24;
  }

  .card {
    background: #1F5C44;
    padding: 18px;
    border-radius: 14px;
    margin-bottom: 22px;
    box-shadow: 0 4px 10px rgba(22, 85, 64, 0.15);
  }

  .card label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #1E2D24;
  }

  input {
    width: 97.5%;
    padding: 12px;
    font-size: 15px;
    border-radius: 8px;

    background: #E6EFE9;       
    border: 1.5px solid #88B393;

    color: #1E2D24;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
  }

  input:focus {
    outline: none;
    border-color: #165540;
    box-shadow: 0 0 0 2px rgba(22, 85, 64, 0.25);
  }

  .action-box {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 35px;
    gap: 40px;
  }

  .save-button {
    flex: 1;
    padding: 14px;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    text-align: center;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #165540, #2ECC71);
    color: white;
  }

  .save-button:hover {
    background: linear-gradient(135deg, #1e7a5a, #3ee38f);
    transform: translateY(-2px);
  }

  .change-password-button {
    display: inline-block;
    flex: 1;
    padding: 14px 0;
    font-size: 16px;
    font-weight: bold;
    border-radius: 30px;
    cursor: pointer;
    text-align: center;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #165540, #2ECC71);
    color: white;
    text-decoration: none;
  }

  .change-password-button:hover {
    background: linear-gradient(135deg, #1e7a5a, #3ee38f);
    transform: translateY(-2px);
  }

  .qr-code {
    width: 90px;
    height: 90px;
    object-fit: contain;
    background: white;
    padding: 8px;
    border-radius: 12px;
    box-shadow: 0 6px 16px rgba(0,0,0,0.35);
    align-self: flex-end;
  }

  @media only screen and (max-width: 600px) {
    .main {
      margin-left: 0;
      padding: 20px;
    }

    .profile-header {
      flex-direction: column;
      align-items: center;
      text-align: center;
      margin-bottom: 25px;
    }

    .save-button,
    .change-password-button {
      width: 100%;
    }

    .card input {
      width: 100%;
      box-sizing: border-box;
    }

    .qr-code {
      position: static;
      display: block;
      margin: 20px auto 30px;
    }

    .action-box {
      flex-direction: column;
      gap: 20px;
    }
  }
</style>



</head>
<body>
  <?php include '../Common/sidebar.php'; ?>

  <div class="main">
    <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;">
    <h2 style="margin:0;">Profile</h2>
    </div>

    <form id="personalInformationForm" class="profile-form-container" method="POST" enctype="multipart/form-data">
      <div class="profile-header">
        <div class="profile-left">
          <img src="../public/images/profile_icon.png" width="100" height="100" 
        style="border-radius: 50%; background: white; object-fit: cover;"
        class="profile-pic"> 

        <div class="profile-info">
          <h3>Name: <?php echo $user['name']; ?></h3>
          <p>Email Address: <?php echo $user['email']; ?></p>
        </div>
      </div>
        <img src="../public/images/user_qr.png" alt="User QR Code" class="qr-code"/>
      </div>

      <div class="card">
        <label>Username</label>
        <input type="text" name="username" value="<?php echo $user['username']; ?>" readonly>
      </div>

      <div class="card">
        <label>Role</label>
        <input type="text" value="<?php echo ucfirst($user['role']); ?>" readonly>
      </div>

      <div class="card">
        <label>Status</label>
        <input type="text" value="<?php echo ucfirst($user['status']); ?>" readonly>
      </div>

      <div class="card">
        <label>Phone Number</label>
        <input type="text" name="phone" value="<?php echo $user['phone_number']; ?>">
      </div>

      <div class="action-box">
        <a class="change-password-button" href="changePassword.php">Change Password</a>
        <button type="submit" name="save" class="save-button">Save</button>
      </div>
    </form>
  </div>

  <script>
    const form = document.getElementById('personalInformationForm');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const formData = new FormData(form);
      const res = await fetch('saveProfile.php', { method: 'POST', body: formData });
      const text = await res.text();

      if (text.startsWith("success")) {
        alert(text.replace("success: ", ""));
        location.reload();
      } else {
        alert(text.replace("error: ", ""));
      }
    });
  </script>
</body>
</html>
