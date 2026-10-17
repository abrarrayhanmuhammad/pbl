/**
 * KickDropShoe MIS - Full Interactive Engine
 * Responsive Mobile Support + 100% Desktop Master-Detail Engine
 */

const ordersData = {
    '#0881': {
        title: 'Detail Pesanan #0881',
        subtitle: 'Budi Santoso • 08123456789',
        dateIn: '17 Okt 2026',
        statusPay: '<span class="font-extrabold text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded text-[10px]">LUNAS – QRIS</span>',
        dropPoint: 'Oro-oro Dowo',
        dateOut: 'Belum selesai',
        status: 'Sedang Diproses',
        items: `
            <div class="p-4 border border-slate-200 rounded-2xl flex justify-between items-center bg-white">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 border border-slate-200"><i class="fas fa-camera text-lg"></i></div>
                    <div><h5 class="font-extrabold text-slate-900 text-sm">Sepatu Nike Air Max <span class="text-[10px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded ml-1">Putih</span></h5><div class="text-xs text-slate-500 space-y-0.5 mt-1 font-medium"><p><i class="fas fa-check text-yellow-600 mr-1"></i> Basic & White Shoes (30K)</p><p><i class="fas fa-check text-yellow-600 mr-1"></i> Unyellowing (30K)</p><p><i class="fas fa-bolt text-blue-500 mr-1"></i> Express (+10K)</p></div></div>
                </div>
                <span class="font-black text-slate-900 text-sm">Rp 70.000</span>
            </div>
            <div class="p-4 border border-slate-200 rounded-2xl flex justify-between items-center bg-white">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 border border-slate-200"><i class="fas fa-upload text-sm"></i></div>
                    <div><h5 class="font-extrabold text-slate-900 text-sm">Tas Coach <span class="text-[10px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded ml-1">Hitam</span></h5><div class="text-xs text-slate-500 space-y-0.5 mt-1 font-medium"><p><i class="fas fa-check text-yellow-600 mr-1"></i> Deep Clean Tas M (35K)</p><p><i class="fas fa-clock text-slate-400 mr-1"></i> Reguler (Free)</p></div></div>
                </div>
                <span class="font-black text-slate-900 text-sm">Rp 35.000</span>
            </div>
        `,
        itemsCount: '2 Item',
        total: 'Rp 105.000'
    },
    '#0882': {
        title: 'Detail Pesanan #0882',
        subtitle: 'Sari Ayu • 08129876543',
        dateIn: '16 Okt 2026',
        statusPay: '<span class="font-extrabold text-slate-600 bg-slate-100 px-2 py-0.5 rounded text-[10px]">BELUM LUNAS</span>',
        dropPoint: 'Suhat',
        dateOut: 'Belum selesai',
        status: 'Diterima',
        items: `
            <div class="p-4 border border-slate-200 rounded-2xl flex justify-between items-center bg-white">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 border border-slate-200"><i class="fas fa-camera text-lg"></i></div>
                    <div><h5 class="font-extrabold text-slate-900 text-sm">Flatshoes <span class="text-[10px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded ml-1">Krem</span></h5><div class="text-xs text-slate-500 space-y-0.5 mt-1 font-medium"><p><i class="fas fa-check text-yellow-600 mr-1"></i> Flatshoes / Heels (25K)</p><p><i class="fas fa-clock text-slate-400 mr-1"></i> Reguler (Free)</p></div></div>
                </div>
                <span class="font-black text-slate-900 text-sm">Rp 35.000</span>
            </div>
        `,
        itemsCount: '1 Item',
        total: 'Rp 35.000'
    },
    '#0880': {
        title: 'Detail Pesanan #0880',
        subtitle: 'Andi • 08561234567',
        dateIn: '15 Okt 2026',
        statusPay: '<span class="font-extrabold text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded text-[10px]">LUNAS – CASH</span>',
        dropPoint: 'Dieng',
        dateOut: '17 Okt 2026',
        status: 'Selesai Diproses',
        items: `
            <div class="p-4 border border-slate-200 rounded-2xl flex justify-between items-center bg-white">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 border border-slate-200"><i class="fas fa-camera text-lg"></i></div>
                    <div><h5 class="font-extrabold text-slate-900 text-sm">Sepatu Converse <span class="text-[10px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded ml-1">Hitam</span></h5><div class="text-xs text-slate-500 space-y-0.5 mt-1 font-medium"><p><i class="fas fa-check text-yellow-600 mr-1"></i> Premium Deep Clean (50K)</p><p><i class="fas fa-bolt text-blue-500 mr-1"></i> Prioritas (+5K)</p></div></div>
                </div>
                <span class="font-black text-slate-900 text-sm">Rp 55.000</span>
            </div>
        `,
        itemsCount: '1 Item',
        total: 'Rp 55.000'
    }
};

