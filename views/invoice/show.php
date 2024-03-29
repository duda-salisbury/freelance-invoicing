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

        <!-- date and due date -->
        <h2>Invoice Details</h2>
        <table class="table">
            <tbody>
                <tr>
                    <th scope="row">ID</th>
                    <td><?php echo $invoice->getId(); ?></td>
                </tr>
                <tr>
                    <th scope="row">Invoice Date</th>
                    <td><?php echo $invoice->invoiceDate; ?></td>
                </tr>
                <tr>
                    <th scope="row">Due Date</th>
                    <td><?php echo $invoice->dueDate; ?></td>
                </tr>
                <tr>
                    <th scope="row">Total</th>
                    <td><?php echo $invoice->total; ?></td>
                </tr>
            </tbody>
        </table>

        <h2> Invoice items </h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoiceItems as $item) : ?>
                    <tr>
                        <td><?php echo $item->description; ?></td>
                        <td><?php echo $item->quantity; ?></td>
                        <td><?php echo $item->unitPrice; ?></td>
                        <td><?php echo $item->getTotal(); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <!-- total -->
                    <td colspan="3">Total</td>
                    <td><?php echo $invoice->total; ?></td>
                </tr>
            </tfoot>
        </table>

    

    </div>
</div>

<?php include 'views/includes/footer.php'; ?>
