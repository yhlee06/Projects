<?php
session_start();
require '../Common/db.php';
require '../Common/accessControl.php';

$stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$donation_id = $_GET['id'] ?? null;
$donation = null;

if ($donation_id) { 
  $stmt = $pdo->prepare("SELECT * FROM food_donation WHERE id = ?"); 
  $stmt->execute([$donation_id]); 
  $donation = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Donation Detail</title>
  <link href="../Common/sidebar.css" rel="stylesheet" type="text/css"/>
  <style>
  /* Mobile-first base styles */
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

  .button-container {
    display: flex;
    gap: 12px;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    flex-wrap: wrap;
  }

  .back-button,
  .delete-button {
    padding: 10px 24px;
    border: none;
    border-radius: 22px;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    transition: transform 0.2s ease;
    margin-bottom: 20px;
  }

  .back-button {
    background: linear-gradient(135deg, #165540, #2ECC71);
    color: white;
  }

  .back-button:hover {
    transform: translateY(-2px);
  }

  .delete-button {
    background: linear-gradient(135deg, #ff6a6a, #c31432);
    color: white;
  }

  .delete-button:hover {
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
  }

  .form-field {
    background: #1F5C44;
    padding: 16px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 4px 10px rgba(22,85,64,0.15);
  }

  .form-field label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #000000ff;
  }

  .form-field input,
  .form-field select {
    width: 100%;
    padding: 10px 0px;
    padding-left: 4px;
    background: #E6EFE9;
    border: 1.5px solid #B7DCC0;
    color: #1E2D24;
    border-radius: 6px;
    font-size: 15px;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.12);
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
  }

  .form-field input:focus,
  .form-field select:focus {
    outline: none;
    border-color: #165540;
    box-shadow: 0 0 0 2px rgba(22,85,64,0.25);
  }

  input[type="date"]::-webkit-calendar-picker-indicator {
    cursor: pointer;
  }

  .form-field select {
    appearance: none;
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 12px;
  }

  .center-container {
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }

  /* SAVE BUTTON */
  .save-button {
    background: linear-gradient(135deg, #165540, #2ECC71);
    color: white;
    padding: 14px 26px;
    border: none;
    border-radius: 18px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    text-align: center;
    box-shadow: 0 4px 12px rgba(22,85,64,0.35);
    transition: transform 0.2s ease;
    max-width: 200px;
    margin-top: 10px;
  }

  .save-button:hover {
    transform: translateY(-2px);
  }

  /* Desktop overrides */
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

    .save-button {
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
    <div class="button-container">
      <button class="back-button" onclick="window.history.back()">< Back</button>
      <?php if ($donation): ?>
        <button class="delete-button" id="deleteDonationBtn">Delete</button>
      <?php endif; ?>
    </div>

    <div class="title">
      <img src="../public/icons/donationdetail.png" width="22" height="22">
      <h2>Donation Detail</h2>
    </div>

    <form id="donationPostInformationForm" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $donation['id'] ?? ''; ?>">

      <div class="upload-box" onclick="document.getElementById('donationImageInput').click()">
        <?php if ($donation && !empty($donation['image'])): ?>
          <img id="preview" src="<?php echo htmlspecialchars($donation['image']); ?>" alt="Donation Image">
        <?php else: ?>
          <span id="uploadText">Tap to upload image (optional)</span>
        <?php endif; ?>
        <input type="file" id="donationImageInput" name="donation_image" accept="image/*" style="display:none;" onchange="previewImage(event)">
      </div>

      <div class="form-field">
        <label>Food name</label>
        <input type="text" name="food_name" value="<?php echo htmlspecialchars($donation['food_name'] ?? ''); ?>" required>
      </div>

      <div class="form-field">
        <label>Description</label>
        <input type="text" name="description" value="<?php echo htmlspecialchars($donation['description'] ?? ''); ?>" required>
      </div>
      
      <div class="form-field">
        <label>Quantity</label>
        <input type="number" name="quantity" value="<?php echo $donation['quantity'] ?? ''; ?>" required>
      </div>

      <div class="form-field">
        <label>Expiry date</label>
        <input type="date" name="expiry_date" value="<?php echo $donation['expiry_date'] ?? ''; ?>" required>
      </div>

      <div class="form-field">
        <label>Location</label>
        <input type="text" name="location" value="<?php echo $donation['location'] ?? ''; ?>" required>
      </div>

      <div class="form-field">
        <label>Status</label>
        <select name="status" required <?php if (($donation['status'] ?? '') === 'pickup' || ($donation['status'] ?? '') === 'expired') echo 'disabled'; ?>>
          <option value="active" <?php if (($donation['status'] ?? '') == 'active') echo 'selected'; ?>>Active</option>
          <option value="pickup" <?php if (($donation['status'] ?? '') == 'pickup') echo 'selected'; ?>>Pick Up</option>
          <option value="expired" <?php if (($donation['status'] ?? '') == 'expired') echo 'selected'; ?>>Expired</option>
        </select>
      </div>

      <?php if (($donation['status'] ?? '') !== 'pickup' && ($donation['status'] ?? '') !== 'expired'): ?>
        <div class="center-container">
          <button class="save-button" type="submit">Save</button>
        </div>
      <?php endif; ?>

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
          if (!preview) {
            const img = document.createElement('img');
            img.id = 'preview';
            img.style.maxHeight = '160px';
            img.style.borderRadius = '10px';
            document.querySelector('.upload-box').appendChild(img);
            uploadText && (uploadText.style.display = 'none');
            img.src = e.target.result;
          } else {
            preview.src = e.target.result;
            preview.style.display = 'block';
            uploadText && (uploadText.style.display = 'none');
          }
        };
        reader.readAsDataURL(input.files[0]);
      }
    }

    const form = document.getElementById('donationPostInformationForm');
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const formData = new FormData(form);

      const res = await fetch('saveDonation.php', { method: 'POST', body: formData });
      const text = await res.text();

      if (text.startsWith("success")) {
        alert(text.replace("success: ", ""));
        location.reload();
      } else {
        alert(text.replace("error: ", ""));
      }
    });

    const deleteBtn = document.getElementById('deleteDonationBtn');
    if (deleteBtn) {
      deleteBtn.addEventListener('click', async () => {
        if (!confirm("Are you sure you want to delete this donation?")) return;
        const formData = new FormData();
        formData.append("id", "<?php echo htmlspecialchars($donation['id'] ?? ''); ?>");
        const res = await fetch('deleteDonation.php', { method: 'POST', body: formData });
        const text = await res.text();

        if (text.startsWith("success")) {
          alert(text.replace("success: ", ""));
          window.location.href = "donationHistory.php";
        } else {
          alert(text.replace("error: ", ""));
        }
      });
    }
  </script>
</body>
</html>
