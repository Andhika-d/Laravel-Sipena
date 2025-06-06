<!DOCTYPE html>
<html>
<head>
    <title>Login - SIPENA</title>
</head>
<body>
    <h2>Login</h2>
    @if ($errors->any())
        <p style="color:red">{{ $errors->first() }}</p>
    @endif
    <form method="POST" action="/login">
        @csrf
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