document.addEventListener('DOMContentLoaded', () => {
    console.log('KickDropShoe Engine Loaded.');

    initCategoryButtons();
    initExtraTreatmentCalculations();
    initPhotoUploads();
    initOrderFilter();
    initMasterDetailOrders();
    initAddItemButton();
    initDetailPesanan();
});

// Mobile Sidebar Toggle
function toggleSidebar() {
    const sidebar = document.getElementById('main-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    if (!sidebar) return;

    if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full');
        if (backdrop) backdrop.classList.remove('hidden');
    } else {
        sidebar.classList.add('-translate-x-full');
        if (backdrop) backdrop.classList.add('hidden');
    }
}

// 1. Pricelist Data
const PRICELIST_DATA = window.PRICELIST_DATA || {};

// 2. Category Switcher Logic
function initCategoryButtons() {
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.cat-btn');
        if (!btn) return;

        const container = btn.closest('.item-dark-container');
        if (!container) return;

        const itemId = container.dataset.itemId || 1;
        const category = btn.dataset.category;

        container.querySelectorAll('.cat-btn').forEach(b => {
            b.className = 'cat-btn py-2 text-[10px] font-bold border border-slate-700 text-slate-400 rounded-lg transition-all cursor-pointer hover:border-slate-500';
        });
        btn.className = 'cat-btn py-2 text-[10px] font-bold border border-brand-yellow bg-brand-yellow/10 text-brand-yellow rounded-lg transition-all cursor-pointer';

        const select = container.querySelector('.service-select');
        if (select && PRICELIST_DATA[category]) {
            select.innerHTML = PRICELIST_DATA[category].map(item =>
                `<option name="${item.id}" value="${item.id}" data-price="${item.price}">${item.name} ${item.variasi ? ` (${item.variasi})` : ''} – Rp ${item.price.toLocaleString('id-ID')} (${item.estimasi_min} hari - ${item.estimasi_max} hari)</option>`
            ).join('');

            const catCaption = container.querySelector('.category-caption');
            if (catCaption) catCaption.innerText = category === 'Shoes Care' ? 'Deep Clean Shoes Care' : category;

            updateItemSubtotal(itemId);
        }
    });
}

// 3. Extra Treatment Calculations
function initExtraTreatmentCalculations() {
    document.addEventListener('change', (e) => {
        if (e.target.matches('input[type="radio"]') || e.target.matches('input[type="checkbox"]') || e.target.matches('.service-select')) {
            const container = e.target.closest('.item-dark-container');
            if (container) {
                const itemId = container.dataset.itemId || 1;
                updateItemSubtotal(itemId);
            }
        }
    });
}

function updateItemSubtotal(itemId) {
    const container = document.querySelector(`.item-dark-container[data-item-id="${itemId}"]`) || document.querySelector(`#item-container-${itemId}`);
    if (!container) return;

    const serviceSelect = container.querySelector('.service-select');
    const selectedOption = serviceSelect?.selectedOptions[0];
    const speedRadio = container.querySelector(`input[name="speed[${itemId}]"]:checked`);
    const extraCheckboxes = container.querySelectorAll(`.extra-check-${itemId}:checked`);

    let total = Number(selectedOption?.dataset.price || 0);

    if (speedRadio) {
        total += Number(speedRadio.dataset.price) || 0;
    }

    extraCheckboxes.forEach(cb => {
        total += Number(cb.dataset.price) || 0;
    });

    container.querySelectorAll(`input[name="speed[${itemId}]"]`).forEach(radio => {
        const label = radio.closest('label');
        const titleSpan = label?.querySelector('.speed-title');

        if (radio.checked) {
            label.className = 'p-3 border-2 border-brand-yellow bg-brand-yellow/10 rounded-xl flex flex-col items-center justify-center cursor-pointer transition';
            if (titleSpan) titleSpan.className = 'speed-title text-[10px] font-bold text-brand-yellow';
        } else {
            label.className = 'p-3 border border-slate-700 rounded-xl flex flex-col items-center justify-center cursor-pointer transition hover:border-slate-500';
            if (titleSpan) titleSpan.className = 'speed-title text-[10px] font-bold text-slate-400';
        }
    });

    const subtotalEl = container.querySelector('.subtotal-val');
    if (subtotalEl) {
        subtotalEl.textContent = `Rp ${total.toLocaleString('id-ID')}`;
    }

    const subtotalInput = container.querySelector('input[name^="subtotal["]');
    if (subtotalInput) {
        subtotalInput.value = total;
    }

    recalculateGrandTotal();
}


