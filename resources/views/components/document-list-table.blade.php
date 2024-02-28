@props([
    'columns' => '',
    'data' => '',
])

<div class="overflow-x-auto">
    <table class="table table-lg">
        <thead>
            <tr class="font-bold text-xl text-center">
                <th></th>
                @foreach($columns as $column)
                    <th>{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr class="hover text-center font-bold">
                    <th>{{ $loop->iteration }}</th>
                    <td>{{ $row['id'] }}</td>
                    <td>{{ $row['documentum_type'] }}</td>
                    <td>{{ $row['partner_name'] }}</td>
                    <td>{{ $row['total'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
