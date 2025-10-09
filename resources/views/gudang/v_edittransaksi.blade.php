<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaksi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #F1F8E9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: #333;
        }

        .container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            padding: 30px;
            box-sizing: border-box;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .icon-header {
            font-size: 48px;
            color: #2E7D32;
            margin-bottom: 15px;
        }

        header h1 {
            color: #1B5E20;
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }

        header p {
            color: #4CAF50;
            margin-top: 5px;
            font-size: 16px;
        }

        .form-container { padding: 0 20px; }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2E7D32;
        }

        .form-control {
            width: calc(100% - 40px);
            padding: 12px 15px 12px 40px;
            border: 1px solid #C8E6C9;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-control:focus {
            border-color: #2E7D32;
            box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.25);
            outline: none;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 40px;
            color: #66bb6a;
            font-size: 16px;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 90px;
            padding-top: 10px;
        }

        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .radio-option {
            background-color: #f5f5f5;
            border: 1px solid #C8E6C9;
            border-radius: 8px;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.2s, border-color 0.2s;
            flex: 1;
            min-width: 180px;
        }

        .radio-option input[type="radio"] {
            display: none;
        }

        .radio-option .checkmark {
            width: 20px;
            height: 20px;
            border: 2px solid #2E7D32;
            border-radius: 50%;
            display: inline-block;
            position: relative;
            margin-right: 12px;
        }

        .radio-option input[type="radio"]:checked + .checkmark:after {
            content: '';
            width: 10px;
            height: 10px;
            background-color: #2E7D32;
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .btn {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #2E7D32;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            margin-top: 30px;
        }

        .btn:hover { background-color: #1B5E20; transform: translateY(-2px); }
        .btn-cancel { background-color: #9E9E9E; margin-top: 15px; }
    </style>
</head>
<body>
<div class="container">
    <header>
        <div class="icon-header">
            <i class="fas fa-edit"></i>
        </div>
        <h1>Edit Transaksi</h1>
        <p>Ubah data transaksi dengan benar</p>
    </header>

    <div class="form-container">
        <form action="{{ route('transaksi.update', $transaksi->id_transaksi) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Jenis Transaksi</label>
                <div class="radio-group">
                    <label class="radio-option">
                        <input type="radio" name="jenis" value="pemasukan" {{ $transaksi->jenis == 'pemasukan' ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        <span><i class="fas fa-arrow-circle-down income-icon"></i> Pemasukan</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="jenis" value="pengeluaran" {{ $transaksi->jenis == 'pengeluaran' ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        <span><i class="fas fa-arrow-circle-up expense-icon"></i> Pengeluaran</span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label>Deskripsi Transaksi</label>
                <i class="fas fa-align-left input-icon"></i>
                <input type="text" class="form-control" name="transaksi" value="{{ $transaksi->transaksi }}" required>
            </div>

            <div class="form-group">
                <label>Jumlah (Rp)</label>
                <i class="fas fa-coins input-icon"></i>
                <input type="number" class="form-control" name="jumlah" value="{{ $transaksi->jumlah }}" min="0" required>
            </div>

            <div class="form-group">
                <label>Keterangan Tambahan</label>
                <textarea class="form-control" name="keterangan" placeholder="Tambahkan catatan">{{ $transaksi->keterangan }}</textarea>
            </div>

            <button type="submit" class="btn"><i class="fas fa-save"></i> Update Transaksi</button>
            <a href="{{ route('admin.transaksi.index') }}" class="btn btn-cancel"><i class="fas fa-arrow-left"></i> Kembali</a>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('editTransactionForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const btn = form.querySelector('button[type="submit"]');

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    fetch(form.action, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: new FormData(form)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data transaksi berhasil diperbarui!',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = "{{ route('admin.transaksi.index') }}";
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message || 'Terjadi kesalahan.',
            });
        }
    })
    .catch(err => {
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan!',
            text: err.message,
        });
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Update Transaksi';
    });
});
</script>
</body>
</html>