function recalculateGrandTotal() {
    let grandTotal = 0;
    document.querySelectorAll('.subtotal-val').forEach(el => {
        const val = parseInt(el.innerText.replace(/[^0-9]/g, '')) || 0;
        grandTotal += val;
    });

    document.querySelectorAll('.grand-total-val').forEach(el => {
        el.innerText = `Rp ${grandTotal.toLocaleString('id-ID')}`;
    });
}

// 4. Dynamic Add & Delete Item Cards
let itemCounter = 0;
function initAddItemButton() {
    const addBtn = document.querySelector('.add-item-btn-trigger');
    if (!addBtn) return;

    addBtn.addEventListener('click', () => {
        itemCounter++;
        const newId = itemCounter;
        const template = `
        <div id="item-container-${newId}" data-item-id="${newId}" class="item-dark-container bg-brand-darkCard text-white rounded-3xl p-6 md:p-8 shadow-xl space-y-6 animate-fade-in">
            <div class="flex justify-between items-center border-b border-slate-800 pb-4">
                <h3 class="text-sm font-extrabold flex items-center gap-3">
                    <span class="bg-brand-yellow text-slate-900 w-6 h-6 rounded-md flex items-center justify-center text-xs font-black">${newId}</span>
                    Detail Barang
                </h3>
                <button type="button" onclick="deleteItemCard(${newId})" class="px-3 py-1 bg-slate-800 hover:bg-red-500/20 hover:text-red-400 text-slate-400 rounded-lg text-xs font-semibold transition">
                    <i class="fas fa-trash mr-1"></i> Hapus
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5">NAMA BARANG</label>
                            <input name="nama_barang[${newId}]" type="text" class="w-full bg-brand-darkInput border border-slate-700/80 rounded-xl px-4 py-3 text-xs font-medium text-white outline-none" placeholder="Misal: Tas / Helm / Sepatu" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5">WARNA</label>
                            <input name="warna_barang[${newId}]" type="text" class="w-full bg-brand-darkInput border border-slate-700/80 rounded-xl px-4 py-3 text-xs font-medium text-white outline-none" placeholder="Warna barang" required>
                        </div>
                    </div>

                    <div class="bg-slate-900/60 p-5 rounded-2xl border border-slate-800/80 space-y-3">
                        <label class="block text-[10px] font-extrabold text-brand-yellow uppercase tracking-wider">KATEGORI & LAYANAN UTAMA</label>
                        
                        <div class="category-buttons grid grid-cols-2 sm:grid-cols-4 gap-2">
                        </div>

                        <p class="category-caption text-[9px] text-slate-500 font-semibold">Deep Clean Shoes Care</p>
                        <div>
                            <select name="layanan_id[${newId}]" id="service-select-${newId}" onchange="updateItemSubtotal(${newId})" class="service-select w-full bg-brand-darkInput border border-slate-700 rounded-xl px-3 py-2.5 text-xs text-white outline-none font-semibold" required>
                                <option value="" selected>Pilih Layanan</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 flex flex-col justify-between">
                    <div class="bg-slate-900/80 p-5 rounded-2xl border border-slate-800 space-y-4">
                        <label class="block text-[10px] font-extrabold text-brand-yellow uppercase tracking-wider">EXTRA TREATMENT</label>
                        
                        <div class="grid grid-cols-3 gap-2">

                            <label class="p-3 border-2 border-brand-yellow bg-brand-yellow/10 rounded-xl flex flex-col items-center justify-center cursor-pointer transition">
                                <input type="radio" name="speed[${newId}]" value="reguler" data-price="0" checked onchange="updateItemSubtotal(${newId})" class="text-brand-yellow mb-1">
                                <span class="speed-title text-[10px] font-bold text-brand-yellow">Reguler</span>
                            </label>


                            ${(window.TREATMENT_KECEPATAN || []).map(item => `
                                <label class="treatment-speed p-3 border border-slate-700 rounded-xl flex flex-col items-center justify-center cursor-pointer transition">
                                    <input type="radio"name="speed[${newId}]" value="${item.id_treatment_tambahan}" data-price="${item.harga_min}" data-harga-max="${item.harga_max}" onchange="updateItemSubtotal(${newId})" class="text-brand-yellow mb-1">
                                    <span class="speed-title text-[10px] font-bold text-slate-400">${item.nama_treatment}</span>
                                    <span class="text-[9px] text-slate-400">${item.keterangan || ''} • +Rp${Number(item.harga_min).toLocaleString('id-ID')}</span>
                                </label>
                            `).join('')}
                        </div>

                        <div class="space-y-2 pt-1">
                            ${(window.TREATMENT_TAMBAHAN || []).map(item => `
                                <label class="flex justify-between items-center p-3.5 border border-slate-700/90 bg-slate-800/50 rounded-xl cursor-pointer hover:border-slate-500 transition">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" name="treatment_tambahan[${newId}][]" value="${item.id_treatment_tambahan}" data-price="${Number(item.id_treatment_tambahan) === 4 ? Number(item.harga_max) : Number(item.harga_min)}" data-harga-max="${Number(item.harga_max)}" onchange="updateItemSubtotal(${newId})" class="extra-check-${newId} w-4 h-4 rounded text-brand-yellow bg-slate-900 border-slate-600 focus:ring-brand-yellow">
                                        <span class="text-xs font-bold text-white">${item.nama_treatment} (${item.keterangan || ''})</span>
                                    </div>
                                    <span class="text-xs font-bold text-brand-yellow">
                                        ${Number(item.harga_min) === Number(item.harga_max) ? '+Rp' + Number(item.harga_min).toLocaleString('id-ID') : '+Rp' + Number(item.harga_min).toLocaleString('id-ID') + ' – Rp' + Number(item.harga_max).toLocaleString('id-ID')}
                                    </span>
                                </label>
                            `).join('')}
                        </div>
                    </div>

                    <div class="bg-black/60 p-4 rounded-xl border border-slate-800 flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-400">Subtotal Barang Ini:</span>
                        <span id="subtotal-val-${newId}" class="subtotal-val text-lg font-black text-brand-yellow">Rp 0</span>
                        <input type="hidden" name="subtotal[${newId}]" id="subtotal-input-${newId}" value="0">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:gap-6 pt-3 border-t border-slate-800 items-stretch">
                <div class="flex flex-col">
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-2 h-7 sm:h-auto flex items-end leading-tight">FOTO BEFORE (SEBELUM)</label>
                    <div class="upload-box-trigger border-2 border-dashed border-slate-700 rounded-2xl p-3 sm:p-4 h-28 sm:h-32 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-slate-800/40 transition">
                        <i class="fas fa-upload text-slate-400 text-base sm:text-lg mb-1"></i>
                        <span class="text-[11px] sm:text-xs font-bold text-white">Upload Foto</span>
                        <span class="text-[9px] sm:text-[10px] text-slate-500 mt-0.5">Maks. 5 MB</span>
                    </div>
                </div>

                <div class="flex flex-col">
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-2 h-7 sm:h-auto flex items-end leading-tight">CATATAN TAMBAHAN</label>
                    <textarea name="catatan[${newId}]" class="w-full bg-brand-darkInput border border-slate-700 rounded-2xl p-3 sm:p-4 text-xs font-medium text-white outline-none h-28 sm:h-32 resize-none placeholder:text-slate-500" placeholder="Ketik noda khusus, robek, atau keluhan di sini..."></textarea>
                </div>
            </div>
        </div>
        `;

        addBtn.insertAdjacentHTML('beforebegin', template);
        generateCategoryButtons(newId);
        renumberItems();
        initPhotoUploads();
        recalculateGrandTotal();
    });
}

