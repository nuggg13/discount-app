<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Perhitungan Diskon</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Kalkulator Diskon</h1>

        <!-- Tombol untuk beralih mode -->
        <div class="flex justify-center mb-4">
            <button id="singleDiscountBtn" class="px-4 py-2 bg-blue-500 text-white rounded-md mx-2 hover:bg-blue-600">
                Diskon Tunggal
            </button>
            <button id="doubleDiscountBtn" class="px-4 py-2 bg-gray-500 text-white rounded-md mx-2 hover:bg-gray-600">
                Diskon A% + B%
            </button>
        </div>

        <form id="discountForm" action="/calculate" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="mode" id="mode" value="single">

            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                <input type="number" name="price" id="price" value="{{ old('price', $price ?? '') }}"
                       class="mt-1 block w-full p-2 border rounded-md @error('price') border-red-500 @enderror"
                       required step="1" min="1">
                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="discount" class="block text-sm font-medium text-gray-700">Diskon A (%)</label>
                <input type="number" name="discount" id="discount" value="{{ old('discount', $discountPercent ?? '') }}"
                       class="mt-1 block w-full p-2 border rounded-md @error('discount') border-red-500 @enderror"
                       required step="1" min="0" max="100">
                @error('discount')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Input untuk Diskon B (hanya muncul di mode double) -->
            <div id="discountBSection" class="hidden">
                <label for="discount_b" class="block text-sm font-medium text-gray-700">Diskon B (%)</label>
                <input type="number" name="discount_b" id="discount_b" value="{{ old('discount_b', $discountBPercent ?? '') }}"
                       class="mt-1 block w-full p-2 border rounded-md @error('discount_b') border-red-500 @enderror"
                       step="1" min="0" max="100">
                @error('discount_b')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">
                Hitung
            </button>
        </form>

        @if(isset($totalPrice))
            <div class="mt-6 p-4 bg-gray-50 rounded-md">
                <h2 class="text-lg font-semibold">Hasil Perhitungan</h2>
                <p>Harga Awal: Rp {{ number_format($price, 0, ',', '.') }}</p>
                @if($mode === 'single')
                    <p>Diskon ({{ $discountPercent }}%): Rp {{ number_format($discountValue, 0, ',', '.') }}</p>
                @else
                    <p>Diskon A ({{ $discountPercent }}%): Rp {{ number_format($discountValue, 0, ',', '.') }}</p>
                    <p>Diskon B ({{ $discountBPercent }}%): Rp {{ number_format($discountBValue, 0, ',', '.') }}</p>
                @endif
                <p class="font-bold">Total Harga: Rp {{ number_format($totalPrice, 0, ',', '.') }}</p>
            </div>
        @endif
    </div>

    <script>
        const singleBtn = document.getElementById('singleDiscountBtn');
        const doubleBtn = document.getElementById('doubleDiscountBtn');
        const form = document.getElementById('discountForm');
        const modeInput = document.getElementById('mode');
        const discountBSection = document.getElementById('discountBSection');

        singleBtn.addEventListener('click', () => {
            modeInput.value = 'single';
            discountBSection.classList.add('hidden');
            singleBtn.classList.add('bg-blue-500', 'hover:bg-blue-600');
            singleBtn.classList.remove('bg-gray-500', 'hover:bg-gray-600');
            doubleBtn.classList.add('bg-gray-500', 'hover:bg-gray-600');
            doubleBtn.classList.remove('bg-blue-500', 'hover:bg-blue-600');
        });

        doubleBtn.addEventListener('click', () => {
            modeInput.value = 'double';
            discountBSection.classList.remove('hidden');
            doubleBtn.classList.add('bg-blue-500', 'hover:bg-blue-600');
            doubleBtn.classList.remove('bg-gray-500', 'hover:bg-gray-600');
            singleBtn.classList.add('bg-gray-500', 'hover:bg-gray-600');
            singleBtn.classList.remove('bg-blue-500', 'hover:bg-blue-600');
        });
    </script>
</body>
</html>