<!DOCTYPE html>
<html>
<body>
<h1>upload doc placeholder page</h1>

@if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

<form action="{{ route('document.reader.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>Choose document:</label>
    <br>
    <input type="file" name="document" required>
    <br><br>

    <button type="submit">Upload Document</button>
</form>

<hr>

</body>
</html><?php
