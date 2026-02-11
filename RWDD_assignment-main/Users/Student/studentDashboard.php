<?php
require_once __DIR__ . "/../../Common/accessControl.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Dashboard</title>

  <!-- Common sidebar CSS -->
<link rel="stylesheet" href="/RWDD_assignment/Common/sidebar.css">

<style>
  .main{
    margin-left: 260px;
    padding: 34px 54px;
    min-height: 100vh;
    box-sizing: border-box;
  }

  .container-wide{
    max-width: 1200px;
    width: 100%;
    margin: 0 auto;
  }

  /* responsive */
  @media (max-width: 900px){
    .main{
      margin-left: 0;
      padding: 20px;
    }
  }

  body {
    background-color: #F3EAD7;
    color: #1E2D24;
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
  }
/* ===== PAGE HEADER ===== */
.page-title{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:12px;
  margin-bottom:18px;
}
.welcome{
  margin:0;
  font-size:22px;
  color:#165540;
}
.sub{
  margin:6px 0 0;
  color:#4b6b5a;
  font-size:13px;
}

/* ===== HERO ===== */
.hero{
  background:#E6EFE9;
  border:1px solid rgba(22,85,64,0.12);
  border-radius:16px;
  padding:18px;
  margin-bottom:16px;
}
.hero-left{
  display:flex;
  gap:14px;
  align-items:center;
}
.avatar{
  width:64px;
  height:64px;
  border-radius:50%;
  object-fit:cover;
}
.hero h2{ 
  margin:0; 
  font-size:22px; 
  color:#165540;
}
.hero p{ 
  margin:6px 0; 
  color:#1E2D24; 
}
.hero small{ 
  color:#4b6b5a; 
}

/* ===== GRID CARDS ===== */
.grid{
  display:grid;
  grid-template-columns: repeat(3, minmax(0,1fr));
  gap:16px;
}
.card{
  display:block;
  background:#A3CFAE;
  border-left:6px solid #165540;
  border-radius:14px;
  padding:16px;
  text-decoration:none;
  color:#1E2D24;
  transition:0.2s;
  min-height:110px;
  box-shadow:0 4px 10px rgba(22,85,64,0.25);
}
.card:hover{
  transform:translateY(-2px);
  background:#B7DCC0;
}
.card h3{
  margin:0 0 8px;
  font-size:16px;
  color:#165540;
}
.card p{
  margin:0;
  color:#1E2D24;
  font-size:13px;
  line-height:1.4;
}

/* ===== QUICK BUTTONS ===== */
.quick{
  display:flex;
  gap:10px;
  flex-wrap:wrap;
}
.btn{
  display:inline-block;
  padding:10px 14px;
  border-radius:10px;
  background:#165540;
  color:#ffffff;
  text-decoration:none;
  font-size:13px;
  font-weight:600;
  transition:0.3s;
}
.btn:hover{
  background:#1e7a5a;
}

/* ===== ICONS ===== */
.mini-icon{
  width:50px;
  height:50px;
  object-fit:contain;
  vertical-align:-10px;
  margin-right:10px;
}

/* ===== STATS ===== */
.stats-row{
  display:grid;
  grid-template-columns: repeat(5, minmax(0,1fr));
  gap:12px;
  margin-bottom:16px;
}
.stat-card{
  background:#ffffff;
  border:1px solid rgba(22,85,64,0.15);
  border-radius:14px;
  padding:14px;
}
.stat-top{
  display:flex;
  align-items:center;
  gap:10px;
  color:#165540;
  font-size:13px;
}
.stat-icon{
  width:50px;
  height:50px;
  object-fit:contain;
}
.stat-value{
  font-size:26px;
  font-weight:700;
  margin-top:10px;
  color:#1E2D24;
}

