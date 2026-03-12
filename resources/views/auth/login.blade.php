<x-layouts.app title="Вхід">
    <div class="auth-wrap">
        <div class="card">
            <h1>Вхід до системи</h1>

            @if ($errors->any())
                <div class="error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="you@example.com" required autofocus>
                </div>

                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password" placeholder="••••••" required>
                </div>

                <button type="submit" class="btn">Увійти</button>
            </form>

            <div class="auth-link">
                Немає акаунту? <a href="{{ route('register') }}">Зареєструватися</a>
            </div>
        </div>
    </div>
</x-layouts.app>
