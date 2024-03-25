<?php include 'views/includes/header.php'; ?>

<div class="row">
    <div class="col">
        <h2>Client Details</h2>
        <table class="table">
            <tbody>
                <tr>
                    <th scope="row">ID</th>
                    <td><?php echo $client->getId(); ?></td>
                </tr>
                <tr>
                    <th scope="row">Name</th>
                    <td><?php echo $client->name; ?></td>
                </tr>
                <tr>
                    <th scope="row">Email</th>
                    <td><?php echo $client->email; ?></td>
                </tr>
                <tr>
                    <th scope="row">Billing Address</th>
                    <td><?php echo $client->billingAddress; ?></td>
                </tr>
            </tbody>
        </table>
        <a href="/client/edit/<?php echo $client->getId(); ?>" class="btn btn-secondary">Edit</a>
        <a href="/client/destroy/<?php echo $client->getId(); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this client?')">Delete</a>
    </div>
</div>

<h2>Invoices For <?php echo $client->name; ?></h2>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Invoice Date</th>
            <th>Due Date</th>
            <th>Total</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($invoices as $invoice): ?>
            <tr>
                <td><?php echo $invoice['id']; ?></td>
                <td><?php echo $invoice['invoice_date']; ?></td>
                <td><?php echo $invoice['due_date']; ?></td>
                <td><?php echo $invoice['total']; ?></td>
                <td>
                    <a href="/invoice/show/<?php echo $invoice['id']; ?>" class="btn btn-primary">View</a>
                    <a href="/invoice/edit/<?php echo $invoice['id']; ?>" class="btn btn-secondary">Edit</a>
                    <a href="/invoice/destroy/<?php echo $invoice['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this invoice?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'views/includes/footer.php'; ?>
