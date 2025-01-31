<!-- resources/views/filament/forms/components/qr-scanner.blade.php -->
<div x-data="qrScanner()"
     x-id="['qr-scanner']"
     class="space-y-2">
    <button type="button"
            @click="toggleScanner"
            style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"

            x-bind:class="{ 'enabled:opacity-70 enabled:cursor-wait': isProcessing }"
            class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action">
        <span x-text="showScanner ? 'Stop Scanning' : 'Start Scanning'"></span>
    </button>

    <template x-if="showScanner">
        <div class="mt-2">
            <div :id="`qr-reader`"
                 wire:ignore
                 class="w-full border-2 rounded-lg border-primary-600"></div>
            <div x-text="errorMessage" class="mt-1 text-sm text-danger-500"></div>
        </div>
    </template>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('qrScanner', () => ({
        showScanner: false,
        errorMessage: null,
        html5QrCode: null,
        scannerId: null,

        init() {
            this.scannerId = `qr-reader`;

            this.$watch('showScanner', async (value) => {
                if (value) {
                    await this.$nextTick();
                    this.initializeScanner();
                } else {
                    this.stopScanner();
                }
            });
        },

        toggleScanner() {
            this.showScanner = !this.showScanner;
        },

        async initializeScanner() {
            try {
                const devices = await Html5Qrcode.getCameras();
                if (devices.length === 0) {
                    throw new Error('No camera devices found');
                }

                this.html5QrCode = new Html5Qrcode(this.scannerId);

                await this.html5QrCode.start(
                    devices[0].id,
                    {
                        fps: 10,
                        qrbox: 250,
                        aspectRatio: 1.777778
                    },
                    qrCodeMessage => this.handleScanSuccess(qrCodeMessage),
                    errorMessage => this.handleScanError(errorMessage)
                );
            } catch (error) {
                this.handleScanError(error);
            }
        },

        handleScanSuccess(qrCodeMessage) {
            this.$wire.set('{{ $getStatePath() }}', qrCodeMessage);
            this.stopScanner();
            this.$wire.validateOnly('{{ $getStatePath() }}');
        },

        // handleScanError(error) {
        //     this.errorMessage = this.parseErrorMessage(error);
        //     this.stopScanner();
        //     console.error('QR Scanner Error:', error);
        // },

        parseErrorMessage(error) {
            const messages = {
                NotAllowedError: 'Izin kamera ditolak. Silakan berikan akses kamera',
                NotFoundError: 'Tidak ditemukan kamera yang sesuai',
                NotSupportedError: 'Browser tidak mendukung fitur scanner',
                NotReadableError: 'Kamera sedang digunakan oleh aplikasi lain'
            };

            return messages[error.name] || 'Gagal menginisialisasi scanner';
        },

        stopScanner() {
            if (this.html5QrCode && this.html5QrCode.isScanning) {
                this.html5QrCode.stop();
            }
            this.showScanner = false;
        }
    }));
});
</script>
@endpush