/* ===== PANELS ===== */
.two-col{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:12px;
  margin-bottom:16px;
}
.three-col{
  display:grid;
  grid-template-columns:1fr 1fr 1fr;
  gap:12px;
  margin-bottom:16px;
}
.panel{
  background:#ffffff;
  border:1px solid rgba(22,85,64,0.15);
  border-radius:14px;
  padding:14px;
}
.panel h3{
  margin:0 0 10px;
  font-size:15px;
  color:#165540;
}
.activity{
  margin:0;
  padding-left:18px;
  color:#1E2D24;
}
.activity li{ margin:8px 0; }

/* ===== TOP RECIPE ===== */
.top-recipe{
  display:flex;
  gap:12px;
  align-items:center;
}
.top-img{
  width:120px;
  height:70px;
  object-fit:cover;
  border-radius:10px;
  border:1px solid rgba(22,85,64,0.15);
}
.top-title{
  color:#165540;
  margin-top:6px;
}

/* ===== MINI GRID ===== */
.mini-grid{
  display:grid;
  grid-template-columns: repeat(3, minmax(0,1fr));
  gap:10px;
}
.mini-card{
  border:1px solid rgba(22,85,64,0.15);
  border-radius:12px;
  overflow:hidden;
  background:#ffffff;
  text-align:center;
}
.mini-card img{
  width:100%;
  height:70px;
  object-fit:cover;
}
.mini-card span{
  display:block;
  padding:8px;
  font-size:12px;
  color:#165540;
}

/* ===== RESPONSIVE ===== */
@media (max-width:1100px){
  .stats-row{ grid-template-columns: repeat(2,1fr); }
  .three-col{ grid-template-columns:1fr; }
  .two-col{ grid-template-columns:1fr; }
}
@media (max-width:900px){
  .grid{ grid-template-columns: repeat(2,1fr); }
}
@media (max-width:600px){
  .grid{ grid-template-columns:1fr; }
}


.main{
  margin-left: 260px;
  padding: 34px 54px;
  min-height: 100vh;
  box-sizing: border-box;
}

/* make content look “wide and centered” like recipes */
.container-wide{
  max-width: 1200px;
  width: 100%;
}

/* make title sizes like My Recipes page */
.welcome{
  font-size: 36px;
  font-weight: 900;
  letter-spacing: 0.3px;
  color:#165540;
}

.sub{
  font-size: 14px;
  color:#4b6b5a;
}

/* make cards spacing closer to recipes look */
.hero,
.stat-card,
.panel{
  border-radius: 18px;
}

.stats-row{
  gap: 14px;
}

.two-col,
.three-col{
  gap: 14px;
}

/* mobile fix (sidebar collapses) */
@media (max-width: 600px){
  .main{
    margin-left: 0;
    padding: 18px;
  }
}

</style>

</head>

