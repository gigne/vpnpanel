<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Download VPN Profile — {{ $user->username }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('ikev2-users.show', $user) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-500">← Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">{{ session('error') }}</div>
            @endif

            <!-- Certificate Warning -->
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-900 px-4 py-3 rounded text-sm">
                ⚠️ This PKCS#12 bundle contains your private key. Download and share it securely.
            </div>

            <!-- P12 Password -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">🔑 P12 Password</h3>
                <p class="text-sm text-gray-600 mb-3">Use this password when installing the certificate:</p>
                <div class="flex gap-2 items-start">
                    <code class="flex-1 bg-gray-100 px-4 py-3 rounded text-base font-mono break-all select-all" id="p12-password">{{ $password }}</code>
                    <button type="button" onclick="copyPassword()" class="shrink-0 px-4 py-2 bg-gray-600 text-white rounded-md text-sm font-semibold hover:bg-gray-700">Copy</button>
                </div>
            </div>

            <!-- Download Buttons -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">📥 Download Profiles</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4" style="margin-bottom:2rem!important;">
                    <!-- iOS / macOS -->
                    <a href="{{ route('ikev2-users.download.mobileconfig', $user) }}"
                       style="display:block;padding:1.25rem 1.5rem;background-color:#4f46e5!important;color:#fff;border-radius:0.5rem;font-size:1.125rem!important;font-weight:700;text-align:center;text-decoration:none;"
                       onmouseover="this.style.backgroundColor='#3730a3'" onmouseout="this.style.backgroundColor='#4f46e5'">
                        🍎 iOS / macOS (.mobileconfig)
                    </a>
                    <!-- Android Native -->
                    <a href="{{ route('ikev2-users.download.p12', $user) }}"
                       style="display:block;padding:1.25rem 1.5rem;background-color:#2563eb!important;color:#fff;border-radius:0.5rem;font-size:1.125rem!important;font-weight:700;text-align:center;text-decoration:none;"
                       onmouseover="this.style.backgroundColor='#1d4ed8'" onmouseout="this.style.backgroundColor='#2563eb'">
                        🤖 Android Native (.p12)
                    </a>
                    <!-- Android SSWAN -->
                    <a href="{{ route('ikev2-users.download.sswan', $user) }}"
                       style="display:block;padding:1.25rem 1.5rem;background-color:#0d9488!important;color:#fff;border-radius:0.5rem;font-size:1.125rem!important;font-weight:700;text-align:center;text-decoration:none;"
                       onmouseover="this.style.backgroundColor='#0f766e'" onmouseout="this.style.backgroundColor='#0d9488'">
                        📋 Android SSWAN (.sswan)
                    </a>
                    <!-- Windows / Linux -->
                    <a href="{{ route('ikev2-users.download.p12', $user) }}"
                       style="display:block;padding:1.25rem 1.5rem;background-color:#475569!important;color:#fff;border-radius:0.5rem;font-size:1.125rem!important;font-weight:700;text-align:center;text-decoration:none;"
                       onmouseover="this.style.backgroundColor='#334155'" onmouseout="this.style.backgroundColor='#475569'">
                        💻 Windows / Linux (.p12)
                    </a>
                </div>
            </div>

            <!-- Android Setup Instructions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">📱 Cara Pakai di Android</h3>

                <div class="space-y-6">
                    <!-- Metode 1: Native VPN -->
                    <div>
                        <h4 class="font-semibold text-blue-800 mb-2">Metode 1 — Native VPN Settings (Recommended)</h4>
                        <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700 ml-2">
                            <li>Klik tombol <strong>Android Native (.p12)</strong> untuk download file .p12</li>
                            <li>Paste password yang ada di atas saat install sertifikat</li>
                            <li>Buka <strong>Settings → Network &amp; internet → VPN → Add VPN</strong></li>
                            <li>Isi:
                                <ul class="list-disc list-inside ml-4 mt-1 space-y-1">
                                    <li>Name: <code>{{ $user->username }}_IKEv2</code></li>
                                    <li>Type: <strong>IKEv2/IPSec certificate</strong></li>
                                    <li>Server: <code>{{ config('vpnpanel.ikev2.server_id', config('vpnpanel.domain', 'vpn.example.com')) }}</code></li>
                                    <li>User: <code>{{ $user->username }}</code></li>
                                </ul>
                            </li>
                            <li>Save → Connect</li>
                        </ol>
                    </div>

                    <!-- Metode 2: SSWAN App -->
                    <div>
                        <h4 class="font-semibold text-teal-800 mb-2">Metode 2 — VPN Manager App (SSWAN)</h4>
                        <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700 ml-2">
                            <li>Install <strong>"IKEv2 VPN Lite"</strong> atau <strong>"OpenVPN Manager"</strong> di Play Store</li>
                            <li>Klik tombol <strong>Android SSWAN (.sswan)</strong> untuk download profile</li>
                            <li>Buka file .sswan dari app → auto-import profil siap pakai</li>
                            <li>Tinggal connect!</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- iOS/macOS Instructions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">🍎 Cara Pakai di iOS / macOS</h3>
                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700 ml-2">
                    <li>Klik tombol <strong>iOS / macOS (.mobileconfig)</strong> untuk download</li>
                    <li>Buka file di perangkat Apple</li>
                    <li>Install profile di <strong>Settings → Profile Downloaded</strong></li>
                    <li>Buka <strong>Settings → General → VPN &amp; Device Management → VPN</strong></li>
                    <li>Aktifkan VPN connection</li>
                </ol>
            </div>

            <!-- Windows/Linux Instructions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">💻 Cara Pakai di Windows / Linux</h4>
                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700 ml-2">
                    <li>Klik tombol <strong>Windows / Linux (.p12)</strong> untuk download</li>
                    <li>Import sertifikat ke system trust store:</li>
                    <ul class="list-disc list-inside ml-4 mt-1 space-y-1">
                        <li><strong>Windows:</strong> Double-click .p12 → Local Machine → Trust Root CA</li>
                        <li><strong>Linux:</strong> <code>sudo cp {{ $user->username }}.p12 /etc/ipsec.d/ && sudo ipsec reread</code></li>
                    </ul>
                    <li>Setup IKEv2 connection menggunakan sertifikat</li>
                </ol>
            </div>

        </div>
    </div>
</x-app-layout>

<script>
function copyPassword() {
    const pw = document.getElementById('p12-password').textContent;
    navigator.clipboard.writeText(pw).then(() => {
        alert('Password copied!');
    }).catch(() => {
        const el = document.getElementById('p12-password');
        el.select();
        document.execCommand('copy');
        alert('Password copied!');
    });
}
</script>
