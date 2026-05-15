<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Document | GovEase</title>

    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('https://use.fontawesome.com/releases/v5.8.1/css/all.css') }}" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/templatemo-chain-app-dev.css') }}">
    @vite(['resources/css/app.css'])
</head>

<body class="portal-page">
<div class="portal-shell">
    <div class="row no-gutters">
        <div class="col-lg-5 d-none d-lg-block">
            <div class="portal-banner">
                <h2>Document Reader</h2>
                <p class="mb-4">
                    Upload a passport, national ID, license, or official document as a PDF or image.
                    The system will scan the file and extract the visible information.
                </p>

                <a href="{{ route('home') }}" class="btn btn-light btn-sm">
                    <i class="fa fa-arrow-left mr-1"></i> Back to Home
                </a>

                @auth
                    @if (Auth::user()->role && Auth::user()->role->name == 'Administrator')
                        <a href="{{ route('admin.document.reader') }}" class="btn btn-light btn-sm ml-2">
                            Admin Reader
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        <div class="col-lg-7">
            <div class="portal-content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="portal-title">Upload Document</h4>
                        <p class="portal-subtitle">
                            Accepted files: PDF, JPG, JPEG, PNG, WEBP. DOCX files are rejected.
                        </p>
                    </div>

                    <a href="{{ route('home') }}" class="d-lg-none small back-link-mobile">Back to Home</a>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger portal-alert">
                        <ul class="mb-0 pl-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success portal-alert">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="portal-panel">
                    <form action="{{ route('document.reader.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="document" class="form-label">Choose Document</label>
                            <input
                                type="file"
                                id="document"
                                name="document"
                                class="form-control"
                                accept=".pdf,.jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp,application/pdf"
                                required>
                            <p class="mt-2 mb-0 text-muted small">
                                The file will be uploaded, analyzed by AI, and saved for admin review.
                            </p>
                        </div>

                        <button type="submit" class="btn-primary-main w-100">
                            <i class="fa fa-upload mr-1"></i> Upload and Analyze
                        </button>
                    </form>
                </div>

                @if (session('analysis'))
                    @php
                        $analysis = session('analysis');
                        $fields = $analysis['fields'] ?? [];
                        $notes = $analysis['notes'] ?? [];

                        if (!is_array($notes)) {
                            $notes = [$notes];
                        }
                    @endphp

                    <div class="mt-4 portal-panel">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="mb-1 font-weight-bold">AI Detected Information</h5>
                                <p class="mb-0 text-muted small">
                                    Uploaded File: {{ session('uploadedFile') }}
                                </p>
                            </div>
                        </div>

                        @if (!empty($analysis['message']))
                            <div class="alert alert-danger portal-alert">
                                {{ $analysis['message'] }}
                            </div>
                        @endif

                        <div class="row mb-3">
                            <div class="col-md-4 mb-2">
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                                    <p class="mb-1 text-xs font-semibold uppercase text-slate-500">Status</p>
                                    <p class="mb-0 text-sm font-semibold text-slate-900">
                                        {{ $analysis['status'] ?? 'unknown' }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4 mb-2">
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                                    <p class="mb-1 text-xs font-semibold uppercase text-slate-500">Document Type</p>
                                    <p class="mb-0 text-sm font-semibold text-slate-900">
                                        {{ str_replace('_', ' ', $analysis['document_type'] ?? 'unknown') }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4 mb-2">
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                                    <p class="mb-1 text-xs font-semibold uppercase text-slate-500">Confidence</p>
                                    <p class="mb-0 text-sm font-semibold text-slate-900">
                                        {{ $analysis['confidence'] ?? 0 }}%
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive rounded-lg border border-slate-200">
                            <table class="table mb-0">
                                <thead class="bg-light">
                                <tr>
                                    <th>Field</th>
                                    <th>Value</th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse ($fields as $key => $value)
                                    <tr>
                                        <td class="font-weight-bold">
                                            {{ ucwords(str_replace('_', ' ', $key)) }}
                                        </td>
                                        <td>
                                            @if (is_array($value))
                                                {{ json_encode($value) }}
                                            @else
                                                {{ $value ?? 'Not found' }}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-muted text-center">
                                            No fields detected.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if (!empty($notes))
                            <div class="mt-3 rounded-lg border border-slate-200 bg-slate-50 p-3">
                                <p class="mb-2 font-weight-bold">Notes</p>
                                <ul class="mb-0 pl-3 text-muted small">
                                    @foreach ($notes as $note)
                                        @if (!empty($note))
                                            <li>{{ $note }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</body>
</html>
