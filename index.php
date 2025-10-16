<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Angkringan Modern Responsif & Cetak</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            padding: 10px; 
            margin: 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: #333;
        }
        h2, h3 { margin-top: 15px; text-align: center; }
        input[type=text], input[type=number] { padding: 8px; border-radius: 6px; border: 1px solid #ccc; margin: 5px 0; width: 100%; max-width: 300px; box-sizing: border-box; }
        button { padding: 8px 12px; border-radius: 6px; cursor: pointer; border: none; font-size: 1em; }
        .tab { display: flex; gap: 5px; flex-wrap: wrap; justify-content: center; margin-bottom: 10px; }
        .tab button { background-color: #4a90e2; color: white; }
        .tab button.active { background-color: #357ab7; }
        #menuTab { display: flex; flex-wrap: nowrap; overflow-x: auto; gap: 10px; padding-bottom: 10px; }
        .menu-card { background-color: #ffffffcc; padding: 15px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); min-width: 160px; cursor: pointer; text-align: center; flex: 0 0 auto; transition: transform 0.2s; }
        .menu-card:hover { transform: scale(1.05); }
        table { border-collapse: collapse; width: 100%; background-color: #ffffffcc; border-radius: 12px; overflow: hidden; margin-bottom: 10px; display: block; overflow-x: auto; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; white-space: nowrap; }
        .hapus-btn { background-color: #e74c3c; color: white; padding: 4px 8px; border-radius: 6px; }
        .ubah-btn { background-color: #f39c12; color: white; padding: 4px 8px; border-radius: 6px; margin: 0 2px;}
        #resetBtn { background-color: #e67e22; color: white; padding: 8px 12px; border-radius: 6px; display: block; margin: 10px auto; width: 150px; }
        #printBtn { background-color: #27ae60; color: white; padding: 8px 12px; border-radius: 6px; display: block; margin: 10px auto; width: 150px; }
        @media (max-width: 600px) {
            .menu-card { min-width: 120px; padding: 10px; font-size: 0.9em; }
            button { font-size: 0.9em; }
        }
        @media print {
            body { background: #fff; color: #000; }
            input, button { display: none; }
        }
    </style>
</head>
<body>

<h2>Angkringan Modern Responsif</h2>

<div style="text-align:center;">
    <input type="text" id="namaPemesan" placeholder="Nama Pemesan">
    <input type="text" id="namaKasir" placeholder="Nama Kasir">
</div>

<h3>Cari Menu:</h3>
<input type="text" id="searchMenu" placeholder="Ketik nama menu..." oninput="filterMenu()">

<div id="menuTab"></div>

<h3>Detail Pesanan</h3>
<p>Pemesan: <span id="tampilPemesan">-</span></p>
<p>Kasir: <span id="tampilKasir">-</span></p>

<table id="tabelPesanan">
    <tr>
        <th>Menu</th>
        <th>Harga</th>
        <th>Jumlah</th>
        <th>Subtotal</th>
        <th>Aksi</th>
    </tr>
</table>

<h3>Total: Rp<span id="totalHarga">0</span></h3>

<div style="text-align:center;">
    <input type="number" id="uangPelanggan" placeholder="Uang Pelanggan">
    <button onclick="hitungKembalian()">Hitung Kembalian</button>
</div>

<h3>Kembalian: Rp<span id="kembalian">0</span></h3>

<button id="resetBtn" onclick="resetPesanan()">🗑 Hapus Semua Pesanan</button>
<button id="printBtn" onclick="cetakPesanan()">🖨 Cetak Pesanan</button>

<script>
let pesanan = {};

const semuaMenu = [
    {nama: "Nasi Bakar Ayam Kemangi", harga: 7000},
    {nama: "Nasi Bakar Cumi Pedas", harga: 8000},
    {nama: "Nasi Kucing Sambal Teri", harga: 5000},
    {nama: "Indomie Rebus + Telur", harga: 10000},
    {nama: "Indomie Goreng + Telur", harga: 10000},
    {nama: "Sate Usus Ayam", harga: 3000},
    {nama: "Sate Ati Ampela", harga: 3000},
    {nama: "Sate Telur Puyuh", harga: 3000},
    {nama: "Sate Kulit Ayam", harga: 3000},
    {nama: "Sate Bakso", harga: 3000},
    {nama: "Sate Sosis", harga: 3000},
    {nama: "Bakwan Sayur", harga: 2000},
    {nama: "Tahu Isi Pedas", harga: 2000},
    {nama: "Tempe Mendoan", harga: 2000},
    {nama: "Kepala Ayam Bakar", harga: 5000},
    {nama: "Ceker Ayam Bakar", harga: 3000},
    {nama: "Kerupuk", harga: 1000},
    {nama: "Kopi Hitam", harga: 4000},
    {nama: "Kopi Susu", harga: 4000},
    {nama: "Good Day", harga: 5000},
    {nama: "Torabika Cappuccino", harga: 5000},
    {nama: "Luwak White Koffie", harga: 5000},
    {nama: "Chocolatos / Beng-Beng", harga: 5000},
    {nama: "Milo", harga: 5000},
    {nama: "Susu Kental Manis", harga: 4000},
    {nama: "Susu Jahe", harga: 4000},
    {nama: "Teh Manis Panas", harga: 3000},
    {nama: "Es Teh Manis", harga: 4000},
    {nama: "Es Kopi Saset", harga: 5000},
    {nama: "Es Good Day Cappuccino", harga: 6000},
    {nama: "Es Cokelat (Chocolatos, Milo)", harga: 6000},
    {nama: "Es Susu Kental Manis", harga: 5000},
    {nama: "Es Jasjus / Marimas", harga: 3000},
    {nama: "Minuman Energi + Es", harga: 5000}
];

function renderMenu(menuList){
    const menuTab = document.getElementById('menuTab');
    menuTab.innerHTML = '';
    menuList.forEach(item => {
        const div = document.createElement('div');
        div.classList.add('menu-card');
        div.innerHTML = `<strong>${item.nama}</strong><br>Rp${item.harga.toLocaleString()}`;
        div.onclick = () => tambahPesanan(item.nama, item.harga);
        menuTab.appendChild(div);
    });
}

function filterMenu(){
    const keyword = document.getElementById('searchMenu').value.toLowerCase();
    const filtered = semuaMenu.filter(item => item.nama.toLowerCase().includes(keyword));
    renderMenu(filtered);
}

function tambahPesanan(menu, harga){
    if(pesanan[menu]) pesanan[menu].jumlah += 1;
    else pesanan[menu] = {harga: harga, jumlah:1};
    updateDisplay();
}

function updateDisplay(){
    const tabel = document.getElementById('tabelPesanan');
    tabel.innerHTML = `<tr>
        <th>Menu</th>
        <th>Harga</th>
        <th>Jumlah</th>
        <th>Subtotal</th>
        <th>Aksi</th>
    </tr>`;
    let total = 0;
    for(let menu in pesanan){
        const item = pesanan[menu];
        const subtotal = item.harga * item.jumlah;
        total += subtotal;
        const row = tabel.insertRow();
        row.insertCell(0).innerText = menu;
        row.insertCell(1).innerText = `Rp${item.harga.toLocaleString()}`;
        row.insertCell(2).innerText = item.jumlah;
        row.insertCell(3).innerText = `Rp${subtotal.toLocaleString()}`;
        const aksiCell = row.insertCell(4);

        const btnTambah = document.createElement('button');
        btnTambah.innerText = '+';
        btnTambah.classList.add('ubah-btn');
        btnTambah.onclick = () => { pesanan[menu].jumlah +=1; updateDisplay(); };
        aksiCell.appendChild(btnTambah);

        const btnKurangi = document.createElement('button');
        btnKurangi.innerText = '-';
        btnKurangi.classList.add('ubah-btn');
        btnKurangi.onclick = () => {
            if(item.jumlah > 1) pesanan[menu].jumlah -= 1;
            else delete pesanan[menu];
            updateDisplay();
        };
        aksiCell.appendChild(btnKurangi);

        const btnHapus = document.createElement('button');
        btnHapus.innerText = 'Hapus';
        btnHapus.classList.add('hapus-btn');
        btnHapus.onclick = () => { delete pesanan[menu]; updateDisplay(); };
        aksiCell.appendChild(btnHapus);
    }
    document.getElementById('totalHarga').innerText = total.toLocaleString();
    document.getElementById('tampilPemesan').innerText = document.getElementById('namaPemesan').value || '-';
    document.getElementById('tampilKasir').innerText = document.getElementById('namaKasir').value || '-';
}

function hitungKembalian(){
    const total = Object.values(pesanan).reduce((acc,item)=>acc+item.harga*item.jumlah,0);
    const uang = parseInt(document.getElementById('uangPelanggan').value) || 0;
    const kembalian = uang - total;
    document.getElementById('kembalian').innerText = (kembalian>=0?kembalian:0).toLocaleString();
}

function resetPesanan(){
    pesanan = {};
    document.getElementById('uangPelanggan').value = '';
    document.getElementById('kembalian').innerText = '0';
    updateDisplay();
}

function cetakPesanan(){
    let printContent = `<h2>Angkringan</h2>`;
    printContent += `<p>Pemesan: ${document.getElementById('namaPemesan').value || '-'}</p>`;
    printContent += `<p>Kasir: ${document.getElementById('namaKasir').value || '-'}</p>`;
    printContent += `<table border="1" style="border-collapse:collapse; width:100%;">`;
    printContent += `<tr><th>Menu</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th></tr>`;
    let total = 0;
    for(let menu in pesanan){
        const item = pesanan[menu];
        const subtotal = item.harga*item.jumlah;
        total += subtotal;
        printContent += `<tr><td>${menu}</td><td>Rp${item.harga.toLocaleString()}</td><td>${item.jumlah}</td><td>Rp${subtotal.toLocaleString()}</td></tr>`;
    }
    printContent += `</table>`;
    printContent += `<p>Total: Rp${total.toLocaleString()}</p>`;
    const uang = parseInt(document.getElementById('uangPelanggan').value) || 0;
    const kembalian = uang - total;
    printContent += `<p>Uang Pelanggan: Rp${uang.toLocaleString()}</p>`;
    printContent += `<p>Kembalian: Rp${(kembalian>=0?kembalian:0).toLocaleString()}</p>`;

    const printWindow = window.open('', '', 'width=600,height=600');
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
}

document.getElementById('namaPemesan').addEventListener('input', updateDisplay);
document.getElementById('namaKasir').addEventListener('input', updateDisplay);

renderMenu(semuaMenu);
</script>
</body>
</html>
