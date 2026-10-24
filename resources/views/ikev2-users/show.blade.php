<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">IKEv2 User: {{ $ikev2User->username }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('ikev2-users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-500">All Users</a>
                <a href="{{ route('ikev2-users.edit', $ikev2User) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-indigo-500">Edit</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            <!-- Status -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Status</h3>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-gray-500">Username</dt><dd class="font-medium">{{ $ikev2User->username }}</dd></div>
                    <div><dt class="text-gray-500">VPN IP</dt><dd class="font-mono">{{ $ikev2User->assigned_ip ?? 'Dynamic' }}</dd></div>
                    <div><dt class="text-gray-500">Status</dt><dd><span class="px-2 py-0.5 rounded text-xs font-medium {{ $ikev2User->isActive() ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">{{ $ikev2User->isActive() ? 'Active' : ($ikev2User->isExpired() ? 'Expired' : 'Disabled') }}</span></dd></div>
                    <div><dt class="text-gray-500">Expires</dt><dd>{{ $ikev2User->expired_at?->format('Y-m-d H:i') ?? 'Never' }}</dd></div>
                    <div><dt class="text-gray-500">Created</dt><dd>{{ $ikev2User->created_at?->format('Y-m-d H:i') }}</dd></div>
                    <div><dt class="text-gray-500">Last Used</dt><dd>{{ $ikev2User->last_used_at?->diffForHumans() ?? 'Never' }}</dd></div>
                </dl>
            </div>

            <!-- Certificate Info -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Certificate</h3>
                @if ($ikev2User->has_certificate)
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Certificate Info</h3>
                        <form method="POST" action="{{ route('ikev2-users.regenerate-certificate', $ikev2User) }}"
                              class="inline"
                              onsubmit="return confirm('Regenerate certificate? Old cert will be invalidated.');">
                            @csrf
                            <button class="inline-flex items-center gap-1 px-3 py-1.5 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Regenerate
                            </button>
                        </form>
                    </div>
                    <dl class="grid grid-cols-2 gap-4 text-sm mb-4">
                        <div><dt class="text-gray-500">Serial</dt><dd class="font-mono">{{ $ikev2User->certificate_serial }}</dd></div>
                        <div><dt class="text-gray-500">Issued</dt><dd>{{ $ikev2User->created_at?->format('Y-m-d H:i') }}</dd></div>
                    </dl>
                    <div class="mt-4">
                        <a href="{{ route('ikev2-users.download', $ikev2User) }}" style="display:inline-flex;align-items:center;padding:0.5rem 1rem;background-color:#059669!important;border-radius:0.375rem;font-weight:600;font-size:0.875rem;color:#fff;text-decoration:none;" onmouseover="this.style.backgroundColor='#047857'" onmouseout="this.style.backgroundColor='#059669'">
                            📥 Download VPN Profile
                        </a>
                    </div>
                @else
                    <p class="text-sm text-gray-400">No certificate generated yet.</p>
                @endif
            </div>

            <!-- Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-red-600 mb-4">Danger Zone</h3>
                <form method="POST" action="{{ route('ikev2-users.destroy', $ikev2User) }}"
                      onsubmit="return confirm('Permanently delete {{ $ikev2User->username }}?');">
                    @csrf @method('DELETE')
                    <button class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white hover:bg-red-500">
                        Delete User
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>