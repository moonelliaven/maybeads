<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk</title>
    <!-- Perbaikan versi font-awesome ke versi stabil 6.4.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Perbaikan selector dari - menjadi * */
        * {
            font-family: 'Poppins', sans-serif;
        }
        h1 {
            font-size: 30px;
            text-align: center;
        }
        body {
            background-color: #f7f7f7;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px auto;
        }
        thead {
            background-color: #d1defb;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        tr td, tr th {
            padding: 10px;
            text-align: left;
        }
        i {
            color: black;
            width: 20px;
            background: none;
        }
        .btn-primary {
            background-color: rgb(64, 92, 159);
            color: white; /* Perbaikan typo white;z */
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Dashboard</h2>
            <h1>Data Produk</h1>
            <a href="{{ route('admin.product.create') }}" class="btn-primary">Tambah Produk</a>
        </div>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Kategori ID</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Deskripsi</th>
                    <th>Dibuat Pada</th>
                    <th colspan="2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="Gambar" width="50">
                            @else
                                Tidak ada
                            @endif
                        </td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->category_id }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->description ?? '-' }}</td>
                        <td>{{ $product->created_at }}</td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="#" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="#" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button style="background: none; border: none; cursor: pointer;" type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                        <i class="fas fa-trash" style="color: red;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>