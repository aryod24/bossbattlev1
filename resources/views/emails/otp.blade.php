<!DOCTYPE html>
<html>
<head>
    <title>Kode OTP Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="color: #333333; text-align: center;">Reset Password CodeBossArena</h2>
        <p style="color: #555555; font-size: 16px;">Halo,</p>
        <p style="color: #555555; font-size: 16px;">Kami menerima permintaan reset password untuk akun Anda. Gunakan kode OTP di bawah ini untuk mereset password Anda:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <span style="font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #00f2ff; background-color: #19191c; padding: 10px 20px; border-radius: 8px;">
                {{ $otp }}
            </span>
        </div>

        <p style="color: #555555; font-size: 16px;">Kode ini hanya berlaku selama 15 menit. Jika Anda tidak merasa melakukan permintaan reset password, silakan abaikan email ini.</p>
        <br>
        <p style="color: #555555; font-size: 16px;">Terima kasih,<br>Tim CodeBossArena</p>
    </div>
</body>
</html>
