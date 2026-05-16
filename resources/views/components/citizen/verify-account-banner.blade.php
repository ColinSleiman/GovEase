@auth
    @if (! Auth::user()->verified)
        <div
            class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-5 py-4 text-amber-950"
            role="alert"
        >
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold">Verify your account</p>
                    <p class="mt-1 text-sm text-amber-900/90">
                        Please verify your email address to unlock full access to GovEase services.
                    </p>
                </div>
                <a href="{{ route('otp.show') }}" class="btn-base btn-variant-blue shrink-0">
                    Verify now
                </a>
            </div>
        </div>
    @endif
@endauth
