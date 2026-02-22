<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full inline-flex items-center justify-center px-4 py-3 bg-[#2e4053] border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wide hover:bg-[#243447] focus:bg-[#243447] active:bg-[#1a252f] focus:outline-none focus:ring-2 focus:ring-[#2e4053] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
