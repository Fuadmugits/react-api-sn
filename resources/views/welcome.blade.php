<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjualan API Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4F46E5;
            --primary-hover: #4338CA;
            --bg-color: #0F172A;
            --card-bg: rgba(30, 41, 59, 0.7);
            --text-main: #F8FAFC;
            --text-muted: #94A3B8;
            --border-color: rgba(255, 255, 255, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            min-height: 100vh;
            padding: 2rem;
            background-image: radial-gradient(circle at top right, #1E1B4B, transparent 40%),
                              radial-gradient(circle at bottom left, #0F172A, transparent 40%);
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(to right, #818CF8, #C084FC);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .header p {
            color: var(--text-muted);
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
        }

        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        .form-control {
            width: 100%;
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            color: var(--text-main);
            outline: none;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
        }

        .btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s ease, transform 0.1s ease;
        }

        .btn:hover {
            background: var(--primary-hover);
        }

        .btn:active {
            transform: scale(0.98);
        }

        .product-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .product-item {
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid var(--border-color);
            padding: 1rem;
            border-radius: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.3s ease;
        }

        .product-item:hover {
            transform: translateY(-2px);
            border-color: rgba(255,255,255,0.2);
        }

        .product-info h3 {
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .product-info p {
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        .product-meta {
            text-align: right;
        }

        .product-price {
            font-weight: 700;
            color: #34D399;
        }

        .product-stock {
            font-size: 0.75rem;
            color: var(--text-muted);
            background: rgba(255,255,255,0.1);
            padding: 0.2rem 0.5rem;
            border-radius: 1rem;
            margin-top: 0.25rem;
            display: inline-block;
        }

        .loading {
            text-align: center;
            color: var(--text-muted);
            padding: 2rem;
        }

        .toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: #10B981;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
            transform: translateY(150%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .toast.show {
            transform: translateY(0);
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>API Penjualan</h1>
            <p>Frontend sederhana untuk melakukan uji coba (Testing) API Anda</p>
        </div>

        <div class="grid">
            <!-- Form Input -->
            <div class="card">
                <h2 class="card-title">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah Barang
                </h2>
                <form id="productForm">
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" id="name" class="form-control" required placeholder="Buku Tulis">
                    </div>
                    <div class="form-group">
                        <label>Deskripsi (Opsional)</label>
                        <textarea id="description" class="form-control" rows="2" placeholder="Buku tulis 38 lembar"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Harga (Rp)</label>
                        <input type="number" id="price" class="form-control" required min="1" placeholder="5000">
                    </div>
                    <div class="form-group">
                        <label>Stok Awal</label>
                        <input type="number" id="stock" class="form-control" required min="1" placeholder="100">
                    </div>
                    <button type="submit" class="btn" id="submitBtn">Simpan Barang via API</button>
                </form>
            </div>

            <!-- List Data -->
            <div class="card">
                <h2 class="card-title">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    Daftar Barang (Data dari API)
                </h2>
                <div id="productList" class="product-list">
                    <div class="loading">Memuat data dari API...</div>
                </div>
            </div>
        </div>
    </div>

    <div class="toast" id="toast">Berhasil disimpan!</div>

    <script>
        const API_URL = '/api/products';
        const productList = document.getElementById('productList');
        const productForm = document.getElementById('productForm');
        const submitBtn = document.getElementById('submitBtn');
        const toast = document.getElementById('toast');

        // Format angka ke Rupiah
        const formatRp = (number) => {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        };

        // Menampilkan Notifikasi
        const showToast = (message, isError = false) => {
            toast.textContent = message;
            toast.style.background = isError ? '#EF4444' : '#10B981';
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        };

        // Fetch data dari API
        const fetchProducts = async () => {
            try {
                const response = await fetch(API_URL);
                const data = await response.json();
                
                if (data.length === 0) {
                    productList.innerHTML = '<div class="loading">Belum ada data barang. Silakan tambah data di samping.</div>';
                    return;
                }

                productList.innerHTML = '';
                data.forEach(product => {
                    const el = document.createElement('div');
                    el.className = 'product-item';
                    el.innerHTML = `
                        <div class="product-info">
                            <h3>${product.name}</h3>
                            <p>${product.description || 'Tidak ada deskripsi'}</p>
                        </div>
                        <div class="product-meta">
                            <div class="product-price">${formatRp(product.price)}</div>
                            <div class="product-stock">Sisa Stok: ${product.stock}</div>
                        </div>
                    `;
                    productList.appendChild(el);
                });
            } catch (error) {
                productList.innerHTML = '<div class="loading" style="color:#EF4444">Gagal mengambil data dari API</div>';
            }
        };

        // Mengirim data ke API
        productForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            submitBtn.textContent = 'Menyimpan...';
            submitBtn.disabled = true;

            const payload = {
                name: document.getElementById('name').value,
                description: document.getElementById('description').value,
                price: parseFloat(document.getElementById('price').value),
                stock: parseInt(document.getElementById('stock').value)
            };

            try {
                const response = await fetch(API_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                if (response.ok) {
                    showToast('Barang berhasil ditambahkan!');
                    productForm.reset();
                    fetchProducts(); // Refresh data
                } else {
                    const errData = await response.json();
                    showToast('Gagal: ' + (errData.message || 'Error validasi'), true);
                }
            } catch (error) {
                showToast('Terjadi kesalahan jaringan', true);
            } finally {
                submitBtn.textContent = 'Simpan Barang via API';
                submitBtn.disabled = false;
            }
        });

        // Load awal
        fetchProducts();
    </script>
</body>
</html>
