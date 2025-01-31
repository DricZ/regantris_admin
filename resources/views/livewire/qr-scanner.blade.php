<div>
    <button type="button"
            @click="showScanner = !showScanner"
            class="filament-button filament-button-size-sm inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset bg-primary-600 hover:bg-primary-500 text-white border-transparent focus:ring-primary-600">
        Scan QR Member
    </button>

    <div x-show="showScanner" class="mt-2">
        <div id="qr-reader" class="w-full"></div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener('livewire:init', () => {
        const html5QrCode = new Html5Qrcode("qr-reader");

        Livewire.on('startScanner', () => {
            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    html5QrCode.start(
                        devices[0].id,
                        {
                            fps: 10,
                            qrbox: 250
                        },
                        qrCodeMessage => {
                            @this.validateCode(qrCodeMessage);
                            html5QrCode.stop();
                        },
                        errorMessage => console.error(errorMessage)
                    ).catch(err => console.error(err));
                }
            });
        });
    });
</script>
@endpush
