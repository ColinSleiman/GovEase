@php
    $documentScan = session('register_document_scan');
    $registerMode = session('register_mode', old('register_mode', 'manual'));
    $documentAnalysis = $documentScan['analysis'] ?? session('document_analysis');
@endphp

<a href="{{ route('auth.google.redirect') }}" class="google-login mb-3 d-inline-block">
    <i class="fab fa-google-plus mr-1"></i> Continue with Google
</a>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 pl-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="portal-tab-buttons mb-3 register-mode-tabs">
    <button
        type="button"
        class="btn btn-outline-primary {{ $registerMode === 'manual' && ! $documentScan ? 'active' : '' }}"
        id="registerManualTabBtn"
    >
        Enter Details
    </button>
    <button
        type="button"
        class="btn btn-outline-primary {{ $registerMode === 'document' || $documentScan ? 'active' : '' }}"
        id="registerDocumentTabBtn"
    >
        Upload ID Document
    </button>
</div>

<div id="registerManualPanel" style="{{ ($registerMode === 'document' || $documentScan) ? 'display:none;' : '' }}">
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <input type="hidden" name="register_mode" value="manual">
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
</div>

<div id="registerDocumentPanel" style="{{ ($registerMode === 'document' || $documentScan) ? '' : 'display:none;' }}">
    @if (! $documentScan)
        <p class="text-muted small mb-3">
            Upload a passport, national ID, or official document. AI will read your name so you can finish creating your account.
        </p>

        <form action="{{ route('register.document.scan') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="register_mode" value="document">
            <div class="form-group">
                <label for="register_document">Identity Document</label>
                <input
                    type="file"
                    id="register_document"
                    name="document"
                    class="form-control @error('document') is-invalid @enderror"
                    accept=".pdf,.jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp,application/pdf"
                    required>
                @error('document')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <p class="mt-2 mb-0 text-muted small">
                    Accepted: PDF, JPG, JPEG, PNG, WEBP (max 15 MB).
                </p>
            </div>
            <button type="submit" class="btn btn-primary-main btn-block">
                <i class="fa fa-upload mr-1"></i> Scan Document and Continue
            </button>
        </form>

        @if (is_array($documentAnalysis))
            @include('components.auth.partials.document-analysis', ['analysis' => $documentAnalysis])
        @endif
    @else
        <div class="alert alert-success portal-alert mb-3">
            Document scanned successfully. Review your name and enter your email and password to finish.
        </div>

        <div class="rounded border bg-light p-3 mb-3">
            <p class="mb-1"><strong>First name:</strong> {{ $documentScan['firstName'] }}</p>
            <p class="mb-0"><strong>Last name:</strong> {{ $documentScan['lastName'] }}</p>
        </div>

        @if (is_array($documentScan['analysis'] ?? null))
            @include('components.auth.partials.document-analysis', ['analysis' => $documentScan['analysis']])
        @endif

        <form action="{{ route('register') }}" method="POST" class="mt-3">
            @csrf
            <input type="hidden" name="register_mode" value="document">
            <div class="form-group">
                <label for="register_document_email">Email Address</label>
                <input
                    type="email"
                    id="register_document_email"
                    class="form-control @error('email') is-invalid @enderror"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    maxlength="255">
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="register_document_password">Password</label>
                <input type="password" id="register_document_password" class="form-control @error('password') is-invalid @enderror" name="password" required minlength="8">
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="register_document_password_confirmation">Confirm Password</label>
                <input type="password" id="register_document_password_confirmation" class="form-control" name="password_confirmation" required minlength="8">
            </div>
            <button type="submit" class="btn btn-primary-main btn-block">Create Account</button>
        </form>

        <form action="{{ route('register.document.cancel') }}" method="POST" class="mt-2">
            @csrf
            <button type="submit" class="btn btn-link btn-block text-muted">Scan a different document</button>
        </form>
    @endif
</div>

<script>
    (function () {
        function switchRegisterMode(mode) {
            var isManual = mode === 'manual';
            var manualBtn = document.getElementById('registerManualTabBtn');
            var documentBtn = document.getElementById('registerDocumentTabBtn');
            var manualPanel = document.getElementById('registerManualPanel');
            var documentPanel = document.getElementById('registerDocumentPanel');

            if (!manualBtn || !documentBtn || !manualPanel || !documentPanel) {
                return;
            }

            manualBtn.classList.toggle('active', isManual);
            documentBtn.classList.toggle('active', !isManual);
            manualPanel.style.display = isManual ? '' : 'none';
            documentPanel.style.display = isManual ? 'none' : '';
        }

        var manualBtn = document.getElementById('registerManualTabBtn');
        var documentBtn = document.getElementById('registerDocumentTabBtn');

        if (manualBtn) {
            manualBtn.addEventListener('click', function () {
                switchRegisterMode('manual');
            });
        }

        if (documentBtn) {
            documentBtn.addEventListener('click', function () {
                switchRegisterMode('document');
            });
        }
    })();
</script>
