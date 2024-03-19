<?php include 'views/includes/header.php'; ?>

<div class="row">
    <div class="col">
        <h2>Edit Client</h2>
        <form action="/client/store" method="post">

            <input type="hidden" name="id" value="<?php echo $client['id']; ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $client['name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $client['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="billing_address" class="form-label">Billing Address</label>
                <textarea class="form-control" id="billing_address" name="billing_address" rows="3" required><?php echo $client['billing_address']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Client</button>
        </form>
    </div>
</div>

<?php include 'views/includes/footer.php'; ?>
