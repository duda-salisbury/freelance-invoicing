<?php include 'views/includes/header.php'; ?>


<!-- list of links: Add/Edit Clients, Create Invoice, Edit Profile, Manaeg Invoices -->

<div class="row">
    <div class="col">
        <h2>Clients</h2>
        <a href="/client/list" class="btn btn-primary">View Clients</a>
        <a href="/client/create" class="btn btn-primary">Add Client</a>
    </div>
</div>


<div class="row">
    <div class="col">
        <h2>Invoices</h2>
        <a href="/invoice/create" class="btn btn-primary">Create Invoice</a>
        <a href="/invoice/list" class="btn btn-primary">Manage Invoices</a>
    </div>

    <div class="col">
        <h2>Profile</h2>
        <a href="/user/edit" class="btn btn-primary">Edit Profile</a>
    </div>

</div>


<?php include 'views/includes/footer.php'; ?>