function generateCategoryButtons(itemId) {
    const categories = window.LAYANAN_DATA || [];
    const container = document.querySelector(
        `#item-container-${itemId} .category-buttons`
    );

    if (!container) return;

    container.innerHTML = '';

    const allCategories = [...new Set([...categories, 'Package'])];

    allCategories.forEach((category, index) => {
        const button = document.createElement('button');

        button.type = 'button';
        button.dataset.category = category;
        button.className = 'cat-btn py-2 text-[10px] font-bold border rounded-lg border-slate-700 text-slate-400';

        button.textContent = category;
        container.appendChild(button);
    });
}

function deleteItemCard(id) {
    const card = document.getElementById(`item-container-${id}`);

    if (card) {
        card.remove();
        renumberItems();
        recalculateGrandTotal();
    }
}

function renumberItems() {
    const itemCards = document.querySelectorAll('.item-dark-container');

    itemCards.forEach((card, index) => {
        const newId = index + 1;

        card.id = `item-container-${newId}`;
        card.dataset.itemId = newId;

        const numberElement = card.querySelector('h3 span');
        if (numberElement) {
            numberElement.textContent = newId;
        }

        const subtotal = card.querySelector('.subtotal-val');
        if (subtotal) {
            subtotal.id = `subtotal-val-${newId}`;
        }

        const serviceSelect = card.querySelector('.service-select');
        if (serviceSelect) {
            serviceSelect.id = `service-select-${newId}`;
        }

        card.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.name = `speed[${newId}]`;
        });

        card.querySelectorAll('input[type="checkbox"][data-price]').forEach(input => {
            input.classList.remove(...Array.from(input.classList).filter(c => c.startsWith('extra-check-')));
            input.classList.add(`extra-check-${newId}`);
        });

        const deleteButton = card.querySelector('button[onclick^="deleteItemCard"]');
        if (deleteButton) {
            deleteButton.setAttribute('onclick', `deleteItemCard(${newId})`);
        }

        card.querySelectorAll('[onchange*="updateItemSubtotal"]').forEach(element => {
            element.setAttribute(
                'onchange',
                element.getAttribute('onchange').replace(
                    /updateItemSubtotal\(\d+\)/g,
                    `updateItemSubtotal(${newId})`
                )
            );
        });

        card.querySelectorAll('[id]').forEach(element => {
            if (element.id.startsWith('subtotal-val-')) {
                element.id = `subtotal-val-${newId}`;
            }
        });
    });

    itemCounter = itemCards.length;
}