<body>
  <?php include __DIR__ . "/../../Common/sidebar.php"; ?>

  <div class="main">
      <div class="container-wide">
    <div class="page-title">
      <div>
        <h2 class="welcome">Welcome, <?php echo htmlspecialchars($user['name'] ?? $user['username'] ?? 'Student'); ?> 
        <img src="/RWDD_assignment/public/icons/hand-waving-hand.gif" class="mini-icon" alt="Hi" width='50', height='50'>
      </h2>
        <p class="sub">Manage your recipes, challenges, stats, and profile in one place.</p>
      </div>
    </div>

    <!-- ===== Dashboard Header Card ===== -->
  <div class="hero">
    <div class="hero-left">
      <img class="avatar" src="/RWDD_assignment/public/icons/user.png" alt="User">
      <div>
        <h2>Welcome back, <?php echo htmlspecialchars($user['name'] ?? $user['username'] ?? 'Student'); ?>!</h2>
        <p>You're making a difference by reducing food waste 
          <img src="/RWDD_assignment/public/icons/world.png" alt="World" width='25' height='25'>
        </p>
        <small>
          <b>Student</b> |
          Joined: <?php echo !empty($user['created_at']) ? date("F Y", strtotime($user['created_at'])) : "N/A"; ?>
        </small>
      </div>
    </div>
  </div>

  <!-- ===== Stats Row ===== -->
  <div class="stats-row">
    <div class="stat-card">
      <div class="stat-top">
        <img src="/RWDD_assignment/public/icons/posted.png" class="stat-icon" alt="">
        <span>Recipes Posted</span>
      </div>
      <div class="stat-value">12</div>
    </div>

    <div class="stat-card">
      <div class="stat-top">
        <img src="/RWDD_assignment/public/icons/like.png" class="stat-icon" alt="">
        <span>Likes Received</span>
      </div>
      <div class="stat-value">340</div>
    </div>

    <div class="stat-card">
      <div class="stat-top">
        <img src="/RWDD_assignment/public/icons/saved.png" class="stat-icon" alt="">
        <span>Recipes Saved</span>
      </div>
      <div class="stat-value">8</div>
    </div>

    <div class="stat-card">
      <div class="stat-top">
        <img src="/RWDD_assignment/public/icons/views.png" class="stat-icon" alt="">
        <span>Total Views</span>
      </div>
      <div class="stat-value">1,250</div>
    </div>

    <div class="stat-card">
      <div class="stat-top">
        <img src="/RWDD_assignment/public/icons/challenges.png" class="stat-icon" alt="">
        <span>Points Earned</span>
      </div>
      <div class="stat-value">780</div>
    </div>
  </div>

  <!-- ===== 2 Big Boxes Row ===== -->
  <div class="two-col">
    <div class="panel">
      <h3>Recent Activity</h3>
      <ul class="activity">
        <li>You added a new recipe: “Healthy Banana Bread”</li>
        <li>Your recipe got 12 new likes!</li>
        <li>You joined the Zero-Waste Challenge.</li>
      </ul>
    </div>

    <div class="panel">
      <h3>Top Recipe</h3>
      <div class="top-recipe">
        <img src="/RWDD_assignment/public/recipes/fried_rice.png" class="top-img" alt="Top recipe">
        <div>
          <p><b>Most Liked Recipe</b></p>
          <p class="top-title">Vegan Fried Rice — 45 Likes</p>
        </div>
      </div>
    </div>
  </div>

  <!-- ===== 3 Panels Row ===== -->
  <div class="three-col">
    <div class="panel">
      <h3>Current Challenge</h3>
      <p>7-Day Zero Waste</p>
      <p>Progress: <b>4 / 7</b> Days Completed</p>
      <p><img src="/RWDD_assignment/public/icons/badge.png" class="mini-icon" alt=""> Badges Earned: <b>2</b></p>
    </div>

    <div class="panel">
      <h3>Saved Recipes (3)</h3>
      <div class="mini-grid">
        <div class="mini-card">
          <img src="/RWDD_assignment/public/recipes/bread_toast.png" alt="">
          <span>Bread Toast</span>
        </div>
        <div class="mini-card">
          <img src="/RWDD_assignment/public/recipes/leftover_veg_stirfry.png" alt="">
          <span>Veggie Stir-Fry</span>
        </div>
        <div class="mini-card">
          <img src="/RWDD_assignment/public/recipes/banana_smoothie.png" alt="">
          <span>Banana Smoothie</span>
        </div>
      </div>
    </div>

    <div class="panel">
      <h3>Liked Recipes (5)</h3>
      <div class="mini-grid">
        <div class="mini-card">
          <img src="/RWDD_assignment/public/recipes/fried_rice.png" alt="">
          <span>Fried Rice</span>
        </div>
        <div class="mini-card">
          <img src="/RWDD_assignment/public/recipes/banana_pancakes.png" alt="">
          <span>Banana Pancakes</span>
        </div>
        <div class="mini-card">
          <img src="/RWDD_assignment/public/recipes/chicken_rice_bowl.png" alt="">
          <span>Chicken Rice Bowl</span>
        </div>
      </div>
    </div>
  </div>


  </div>
</body>
</html>
