@extends('layouts.guru')

@section('header', 'Absensi Kamera Guru')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    <!-- Status Kartu Absensi Hari Ini -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <div>
                <h3 class="font-bold text-gray-900 text-lg">Absensi Harian Guru</h3>
                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
            </div>
            @if(!$attendance)
                <span class="px-3 py-1 bg-amber-50 text-amber-700 text-xs font-semibold rounded-full border border-amber-200">
                    Belum Absen Masuk
                </span>
            @elseif($attendance && !$attendance->clock_out)
                <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full border border-blue-200">
                    Sudah Masuk (Belum Pulang)
                </span>
            @else
                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-full border border-emerald-200">
                    Absensi Selesai
                </span>
            @endif
        </div>

        <!-- Detail Kehadiran Hari Ini -->
        @if($attendance)
        <div class="grid grid-cols-2 gap-4 mt-4 pt-2">
            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                <p class="text-xs text-gray-500 font-medium">Jam Masuk (Clock In)</p>
                <p class="text-base font-bold text-gray-800 mt-0.5">{{ $attendance->clock_in ?? '-' }}</p>
                <span class="inline-block mt-1 text-[11px] font-semibold px-2 py-0.5 rounded {{ $attendance->status == 'Terlambat' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                    Status: {{ $attendance->status }}
                </span>
            </div>
            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                <p class="text-xs text-gray-500 font-medium">Jam Pulang (Clock Out)</p>
                <p class="text-base font-bold text-gray-800 mt-0.5">{{ $attendance->clock_out ?? 'Belum Absen' }}</p>
                @if($attendance->clock_out)
                    <span class="inline-block mt-1 text-[11px] font-semibold px-2 py-0.5 rounded bg-emerald-100 text-emerald-700">
                        Tercatat
                    </span>
                @endif
            </div>
        </div>
        @endif
    </div>

    @if(!$attendance || !$attendance->clock_out)
    <!-- Kamera & Aksi -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
        <h4 class="font-semibold text-gray-800 text-sm">
            @if(!$attendance)
                📸 Ambil Foto untuk Absen Masuk
            @else
                📸 Ambil Foto untuk Absen Pulang
            @endif
        </h4>

        <!-- Area Video Kamera -->
        <div class="relative bg-gray-900 rounded-xl overflow-hidden aspect-[4/3] flex justify-center items-center shadow-inner">
            <video id="camera-stream" autoplay playsinline class="object-cover w-full h-full transform scale-x-[-1]"></video>
            <canvas id="canvas" class="hidden"></canvas>
            
            <div id="camera-loading" class="absolute inset-0 flex flex-col items-center justify-center text-white bg-gray-900/80">
                <svg class="animate-spin h-8 w-8 text-emerald-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <p class="text-xs text-gray-300">Menghubungkan ke kamera...</p>
            </div>

            <div id="camera-error" class="hidden absolute inset-0 flex flex-col items-center justify-center text-center p-4 bg-red-900/90 text-white">
                <svg class="w-10 h-10 text-red-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <p class="text-xs font-semibold">Gagal Mengakses Kamera</p>
                <p class="text-[11px] text-red-200 mt-1">Pastikan izin akses kamera pada browser sudah aktif/diizinkan.</p>
            </div>
        </div>

        <form action="{{ route('guru.attendance.store_camera') }}" method="POST" id="attendance-form">
            @csrf
            <input type="hidden" name="photo" id="photo-input">
            
            @if(!$attendance)
                <button type="button" id="capture-btn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Ambil Foto & Absen Masuk
                </button>
            @else
                <button type="button" id="capture-btn" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Ambil Foto & Absen Pulang
                </button>
            @endif
        </form>
    </div>
    @else
    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 text-center">
        <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <h4 class="font-bold text-emerald-900 text-base">Absensi Hari Ini Lengkap</h4>
        <p class="text-xs text-emerald-700 mt-1">Terima kasih, Anda telah melakukan absensi masuk dan absensi pulang untuk hari ini.</p>
    </div>
    @endif

</div>

@if(!$attendance || !$attendance->clock_out)
<script>
    const video = document.getElementById('camera-stream');
    const canvas = document.getElementById('canvas');
    const captureBtn = document.getElementById('capture-btn');
    const photoInput = document.getElementById('photo-input');
    const form = document.getElementById('attendance-form');
    const loading = document.getElementById('camera-loading');
    const errorBox = document.getElementById('camera-error');

    // Mengaktifkan Kamera Depan
    navigator.mediaDevices.getUserMedia({ 
        video: { 
            facingMode: 'user',
            width: { ideal: 640 },
            height: { ideal: 480 }
        } 
    })
    .then(stream => { 
        video.srcObject = stream;
        video.onloadedmetadata = () => {
            loading.classList.add('hidden');
        };
    })
    .catch(err => {
        console.error("Kamera tidak diizinkan atau tidak tersedia:", err);
        loading.classList.add('hidden');
        errorBox.classList.remove('hidden');
        captureBtn.disabled = true;
        captureBtn.classList.add('opacity-50', 'cursor-not-allowed');
    });

    captureBtn.addEventListener('click', () => {
        if (!video.videoWidth) return;

        // Ambil frame dari video ke canvas
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        
        const context = canvas.getContext('2d');
        // Mirroring canvas sesuai tampilan video
        context.translate(canvas.width, 0);
        context.scale(-1, 1);
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Konversi ke Base64
        const imageData = canvas.toDataURL('image/jpeg', 0.85);
        photoInput.value = imageData;
        
        captureBtn.disabled = true;
        captureBtn.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-white mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            Menyimpan Absensi...
        `;

        // Submit form ke server
        form.submit();
    });
</script>
@endif
@endsection