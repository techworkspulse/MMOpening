<table class="table table-bordered alternate">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>IC/Passport</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr class="info">
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->username }}</td>
            <td>{{ ($user->isRedeemed == 1) ? "Redeemed" : "N/A" }}</td>
        </tr>
        @endforeach
    </tbody>
</table>