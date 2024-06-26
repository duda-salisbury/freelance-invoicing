<?php include 'views/includes/header.php'; ?>

<div class="row">
    <div class="col">
        <!-- form for invoice details -->
        <form action="/invoice/store" method="post">

            <h2>Create New Invoice</h2>
            <!-- creating for a specific client -->
            <p>Creating invoice for: <?php echo $client->name; ?></p>
            <?php // if there is a $client, show the client details
            if ($client->getId() > 0) : ?>
                <input type="hidden" name="client_id" value="<?php echo $client->getId(); ?>">
            <!-- card with client details -->
            <div class="card my-5">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $client->name; ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $client->email; ?></h6>
                    <p class="card-text"><?php echo $client->billingAddress; ?></p>

                </div>
            </div>
            <?php endif;
            // if there is no client, show a select dropdown to choose a client
            if ($client->getId() == 0) : ?>
                <div class="form-group my-2">
                <label for="client_id">Select Client</label>
                <select name="client_id" class="form-control" required>
                    <option value="">Select Client</option>
                    <?php foreach ($clients as $client) : ?>
                        <option value="<?php echo $client->getId(); ?>"><?php echo $client->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>


            <div class="form-group" id="invoice-details">
                <label for="invoice_date">Invoice Date</label>
                <input type="date" value="<?php echo $invoice->invoiceDate;
                ?>" name="invoice_date" class="form-control" required>
            </div>

            <div class="form-group my-3">
                <label for="due_date">Due Date</label>
                <input type="date" name="due_date" 
                value = "<?php echo $invoice->dueDate; ?>"
                class="form-control" required>
            </div>

            <input type="hidden" name="id" value="<?php echo $invoice->getId(); ?>">

            <div class="form-group my-3">
                <label for="notes">Notes</label>
                <textarea name="notes" class="form-control"></textarea>
            </div>
            <div class="form-group my-3">
                <h3>Invoice Items</h3>
                <!-- table for items -->
                <table class="table table-bordered table-striped" id="invoice-items-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Subtotal</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="invoice-items">
                        <?php $invoiceItems = $invoice->getItems(); ?>
                        <?php foreach ($invoiceItems as $item) : ?>
                            <tr>
                                <!-- hidden input for item id -->
                                <input type="hidden" name="item_id[]" value="<?php echo $item->getId(); ?>">
                                <td><input type="text" name="description[]" class="form-control" value="<?php echo $item->description; ?>" required></td>
                                <td><input type="number" name="quantity[]" class="form-control" value="<?php echo $item->quantity; ?>" required></td>
                                <td><input type="number" name="unit_price[]" class="form-control" value="<?php echo $item->unitPrice; ?>" required></td>
                                <td>
                                    <span class="subtotal"><?php echo $item->getTotal(); ?></span>
                                </td>
                                <td> <button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                    <!-- button to add new row -->
                    <tfoot>
                        <tr>

                            <td colspan="5" align="right">
                                <button type="button" class="btn btn-primary" onclick="addRow()">Add Item</button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">Total</td>
                            <td><input type="number" 
                            value="<?php echo $invoice->total; ?>"
                            
                            name="total_amount" id="total_amount" class="form-control" required readonly></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <button type="submit" class="btn btn-primary">Save Invoice</button>
        </form>
    </div>
</div>

<script>
    // add new row to the tbody (no jQuery)
    function addRow() {
        var tbody = document.getElementById('invoice-items');
        var tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" name="description[]" class="form-control" required></td>
            <td><input type="number" name="quantity[]" class="form-control" required></td>
            <td><input type="number" name="unit_price[]" class="form-control" required></td>
            <td>
                <span class="subtotal">0.00</span>
            </td>
            <td> <button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
        `;
        tbody.appendChild(tr);
    }

    function removeRow(button) {
        var tr = button.closest('tr');
        tr.remove();

        // also recalculate the total
        var subtotals = Array.from(document.querySelectorAll('.subtotal'));
        var total = subtotals.reduce((acc, subtotal) => acc + parseFloat(subtotal.textContent), 0);
        document.getElementById('total_amount').value = total.toFixed(2);

    }
</script>

<script>
    // calculate subtotal and total when an input changes
    document.getElementById('invoice-items-table').addEventListener('input', function(e) {
        var target = e.target;
        if (target.tagName === 'INPUT') {
            var tr = target.closest('tr');
            var quantity = tr.querySelector('input[name="quantity[]"]').value;
            var unitPrice = tr.querySelector('input[name="unit_price[]"]').value;
            var subtotal = quantity * unitPrice;
            tr.querySelector('.subtotal').textContent = subtotal.toFixed(2);

            var subtotals = Array.from(document.querySelectorAll('.subtotal'));
            var total = subtotals.reduce((acc, subtotal) => acc + parseFloat(subtotal.textContent), 0);
            document.getElementById('total_amount').value = total.toFixed(2);
        }
    });
</script>

<?php include 'views/includes/footer.php'; ?>