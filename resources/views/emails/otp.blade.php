<!DOCTYPE html>
<html>
<head>
    <title>Kode OTP PartKu</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 500px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; text-align: center;">
        <h2 style="color: #cc0000;">PartKu</h2>
        <p>Halo, terima kasih telah mendaftar di PartKu.</p>
        <p>Berikut adalah kode verifikasi rahasia kamu:</p>
        
        <div style="font-size: 36px; font-weight: bold; letter-spacing: 5px; color: #333; margin: 20px 0; background: #f8f9fa; padding: 15px; border-radius: 8px;">
            {{ $otp }}
        </div>
        
        <p style="color: #666; font-size: 14px;">Kode ini hanya berlaku selama 10 menit. Jangan bagikan kode ini kepada siapa pun.</p>
    </div>
</body>
</html>