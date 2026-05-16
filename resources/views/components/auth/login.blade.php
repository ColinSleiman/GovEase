<div class="mb-3">
    <a href="{{ route('auth.google.redirect') }}" class="google-login">
        <i class="fab fa-google-plus mr-1"></i> Continue with Google
    </a>

    <a href="{{ route('social.redirect', 'github') }}" class="google-login mt-2">
        <i class="fab fa-github mr-1"></i> Continue with GitHub
    </a>

    <a href="{{ route('social.redirect', 'facebook') }}" class="google-login mt-2">
        <i class="fab fa-facebook mr-1"></i> Continue with Facebook
    </a>

    <a href="{{ route('social.redirect', 'instagram') }}" class="google-login mt-2">
        <i class="fab fa-instagram mr-1"></i> Continue with Instagram
    </a>
</div>

<form action="{{ route('login') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="login_email">Email Address</label>
        <input type="email" id="login_email" class="form-control" name="email" value="{{ old('email') }}" required>
    </div>
    <div class="form-group">
        <label for="login_password">Password</label>
        <input type="password" id="login_password" class="form-control" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary-main btn-block">Sign In</button>
</form>
