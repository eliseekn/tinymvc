<div class="d-flex align-items-center justify-content-center min-vh-100">
	<div class="container" style="width: 400px">
        <h1 class="display-4 py-3 text-center">Login</h1>

        <div class="card shadow p-5">
            <form method="post" action="<?= absolute_url('admin/login') ?>">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Username" class="form-control" required>
                </div>

                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-lg btn-dark">Submit</button>
            </form>
        </div>
    </div>
</div>