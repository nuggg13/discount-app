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
        <form action="/calculate" method="POST" class="space-y-4">
            @csrf
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
                <label for="discount" class="block text-sm font-medium text-gray-700">Diskon (%)</label>
                <input type="number" name="discount" id="discount" value="{{ old('discount', $discountPercent ?? '') }}" 
                       class="mt-1 block w-full p-2 border rounded-md @error('discount') border-red-500 @enderror" 
                       required step="1" min="0" max="100">
                @error('discount')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">
                HITUNG
            </button>
        </form>

        @if(isset($totalPrice))
            <div class="mt-6 p-4 bg-gray-50 rounded-md">
                <h2 class="text-lg font-semibold">Hasil Perhitungan</h2>
                <p>Harga Awal: Rp {{ number_format($price, 0, ',', '.') }}</p>
                <p>Diskon ({{ $discountPercent }}%): Rp {{ number_format($discountValue, 0, ',', '.') }}</p>
                <p class="font-bold">Total Harga: Rp {{ number_format($totalPrice, 0, ',', '.') }}</p>
            </div>
        @endif
    </div>
</body>
</html>