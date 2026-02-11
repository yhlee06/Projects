<?php
session_start();
require '../Common/accessControl.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Scan User QR</title>

<script src="https://unpkg.com/html5-qrcode"></script>

<style>
body{
  background:#F3EAD7;
  font-family:Arial,sans-serif;
  text-align:center;
}
.main{
  padding:20px;
}
#reader{
  width:100%;
  max-width:360px;
  margin:20px auto;
  border-radius:12px;
  overflow:hidden;
}
.result{
  margin-top:20px;
  font-size:16px;
  font-weight:bold;
  color:#165540;
}
.back-btn{
  margin-top:20px;
  padding:10px 16px;
  border-radius:20px;
  border:none;
  background:#165540;
  color:white;
  font-weight:bold;
  cursor:pointer;
}
</style>
</head>

<body>
<div class="main">
  <h2>Scan User QR Code</h2>
  <p>Point camera at user’s QR code</p>

  <div id="reader"></div>

  <div class="result" id="result"></div>

  <button class="back-btn" onclick="history.back()">Back</button>
</div>

<script>
const resultBox = document.getElementById("result");

function onScanSuccess(decodedText){
  resultBox.innerHTML = "Scanned QR Data:<br>" + decodedText;
  html5QrCode.stop();
}

const html5QrCode = new Html5Qrcode("reader");

html5QrCode.start(
  { facingMode: "environment" },
  { fps: 10, qrbox: 250 },
  onScanSuccess
);
</script>

</body>
</html>
