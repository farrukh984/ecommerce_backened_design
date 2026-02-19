<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <input type="email" name="email" value="{{ old('email') }}" required>
    <button type="submit">Send Reset Link</button>
</form>
