<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Perhitungan Diskon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
    --bg-color: #fff0f5;
    --container-bg: #ffffff;
    --input-bg: #ffffff;
    --input-border: #f8bbd0;
    --text-color: #4b006e;
    --label-color: #880e4f;
    --button-bg: #e91e63;
    --button-hover: #c2185b;
    --result-bg: #ffebef;
    --error-color: #ff1744;
    --secondary-button-bg: #f8bbd0;
    --secondary-button-hover: #f06292;
    --slider-track: #f8bbd0;
    --slider-thumb: #e91e63;
}

[data-theme="dark"] {
    --bg-color: #1a1a1a;
    --container-bg: #2c2c2c;
    --input-bg: #3a3a3a;
    --input-border: #616161;
    --text-color: #ffe6f0;
    --label-color: #f8bbd0;
    --button-bg: #ff4081;
    --button-hover: #f50057;
    --result-bg: #3b3b3b;
    --error-color: #ff5252;
    --secondary-button-bg: #757575;
    --secondary-button-hover: #9e9e9e;
    --slider-track: #757575;
    --slider-thumb: #ff4081;
}


        body, .container, input, button, .result, label, span {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        /* Styling untuk range slider */
        input[type="range"] {
            -webkit-appearance: none;
            width: 100%;
            height: 8px;
            background: var(--slider-track);
            border-radius: 5px;
            outline: none;
        }

        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 20px;
            height: 20px;
            background: var(--slider-thumb);
            border-radius: 50%;
            cursor: pointer;
        }

        input[type="range"]::-moz-range-thumb {
            width: 20px;
            height: 20px;
            background: var(--slider-thumb);
            border-radius: 50%;
            cursor: pointer;
            border: none;
        }

        input[type="range"]::-webkit-slider-thumb:hover {
            background: var(--button-hover);
        }

        input[type="range"]::-moz-range-thumb:hover {
            background: var(--button-hover);
        }
    </style>
