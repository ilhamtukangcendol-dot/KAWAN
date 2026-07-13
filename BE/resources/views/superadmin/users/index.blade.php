<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-2xl text-slate-800 leading-tight uppercase tracking-wide">
                {{ __('Manajemen Pengguna (User Management)') }}
            </h2>
            <a href="{{ route('superadmin.users.create') }}" class="px-5 py-3 bg-purple-600 hover:bg-purple-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg shadow-purple-200 transition duration-300 inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Pengguna Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold flex items-center justify-between">
                    <span>✅ {{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 font-bold">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-xs font-bold flex items-center justify-between">
                    <span>❌ {{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 font-bold">&times;</button>
                </div>
            @endif

            <!-- Filter & Search Form -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100">
                <form method="GET" action="{{ route('superadmin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Cari Pengguna</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold text-slate-700 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Filter Role</label>
                        <select name="role" class="w-full border-slate-100 rounded-2xl bg-slate-50 text-xs py-3 px-4 font-semibold text-slate-700 focus:ring-purple-500">
                            <option value="">Semua Role (4 Role)</option>
                            <option value="1" {{ request('role') == '1' ? 'selected' : '' }}>Super Admin (Role 1)</option>
                            <option value="2" {{ request('role') == '2' ? 'selected' : '' }}>Ketua RT (Role 2)</option>
                            <option value="3" {{ request('role') == '3' ? 'selected' : '' }}>Bendahara (Role 3)</option>
                            <option value="4" {{ request('role') == '4' ? 'selected' : '' }}>Warga (Role 4)</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="w-full py-3 bg-slate-900 hover:bg-black text-white text-xs font-black uppercase tracking-widest rounded-2xl transition duration-300">
                            Filter
                        </button>
                        @if(request('search') || request('role'))
                            <a href="{{ route('superadmin.users.index') }}" class="px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-black uppercase tracking-widest rounded-2xl transition duration-300">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabel Pengguna -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                <th class="py-4 px-6">ID / User</th>
                                <th class="py-4 px-6">Email</th>
                                <th class="py-4 px-6 text-center">Hak Akses / Role</th>
                                <th class="py-4 px-6 text-center">Status Terhubung Warga</th>
                                <th class="py-4 px-6 text-center">Aksi Operasional</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($users as $user)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center font-black text-xs shrink-0">
                                                @if($user->avatar)
                                                    <img src="{{ asset($user->avatar) }}" alt="Avatar" class="w-full h-full object-cover rounded-2xl">
                                                @else
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-black text-slate-800 text-sm leading-tight">{{ $user->name }}</p>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">ID: #{{ $user->id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 font-semibold text-slate-600 text-xs">{{ $user->email }}</td>
                                    <td class="py-4 px-6 text-center">
                                        @if($user->role == 1)
                                            <span class="px-3 py-1 bg-purple-100 text-purple-800 text-[10px] font-black rounded-xl uppercase tracking-wider inline-block">1 - Super Admin</span>
                                        @elseif($user->role == 2)
                                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-[10px] font-black rounded-xl uppercase tracking-wider inline-block">2 - Ketua RT</span>
                                        @elseif($user->role == 3)
                                            <span class="px-3 py-1 bg-amber-100 text-amber-800 text-[10px] font-black rounded-xl uppercase tracking-wider inline-block">3 - Bendahara</span>
                                        @else
                                            <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-xl uppercase tracking-wider inline-block">4 - Warga</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        @if($user->warga)
                                            <span class="px-2.5 py-0.5 bg-slate-100 text-slate-700 text-[10px] font-bold rounded-lg border border-slate-200">
                                                NIK: {{ $user->warga->nik }}
                                            </span>
                                        @else
                                            <span class="text-[10px] font-bold text-slate-300 uppercase italic">Belum terhubung</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('superadmin.users.edit', $user->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-purple-100 text-slate-700 hover:text-purple-700 font-bold text-xs rounded-xl transition duration-200">
                                                Edit Role / Data
                                            </a>
                                            
                                            @if(auth()->id() !== $user->id)
                                                <form method="POST" action="{{ route('superadmin.users.destroy', $user->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white font-bold text-xs rounded-xl transition duration-200">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-slate-400 font-bold text-sm">
                                        Tidak ada pengguna ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
