<nav class="bg-white border-b border-gray-200 shadow-sm px-4 py-3">

    <div class="flex justify-between items-center">

        <!-- LEFT -->
        <div class="flex items-center gap-6">

            {{-- LOGO --}}
            <a href="{{ Auth::user()->role == 'owner'
                        ? route('owner.dashboard')
                        : route('pegawai.dashboard') }}"
               class="font-bold text-2xl text-indigo-600">
                UMKM APP
            </a>


            {{-- ========================= --}}
            {{-- OWNER MENU --}}
            {{-- ========================= --}}
            @if(Auth::user()->role == 'owner')

                <a href="{{ route('owner.dashboard') }}"
                   class="text-gray-700 hover:text-indigo-600 font-medium">
                    Dashboard
                </a>

                <a href="{{ route('products.index') }}"
                   class="text-gray-700 hover:text-indigo-600 font-medium">
                    Produk
                </a>

                <a href="{{ route('employees.index') }}"
                   class="text-gray-700 hover:text-indigo-600 font-medium">
                    Pegawai
                </a>

                <a href="{{ route('ingredients.index') }}"
                   class="text-gray-700 hover:text-indigo-600 font-medium">
                    Bahan Baku
                </a>

                <a href="{{ route('owner.reviews.index') }}"
                   class="text-gray-700 hover:text-indigo-600 font-medium">
                    Review Pelanggan
                </a>


                {{-- DROPDOWN LAPORAN --}}
                <div class="relative group">

                    <button class="text-gray-700 hover:text-indigo-600 font-medium flex items-center gap-1">

                        Laporan Penjualan

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M19 9l-7 7-7-7" />

                        </svg>

                    </button>


                    {{-- DROPDOWN CONTENT --}}
                    <div class="absolute hidden group-hover:block bg-white border rounded shadow-md mt-2 w-52 z-50">

                        <a href="{{ route('sales.report') }}"
                           class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Rekap Penjualan
                        </a>

                        <a href="{{ route('best.products') }}"
                           class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Produk Terlaris
                        </a>

                    </div>

                </div>

            @endif



            {{-- ========================= --}}
            {{-- PEGAWAI MENU --}}
            {{-- ========================= --}}
            @if(Auth::user()->role == 'pegawai')

                <a href="{{ route('pegawai.dashboard') }}"
                   class="text-gray-700 hover:text-indigo-600 font-medium">
                    Dashboard
                </a>

                <a href="{{ route('ingredients.index') }}"
                   class="text-gray-700 hover:text-indigo-600 font-medium">
                    Bahan Baku
                </a>

                <a href="{{ route('employee.orders.index') }}"
                   class="text-gray-700 hover:text-indigo-600 font-medium">
                    Pesanan
                </a>

            @endif

        </div>



        <!-- RIGHT -->
        <div class="flex items-center gap-4">

            {{-- USER NAME --}}
            <span class="text-gray-600 font-medium">
                {{ Auth::user()->name }}
            </span>


            {{-- LOGOUT --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow-sm">
                    Logout
                </button>
            </form>

        </div>

    </div>

</nav>