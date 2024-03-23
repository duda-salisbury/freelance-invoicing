<?php include 'views/includes/header.php'; ?>

<div class="row">
    <div class="col">
        <h2>Create New Invoice</h2>
        <!-- creating for a specific client -->
        <p>Creating invoice for: <?php echo $client['name']; ?></p>
        <!-- card with client details -->
        <div class="card my-5">
            <div class="card-body">
                <h5 class="card-title"><?php echo $client['name']; ?></h5>
                <h6 class="card-subtitle mb-2 text-muted"><?php echo $client['email']; ?></h6>
                <p class="card-text"><?php echo $client['billing_address']; ?></p>
            </div>
        </div>

        <!-- form for invoice details -->
        <form action="/invoice/store" method="post">
            <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
            <div class="form-group" id="invoice-details">
                <label for="invoice_date">Invoice Date</label>
                <input type="date" value="<?php echo date('Y-m-d'); ?>" name="invoice_date" class="form-control" required>
            </div>

            <div class="form-group my-3">
                <label for="due_date">Due Date</label>
                <input type="date" name="due_date" class="form-control" required>
            </div>

            <div class="form-group my-3">
                <label for="notes">Notes</label>
                <textarea name="notes" class="form-control"></textarea>
            </div>
            <div class="form-group my-3">
                <h3>Invoice Items</h3>
                <!-- table for items -->
                <table id="invoice-items-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="invoice-items">
                        <tr>
                            <td><input type="text" name="description[]" class="form-control" required></td>
                            <td><input type="number" name="quantity[]" class="form-control" required></td>
                            <td><input type="number" name="unit_price[]" class="form-control" required></td>
                            <td><input type="number" name="total[]" class="form-control" required readonly></td>
                        </tr>
                    </tbody>

                    <!-- button to add new row -->
                    <tfoot>
                        <tr>
                            <td colspan="4">
                                <button type="button" class="btn btn-primary" onclick="addRow()">Add Item</button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">Total</td>
                            <td><input type="number" name="total_amount" id="total_amount" class="form-control" required readonly></td>
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
            <td><input type="number" name="total[]" class="form-control" required readonly></td>
        `;
        tbody.appendChild(tr);
    }
</script>

<script>
    // Calculate subtotal and total amount
    document.addEventListener("input", function(event) {
        if (event.target.tagName === 'INPUT') {
            var quantity = event.target.parentNode.parentNode.querySelector("input[name='quantity[]']").value;
            var unit_price = event.target.parentNode.parentNode.querySelector("input[name='unit_price[]']").value;
            var subtotal = quantity * unit_price;
            event.target.parentNode.parentNode.querySelector("input[name='total[]']").value = subtotal;

            // Calculate total amount
            var total_amount = 0;
            document.querySelectorAll("input[name='total[]']").forEach(function(input) {
                total_amount += parseFloat(input.value);
            });
            document.getElementById("total_amount").value = total_amount;
        }
    });
</script>

<?php include 'views/includes/footer.php'; ?>