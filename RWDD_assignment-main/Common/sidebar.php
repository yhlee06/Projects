<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

$user_username = $_SESSION['username'] ?? null;
if (!$user_username) {
    die("User not logged in.");
}

$stmt = $pdo->prepare("SELECT role, user_type FROM user WHERE username = ?");
$stmt->execute([$user_username]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$role = $row['role'] ?? null;
$user_type = $row['user_type'] ?? null;
$base_url = "/RWDD_assignment/";
?>

<style>
  .logout_button_container {
    display: flex;
  }

  .logout_button {
    display: inline-block;
    width: 95px;
    height: 40px;
    line-height: 40px;
    text-align: center;
    background: linear-gradient(135deg, #265852, #4ba26f);
    color: white;
    font-size: 14px;
    font-weight: bold;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
    transition: all 0.3s ease;
    margin-left: auto;
    margin-top: 10px;
  }

  .logout_button:hover {
    background: linear-gradient(135deg, #6db9a3, #b8d6c1);
    transform: scale(1.05);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
  }
</style>
<input type="checkbox" id="nav-toggle" class="nav-toggle" style="display: none;">

<label for="nav-toggle" class="mobile-nav-button">
  <span class="hamburger"></span>
</label>

<div class="sidebar">
  <div class="logo_name_container">
    <div class="logo-pic">
      <img src="/RWDD_assignment/public/images/logo.jpeg" width="50" height="50" style="border-radius:50%;">
    </div>
    <h2 style="margin-top: 7px; color: white">Zero Waste</h2>
  </div>

  <?php if ($role === 'food_donor') { ?>

    <a href="../FoodDonor/dashboard.php" class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active':''; ?>">Dashboard</a>
    <a href="../FoodDonor/reward.php" class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='reward.php'?'active':''; ?>">Reward</a>
    <a href="../FoodDonor/donationHistory.php" class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='donationHistory.php'?'active':''; ?>">History</a>
    <a href="../FoodDonor/impactTracker.php" class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='impactTracker.php'?'active':''; ?>">Impact Tracker</a>
    <a href="../Common/profile.php" class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='profile.php'?'active':''; ?>">Profile</a>

<?php } elseif ($role === 'user' && ($user_type ?? '') === 'student') { ?>

    <a href="<?php echo $base_url; ?>Users/Student/studentDashboard.php"class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='studentDashboard.php'?'active':''; ?>">Student Dashboard</a>
    <a href="<?php echo $base_url; ?>Users/Student/recipes.php"class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='recipes.php'?'active':''; ?>">Recipes</a>
    <a href="<?php echo $base_url; ?>Users/Student/challenges.php" class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='challenges.php'?'active':''; ?>">Challenges</a>
    <a href="<?php echo $base_url; ?>Users/Student/community.php" class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='community.php'?'active':''; ?>">Community</a>
    <a href="<?php echo $base_url; ?>Users/Student/contact.php"  class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='contact.php'?'active':''; ?>">Contact</a>
    <a href="<?php echo $base_url; ?>Users/Student/feedback.php" class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='feedback.php'?'active':''; ?>">Feedback</a>
    <a href="<?php echo $base_url; ?>Common/profile.php" class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='profile.php'?'active':''; ?>">Profile </a>


  <?php } elseif ($role === 'user' && $user_type === 'staff') { ?>

  <a href="<?php echo $base_url; ?>Users/Staff/staffDashboard.php" class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='staffDashboard.php'?'active':''; ?>">Dashboard</a>
  <a href="<?php echo $base_url; ?>Users/Staff/homepage.php" class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='homepage.php'?'active':''; ?>">Homepage</a>
  <a href="<?php echo $base_url; ?>Users/Staff/Feedback.php" class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='Feedback.php'?'active':''; ?>">Feedback</a>
  <a href="<?php echo $base_url; ?>Users/Staff/likeRecipe.php" class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='likeRecipe.php'?'active':''; ?>">Manage Recipes</a>
  <a href="<?php echo $base_url; ?>Users/Staff/joinChallenge.php" class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='joinChallenge.php'?'active':''; ?>">Challenges</a>
  <a href="<?php echo $base_url; ?>Common/profile.php" class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='profile.php'?'active':''; ?>">Profile</a>

  <?php } elseif ($role === 'admin') { ?>

    <a href="../Admin/userManagement.php" class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='userManagement.php'?'active':''; ?>">User Management</a>
    <a href="../Admin/recipeReview.php" class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='recipeReview.php'?'active':''; ?>">Recipe Review</a>
    <a href="../Admin/challenge.php" class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='challenge.php'?'active':''; ?>">Challenges</a>
    <a href="../Admin/analytics.php" class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='analytics.php'?'active':''; ?>">Analytics</a>
    <a href="../Admin/feedback.php" class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='feedback.php'?'active':''; ?>">Feedback</a>
    <a href="../Common/profile.php" class="menu-card <?php echo basename($_SERVER['PHP_SELF'])=='profile.php'?'active':''; ?>">Profile</a>

  <?php } ?>

  <div class="logout_button_container">
    <button class="logout_button" onclick="window.location.href='/RWDD_assignment/LoginSignUp/logout.php'">Log Out</button>
  </div>
</div>


