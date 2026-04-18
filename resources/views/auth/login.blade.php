<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center" style="height:100vh;">

<div class="container">
<div class="row justify-content-center">

<div class="col-md-4">
<div class="card shadow">

<div class="card-body">

<h4 class="text-center mb-4">Login E-Disposisi</h4>

<form method="POST" action="/login">
@csrf

<input type="email" name="email" class="form-control mb-3" placeholder="Email" required>

<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

<button class="btn btn-primary w-100">Login</button>

</form>

</div>
</div>
</div>

</div>
</div>

</body>
</html>