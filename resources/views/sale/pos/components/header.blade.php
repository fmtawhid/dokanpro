<div class="print:hidden">
    <div class="h-[65px] px-8 bg-white dark:bg-slate-950 shadow justify-between items-center flex gap-1 print:hidden">
        <div class="flex items-center gap-6">
            <a href="{{ route('root') }}" class="w-32 md:w-40 transition">
                <img src="{{ $general_settings->logo->file ?? asset('/logo/logo.png') }}" class="w-full" />
            </a>
        </div>
        <div class="flex items-center gap-8">
            <!-- time -->
            <div id="dateTimeSection"
                class="text-sm sm:text-xl font-bold tracking-tight leading-tight dark:text-slate-400">
                <span id="date"></span> |
                <span id="hours"></span><span class="animated-element sm:text-2xl leading-3">:</span><span
                    id="minutes"></span>
            </div>
            <a href="{{ route('root') }}"
                class="hidden sm:flex rounded-full justify-center items-center cursor-pointer">
                <img src="{{ asset('/icons/home.svg') }}"class="w-8 h-8" />
            </a>
            <a href="{{ route('sale.draft') }}"
                class="hidden sm:flex rounded-full justify-center items-center cursor-pointer">
                <img src="{{ asset('/icons/draft.svg') }}" class="w-8 h-8" />
            </a>
            {{-- <button type="button" id="wallet" class="hidden md:flex items-center gap-6 transition">
                <img src="{{ asset('/icons/wallet.svg') }}" class="w-8 h-8" />
            </button> --}}
            <button type="button" id="zoom-in" class="hidden md:flex items-center gap-6 transition">
                <img src="{{ asset('/icons/zoom-in.svg') }}" class="w-8 h-8" />
            </button>
            <button type="button" id="zoom-out" class="hidden md:flex items-center gap-6 transition">
                <img src="{{ asset('/icons/zoom-out.svg') }}" class="w-8 h-8" />
            </button>
            <button type="button" id="logout"
                class="grow-0 rounded-full flex justify-center items-center cursor-pointer">
                <img src="{{ asset('/icons/logout.svg') }}" class="w-8 h-8" />
            </button>
        </div>
    </div>
</div>
