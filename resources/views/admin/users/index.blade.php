@extends('layouts.app')

@section('title', 'Manajemen Pengguna (Users)')
@section('page_title', 'Users / Pengguna')

@section('content')
<div class="space-y-3.5 pb-6">

    <!-- Top Bento Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <!-- Card 1: Total Pengguna -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Pengguna Sistem</p>
                <h3 class="text-xl font-extrabold text-slate-900 mt-0.5">{{ number_format($users->total(), 0, ',', '.') }}</h3>
                <span class="text-[10px] font-medium text-slate-500">Semua role akun</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>

        <!-- Card 2: Administrator & Supervisor -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Admin & Supervisor</p>
                <h3 class="text-xl font-extrabold text-indigo-600 mt-0.5">
                    {{ \App\Models\User::where('role', 'admin')->count() }}
                </h3>
                <span class="text-[10px] font-medium text-slate-500">Akses penuh & approval</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
        </div>

        <!-- Card 3: Teller Kasir -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Petugas Teller</p>
                <h3 class="text-xl font-extrabold text-emerald-600 mt-0.5">
                    {{ \App\Models\User::where('role', 'teller')->count() }}
                </h3>
                <span class="text-[10px] font-medium text-slate-500">Operator loket kas</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>

        <!-- Card 4: Siswa Terdaftar -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Akun Siswa (Savers)</p>
                <h3 class="text-xl font-extrabold text-blue-600 mt-0.5">
                    {{ \App\Models\User::where('role', 'saver')->count() }}
                </h3>
                <span class="text-[10px] font-medium text-slate-500">Login tabungan digital</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
        </div>
    </div>

    <!-- Main Table Bento Box with Controls -->
    <div class="bg-white rounded-[26px] p-4 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-3.5">
        
        <!-- Action Toolbar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <!-- Search & Filter Form -->
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-2">
                <div class="flex items-center bg-slate-100/90 rounded-xl px-3 py-1.5 text-xs text-slate-600 border border-slate-200/60 w-56 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-500/20">
                    <svg class="w-3.5 h-3.5 text-slate-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / Email Pengguna..." class="bg-transparent border-none outline-none text-xs w-full p-0">
                </div>

                <select name="role" onchange="this.form.submit()" class="bg-slate-100/90 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 border border-slate-200/60 outline-none">
                    <option value="">Semua Peran (Role)</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin / Supervisor</option>
                    <option value="teller" {{ request('role') == 'teller' ? 'selected' : '' }}>Teller</option>
                    <option value="saver" {{ request('role') == 'saver' ? 'selected' : '' }}>Siswa (Saver)</option>
                </select>

                <select name="status" onchange="this.form.submit()" class="bg-slate-100/90 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 border border-slate-200/60 outline-none">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>

                @if(request()->anyFilled(['search', 'role', 'status']))
                    <a href="{{ route('admin.users.index') }}" class="text-[11px] text-rose-500 hover:text-rose-700 font-semibold px-2 py-1">Reset</a>
                @endif
            </form>

            <button type="button" onclick="openUserModal()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-sm transition-all hover:shadow">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Pengguna</span>
            </button>
        </div>

        <!-- Dense Responsive Table -->
        <div class="overflow-x-auto rounded-2xl border border-slate-100">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200/60">
                        <th class="py-2.5 px-3">Nama Pengguna</th>
                        <th class="py-2.5 px-3">Email Akun</th>
                        <th class="py-2.5 px-3">Role / Hak Akses</th>
                        <th class="py-2.5 px-3 text-center">Status</th>
                        <th class="py-2.5 px-3">Terdaftar Sejak</th>
                        <th class="py-2.5 px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $u)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-2.5 px-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs uppercase">
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
                            <td class="py-2.5 px-3 font-mono text-slate-700">
                                {{ $u->email }}
                            </td>
                            <td class="py-2.5 px-3">
                                @if($u->role === 'admin')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        Admin & Supervisor
                                    </span>
                                @elseif($u->role === 'teller')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Teller Kasir
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        Siswa (Saver)
                                    </span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                @if($u->status === 'active')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-slate-500 text-[11px]">
                                {{ $u->created_at->format('d/m/Y') }}
                            </td>
                            <td class="py-2.5 px-3 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" 
                                            onclick='editUser(@json($u))' 
                                            class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors" 
                                            title="Edit Pengguna">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>

                                    @if($u->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini? Akun dengan histori transaksi tidak dapat dihapus.')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition-colors" 
                                                    title="Hapus Pengguna">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                Tidak ada data pengguna yang cocok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Compact Pagination -->
        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
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
<!-- MODAL: Tambah / Edit Pengguna                                             -->
<!-- ========================================================================= -->
<div id="userModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 id="userModalTitle" class="text-sm font-bold text-slate-800">Tambah Pengguna Baru</h3>
            <button onclick="closeUserModal()" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
        </div>
        <form id="userForm" method="POST" action="{{ route('admin.users.store') }}" class="mt-4 space-y-3.5">
            @csrf
            <input type="hidden" name="_method" id="userFormMethod" value="POST">

            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                <input type="text" name="name" id="u_name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Email Akun *</label>
                <input type="email" name="email" id="u_email" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Password <span id="passOptional" class="text-slate-400 font-normal"></span></label>
                <input type="password" name="password" id="u_password" placeholder="Minimal 6 karakter" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Role / Peran *</label>
                    <select name="role" id="u_role" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 focus:bg-white">
                        <option value="admin">Admin / Supervisor</option>
                        <option value="teller">Teller Kasir</option>
                        <option value="saver">Siswa (Saver)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Status Akun *</label>
                    <select name="status" id="u_status" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 focus:bg-white">
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeUserModal()" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-200">Batal</button>
                <button type="submit" class="px-5 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 shadow-sm">Simpan Akun</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openUserModal() {
        document.getElementById('userModalTitle').innerText = 'Tambah Pengguna Baru';
        document.getElementById('userFormMethod').value = 'POST';
        document.getElementById('userForm').action = "{{ route('admin.users.store') }}";
        document.getElementById('userForm').reset();
        document.getElementById('passOptional').innerText = '*';
        document.getElementById('u_password').required = true;
        document.getElementById('userModal').classList.remove('hidden');
    }

    function editUser(user) {
        document.getElementById('userModalTitle').innerText = 'Edit Pengguna: ' + user.name;
        document.getElementById('userFormMethod').value = 'PUT';
        document.getElementById('userForm').action = "/admin/users/" + user.id;

        document.getElementById('u_name').value = user.name;
        document.getElementById('u_email').value = user.email;
        document.getElementById('u_role').value = user.role;
        document.getElementById('u_status').value = user.status;
        
        document.getElementById('passOptional').innerText = '(Kosongkan jika tidak diubah)';
        document.getElementById('u_password').value = '';
        document.getElementById('u_password').required = false;

        document.getElementById('userModal').classList.remove('hidden');
    }

    function closeUserModal() {
        document.getElementById('userModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
