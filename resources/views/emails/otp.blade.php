<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GovEase - Your Verification Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 40px;
            border-radius: 0 0 10px 10px;
            text-align: center;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 0.5em;
            background: white;
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
            margin: 20px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>GovEase</h1>
        <p>Government Services Platform</p>
    </div>
    
    <div class="content">
        <h2>Your Verification Code</h2>
        <p>Please use the following 6-digit code to verify your account:</p>
        
        <div class="otp-code">{{ $otp }}</div>
        
        <p><strong>This code will expire in 10 minutes.</strong></p>
        <p>If you didn't request this code, please ignore this email.</p>
    </div>
    
    <div class="footer">
        <p>&copy; 2026 GovEase. All rights reserved.</p>
        <p>This is an automated message, please do not reply to this email.</p>
    </div>
</body>
</html>