// 5. Photo Upload Preview Simulation
function initPhotoUploads() {
    document.querySelectorAll('.upload-box-trigger').forEach(box => {
        box.addEventListener('click', () => {
            const container = box.closest('.item-dark-container');
            const form = box.closest('form');

            if (!container || !form) {
                console.error('Container barang atau form tidak ditemukan.');
                return;
            }

            const itemId = container.dataset.itemId;

            const input = document.createElement('input');
            input.type = 'file';
            input.name = `foto_before[${itemId}]`;
            input.accept = 'image/jpeg,image/png,image/webp';
            input.hidden = true;

            form.appendChild(input);

            input.addEventListener('change', () => {
                const file = input.files[0];
                if (!file) return;

                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran foto maksimal 5 MB.');
                    input.remove();
                    return;
                }

                if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                    alert('Foto harus berformat JPG, PNG, atau WEBP.');
                    input.remove();
                    return;
                }

                const reader = new FileReader();

                reader.onload = event => {
                    box.innerHTML = `<img src="${event.target.result}" class="w-full h-full object-cover rounded-2xl" alt="Foto sebelum">`;
                    box.classList.remove('border-2', 'border-dashed');
                    box.dataset.photoSelected = 'true';
                };

                reader.readAsDataURL(file);
            }, { once: true });

            input.click();
        });
    });
}


