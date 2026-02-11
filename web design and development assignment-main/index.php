<?php
session_start();

$LOGO_PATH = "/RWDD_assignment/public/images/logo.jpeg"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Role</title>

  <style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{
      font-family:'Josefin Sans', sans-serif;
      height:100vh;
      display:flex;
      justify-content:center;
      align-items:center;
      overflow:hidden;

      /* ✅ admin-like background (cream) */
      background:#f6f0e6;
    }

    /* subtle background overlay like admin page */
    body::before{
      content:"";
      position:fixed;
      inset:0;
      background:
        radial-gradient(circle at 20% 20%, rgba(16,185,129,.12), transparent 55%),
        radial-gradient(circle at 80% 70%, rgba(34,197,94,.10), transparent 55%);
      pointer-events:none;
    }

    /* =========================
       SPLASH SCREEN (ANIMATION)
       ========================= */
    .splash{
      position:fixed;
      inset:0;
      display:flex;
      align-items:center;
      justify-content:center;
      z-index:9999;

      /* ✅ dark green like sidebar */
      background: linear-gradient(180deg, #0b3b2c 0%, #0f5a40 55%, #0b3b2c 100%);
      transition: opacity .45s ease, visibility .45s ease;
    }
    .splash.hide{
      opacity:0;
      visibility:hidden;
      pointer-events:none;
    }

    .splash-inner{
      text-align:center;
      animation: splashIn .7s ease forwards;
      opacity:0;
      transform: translateY(10px);
    }

    .logo-wrap{
      width:140px;
      height:140px;
      margin:0 auto 16px;
      border-radius:28px;
      display:flex;
      align-items:center;
      justify-content:center;

      background: rgba(255,255,255,.92);
      box-shadow: 0 14px 35px rgba(0,0,0,.25);
      animation: pop .85s ease forwards;
    }
    .logo{
      width:95px;
      height:95px;
      object-fit:contain;
      animation: float 1.8s ease-in-out infinite;
    }

    .app-name{
      font-size:22px;
      font-weight:800;
      color:#eafff4;
      margin-bottom:6px;
      letter-spacing:.2px;
    }
    .tagline{
      font-size:14px;
      color: rgba(234,255,244,.75);
      margin-bottom:16px;
    }

    .loader{
      width:58px;
      height:58px;
      border-radius:50%;
      border:7px solid rgba(234,255,244,.20);
      border-top-color: rgba(234,255,244,.92);
      margin:0 auto;
      animation: spin .9s linear infinite;
    }

    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes float{
      0%,100%{ transform: translateY(0); }
      50%{ transform: translateY(-7px); }
    }
    @keyframes splashIn{
      to { opacity:1; transform: translateY(0); }
    }
    @keyframes pop{
      0%{ transform: scale(.92); }
      60%{ transform: scale(1.04); }
      100%{ transform: scale(1); }
    }

    /* =========================
       ROLE CARD
       ========================= */
    .card{
      width:390px;
      padding:42px 32px;
      border-radius:18px;
      text-align:center;

      background: rgba(255,255,255,.92);
      border: 1px solid rgba(15,90,64,.15);
      box-shadow: 0 16px 40px rgba(15, 90, 64, 0.18);

      opacity:0;
      transform: translateY(10px) scale(.99);
      transition: opacity .45s ease, transform .45s ease;
      position:relative;
      z-index:1;
    }
    .card.show{
      opacity:1;
      transform: translateY(0) scale(1);
    }

    h1{
      margin-bottom:18px;
      font-size:28px;
      font-weight:900;
      color:#0b3b2c; /* dark green */
    }

    .btn{
      display:block;
      width:100%;
      padding:14px;
      margin:12px 0;
      border:none;
      border-radius:12px;
      font-weight:800;
      font-size:16px;
      cursor:pointer;

      box-shadow: 0 10px 18px rgba(0,0,0,.10);
      transition: transform .18s ease, filter .18s ease, box-shadow .18s ease;
    }

    .admin{ background:#f59e0b; color:#fff; } /* gold/orange */
    .donor{ background:#3b82f6; color:#fff; } /* blue */
    .user{  background:#16a34a; color:#fff; } /* green */

    .btn:hover{
      transform: translateY(-2px);
      filter: brightness(1.14);
      box-shadow: 0 16px 26px rgba(0,0,0,.14);
    }
    .btn:active{
      transform: translateY(0) scale(.98);
      filter: brightness(0.95);
    }

    /* small green pill like admin UI */
    .hint{
      margin-top:10px;
      font-size:12.5px;
      color: rgba(11,59,44,.70);
    }
  </style>
</head>

<body>

  <!-- SPLASH -->
  <div class="splash" id="splash">
    <div class="splash-inner">
      <div class="logo-wrap">
        <img class="logo" src="<?= htmlspecialchars($LOGO_PATH) ?>" alt="Logo"
             onerror="this.style.display='none'">
      </div>
      <div class="app-name">Zero Waste</div>
      <div class="tagline">Preparing your workspace…</div>
      <div class="loader"></div>
    </div>
  </div>

  <!-- ROLE PAGE -->
  <div class="card" id="roleCard">
    <h1>Select Role</h1>

    <button class="btn admin" onclick="location.href='LoginSignUp/login.php?role=admin'">
      Admin
    </button>

    <button class="btn donor" onclick="location.href='LoginSignUp/login.php?role=food_donor'">
      Food Donor
    </button>

    <button class="btn user" onclick="location.href='LoginSignUp/login.php?role=user'">
      User (Student / Staff)
    </button>

    <div class="hint">Zero Waste Recipe Platform</div>
  </div>

  <script>
    window.addEventListener("load", () => {
      const splash = document.getElementById("splash");
      const roleCard = document.getElementById("roleCard");

      setTimeout(() => {
        splash.classList.add("hide");
        roleCard.classList.add("show");
      }, 1600);
    });
  </script>

</body>
</html>
