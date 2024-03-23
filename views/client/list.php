<?php include 'views/includes/header.php'; ?>



<div class="row">
    <div class="col">
        <?php if (!empty($clients)): ?>
        <h2>Clients</h2>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Billing Address</th>
                    <th scope="col">Invoice</th>
                    <th scope="col">Other</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                <tr>
                    <th scope="row"><?php echo $client['id']; ?></th>
                    <td><?php echo $client['name']; ?></td>
                    <td><?php echo $client['email']; ?></td>
                    <td><?php echo $client['billing_address']; ?></td>
                    <td>
                        <!-- create invoice -->
                        <a href="/invoice/create/<?php echo $client['id']; ?>" class="btn btn-success btn-sm">Create Invoice</a>
                        <a href="/client/show/<?php echo $client['id']; ?>" class="btn btn-primary btn-sm">View Client</a>
                    </td>
                    <td>
                        <a href="/client/edit/<?php echo $client['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="/client/destroy/<?php echo $client['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                No clients found. <a href="/client/create">Add a new client</a>
            </div>
        <?php endif; ?>
        <a href="/client/create" class="btn btn-success">Add New Client</a>
    </div>
</div>

<?php include 'views/includes/footer.php'; ?>
