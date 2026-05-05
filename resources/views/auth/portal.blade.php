<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Access GovEase portal securely.">
    <title>{{ $title }}</title>

    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('https://use.fontawesome.com/releases/v5.8.1/css/all.css') }}" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/templatemo-chain-app-dev.css') }}">
    @vite(['resources/css/app.css'])
</head>
<body class="portal-page">
@php
    $showRegister = old('firstName') || old('lastName') || $errors->has('firstName') || $errors->has('lastName') || $errors->has('password_confirmation');
@endphp

<div class="portal-shell">
    <div class="row no-gutters">
        <div class="col-lg-5 d-none d-lg-block">
            <div class="portal-banner">
                <h2>Welcome to GovEase Portal</h2>
                <p class="mb-4">
                    Sign in to access your dashboard, manage requests, and stay updated on your public service activities.
                </p>
                <a href="{{ route('home') }}" class="btn btn-light btn-sm">
                    <i class="fa fa-arrow-left mr-1"></i> Back to Home
                </a>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="portal-content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="portal-title">Portal Access</h4>
                        <p class="portal-subtitle">Choose how you want to continue.</p>
                    </div>
                    <a href="{{ route('home') }}" class="d-lg-none small back-link-mobile">Back to Home</a>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger portal-alert">{{ session('error') }}</div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success portal-alert">{{ session('success') }}</div>
                @endif

                <div class="portal-tab-buttons mb-3">
                    <button class="btn btn-outline-primary {{ $showRegister ? '' : 'active' }}" id="loginTabBtn" type="button">Sign In</button>
                    <button class="btn btn-outline-primary {{ $showRegister ? 'active' : '' }}" id="registerTabBtn" type="button">Create Account</button>
                </div>

                <div id="loginPanel" class="portal-panel" style="{{ $showRegister ? 'display:none;' : '' }}">
                    <x-auth.login />
                </div>

                <div id="registerPanel" class="portal-panel" style="{{ $showRegister ? '' : 'display:none;' }}">
                    <x-auth.register />
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script>
    $(function () {
        function switchTab(tab) {
            var isLogin = tab === 'login';

            $('#loginTabBtn').toggleClass('active', isLogin);
            $('#registerTabBtn').toggleClass('active', !isLogin);
            $('#loginPanel').toggle(isLogin);
            $('#registerPanel').toggle(!isLogin);
        }

        $('#loginTabBtn').on('click', function () {
            switchTab('login');
        });

        $('#registerTabBtn').on('click', function () {
            switchTab('register');
        });
    });
</script>
</body>
</html>
