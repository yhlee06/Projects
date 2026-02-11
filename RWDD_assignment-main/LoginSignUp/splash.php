<?php
session_start();

$redirectTo = "/RWDD_assignment/LoginSignUp/splash.php"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Loading...</title>

  <style>
    body{
      margin:0;
      height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      background: linear-gradient(135deg, #eafff1, #ffffff);
      font-family: Arial, sans-serif;
      overflow:hidden;
    }

    .splash{
      text-align:center;
      animation: fadeIn .6s ease forwards;
      opacity:0;
      transform: translateY(10px);
    }

    .logo-wrap{
      width:130px;
      height:130px;
      margin:0 auto 18px;
      border-radius: 28px;
      display:flex;
      align-items:center;
      justify-content:center;
      background: rgba(255,255,255,.8);
      box-shadow: 0 10px 30px rgba(0,0,0,.10);
      animation: pop .8s ease forwards;
    }

    .logo{
      width:90px;
      height:90px;
      object-fit:contain;
      animation: float 1.8s ease-in-out infinite;
    }

    .app-name{
      font-size:20px;
      font-weight:800;
      color:#0f6a35;
      margin:0 0 6px;
      letter-spacing:.2px;
    }

    .tagline{
      margin:0 0 18px;
      color:#2f5b3f;
      font-size:14px;
      opacity:.9;
    }

    .loader{
      width:56px;
      height:56px;
      border-radius:50%;
      border:6px solid rgba(31,191,97,.20);
      border-top-color:#1fbf61;
      margin:0 auto;
      animation: spin .9s linear infinite;
    }

    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes float{
      0%,100%{ transform: translateY(0); }
      50%{ transform: translateY(-6px); }
    }
    @keyframes fadeIn{
      to { opacity:1; transform: translateY(0); }
    }
    @keyframes pop{
      0%{ transform: scale(.92); }
      60%{ transform: scale(1.03); }
      100%{ transform: scale(1); }
    }
  </style>
</head>

<body>
  <div class="splash">
    <div class="logo-wrap">
      <img class="logo" src="/RWDD_assignment/public/images/logo.jpeg"; alt="App Logo">
    </div>

    <p class="app-name">Zero Waste Recipes</p>
    <p class="tagline">Loading your dashboard…</p>

    <div class="loader"></div>
  </div>

  <script>
    // after 2 seconds, go to your main page
    setTimeout(() => {
      window.location.href = "<?= $redirectTo ?>";
    }, 2000);
  </script>
</body>
</html>
