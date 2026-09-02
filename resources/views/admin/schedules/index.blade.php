@extends('layouts.admin')
@section('header', 'Jadwal Pelajaran')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Controls (Disembunyikan saat Print) -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 print:hidden">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Matriks Jadwal Pelajaran Sekolah</h2>
            <p class="text-sm text-gray-500">Format tabel terpusat per hari, jam pelajaran, dan kelas sesuai template PDF.</p>
        </div>
        
        <div class="flex items-center gap-2 flex-wrap">
            <!-- Tombol Switch Tampilan -->
            <div class="bg-gray-100 p-1 rounded-xl flex items-center border border-gray-200 text-xs font-semibold">
                <button type="button" id="btn-view-matrix" onclick="switchView('matrix')" class="px-3 py-1.5 rounded-lg bg-white shadow-sm text-blue-700 font-bold transition">
                    📋 Tampilan Matriks (PDF)
                </button>
                <button type="button" id="btn-view-list" onclick="switchView('list')" class="px-3 py-1.5 rounded-lg text-gray-600 hover:text-gray-900 transition">
                    📑 Tampilan Daftar
                </button>
            </div>

            <!-- Tombol Print PDF -->
            <button onclick="window.print()" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-sm transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak / Simpan PDF
            </button>

            <!-- Tombol Tambah Jadwal -->
            <a href="{{ route('admin.schedules.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-sm transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Jadwal
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-sm print:hidden">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm print:hidden">
            {{ session('error') }}
        </div>
    @endif

    <!-- ============================================================== -->
    <!-- 1. TAMPILAN MATRIKS PERSIS SEPERTI DI PDF -->
    <!-- ============================================================== -->
    <div id="view-matrix" class="space-y-6">
        <div class="bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-gray-200 overflow-x-auto print:p-0 print:border-none print:shadow-none">
            
            <div class="text-center mb-6 pb-2 border-b border-gray-200 print:mb-4">
                <h1 class="text-xl font-extrabold tracking-wide uppercase text-gray-900">Jadwal Pelajaran Sekolah</h1>
                <p class="text-xs text-gray-500 uppercase tracking-widest mt-0.5">Tahun Ajaran Aktif</p>
            </div>

            @foreach($days as $day)
            <div class="mb-8 last:mb-0 break-inside-avoid">
                <table class="w-full border-collapse border-2 border-gray-900 text-center text-xs">
                    <thead>
                        <!-- Baris Header Hari & Kelas (Background Kuning Sesuai PDF) -->
                        <tr class="bg-amber-300 text-gray-950 font-black uppercase text-xs md:text-sm tracking-wider border-2 border-gray-900">
                            <th class="border-2 border-gray-900 p-2 md:p-2.5 w-32 md:w-44 text-left pl-4">
                                HARI {{ strtoupper($day) }}
                            </th>
                            @foreach($classes as $class)
                                <th class="border-2 border-gray-900 p-2 md:p-2.5 min-w-[130px]">
                                    KELAS {{ strtoupper($class->name) }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="text-gray-900">
                        @foreach($timeSlots as $slotIndex => $slot)
                        
                        <!-- Baris 1: Jam Ke - Mata Pelajaran -->
                        <tr class="border-t border-gray-900 bg-white hover:bg-amber-50/40 transition">
                            <td class="border-2 border-gray-900 p-2 font-bold uppercase text-left pl-4 bg-gray-50/80">
                                <div class="flex items-center justify-between">
                                    <span>{{ $slot['label'] }}</span>
                                    <span class="text-[10px] text-gray-500 font-normal print:hidden">{{ $slot['start'] }}-{{ $slot['end'] }}</span>
                                </div>
                            </td>
                            @foreach($classes as $class)
                                @php $item = $matrix[$day][$slotIndex][$class->id] ?? null; @endphp
                                <td class="border-2 border-gray-900 p-2 font-semibold text-gray-900">
                                    @if($item)
                                        <div class="flex items-center justify-center gap-1 group">
                                            <span class="text-blue-900 font-bold uppercase">{{ $item->subject->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-300 font-normal">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        <!-- Baris 2: Pengajar - Guru Pengampu -->
                        <tr class="border-b-2 border-gray-900 bg-gray-50/30 hover:bg-amber-50/40 transition">
                            <td class="border-2 border-gray-900 p-2 font-semibold uppercase text-left pl-4 text-gray-600 bg-gray-100/60">
                                PENGAJAR
                            </td>
                            @foreach($classes as $class)
                                @php $item = $matrix[$day][$slotIndex][$class->id] ?? null; @endphp
                                <td class="border-2 border-gray-900 p-2 text-gray-700">
                                    @if($item)
                                        <div class="flex flex-col items-center justify-center">
                                            <span class="text-[11px] font-medium text-gray-800 uppercase">{{ $item->teacher->name }}</span>
                                            <!-- Aksi Cepat (Hanya Tampil di Layar, disembunyikan saat print) -->
                                            <div class="flex items-center gap-1.5 mt-1 print:hidden">
                                                <a href="{{ route('admin.schedules.edit', $item->id) }}" class="text-[10px] text-blue-600 hover:text-blue-800 font-semibold px-1.5 py-0.5 bg-blue-50 hover:bg-blue-100 rounded transition" title="Edit Jadwal">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.schedules.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal {{ $item->subject->name }} di Kelas {{ $class->name }}?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-[10px] text-red-600 hover:text-red-800 font-semibold px-1.5 py-0.5 bg-red-50 hover:bg-red-100 rounded transition" title="Hapus Jadwal">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Tombol Tambah Cepat jika slot kosong -->
                                        <div class="print:hidden">
                                            <a href="{{ route('admin.schedules.create', ['day' => $day, 'class_room_id' => $class->id, 'start_time' => $slot['start'], 'end_time' => $slot['end']]) }}" 
                                               class="text-[11px] text-gray-400 hover:text-blue-600 hover:bg-blue-50 px-2 py-0.5 rounded transition inline-block">
                                                + Isi
                                            </a>
                                        </div>
                                        <span class="hidden print:inline text-gray-300">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
            @endforeach

        </div>
    </div>


    <!-- ============================================================== -->
    <!-- 2. TAMPILAN DAFTAR LIST TABEL BIASA (ALTERNATIF) -->
    <!-- ============================================================== -->
    <div id="view-list" class="hidden space-y-4 print:hidden">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                            <th class="p-4 border-b font-semibold">Hari</th>
                            <th class="p-4 border-b font-semibold">Waktu</th>
                            <th class="p-4 border-b font-semibold">Kelas</th>
                            <th class="p-4 border-b font-semibold">Mata Pelajaran</th>
                            <th class="p-4 border-b font-semibold">Guru Pengampu</th>
                            <th class="p-4 border-b font-semibold text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        @forelse($schedules as $jadwal)
                        <tr class="border-b hover:bg-blue-50 transition">
                            <td class="p-4 font-bold text-gray-900">
                                <span class="px-2.5 py-1 bg-amber-100 text-amber-900 rounded-lg text-xs font-bold">
                                    {{ $jadwal->day }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg text-xs font-semibold border border-blue-100">
                                    {{ substr($jadwal->start_time, 0, 5) }} - {{ substr($jadwal->end_time, 0, 5) }}
                                </span>
                            </td>
                            <td class="p-4 font-semibold text-gray-900">Kelas {{ $jadwal->classRoom->name }}</td>
                            <td class="p-4 font-bold text-blue-900">{{ $jadwal->subject->name }}</td>
                            <td class="p-4">{{ $jadwal->teacher->name }}</td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.schedules.edit', $jadwal->id) }}" class="text-blue-600 hover:text-blue-800 font-medium text-xs bg-blue-50 hover:bg-blue-100 px-2.5 py-1.5 rounded-lg transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.schedules.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-xs bg-red-50 hover:bg-red-100 px-2.5 py-1.5 rounded-lg transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-400">Belum ada jadwal yang dipetakan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Script Switch Tampilan & Print Settings -->
<script>
    function switchView(view) {
        const matrixView = document.getElementById('view-matrix');
        const listView = document.getElementById('view-list');
        const btnMatrix = document.getElementById('btn-view-matrix');
        const btnList = document.getElementById('btn-view-list');

        if (view === 'matrix') {
            matrixView.classList.remove('hidden');
            listView.classList.add('hidden');
            btnMatrix.className = 'px-3 py-1.5 rounded-lg bg-white shadow-sm text-blue-700 font-bold transition';
            btnList.className = 'px-3 py-1.5 rounded-lg text-gray-600 hover:text-gray-900 transition';
        } else {
            matrixView.classList.add('hidden');
            listView.classList.remove('hidden');
            btnList.className = 'px-3 py-1.5 rounded-lg bg-white shadow-sm text-blue-700 font-bold transition';
            btnMatrix.className = 'px-3 py-1.5 rounded-lg text-gray-600 hover:text-gray-900 transition';
        }
    }
</script>

<!-- Styling Tambahan Khusus Print -->
<style>
    @media print {
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        body {
            background: white !important;
            color: black !important;
            font-size: 10pt;
        }
        aside, nav, header, footer, .print\:hidden {
            display: none !important;
        }
        main {
            padding: 0 !important;
            margin: 0 !important;
        }
    }
</style>
@endsection