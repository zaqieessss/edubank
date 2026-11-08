@extends('layouts.app')

@section('title', 'Manajemen Pengguna (Users)')
@section('page_title', 'Users / Pengguna')

@section('content')
<div class="min-h-full flex-1 flex flex-col justify-between gap-4 pb-2">

    <!-- Top Bento Stats Grid (4 Cards Seragam & Spacious) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 shrink-0">
        <!-- Card 1: Total Pengguna -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Total Pengguna
            </span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-slate-900">
                    {{ number_format($users->total(), 0, ',', '.') }}
                </span>
                <span class="text-xs text-slate-400 font-medium">Akun</span>
            </div>
        </div>

        <!-- Card 2: Administrator & Supervisor -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Admin & Supervisor
            </span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-indigo-600">
                    {{ number_format(\App\Models\User::where('role', 'admin')->count(), 0, ',', '.') }}
                </span>
                <span class="text-xs text-indigo-600/80 font-medium">Akun</span>
            </div>
        </div>

        <!-- Card 3: Teller Kasir -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Petugas Teller
            </span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-emerald-600">
                    {{ number_format(\App\Models\User::where('role', 'teller')->count(), 0, ',', '.') }}
                </span>
                <span class="text-xs text-emerald-600/80 font-medium">Akun</span>
            </div>
        </div>

        <!-- Card 4: Siswa Terdaftar -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Akun Siswa (Savers)
            </span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-blue-600">
                    {{ number_format(\App\Models\User::where('role', 'saver')->count(), 0, ',', '.') }}
                </span>
                <span class="text-xs text-blue-600/80 font-medium">Akun</span>
            </div>
        </div>
    </div>

    <!-- Main Table Bento Box (Memanjang Elegan Sejajar Bawah Sidebar) -->
    <div class="bg-white rounded-[24px] p-5 md:p-6 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[420px]">
        
        <div class="flex-1 flex flex-col min-h-0">
            <!-- Action Toolbar (Pencarian, Filter Role & Status, Tambah Pengguna) -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100 shrink-0">
                <!-- Search & Filter Form -->
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-2">
                    <!-- Search Input -->
                    <div class="flex items-center bg-slate-50 border border-slate-200/70 rounded-xl px-3 py-1.5 text-xs text-slate-600 w-56 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-500/20">
                        <svg class="w-3.5 h-3.5 text-slate-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / Email Pengguna..." class="bg-transparent border-none outline-none text-xs w-full p-0">
                    </div>

                    <!-- Filter Role -->
                    <select name="role" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200/70 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 outline-none focus:bg-white">
                        <option value="">Semua Peran (Role)</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin / Supervisor</option>
                        <option value="teller" {{ request('role') == 'teller' ? 'selected' : '' }}>Teller</option>
                        <option value="saver" {{ request('role') == 'saver' ? 'selected' : '' }}>Siswa (Saver)</option>
                    </select>

                    <!-- Filter Status -->
                    <select name="status" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200/70 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 outline-none focus:bg-white">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>

                    @if(request()->anyFilled(['search', 'role', 'status']))
                        <a href="{{ route('admin.users.index') }}" class="text-[11px] text-rose-500 hover:text-rose-700 font-semibold px-2 py-1">Reset</a>
                    @endif
                </form>

                <!-- Tambah Pengguna Button (Dedicated Page) -->
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-sm transition-all hover:shadow">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Pengguna</span>
                </a>
            </div>

            <!-- Table Container -->
            <div class="flex-1 overflow-x-auto mt-2">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-semibold text-slate-400">
                            <th class="py-2.5 px-3 font-medium">Nama Pengguna</th>
                            <th class="py-2.5 px-3 font-medium">Email Akun</th>
                            <th class="py-2.5 px-3 font-medium">Role / Hak Akses</th>
                            <th class="py-2.5 px-3 font-medium text-center">Status</th>
                            <th class="py-2.5 px-3 font-medium">Terdaftar Sejak</th>
                            <th class="py-2.5 px-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($users as $u)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3 px-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs uppercase shrink-0">
                                            {{ substr($u->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800">{{ $u->name }}</div>
                                            @if($u->id === auth()->id())
                                                <span class="text-[9px] bg-blue-100 text-blue-700 px-1.5 py-0.2 rounded font-bold">Akun Anda</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3 font-mono text-slate-700 text-xs">
                                    {{ $u->email }}
                                </td>
                                <td class="py-3 px-3">
                                    @if($u->role === 'admin')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200/80">
                                            Admin & Supervisor
                                        </span>
                                    @elseif($u->role === 'teller')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                            Teller Kasir
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200/80">
                                            Siswa (Saver)
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-center">
                                    @if($u->status === 'active')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200/80">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-slate-500 text-xs">
                                    {{ $u->created_at->format('d/m/Y') }}
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Edit Button (Dedicated Page) -->
                                        <a href="{{ route('admin.users.edit', $u->id) }}" 
                                           class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors" 
                                           title="Edit Pengguna">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>

                                        @if($u->id !== auth()->id())
                                            <!-- Delete Button (Centered Modal Confirmation) -->
                                            <button type="button" 
                                                    onclick="openDeleteUserModal('{{ route('admin.users.destroy', $u->id) }}', '{{ addslashes($u->name) }}')"
                                                    class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition-colors cursor-pointer" 
                                                    title="Hapus Pengguna">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-1.5">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                        <span class="text-xs font-medium">Tidak ada data pengguna yang cocok</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Compact Pagination Footer (Pinned at bottom) -->
        <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500 shrink-0">
            <div>
                Menampilkan <span class="font-bold text-slate-800">{{ $users->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-800">{{ $users->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $users->total() }}</span> pengguna
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: Hapus Pengguna (Centered Backdrop Blur Confirmation)                -->
<!-- ========================================================================= -->
<div id="deleteUserModal" 
     class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
     onclick="if(event.target === this) closeDeleteUserModal()">
    
    <div class="bg-white rounded-[24px] max-w-md w-full p-6 border border-slate-200/80 shadow-2xl space-y-4 relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Konfirmasi Hapus Pengguna</h3>
            </div>
            <button type="button" onclick="closeDeleteUserModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors font-bold text-sm">
                &times;
            </button>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            Apakah Anda yakin ingin menghapus akun pengguna <strong id="deleteUserName" class="text-slate-800"></strong>? Akun dengan histori transaksi audit tidak dapat dihapus (ON DELETE RESTRICT).
        </p>

        <form id="deleteUserForm" method="POST" action="" class="pt-2">
            @csrf
            @method('DELETE')
            <div class="flex items-center justify-end gap-2">
                <button type="button" onclick="closeDeleteUserModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold transition-all shadow-xs cursor-pointer">
                    Ya, Hapus Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openDeleteUserModal(actionUrl, userName) {
        const form = document.getElementById('deleteUserForm');
        const nameEl = document.getElementById('deleteUserName');
        const modal = document.getElementById('deleteUserModal');

        if (form && nameEl && modal) {
            form.action = actionUrl;
            nameEl.innerText = userName;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeDeleteUserModal() {
        const modal = document.getElementById('deleteUserModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteUserModal();
        }
    });
</script>
@endpush
@endsection
