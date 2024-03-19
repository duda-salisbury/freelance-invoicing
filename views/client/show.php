<?php include 'views/includes/header.php'; ?>

<div class="row">
    <div class="col">
        <h2>Client Details</h2>
        <table class="table">
            <tbody>
                <tr>
                    <th scope="row">ID</th>
                    <td><?php echo $client['id']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Name</th>
                    <td><?php echo $client['name']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Email</th>
                    <td><?php echo $client['email']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Billing Address</th>
                    <td><?php echo $client['billing_address']; ?></td>
                </tr>
            </tbody>
        </table>
        <a href="/client/edit/<?php echo $client['id']; ?>" class="btn btn-secondary">Edit</a>
        <a href="/client/destroy/<?php echo $client['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this client?')">Delete</a>
    </div>
</div>

<?php include 'views/includes/footer.php'; ?>