// 6. Master-Detail Order Selection:
// - On Desktop: Seamless in-place switch on right panel (Persis 100% Desain Asli)
// - On Mobile: Navigate to detail_pesanan.php without scrolling
function initMasterDetailOrders() {
    const orderCards = document.querySelectorAll('.order-card-item');
    orderCards.forEach(card => {
        card.addEventListener('click', (e) => {
            const titleEl = card.querySelector('h4');
            const orderId = titleEl ? titleEl.innerText.split(' ')[0] : '#0881'; // e.g. #0881
            const orderNum = orderId.replace('#', '');

            // Jika di Mobile (< 1024px): Buka halaman detail_pesanan.php
            if (window.innerWidth < 1024) {
                window.location.href = `/pesanan/detail`;
                return;
            }

            // detail_pesanan.php?id=${orderNum}

            // Jika di Desktop (>= 1024px): Ganti panel kanan di tempat (Desain Asli)
            orderCards.forEach(c => {
                c.className = 'order-card-item p-5 rounded-2xl border border-slate-200 bg-white hover:border-slate-300 cursor-pointer transition-all';
            });
            card.className = 'order-card-item p-5 rounded-2xl border-2 border-brand-yellow bg-yellow-50/30 shadow-sm cursor-pointer relative transition-all';

            const data = ordersData[orderId];
            if (data) {
                const elTitle = document.querySelector('.detail-title');
                if (elTitle) elTitle.innerText = data.title;
                const elSub = document.querySelector('.detail-subtitle');
                if (elSub) elSub.innerText = data.subtitle;
                const elDateIn = document.querySelector('.detail-date-in');
                if (elDateIn) elDateIn.innerText = data.dateIn;
                const elStatusPay = document.querySelector('.detail-status-pay');
                if (elStatusPay) elStatusPay.innerHTML = data.statusPay;
                const elDropPoint = document.querySelector('.detail-drop-point');
                if (elDropPoint) elDropPoint.innerText = data.dropPoint;
                const elDateOut = document.querySelector('.detail-date-out');
                if (elDateOut) elDateOut.innerText = data.dateOut;
                const elItemsCount = document.querySelector('.detail-items-count');
                if (elItemsCount) elItemsCount.innerText = 'Daftar Barang (' + data.itemsCount + ')';
                const elTotal = document.querySelector('.detail-total');
                if (elTotal) elTotal.innerText = data.total;
                const elItemsContainer = document.querySelector('.detail-items-container');
                if (elItemsContainer) elItemsContainer.innerHTML = data.items;

                const detailSelect = document.querySelector('#detail-status-select');
                if (detailSelect) {
                    Array.from(detailSelect.options).forEach(opt => {
                        if (opt.text.includes(data.status)) {
                            opt.selected = true;
                        } else {
                            opt.selected = false;
                        }
                    });
                }
            }
        });
    });
}

// 7. Order Filter
function initOrderFilter() {
    const searchInput = document.querySelector('#order-search-input');
    const statusSelect = document.querySelector('#order-status-select');

    if (!searchInput && !statusSelect) return;

    const filterHandler = () => {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const status = statusSelect ? statusSelect.value : 'Semua Status';
        const cards = document.querySelectorAll('.order-card-item');

        cards.forEach(card => {
            const text = card.innerText.toLowerCase();
            const matchesSearch = query === '' || text.includes(query);

            let matchesStatus = true;
            if (status !== 'Semua Status') {
                if (status === 'Diproses') {
                    matchesStatus = text.includes('diproses') || text.includes('sedang diproses');
                } else {
                    matchesStatus = text.includes(status.toLowerCase());
                }
            }

            card.style.display = (matchesSearch && matchesStatus) ? 'block' : 'none';
        });
    };

    if (searchInput) searchInput.addEventListener('input', filterHandler);
    if (statusSelect) statusSelect.addEventListener('change', filterHandler);
}


function tampilkanDokumentasiFoto(barang) {
    const beforeContainer = document.getElementById('detail-photos-before');
    const afterContainer = document.getElementById('detail-photos-after');

    if (!beforeContainer || !afterContainer) return;

    function aturGrid(container, jumlah) {
        const kolom = Math.min(2, Math.max(1, jumlah));

        container.style.gridTemplateColumns =
            `repeat(${kolom}, minmax(0, 1fr))`;
    }

    aturGrid(beforeContainer, barang.length);
    aturGrid(afterContainer, barang.length);

    beforeContainer.replaceChildren();
    afterContainer.replaceChildren();

    function buatKartuFoto(item, jenis) {
        const path = jenis === 'before'
            ? item.foto_before
            : item.foto_sesudah;

        const card = document.createElement('div');
        card.className = 'min-w-0';

        const title = document.createElement('p');
        title.className = 'text-xs font-bold text-slate-700 mb-2';
        title.textContent = [item.nama_barang, item.merk]
            .filter(Boolean)
            .join(' ') || 'Barang';

        const box = document.createElement('div');
        box.className = 'w-full h-40 bg-slate-200 rounded-2xl overflow-hidden border border-slate-200 flex flex-col items-center justify-center gap-2 text-slate-400';

        if (path) {
            const img = document.createElement('img');
            img.src = '/' + String(path).replace(/^\/+/, '');
            img.alt = jenis === 'before'
                ? 'Foto sebelum dicuci'
                : 'Foto sesudah dicuci';
            img.className = 'w-full h-full object-cover';

            img.onerror = function () {
                box.replaceChildren();

                const icon = document.createElement('i');
                icon.className = 'fas fa-camera text-3xl';

                const text = document.createElement('span');
                text.className = 'text-xs font-medium';
                text.textContent = 'Foto gagal dimuat';

                box.append(icon, text);
            };

            box.appendChild(img);
        } else {
            const icon = document.createElement('i');
            icon.className = 'fas fa-camera text-3xl';

            const text = document.createElement('span');
            text.className = 'text-xs font-medium';
            text.textContent = 'Foto belum tersedia';

            box.append(icon, text);
        }

        card.append(title, box);
        return card;
    }

    if (!barang.length) {
        const empty = document.createElement('p');
        empty.className = 'text-xs text-slate-400';
        empty.textContent = 'Belum ada barang.';
        beforeContainer.appendChild(empty.cloneNode(true));
        afterContainer.appendChild(empty);
        return;
    }

    barang.forEach(function (item) {
        beforeContainer.appendChild(buatKartuFoto(item, 'before'));
        afterContainer.appendChild(buatKartuFoto(item, 'after'));
    });
}



