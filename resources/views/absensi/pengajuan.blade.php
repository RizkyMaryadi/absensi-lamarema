<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengajuan Izin/Cuti - Lamarema Fashion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased selection:bg-amber-500 selection:text-white">

    <nav class="bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center sticky top-0 z-40 shadow-sm">
        <div class="flex items-center gap-4">
            <a href="/" class="text-slate-500 hover:text-amber-500 transition-colors mr-2">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div class="flex items-center gap-3">
                <div class="bg-white p-1 rounded-xl shadow-sm border border-slate-100">
                    <img src="{{ asset('images/logo-lamarema.png') }}" alt="Logo" class="w-10 h-10 rounded-lg object-cover">
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-wide text-slate-900">Admin Panel</h1>
                    <p class="text-xs text-amber-500 font-bold tracking-wider uppercase">Laporan Pengajuan</p>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-6 py-8 max-w-7xl">
        <div class="flex items-center gap-3 mb-6">
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight"><i class="fas fa-envelope-open-text text-amber-500 mr-2"></i> Laporan Pengajuan Izin & Cuti</h2>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-6 py-4 rounded-2xl mb-6 shadow-sm flex items-center gap-3">
                <i class="fas fa-check-circle text-xl"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="text-xs uppercase text-slate-400 border-b border-slate-100 bg-slate-50/50">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-center">No</th>
                            <th class="px-6 py-4 font-semibold">Tgl Pengajuan</th>
                            <th class="px-6 py-4 font-semibold">Pegawai</th>
                            <th class="px-6 py-4 font-semibold">Jenis</th>
                            <th class="px-6 py-4 font-semibold">Periode (Tgl)</th>
                            <th class="px-6 py-4 font-semibold">Alasan</th>
                            <th class="px-6 py-4 font-semibold">Bukti</th>
                            <th class="px-6 py-4 font-semibold text-center">Status & Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pengajuan as $index => $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-xs font-semibold text-slate-500">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $item->pegawai->name ?? '-' }}</td>
                            <td class="px-6 py-4 font-bold text-amber-600">{{ $item->jenis }}</td>
                            <td class="px-6 py-4 text-xs font-medium">
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}<br>
                                <span class="text-slate-400">s/d</span><br>
                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-xs">{{ $item->alasan }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($item->bukti_foto)
                                    <a href="{{ asset('storage/' . $item->bukti_foto) }}" target="_blank" class="text-blue-500 hover:text-blue-700 underline text-xs font-bold">
                                        <i class="fas fa-image"></i> Lihat
                                    </a>
                                @else
                                    <span class="text-slate-300 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->status === 'Menunggu')
                                    <form action="{{ route('pengajuan.status', $item->id) }}" method="POST" class="flex flex-col gap-2">
                                        @csrf
                                        <button type="submit" name="status" value="Disetujui" class="bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-200 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                            <i class="fas fa-check"></i> Setujui
                                        </button>
                                        <button type="submit" name="status" value="Ditolak" class="bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </form>
                                @else
                                    @if($item->status === 'Disetujui')
                                        <span class="bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase"><i class="fas fa-check"></i> Disetujui</span>
                                    @else
                                        <span class="bg-rose-100 text-rose-700 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase"><i class="fas fa-times"></i> Ditolak</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center text-slate-400 italic">Belum ada pengajuan izin/cuti dari pegawai.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
