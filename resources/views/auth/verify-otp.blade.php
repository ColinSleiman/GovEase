<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Account - GovEase</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .verify-container {
            width: 100%;
            max-width: 500px;
        }
        
        .verify-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            padding: 50px 40px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .verify-header {
            margin-bottom: 40px;
        }
        
        .verify-header .icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .verify-header .icon i {
            color: white;
            font-size: 36px;
        }
        
        .verify-header h2 {
            color: #2c3e50;
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 15px;
            line-height: 1.3;
        }
        
        .verify-header p {
            color: #6c757d;
            font-size: 16px;
            line-height: 1.6;
            margin: 0;
        }
        
        .otp-input-group {
            margin-bottom: 30px;
        }
        
        .otp-input {
            width: 100%;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 0.8em;
            text-align: center;
            border: 3px solid #e9ecef;
            border-radius: 15px;
            padding: 20px 15px;
            background: #f8f9fa;
            transition: all 0.3s ease;
            outline: none;
        }
        
        .otp-input:focus {
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: scale(1.02);
        }
        
        .otp-input::placeholder {
            color: #adb5bd;
            font-weight: 400;
        }
        
        .btn-verify {
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 15px;
            padding: 18px 30px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-verify:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
        }
        
        .btn-verify:active {
            transform: translateY(-1px);
        }
        
        .btn-resend {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
            border-radius: 15px;
            padding: 15px 25px;
            font-weight: 600;
            font-size: 15px;
            width: 100%;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-resend:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-resend:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .alert {
            border-radius: 12px;
            margin-bottom: 25px;
            padding: 15px 20px;
            border: none;
            font-weight: 500;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
            color: white;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            margin-top: 30px;
            color: #6c757d;
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            color: #667eea;
            transform: translateX(-5px);
        }
        
        .back-link i {
            margin-right: 8px;
        }
        
        @media (max-width: 576px) {
            .verify-card {
                padding: 40px 30px;
                margin: 10px;
            }
            
            .verify-header h2 {
                font-size: 24px;
            }
            
            .otp-input {
                font-size: 28px;
                padding: 18px 12px;
            }
            
            .btn-verify, .btn-resend {
                padding: 16px 25px;
            }
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <div class="verify-card">
            <div class="verify-header">
                <div class="icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h2>Verify Your Account</h2>
                <p>Please enter the 6-digit verification code sent to your email address.</p>
            </div>
            
            @if (session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif
            
            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            
            <form id="otp-form" action="{{ route('otp.verify') }}" method="POST">
                @csrf
                <div class="otp-input-group">
                    <input type="text" 
                           id="otp" 
                           name="otp" 
                           maxlength="6" 
                           pattern="[0-9]{6}" 
                           required 
                           placeholder="123456"
                           class="otp-input">
                </div>
                
                <button type="submit" class="btn-verify">
                    <i class="fas fa-check-circle"></i> Verify Account
                </button>
            </form>
            
            <button type="button" id="resend-otp" class="btn-resend">
                <i class="fas fa-envelope"></i> Resend OTP
            </button>
            
            <a href="{{ route('home') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
    $(document).ready(function() {
        // Auto-format OTP input
        $('#otp').on('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
        
        // Resend OTP
        $('#resend-otp').click(function() {
            var btn = $(this);
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');
            
            $.ajax({
                url: '{{ route('otp.send') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert('OTP sent successfully! Please check your email.');
                    btn.prop('disabled', false).html('<i class="fas fa-envelope"></i> Resend OTP');
                },
                error: function(xhr) {
                    alert('Failed to send OTP: ' + xhr.responseJSON.message);
                    btn.prop('disabled', false).html('<i class="fas fa-envelope"></i> Resend OTP');
                }
            });
        });
    });
    </script>
</body>
</html>
