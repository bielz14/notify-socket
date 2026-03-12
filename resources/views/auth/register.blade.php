<x-layouts.app title="Реєстрація">
    <div class="auth-wrap">
        <div class="card">
            <h1>Реєстрація</h1>

            @if ($errors->any())
                <div class="error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label>Ім'я</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           placeholder="Ваше ім'я" required autofocus>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="you@example.com" required>
                </div>

                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password" placeholder="Мін. 6 символів" required>
                </div>

                <div class="form-group">
                    <label>Підтвердження пароля</label>
                    <input type="password" name="password_confirmation" placeholder="••••••" required>
                </div>

                <button type="submit" class="btn">Створити акаунт</button>
            </form>

            <div class="auth-link">
                Вже є акаунт? <a href="{{ route('login') }}">Увійти</a>
            </div>
        </div>
    </div>
</x-layouts.app>
