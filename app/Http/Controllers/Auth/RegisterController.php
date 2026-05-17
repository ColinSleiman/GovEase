<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\DocumentReaderService;
use Auth;
use Hash;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __construct(
        private DocumentReaderService $documentReader
    ) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $documentScan = $request->session()->get('register_document_scan');
        $isDocumentMode = $request->input('register_mode') === 'document' || is_array($documentScan);

        if ($isDocumentMode) {
            if (! is_array($documentScan)) {
                return redirect()
                    ->route('portal.access')
                    ->withErrors(['document' => 'Please upload and scan your ID document before creating an account.'])
                    ->with('open_register_tab', true)
                    ->with('register_mode', 'document');
            }

            return $this->registerFromDocumentScan($request, $documentScan);
        }

        if ($request->input('register_mode') !== 'document' && ($request->filled('firstName') || $request->filled('email'))) {
            $request->session()->forget('register_document_scan');

            return $this->registerManually($request);
        }

        return $this->registerManually($request);
    }

    private function registerManually(Request $request)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $this->createCitizenUser(
            $validated['firstName'],
            $validated['lastName'],
            $validated['email'],
            $validated['password']
        );

        Auth::login($user);

        return redirect()
            ->route('citizen.dashboard')
            ->with('success', 'Account created successfully. Please verify your account to continue.');
    }

    private function registerFromDocumentScan(Request $request, array $documentScan)
    {
        $validated = $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $firstName = $documentScan['firstName'];
        $lastName = $documentScan['lastName'];
        $fileName = $documentScan['fileName'] ?? null;

        $user = $this->createCitizenUser(
            $firstName,
            $lastName,
            $validated['email'],
            $validated['password']
        );

        if ($fileName) {
            $this->documentReader->saveUploaderMetadata($fileName, $user);
        }

        $request->session()->forget('register_document_scan');

        Auth::login($user);

        return redirect()
            ->route('citizen.dashboard')
            ->with('success', 'Account created from your document. Please verify your account to continue.');
    }

    private function createCitizenUser(string $firstName, string $lastName, string $email, string $password): User
    {
        $citizenRole = Role::where(['name' => 'Citizen'])->firstOrFail();

        return User::create([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'password' => Hash::make($password),
            'office_id' => null,
            'role_id' => $citizenRole->id,
            'verified' => false,
        ]);
    }
}
