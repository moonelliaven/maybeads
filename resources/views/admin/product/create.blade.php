<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maybeads</title>
        <style>
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
        tr td {
            padding: 0 0 10px 10px;
        }
        i {
            color: black;
            width: 20px;
            background: none;

        }

        .btn-primary {
            background-color: rgb(64, 92, 159);
            color: white;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        form {
            width: 100%;
            margin: 0 auto;
            paddng: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
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
        <h1>Tambah Produk</h1>

        <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label for="category_id">Kategori:</label>
            <select name="category_id" id="category_id" required>
                <option value="">Pilih Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </select>
            <br><br>

            <label for="product_name">Nama Produk:</label>
            <input type="text" name="product_name" id="product_name" maxlength="100" required>
            <br><br>

            <label for="image">Gambar Produk:</label>
            <input type="file" name="image" id="image" accept="image/*">
            <br><br>

            <label for="description">Deskripsi:</label>
            <textarea name="description" id="description"></textarea>
            <br><br>

            <label for="price">Harga:</label>
            <input type="text" name="price" id="price" maxlength="100" required>
            <br><br>

            <label for="stock">Stok:</label>
            <input type="number" name="stock" id="stock" min="0" value="0" required>
            <br><br>

            <button type="submit" class="btn-primary">Simpan</button>
            <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>