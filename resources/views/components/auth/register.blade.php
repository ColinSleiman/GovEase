@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 pl-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('register') }}" method="POST">
    @csrf
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="firstName">First Name</label>
            <input type="text" id="firstName" class="form-control" name="firstName" value="{{ old('firstName') }}" required minlength="2" maxlength="255">
        </div>
        <div class="form-group col-md-6">
            <label for="lastName">Last Name</label>
            <input type="text" id="lastName" class="form-control" name="lastName" value="{{ old('lastName') }}" required minlength="2" maxlength="255">
        </div>
    </div>
    <div class="form-group">
        <label for="register_email">Email Address</label>
        <input type="email" id="register_email" class="form-control" name="email" value="{{ old('email') }}" required maxlength="255">
    </div>
    <div class="form-group">
        <label for="register_password">Password</label>
        <input type="password" id="register_password" class="form-control" name="password" required minlength="8">
    </div>
    <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" required minlength="8">
    </div>
    <button type="submit" class="btn btn-primary-main btn-block">Create Account</button>
</form>
