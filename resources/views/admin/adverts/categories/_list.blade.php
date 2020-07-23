<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Slug</th>
    </tr>
    </thead>
    <tbody>
    @foreach($categories as $category)
        <tr>
            <td>{{ $category->id }}</td>
            <td><a href="{{ route('admin.adverts.categories.show', $category) }}">{{ $category->name }}</a></td>
            <td>{{ $category->slug }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
