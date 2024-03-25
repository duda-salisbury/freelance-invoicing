<?php include 'views/includes/header.php'; ?>


<!-- list of links: Add/Edit Clients, Create Invoice, Edit Profile, Manage Invoices, in a simple UL veritcal menu list of links -->

<div class="row">
    <div class="col">
        <h2>Dashboard</h2>
        <ul class="list-group my-4">
            <li class="list-group-item"><a href="/client/list">Clients</a></li>
            <li class="list-group-item"><a href="/invoice/create">Create Invoice</a></li>
            <li class="list-group-item"><a href="/profile/edit">Edit Profile</a></li>
            <li class="list-group-item"><a href="/invoice/list">Manage Invoices</a></li>
        </ul>
    </div>
</div>







<?php include 'views/includes/footer.php'; ?>