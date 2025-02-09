<x-filament-panels::page>
    @if ($this->hasInfolist())
        {{ $this->infolist }}
    @else
        {{ $this->form }}
    @endif
</x-filament-panels::page>
{{-- <script src="{{ asset('js/qrcode/qrcode.js') }}"></script>
<script>
    var qrcode = new QRCode(document.getElementById("qrcode"), {
        width: 128,
        height: 128,
        colorDark : "#000000",
        colorLight : "#ffffff",
    });

    console.log(qrcode);

    function makeCode() {
        var elText = '{{ $this->getRecord()->nis }}';
        console.log(elText);
        qrcode.makeCode(elText);
    }

    makeCode();
</script> --}}
