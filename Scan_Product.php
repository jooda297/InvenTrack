<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Scan Product QR</title>
  <style>
    body{font-family:Arial;margin:0;background:#fff;padding:50px;}
    .wrap{max-width:700px;margin:auto;text-align:center;}
    input{width:100%;padding:14px;border:1px solid #ccc;border-radius:12px;font-size:14px;}
    button{margin-top:12px;padding:12px 18px;border-radius:12px;border:1px solid #222;background:#fff;cursor:pointer;}
    .note{color:#1a7f37;margin:10px 0;}
    video{width:100%;max-width:520px;border:1px solid #ddd;border-radius:14px;margin-top:18px;}
  </style>
</head>
<body>
<div class="wrap">
  <h1>Scan Product QR</h1>

  <div class="note">✅ If camera scanning doesn’t work on your laptop, open this page from your phone.</div>

  <hr style="margin:20px 0;">

  <h3>Manual (paste the QR link)</h3>
  <form onsubmit="return openLink();">
    <input id="qrLink" placeholder="Paste QR link here (example: http://localhost/inventrack/qr.php?pid=84)">
    <button type="submit">Open</button>
  </form>

  <script>
    function openLink(){
      const v = document.getElementById('qrLink').value.trim();
      if(!v){ alert("Paste a link first"); return false; }
      window.location.href = v;
      return false;
    }
  </script>

  <hr style="margin:26px 0;">

  <h3>Camera Scan (optional)</h3>
  <p style="color:#666;">This needs camera permission. Some browsers block camera on non-https. Localhost usually works.</p>

  <video id="video" autoplay playsinline></video>
  <p id="status" style="color:#666;"></p>

  <!-- No library here; camera scan needs a JS QR decoder library to actually decode.
       So camera view works, but decoding needs a library.
       For now manual paste is enough for testing. -->
  <script>
    (async () => {
      try{
        const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } });
        document.getElementById('video').srcObject = stream;
        document.getElementById('status').innerText = "Camera opened ✅ (decoding requires a JS QR decoder library).";
      }catch(e){
        document.getElementById('status').innerText = "Camera not allowed ❌ — use manual paste or open from phone.";
      }
    })();
  </script>

</div>
</body>
</html>
