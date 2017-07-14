<table class="table table-bordered alternate">
    <thead>
            <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>IC/Passport</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Status</th>
            </tr>
    </thead>
    <tbody>
    @foreach ($users as $user)
            <tr class="info">
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>'{{ $user->username }}'</td>
                    <td>{{ $user->email }}</td>
                    <td>'{{ $user->PhoneNumber }}'</td>
                    <td>{{ ($user->isRedeemed == 1) ? "Redeemed at " . date('d F Y H:i:s', strtotime($user->RedeemedDate)) : "N/A" }}</td>
            </tr>
    @endforeach
    </tbody>
</table>