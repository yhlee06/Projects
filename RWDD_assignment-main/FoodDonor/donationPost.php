<?php
session_start();
require '../Common/db.php';
require '../Common/accessControl.php';

$stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Donation Post</title>
  <link href="../Common/sidebar.css" rel="stylesheet" type="text/css"/>
 <style>
  body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #F3EAD7;
    color: #1E2D24;
  }

  .main {
    margin-left: 0;
    padding: 16px;
    padding-bottom: 84px;
    max-width: 960px;
    margin-right: auto;
    margin-left: auto;
  }

  .back-button {
    padding: 10px 24px;
    border: none;
    border-radius: 22px;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    background: linear-gradient(135deg, #165540, #2ECC71);
    color: white;
    box-shadow: 0 4px 12px rgba(22,85,64,0.35);
    transition: transform 0.2s ease;
    margin-bottom: 20px;
  }

  .back-button:hover {
    transform: translateY(-2px);
  }

  .title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
  }

  .title h2 {
    margin: 0;
    font-size: 18px;
    color: #165540;
  }

  .upload-box {
    width: 100%;
    min-height: 200px;
    background: #E6EFE9;
    border: 2px dashed #88B393;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 30px;
    cursor: pointer;
  }

  .upload-box img {
    max-height: 160px;
    border-radius: 10px;
    display: none;
  }

  /* ✅ FORM CARD – DARK GREEN */
  .form-field {
    background: #1F5C44;
    padding: 16px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 4px 12px rgba(22,85,64,0.35);
  }

  /* ✅ LABELS – WHITE */
  .form-field label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #ffffff;
  }

  /* ✅ INPUTS – LIGHT FOR CONTRAST */
  .form-field input {
    width: 100%;
    padding: 10px 0px;
    padding-left: 4px;
    background: #E6EFE9;
    border: 1.5px solid #B7DCC0;
    color: #1E2D24;
    border-radius: 6px;
    font-size: 15px;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.15);
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
  }

  .form-field input::placeholder {
    color: #6b6b6b;
  }

  .form-field input:focus {
    outline: none;
    border-color: #2ECC71;
    box-shadow: 0 0 0 2px rgba(46,204,113,0.35);
  }

  input[type="date"]::-webkit-calendar-picker-indicator {
    cursor: pointer;
  }

  .center-container {
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }

  .post-button {
    background: linear-gradient(135deg, #165540, #2ECC71);
    color: white;
    padding: 16px 60px;
    border: none;
    border-radius: 25px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    text-align: center;
    box-shadow: 0 4px 12px rgba(22,85,64,0.35);
    transition: transform 0.2s ease;
    max-width: 200px;
    margin-top: 10px;
  }

  .post-button:hover {
    transform: translateY(-2px);
  }

  @media (min-width: 768px) {
    .main {
      margin-left: 310px;
      padding: 28px;
      max-width: 1130px;
    }

    .title h2 {
      font-size: 20px;
    }

    .center-container {
      justify-content: flex-start;
    }

    .post-button {
      min-width: 180px;
      display: block;
      margin: 0 auto;
    }
  }
</style>


</head>
<body>
  <?php include '../Common/sidebar.php'; ?>
  <div class="main">
    <button class="back-button" onclick="window.history.back()">< Back</button>

    <div class="title">
      <img src="../public/icons/donation.png" width="25" height="25">
      <h2 style="margin:0;">Donation Post</h2>
    </div>

    <form id="donationForm" method="POST" enctype="multipart/form-data">
      <div class="upload-box" onclick="document.getElementById('donationImageInput').click()">
        <input type="file" id="donationImageInput" name="donation_image" accept="image/*" style="display:none;" onchange="previewImage(event)">
        <span id="uploadText">Click to upload image</span>
        <img id="preview" />
      </div>

      <div class="form-field">
        <label>Food Name</label>
        <input type="text" name="food_name" placeholder="Enter food name" required>
      </div>

      <div class="form-field">
        <label>Description</label>
        <input type="text" name="description" placeholder="Enter description" required>
      </div>

      <div class="form-field">
        <label>Quantity</label>
        <input type="number" name="quantity" placeholder="Enter quantity" required>
      </div>

      <div class="form-field">
        <label>Expiry Date</label>
        <input type="date" name="expiry_date" required>
      </div>

      <div class="form-field">
        <label>Location</label>
        <input type="text" name="location" placeholder="Enter location" required>
      </div>

      <div class="center-container">
        <button class="post-button" type="submit">Post</button>
      </div>
    </form>
  </div>

  <script>
    function previewImage(event) {
      const input = event.target;
      const preview = document.getElementById('preview');
      const uploadText = document.getElementById('uploadText');

      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.style.display = 'block';
          uploadText.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
      }
    }

    const form = document.getElementById('donationForm');
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const formData = new FormData(form);
      const res = await fetch('uploadDonation.php', { method: 'POST', body: formData });
      const text = await res.text();

      if (text.startsWith("success")) {
        alert("Upload successfully!");
        window.location.href = "donationHistory.php";
      } else {
        alert(text.replace("error:", "").trim());
      }
    });
  </script>
</body>
</html>