</head>
<body class="bg-[var(--bg-color)] flex items-center justify-center h-screen">
    <div class="container bg-[var(--container-bg)] p-8 rounded-lg shadow-lg w-full max-w-md text-[var(--text-color)]">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-center">Kalkulator Diskon</h1>
            <button id="themeToggle" class="p-2 rounded-full bg-[var(--button-bg)] hover:bg-[var(--button-hover)] text-white" aria-label="Toggle dark mode" title="Ganti Tema">
                <span id="themeIcon">☀</span>
            </button>
        </div>

        <div class="flex justify-center mb-4">
            <button id="singleDiscountBtn" class="px-4 py-2 bg-[var(--button-bg)] text-white rounded-md mx-2 hover:bg-[var(--button-hover)]">
                Diskon Tunggal
            </button>
            <button id="doubleDiscountBtn" class="px-4 py-2 bg-[var(--secondary-button-bg)] text-white rounded-md mx-2 hover:bg-[var(--secondary-button-hover)]">
                Diskon A% + B%
            </button>
        </div>

        <form id="discountForm" action="/calculate" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="mode" id="mode" value="single">

            <div>
                <label for="price" class="block text-sm font-medium text-[var(--label-color)]">Harga (Rp)</label>
                <input type="number" name="price" id="price" value="{{ old('price', $price ?? '') }}"
                       class="mt-1 block w-full p-2 border rounded-md bg-[var(--input-bg)] border-[var(--input-border)] text-[var(--text-color)] {{ $errors->has('price') ? 'border-[var(--error-color)]' : '' }}"
                       required step="1" min="1">
                @error('price')
                    <p class="text-[var(--error-color)] text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="discount" class="block text-sm font-medium text-[var(--label-color)]">Diskon A (%)</label>
                <div class="flex items-center space-x-4">
                    <input type="range" name="discount" id="discount" value="{{ old('discount', $discountPercent ?? '1') }}"
                           min="1" max="100" step="1"
                           class="mt-1 w-full"
                           required>
                    <span id="discountValue" class="text-[var(--text-color)]">{{ old('discount', $discountPercent ?? '1') }}%</span>
                </div>
                @error('discount')
                    <p class="text-[var(--error-color)] text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="discountBSection" class="hidden">
                <label for="discount_b" class="block text-sm font-medium text-[var(--label-color)]">Diskon B (%)</label>
                <div class="flex items-center space-x-4">
                    <input type="range" name="discount_b" id="discount_b" value="{{ old('discount_b', $discountBPercent ?? '1') }}"
                           min="1" max="100" step="1"
                           class="mt-1 w-full">
                    <span id="discountBValue" class="text-[var(--text-color)]">{{ old('discount_b', $discountBPercent ?? '1') }}%</span>
                </div>
                @error('discount_b')
                    <p class="text-[var(--error-color)] text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-[var(--button-bg)] text-white p-2 rounded-md hover:bg-[var(--button-hover)]">
                Hitung
            </button>
        </form>

        @if(isset($totalPrice))
            <div class="result mt-6 p-4 bg-[var(--result-bg)] rounded-md">
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
        document.addEventListener('DOMContentLoaded', () => {
            // Dark Mode Toggle
            const html = document.documentElement;
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');

            if (!themeToggle || !themeIcon) {
                console.error('Elemen tidak ditemukan:', {
                    themeToggle: !!themeToggle,
                    themeIcon: !!themeIcon
                });
                return;
            }

            function setTheme(theme) {
                console.log('Mengatur tema:', theme);
                html.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
                themeIcon.textContent = theme === 'dark' ? '☀' : '🌙';
                console.log('Atribut data-theme:', html.getAttribute('data-theme'));
            }

            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            setTheme(savedTheme || (prefersDark ? 'dark' : 'light'));

            themeToggle.addEventListener('click', () => {
                console.log('Tombol themeToggle diklik');
                const currentTheme = html.getAttribute('data-theme');
                setTheme(currentTheme === 'dark' ? 'light' : 'dark');
            });

            // Mode Diskon Toggle
            const singleBtn = document.getElementById('singleDiscountBtn');
            const doubleBtn = document.getElementById('doubleDiscountBtn');
            const form = document.getElementById('discountForm');
            const modeInput = document.getElementById('mode');
            const discountBSection = document.getElementById('discountBSection');

            if (singleBtn && doubleBtn && form && modeInput && discountBSection) {
                singleBtn.addEventListener('click', () => {
                    modeInput.value = 'single';
                    discountBSection.classList.add('hidden');
                    singleBtn.classList.add('bg-[var(--button-bg)]', 'hover:bg-[var(--button-hover)]');
                    singleBtn.classList.remove('bg-[var(--secondary-button-bg)]', 'hover:bg-[var(--secondary-button-hover)]');
                    doubleBtn.classList.add('bg-[var(--secondary-button-bg)]', 'hover:bg-[var(--secondary-button-hover)]');
                    doubleBtn.classList.remove('bg-[var(--button-bg)]', 'hover:bg-[var(--button-hover)]');
                });

                doubleBtn.addEventListener('click', () => {
                    modeInput.value = 'double';
                    discountBSection.classList.remove('hidden');
                    doubleBtn.classList.add('bg-[var(--button-bg)]', 'hover:bg-[var(--button-hover)]');
                    doubleBtn.classList.remove('bg-[var(--secondary-button-bg)]', 'hover:bg-[var(--secondary-button-hover)]');
                    singleBtn.classList.add('bg-[var(--secondary-button-bg)]', 'hover:bg-[var(--secondary-button-hover)]');
                    singleBtn.classList.remove('bg-[var(--button-bg)]', 'hover:bg-[var(--button-hover)]');
                });
            } else {
                console.error('Elemen diskon tidak ditemukan:', {
                    singleBtn: !!singleBtn,
                    doubleBtn: !!doubleBtn,
                    form: !!form,
                    modeInput: !!modeInput,
                    discountBSection: !!discountBSection
                });
            }

            // Range Slider Value Display
            const discountSlider = document.getElementById('discount');
            const discountValue = document.getElementById('discountValue');
            const discountBSlider = document.getElementById('discount_b');
            const discountBValue = document.getElementById('discountBValue');

            if (discountSlider && discountValue) {
                discountSlider.addEventListener('input', () => {
                    discountValue.textContent = ${discountSlider.value}%;
                });
            }

            if (discountBSlider && discountBValue) {
                discountBSlider.addEventListener('input', () => {
                    discountBValue.textContent = ${discountBSlider.value}%;
                });
            }
        });
    </script>
</body>
</html>