function initDetailPesanan() {
    const cards = document.querySelectorAll('.order-card-item');
    const el = {
        title: document.getElementById('detail-title'),
        subtitle: document.getElementById('detail-subtitle'),
        dateIn: document.getElementById('detail-date-in'),
        dateOut: document.getElementById('detail-date-out'),
        statusPay: document.getElementById('detail-status-pay'),
        dropPoint: document.getElementById('detail-drop-point'),
        status: document.getElementById('detail-status-select'),
        itemsCount: document.getElementById('detail-items-count'),
        items: document.getElementById('detail-items-container'),
        total: document.getElementById('detail-total'),
        photoBefore: document.getElementById('detail-photo-before'),
        photoAfter: document.getElementById('detail-photo-after'),
        whatsappCustomer: document.getElementById('detail-whatsapp-customer'),
        edit: document.getElementById('detail-edit-link'),
        invoice: document.getElementById('detail-invoice-link'),
    };

    // Pastikan elemen utama tersedia pada halaman ini.
    if (!cards.length || !el.title || !el.items) {
        return;
    }

    function rupiah(value) {
        return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
    }

    function tanggal(value) {
        if (!value) return 'Belum selesai';

        const date = new Date(value + 'T00:00:00');

        return Number.isNaN(date.getTime())
            ? value
            : date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
    }

    function buatElemen(tag, className, text) {
        const node = document.createElement(tag);
        node.className = className;
        node.textContent = text ?? '';
        return node;
    }

    function tampilkanBarang(barang) {
        el.items.replaceChildren();

        if (!barang.length) {
            el.items.appendChild(
                buatElemen(
                    'p',
                    'text-xs text-slate-500',
                    'Belum ada detail barang.'
                )
            );
            return;
        }

        barang.forEach(function (item) {
            const card = buatElemen(
                'div',
                'p-4 border border-slate-200 rounded-2xl flex justify-between items-center bg-white',
                ''
            );

            const left = buatElemen(
                'div',
                'flex items-center gap-4',
                ''
            );

            const photoBox = buatElemen(
                'div',
                'w-14 h-14 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 border border-slate-200 overflow-hidden',
                ''
            );

            if (item.foto_before) {
                const img = document.createElement('img');
                img.src = '/' + String(item.foto_before).replace(/^\/+/, '');
                img.alt = 'Foto ' + (item.nama_barang || 'barang');
                img.className = 'w-full h-full object-cover';
                photoBox.appendChild(img);
            } else {
                photoBox.appendChild(
                    buatElemen('i', 'fas fa-camera text-lg', '')
                );
            }

            const info = buatElemen('div', '', '');
            const heading = buatElemen(
                'h5',
                'font-extrabold text-slate-900 text-sm',
                ''
            );

            heading.appendChild(
                document.createTextNode(
                    [item.nama_barang, item.merk]
                        .filter(Boolean)
                        .join(' ')
                )
            );

            if (item.warna) {
                heading.appendChild(document.createTextNode(' '));
                heading.appendChild(
                    buatElemen(
                        'span',
                        'text-[10px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded ml-1',
                        item.warna
                    )
                );
            }

            const serviceList = buatElemen(
                'div',
                'text-xs text-slate-500 space-y-0.5 mt-1 font-medium',
                ''
            );

            if (item.nama_layanan) {
                serviceList.appendChild(
                    buatElemen(
                        'p',
                        '',
                        item.nama_layanan + ' (' + rupiah(item.harga_satuan) + ')'
                    )
                );
            }

            (item.treatments || []).forEach(function (treatment) {
                serviceList.appendChild(
                    buatElemen(
                        'p',
                        '',
                        treatment.nama_treatment + ' (' + rupiah(treatment.harga) + ')'
                    )
                );
            });

            if (item.catatan) {
                serviceList.appendChild(
                    buatElemen('p', '', 'Catatan: ' + item.catatan)
                );
            }

            info.append(heading, serviceList);
            left.append(photoBox, info);

            const price = buatElemen(
                'span',
                'font-black text-slate-900 text-sm',
                rupiah(item.subtotal)
            );

            card.append(left, price);
            el.items.appendChild(card);
        });
    }

    async function pilihPesanan(card) {
        const id = card.dataset.id;

        cards.forEach(function (item) {
            item.classList.remove(
                'border-brand-yellow',
                'bg-yellow-50/30'
            );

            item.classList.add('border-slate-200', 'bg-white');
        });

        card.classList.remove('border-slate-200', 'bg-white');
        card.classList.add('border-brand-yellow', 'bg-yellow-50/30');

        el.title.textContent = 'Memuat detail pesanan...';

        el.items.replaceChildren(
            buatElemen(
                'p',
                'text-xs text-slate-500',
                'Mengambil data dari database...'
            )
        );

        try {
            const response = await fetch(
                '/pesanan/detail-data?id=' + encodeURIComponent(id),
                {
                    headers: {
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                }
            );

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(
                    result.message || 'Gagal memuat detail pesanan.'
                );
            }

            const p = result.pesanan;
            const barang = result.barang || [];

            el.title.textContent =
                'Detail Pesanan ' + (p.kode_pesanan || '');

            if (el.subtitle) {
                el.subtitle.textContent =
                    (p.nama_customer || '-') + ' • ' + (p.no_hp || '-');
            }

            if (el.dateIn) {
                el.dateIn.textContent = tanggal(p.tanggal_masuk);
            }

            if (el.dateOut) {
                el.dateOut.textContent = tanggal(p.tanggal_selesai);
            }

            if (el.dropPoint) {
                el.dropPoint.textContent = p.alamat_lokasi || '-';
            }

            if (el.total) {
                el.total.textContent = rupiah(p.total_bayar);
            }

            if (el.statusPay) {
                el.statusPay.replaceChildren();

                const payment = buatElemen(
                    'span',
                    'font-extrabold px-2 py-0.5 rounded text-[10px]',
                    (p.status_bayar || '-') +
                    (p.metode_bayar ? ' – ' + p.metode_bayar : '')
                );

                if (p.status_bayar === 'Lunas') {
                    payment.classList.add(
                        'text-emerald-600',
                        'bg-emerald-100'
                    );
                } else if (p.status_bayar === 'DP') {
                    payment.classList.add(
                        'text-yellow-800',
                        'bg-yellow-100'
                    );
                } else {
                    payment.classList.add(
                        'text-slate-600',
                        'bg-slate-100'
                    );
                }

                el.statusPay.appendChild(payment);
            }

            if (el.status) {
                el.status.value = p.status || '';
            }

            if (el.itemsCount) {
                el.itemsCount.textContent =
                    'Daftar Barang (' + barang.length + ' Item)';
            }

            tampilkanBarang(barang);
            tampilkanDokumentasiFoto(barang);

            if (el.whatsappCustomer) {
                el.whatsappCustomer.textContent =
                    (p.nama_customer || '-') + ' • ' + (p.no_hp || '-');
            }

            if (el.edit) {
                el.edit.href =
                    '/pesanan/edit?id=' + encodeURIComponent(p.id_pesanan);
            }

            if (el.invoice) {
                el.invoice.href =
                    '/invoice?id=' + encodeURIComponent(p.id_pesanan);
            }

        } catch (error) {
            el.title.textContent = 'Gagal memuat detail';

            el.items.replaceChildren(
                buatElemen(
                    'p',
                    'text-xs text-red-600',
                    error.message
                )
            );
        }
    }

    cards.forEach(function (card) {
        card.addEventListener('click', function () {
            pilihPesanan(card);
        });
    });

    pilihPesanan(cards[0]);
}
