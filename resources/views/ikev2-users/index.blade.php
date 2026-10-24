<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">IKEv2 Users (Mobile VPN Clients)</h2>
            <div class="flex gap-2">
                <a href="{{ route('ikev2-users.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-indigo-500">
                    + New
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                    @if (session('generated_password'))
                        &nbsp;<span class="font-mono text-sm font-semibold">• Password auto-generated:
                            <span class="select-all">{{ session('generated_password') }}</span>
                        </span>
                    @endif
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg overflow-hidden hidden lg:block">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-left text-gray-500">
                        <tr>
                            <th class="px-6 py-3">Username</th>
                            <th class="px-6 py-3">VPN IP</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Expires</th>
                            <th class="px-6 py-3">Cert</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 font-medium text-gray-800">{{ $user->username }}</td>
                                <td class="px-6 py-3 font-mono text-gray-600">{{ $user->assigned_ip ?? '-' }}</td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-0.5 rounded text-xs font-medium {{ $user->isActive() ? 'bg-green-100 text-green-800' : ($user->isExpired() ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-600') }}">
                                        {{ $user->is_enabled ? ($user->isExpired() ? 'expired' : 'active') : 'disabled' }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-gray-500">{{ $user->expired_at?->format('Y-m-d') ?? '-' }}</td>
                                <td class="px-6 py-3">
                                    @if ($user->has_certificate) ✓ @else - @endif
                                </td>
                                <td class="px-6 py-3 space-x-1 whitespace-nowrap">
                                    <a href="{{ route('ikev2-users.show', $user) }}" class="text-indigo-600 hover:underline text-xs">View</a>
                                    <a href="{{ route('ikev2-users.edit', $user) }}" class="text-indigo-600 hover:underline text-xs">Edit</a>
                                    <form method="POST" action="{{ route('ikev2-users.destroy', $user) }}" class="inline"
                                          onsubmit="return confirm('Delete {{ $user->username }}?');">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:underline text-xs">Del</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">No IKEv2 users found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-6 py-3 border-t border-gray-100">{{ $users->links() }}</div>
            </div>

            <div class="lg:hidden space-y-3">
                @forelse ($users as $user)
                    <div class="bg-white shadow-sm rounded-lg p-4">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $user->username }}</h3>
                                <p class="text-xs font-mono text-gray-500 mt-1">{{ $user->assigned_ip ?? '—' }}</p>
                            </div>
                            <span class="px-2 py-0.5 rounded text-xs font-medium {{ $user->isActive() ? 'bg-green-100 text-green-800' : ($user->isExpired() ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-600') }}">
                                {{ $user->is_enabled ? ($user->isExpired() ? 'expired' : 'active') : 'disabled' }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm mb-3">
                            <div class="text-gray-500">Expires</div>
                            <div class="text-gray-700">{{ $user->expired_at?->format('Y-m-d') ?? '—' }}</div>
                            <div class="text-gray-500">Cert</div>
                            <div class="text-gray-700">{{ $user->has_certificate ? '✓' : '—' }}</div>
                        </div>
                        <div class="flex gap-2 pt-2 border-t border-gray-100">
                            <a href="{{ route('ikev2-users.show', $user) }}" class="flex-1 text-center px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded text-xs font-medium hover:bg-indigo-100">View</a>
                            <a href="{{ route('ikev2-users.edit', $user) }}" class="flex-1 text-center px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded text-xs font-medium hover:bg-indigo-100">Edit</a>
                            <form method="POST" action="{{ route('ikev2-users.destroy', $user) }}" class="flex-1"
                                  onsubmit="return confirm('Delete {{ $user->username }}?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full px-3 py-1.5 bg-red-50 text-red-700 rounded text-xs font-medium hover:bg-red-100">Del</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white shadow-sm rounded-lg p-8 text-center text-gray-400">No IKEv2 users found.</div>
                @endforelse
                <div class="px-2">{{ $users->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>