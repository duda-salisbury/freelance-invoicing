<?php include 'views/includes/header.php'; ?>

<div class="row">
    <div class="col">
        <h2>Create New Client</h2>
        <form action="/client/store/" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="billing_address" class="form-label">Billing Address</label>
                <textarea class="form-control" id="billing_address" name="billing_address" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Create Client</button>
        </form>
    </div>
</div>

<?php include 'views/includes/footer.php'; ?>
