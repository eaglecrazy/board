<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>Наименование</th>
        <th>ЧПУ</th>
    </tr>
    </thead>
    <tbody>
    @foreach($regions as $region)
        <tr>
            <td>{{ $region->id }}</td>
            <td><a href="{{ route('admin.regions.show', $region) }}">{{ $region->name }}</a></td>
            <td>{{ $region->slug }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
