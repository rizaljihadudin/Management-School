<x-filament-panels::page>
    @if ($this->hasInfolist())
        {{ $this->infolist }}

        <div class="relative flex flex-col my-6 shadow-sm border border-slate-200 rounded-lg w-96">
            <div class="relative h-56 m-2.5 overflow-hidden text-white rounded-md" id="qrcode" style="width: 100px;height: 100px; margin-top: 15px;">

            </div>
        </div>
    @else
        {{ $this->form }}
    @endif
</x-filament-panels::page>
<script src="{{ asset('js/qrcode/qrcode.js') }}"></script>
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
</script>
