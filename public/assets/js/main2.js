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
                `<option name="${item.id}" value="${item.id}" data-type="${item.tipe}" data-price="${item.price}">${item.name} ${item.variasi ? ` (${item.variasi})` : ''} – Rp ${item.price.toLocaleString('id-ID')} (${item.estimasi_min} hari - ${item.estimasi_max} hari)</option>`
            ).join('');

            const catCaption = container.querySelector('.category-caption');
            if (catCaption) catCaption.innerText = category === 'Shoes Care' ? 'Deep Clean Shoes Care' : category;

            updateItemSubtotal(itemId);
        }
    });
}

// 3. Extra Treatment Calculations
function initExtraTreatmentCalculations() {
    document.addEventListener('input', (e) => {
        if (!e.target.matches('.harga-manual-input')) return;
        const container = e.target.closest('.item-dark-container');
        if (container) updateItemSubtotal(container.dataset.itemId || 1);
    });
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

function manualPriceHtml(itemId, item, checked, value) {
    if (!item.harga_manual) return '';
    const min = Number(item.harga_min);
    const hasMax = item.harga_max !== null && item.harga_max !== undefined;
    const max = Number(item.harga_max);
    return `
        <div class="harga-manual-wrap ${checked ? '' : 'hidden'}">
            <input type="number" inputmode="numeric"
                name="harga_manual[${itemId}][${item.id_treatment_tambahan}]"
                min="${min}" ${hasMax ? `max="${max}"` : ''}
                value="${value ?? ''}"
                placeholder="Harga ${item.nama_treatment} (Rp${min.toLocaleString('id-ID')}${hasMax ? '–' + max.toLocaleString('id-ID') : '+'})"
                ${checked ? 'required' : 'disabled'}
                class="harga-manual-input w-full bg-brand-darkInput border border-slate-700 rounded-xl px-3 py-2.5 text-xs text-white outline-none font-semibold">
        </div>`;
}

function getManualInput(checkbox) {
    const wrap = checkbox.closest('label')?.nextElementSibling;
    return wrap && wrap.classList.contains('harga-manual-wrap')
        ? wrap.querySelector('.harga-manual-input') : null;
}

function syncManualPriceInputs(container, itemId) {
    container.querySelectorAll(`.extra-check-${itemId}[data-manual="1"]`).forEach(cb => {
        const input = getManualInput(cb);
        if (!input) return;
        input.closest('.harga-manual-wrap').classList.toggle('hidden', !cb.checked);
        input.disabled = !cb.checked;
        input.required = cb.checked;
    });
}

function updateItemSubtotal(itemId) {
    const container = document.querySelector(`.item-dark-container[data-item-id="${itemId}"]`) || document.querySelector(`#item-container-${itemId}`);
    if (!container) return;

    const serviceSelect = container.querySelector('.service-select');
    const selectedOption = serviceSelect?.selectedOptions[0];
    const speedRadio = container.querySelector(`input[name="speed[${itemId}]"]:checked`);
    const extraCheckboxes = container.querySelectorAll(`.extra-check-${itemId}:checked`);

    const tipeInput = container.querySelector('.tipe-layanan');
    if (tipeInput) {
        tipeInput.value = selectedOption.dataset.type || '';
    }

    let total = Number(selectedOption?.dataset.price || 0);

    if (speedRadio) {
        total += Number(speedRadio.dataset.price) || 0;
    }

    syncManualPriceInputs(container, itemId);

    extraCheckboxes.forEach(cb => {
        if (cb.dataset.manual === '1') {
            total += Number(getManualInput(cb)?.value) || 0;
        } else {
            total += Number(cb.dataset.price) || 0;
        }
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

                            <input type="hidden" name="tipe_layanan[${newId}]" class="tipe-layanan">
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
                                    <input type="radio" name="speed[${newId}]" value="${item.id_treatment_tambahan}" data-speed-name="${item.nama_treatment.toLowerCase()}" data-price="${item.harga_min}" data-harga-max="${item.harga_max}" onchange="updateItemSubtotal(${newId})" class="text-brand-yellow mb-1">
                                    <span class="speed-title text-[10px] font-bold text-slate-400">${item.nama_treatment}</span>
                                    <span class="text-[9px] text-slate-400">${item.keterangan || ''} • +Rp${Number(item.harga_min).toLocaleString('id-ID')}</span>
                                </label>
                            `).join('')}
                        </div>

                        <div class="space-y-2 pt-1">
                            ${(window.TREATMENT_TAMBAHAN || []).map(item => `
                                <label class="flex justify-between items-center p-3.5 border border-slate-700/90 bg-slate-800/50 rounded-xl cursor-pointer hover:border-slate-500 transition">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" name="treatment_tambahan[${newId}][]" value="${item.id_treatment_tambahan}" data-price="${Number(item.harga_min)}" data-manual="${item.harga_manual ? 1 : 0}" data-harga-max="${Number(item.harga_max)}" onchange="updateItemSubtotal(${newId})" class="extra-check-${newId} w-4 h-4 rounded text-brand-yellow bg-slate-900 border-slate-600 focus:ring-brand-yellow">
                                        <span class="text-xs font-bold text-white">${item.nama_treatment} (${item.keterangan || ''})</span>
                                    </div>
                                    <span class="text-xs font-bold text-brand-yellow">
                                        ${Number(item.harga_min) === Number(item.harga_max) ? '+Rp' + Number(item.harga_min).toLocaleString('id-ID') : '+Rp' + Number(item.harga_min).toLocaleString('id-ID') + ' – Rp' + Number(item.harga_max).toLocaleString('id-ID')}
                                    </span>
                                </label>
                                ${manualPriceHtml(newId, item, false, '')}
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
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-2 h-7 sm:h-auto flex items-end leading-tight">
                        FOTO BEFORE (SEBELUM)
                    </label>

                    <div id="upload-before-trigger" class="upload-box-trigger border-2 border-dashed border-slate-700 rounded-2xl p-3 sm:p-4 h-28 sm:h-32 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-slate-800/40 transition">
                        <i class="fas fa-camera text-slate-400 text-base sm:text-lg mb-1"></i>
                        <span class="text-[11px] sm:text-xs font-bold text-white">Upload Foto</span>
                        <span class="text-[9px] sm:text-[10px] text-slate-500 mt-0.5">Maks. 5 MB</span>
                    </div>

                    <p id="foto-before-name" class="text-xs text-slate-400 mt-2"></p>
                    <img id="foto-before-preview" class="hidden mt-2 w-full max-h-48 object-contain rounded-xl" alt="Preview foto Before">
                </div>
                <div class="flex flex-col">
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-2 h-7 sm:h-auto flex items-end leading-tight">CATATAN TAMBAHAN</label>
                    <textarea name="catatan[${newId}]" class="w-full bg-brand-darkInput border border-slate-700 rounded-2xl p-3 sm:p-4 text-xs font-medium text-white outline-none h-28 sm:h-32 resize-none placeholder:text-slate-500" placeholder="Ketik noda khusus, robek, atau keluhan di sini..."></textarea>
                </div>
            </div>
        </div>

        <div id="photo-source-sheet" class="fixed inset-0 z-[9999] hidden">
            <div id="photo-source-backdrop" class="absolute inset-0 bg-black/60"></div>
            <div id="photo-source-panel" class="absolute bottom-0 left-0 right-0 bg-slate-900 border-t border-slate-700 rounded-t-3xl p-5 pb-8 transition-transform duration-300 translate-y-full">
                <div class="w-12 h-1.5 rounded-full bg-slate-600 mx-auto mb-5"></div>
                <h3 class="text-white font-extrabold text-base">Upload Foto Before</h3>
                <p class="text-slate-400 text-xs mt-1 mb-5">Pilih sumber foto</p>
                <button type="button" id="photo-source-camera" class="w-full flex items-center gap-4 p-4 rounded-2xl border border-slate-700 bg-slate-800 text-left mb-3">
                    <i class="fas fa-camera text-yellow-400 text-xl w-8 text-center"></i>
                    <div>
                        <p class="text-white text-sm font-bold">Buka Kamera</p>
                        <p class="text-slate-400 text-xs mt-1">Ambil foto langsung</p>
                    </div>
                </button>
                <button type="button" id="photo-source-gallery" class="w-full flex items-center gap-4 p-4 rounded-2xl border border-slate-700 bg-slate-800 text-left">
                    <i class="fas fa-images text-blue-400 text-xl w-8 text-center"></i>
                    <div>
                        <p class="text-white text-sm font-bold">Pilih dari Galeri</p>
                        <p class="text-slate-400 text-xs mt-1">Pilih foto yang tersimpan</p>
                    </div>
                </button>
                <button type="button" id="photo-source-cancel" class="w-full mt-4 py-3 rounded-xl bg-slate-800 text-slate-300 text-sm font-bold">
                    Batal
                </button>
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

function initEditAddItemButton() {
    const addBtn = document.querySelector('.add-edit-item-btn-trigger');
    if (!addBtn) return;

    addBtn.addEventListener('click', () => {
        const existingIds = Array.from(
            document.querySelectorAll('.item-dark-container')
        ).map(card => Number(card.dataset.itemId) || 0);

        const newId = Math.max(itemCounter, ...existingIds) + 1;
        itemCounter = newId;

        const template = `
        <div id="item-container-${newId}" data-item-id="${newId}" class="item-dark-container bg-brand-darkCard text-white rounded-3xl p-6 md:p-8 shadow-xl space-y-6 animate-fade-in">
            <div class="flex justify-between items-center border-b border-slate-800 pb-4">
                <h3 class="text-sm font-extrabold flex items-center gap-3">
                    <span class="bg-brand-yellow text-slate-900 w-6 h-6 rounded-md flex items-center justify-center text-xs font-black">${newId}</span>
                    Detail Barang
                </h3>
                <button type="button" onclick="deleteEditItemCard(${newId})" class="px-3 py-1 bg-slate-800 hover:bg-red-500/20 hover:text-red-400 text-slate-400 rounded-lg text-xs font-semibold transition">
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

                        <div class="category-buttons grid grid-cols-2 sm:grid-cols-4 gap-2"></div>

                        <p class="category-caption text-[9px] text-slate-500 font-semibold">Deep Clean Shoes Care</p>

                        <div>
                            <select name="layanan_id[${newId}]" id="service-select-${newId}" onchange="updateItemSubtotal(${newId})" class="service-select w-full bg-brand-darkInput border border-slate-700 rounded-xl px-3 py-2.5 text-xs text-white outline-none font-semibold" required>
                                <option value="" selected>Pilih Layanan</option>
                            </select>

                            <input type="hidden" name="tipe_layanan[${newId}]" class="tipe-layanan">
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
                                    <input type="radio" name="speed[${newId}]" value="${item.id_treatment_tambahan}" data-speed-name="${item.nama_treatment.toLowerCase()}" data-price="${item.harga_min}" data-harga-max="${item.harga_max}" onchange="updateItemSubtotal(${newId})" class="text-brand-yellow mb-1">
                                    <span class="speed-title text-[10px] font-bold text-slate-400">${item.nama_treatment}</span>
                                    <span class="text-[9px] text-slate-400">${item.keterangan || ''} • +Rp${Number(item.harga_min).toLocaleString('id-ID')}</span>
                                </label>
                            `).join('')}
                        </div>

                        <div class="space-y-2 pt-1">
                            ${(window.TREATMENT_TAMBAHAN || []).map(item => `
                                <label class="flex justify-between items-center p-3.5 border border-slate-700/90 bg-slate-800/50 rounded-xl cursor-pointer hover:border-slate-500 transition">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" name="treatment_tambahan[${newId}][]" value="${item.id_treatment_tambahan}" data-price="${Number(item.harga_min)}" data-manual="${item.harga_manual ? 1 : 0}" data-harga-max="${Number(item.harga_max)}" onchange="updateItemSubtotal(${newId})" class="extra-check-${newId} w-4 h-4 rounded text-brand-yellow bg-slate-900 border-slate-600 focus:ring-brand-yellow">
                                        <span class="text-xs font-bold text-white">${item.nama_treatment} (${item.keterangan || ''})</span>
                                    </div>
                                    <span class="text-xs font-bold text-brand-yellow">
                                        ${Number(item.harga_min) === Number(item.harga_max) ? '+Rp' + Number(item.harga_min).toLocaleString('id-ID') : '+Rp' + Number(item.harga_min).toLocaleString('id-ID') + ' – Rp' + Number(item.harga_max).toLocaleString('id-ID')}
                                    </span>
                                </label>
                                ${manualPriceHtml(newId, item, false, '')}
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
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-2 h-7 sm:h-auto flex items-end leading-tight">
                        FOTO BEFORE (SEBELUM)
                    </label>

                    <div id="upload-before-trigger" class="upload-box-trigger border-2 border-dashed border-slate-700 rounded-2xl p-3 sm:p-4 h-28 sm:h-32 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-slate-800/40 transition">
                        <i class="fas fa-camera text-slate-400 text-base sm:text-lg mb-1"></i>
                        <span class="text-[11px] sm:text-xs font-bold text-white">Upload Foto</span>
                        <span class="text-[9px] sm:text-[10px] text-slate-500 mt-0.5">Maks. 5 MB</span>
                    </div>

                    <p id="foto-before-name" class="text-xs text-slate-400 mt-2"></p>
                    <img id="foto-before-preview" class="hidden mt-2 w-full max-h-48 object-contain rounded-xl" alt="Preview foto Before">
                </div>
                <div class="flex flex-col">
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase mb-2 h-7 sm:h-auto flex items-end leading-tight">CATATAN TAMBAHAN</label>
                    <textarea name="catatan[${newId}]" class="w-full bg-brand-darkInput border border-slate-700 rounded-2xl p-3 sm:p-4 text-xs font-medium text-white outline-none h-28 sm:h-32 resize-none placeholder:text-slate-500" placeholder="Ketik noda khusus, robek, atau keluhan di sini..."></textarea>
                </div>
            </div>
        </div>

        <div id="photo-source-sheet" class="fixed inset-0 z-[9999] hidden">
            <div id="photo-source-backdrop" class="absolute inset-0 bg-black/60"></div>
            <div id="photo-source-panel" class="absolute bottom-0 left-0 right-0 bg-slate-900 border-t border-slate-700 rounded-t-3xl p-5 pb-8 transition-transform duration-300 translate-y-full">
                <div class="w-12 h-1.5 rounded-full bg-slate-600 mx-auto mb-5"></div>
                <h3 class="text-white font-extrabold text-base">Upload Foto Before</h3>
                <p class="text-slate-400 text-xs mt-1 mb-5">Pilih sumber foto</p>
                <button type="button" id="photo-source-camera" class="w-full flex items-center gap-4 p-4 rounded-2xl border border-slate-700 bg-slate-800 text-left mb-3">
                    <i class="fas fa-camera text-yellow-400 text-xl w-8 text-center"></i>
                    <div>
                        <p class="text-white text-sm font-bold">Buka Kamera</p>
                        <p class="text-slate-400 text-xs mt-1">Ambil foto langsung</p>
                    </div>
                </button>
                <button type="button" id="photo-source-gallery" class="w-full flex items-center gap-4 p-4 rounded-2xl border border-slate-700 bg-slate-800 text-left">
                    <i class="fas fa-images text-blue-400 text-xl w-8 text-center"></i>
                    <div>
                        <p class="text-white text-sm font-bold">Pilih dari Galeri</p>
                        <p class="text-slate-400 text-xs mt-1">Pilih foto yang tersimpan</p>
                    </div>
                </button>
                <button type="button" id="photo-source-cancel" class="w-full mt-4 py-3 rounded-xl bg-slate-800 text-slate-300 text-sm font-bold">
                    Batal
                </button>
            </div>
        </div>
        `;

        addBtn.insertAdjacentHTML('beforebegin', template);

        const card = document.querySelector(`#item-container-${newId}`);
        if (!card) return;

        renumberItems();

        const itemId = Number(card.dataset.itemId);

        const defaultCategory = window.LAYANAN_DATA?.[0] || 'Package';

        card.dataset.selectedCategory = defaultCategory;
        card.dataset.selectedService = '';
        card.dataset.selectedType = '';
        card.dataset.previousCategory = defaultCategory;

        generateEditCategoryButtons(itemId);
        loadServicesForItem(itemId, defaultCategory);

        generateSpeedTreatments(itemId, 'reguler');
        generateExtraTreatments(itemId, []);

        updateSpeedAvailability(itemId, defaultCategory);

        recalculateGrandTotal();
    });
}

function updateSpeedAvailability(itemId, category, recalculate = false) {
    const container = document.querySelector(`#item-container-${itemId}`);
    if (!container) return;

    const normalizedCategory = String(category).trim().toLowerCase();
    const isRestricted = ['repair', 'package'].includes(normalizedCategory);
    const speedInputs = container.querySelectorAll(`input[name="speed[${itemId}]"]`);
    let speedChanged = false;

    speedInputs.forEach(input => {
        const speedName = input.dataset.speedName || '';
        const isRestrictedSpeed = speedName.includes('express') || speedName.includes('prioritas');

        if (!isRestrictedSpeed) return;

        input.disabled = isRestricted;

        const label = input.closest('label');
        if (label) {
            label.classList.toggle('opacity-100', isRestricted);
            label.classList.toggle('hover:border-slate-500', isRestricted);
        }

        if (isRestricted && input.checked) {
            const reguler = container.querySelector(`input[name="speed[${itemId}]"][value="reguler"]`);
            if (reguler) {
                reguler.checked = true;
                speedChanged = true;
            }
        }
    });

    if (recalculate && speedChanged) {
        updateItemSubtotal(itemId);
    }
}

function generateEditCategoryButtons(itemId) {
    const categories = window.LAYANAN_DATA || [];
    const container = document.querySelector(
        `#item-container-${itemId} .category-buttons`
    );

    const card = document.querySelector(`#item-container-${itemId}`);

    if (!container || !card) return;

    const selectedCategory = card.dataset.selectedCategory;
    const allCategories = [...new Set([...categories, 'Package'])];

    container.innerHTML = '';

    allCategories.forEach(category => {
        const button = document.createElement('button');
        const active = category === selectedCategory;

        button.type = 'button';
        button.dataset.category = category;
        button.className = `cat-btn py-2 text-[10px] font-bold border rounded-lg ${active
            ? 'border-brand-yellow bg-brand-yellow/10 text-brand-yellow'
            : 'border-slate-700 text-slate-400'
            }`;

        button.textContent = category;

        button.addEventListener('click', () => {
            const currentCard = button.closest('.item-dark-container');

            if (!currentCard) return;

            const currentId = Number(currentCard.dataset.itemId);

            selectEditCategory(currentId, category);
        });

        container.appendChild(button);
    });
}

function loadServicesForItem(itemId, category) {
    const card = document.querySelector(`#item-container-${itemId}`);
    const select = document.querySelector(`#service-select-${itemId}`);

    if (!card || !select) return;

    const selectedService = String(card.dataset.selectedService || '');
    const selectedType = String(card.dataset.selectedType || '');
    const services = window.PRICELIST_DATA[category] || [];

    select.innerHTML = '<option value="">Pilih Layanan</option>';

    services.forEach(service => {
        const option = document.createElement('option');

        option.value = service.id;
        option.dataset.type = service.tipe;
        option.dataset.price = service.price;

        const variasi = service.variasi ? ` (${service.variasi})` : '';
        const estimasi = `${service.estimasi_min}–${service.estimasi_max} hari`;

        option.textContent =
            `${service.name}${variasi} – Rp ${Number(service.price).toLocaleString('id-ID')} (${estimasi})`;

        if (
            String(service.id) === selectedService &&
            service.tipe === selectedType
        ) {
            option.selected = true;
        }

        select.appendChild(option);
    });

    const caption = card.querySelector('.category-caption');

    if (caption) {
        caption.textContent = category;
    }

    select.dispatchEvent(new Event('change', { bubbles: true }));
}

function selectEditCategory(itemId, category) {
    const card = document.querySelector(`#item-container-${itemId}`);

    if (!card) return;

    card.dataset.selectedCategory = category;

    card.querySelectorAll('.cat-btn').forEach(button => {
        const active = button.dataset.category === category;

        button.classList.toggle('border-brand-yellow', active);
        button.classList.toggle('bg-brand-yellow/10', active);
        button.classList.toggle('text-brand-yellow', active);
        button.classList.toggle('border-slate-700', !active);
        button.classList.toggle('text-slate-400', !active);
    });

    const caption = card.querySelector('.category-caption');

    if (caption) {
        caption.textContent = category;
    }

    const select = card.querySelector('.service-select');
    const previousService = card.dataset.selectedService || '';

    if (category !== card.dataset.previousCategory) {
        card.dataset.selectedService = '';
    }

    card.dataset.previousCategory = category;

    loadServicesForItem(itemId, category);

    if (select) {
        select.value = card.dataset.selectedService || '';
    }

    updateItemSubtotal(itemId);
}

function deleteEditItemCard(id) {
    const card = document.getElementById(`item-container-${id}`);

    if (!card) return;

    const hiddenIdInput = card.previousElementSibling;

    if (
        hiddenIdInput &&
        hiddenIdInput.matches('input[name^="id_detail_pesanan["]')
    ) {
        hiddenIdInput.remove();
    }

    card.remove();

    requestAnimationFrame(() => {
        renumberItems();
        recalculateGrandTotal();
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
    const itemCards = Array.from(
        document.querySelectorAll('.item-dark-container')
    );

    itemCards.forEach((card, index) => {
        const newId = String(index + 1);

        const hiddenIdInput = card.previousElementSibling;

        if (
            hiddenIdInput &&
            hiddenIdInput.matches('input[name^="id_detail_pesanan["]')
        ) {
            hiddenIdInput.name = `id_detail_pesanan[${newId}]`;
        }

        card.querySelectorAll('[name]').forEach(element => {
            if (element.name) {
                element.name = element.name.replace(
                    /\[\d+\]/,
                    `[${newId}]`
                );
            }
        });

        card.id = `item-container-${newId}`;
        card.dataset.itemId = newId;

        const numberElement = card.querySelector('h3 span');

        if (numberElement) {
            numberElement.textContent = newId;
        }

        card.querySelectorAll('[id]').forEach(element => {
            if (element.id) {
                element.id = element.id.replace(
                    /-\d+$/,
                    `-${newId}`
                );
            }
        });

        card.querySelectorAll('[data-item-id]').forEach(element => {
            element.dataset.itemId = newId;
        });

        const tipeInput = card.querySelector('.tipe-layanan');

        if (tipeInput) {
            tipeInput.name = `tipe_layanan[${newId}]`;
        }

        card.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.name = `speed[${newId}]`;
        });

        card.querySelectorAll('input[type="checkbox"][data-price]').forEach(input => {
            input.classList.remove(
                ...Array.from(input.classList).filter(className =>
                    className.startsWith('extra-check-')
                )
            );

            input.classList.add(`extra-check-${newId}`);
        });

        const deleteButton = card.querySelector('button[onclick*="ItemCard"]');

        if (deleteButton) {
            const isEdit = deleteButton.getAttribute('onclick')
                .includes('deleteEditItemCard');

            deleteButton.setAttribute(
                'onclick',
                `${isEdit ? 'deleteEditItemCard' : 'deleteItemCard'}(${newId})`
            );
        }

        card.querySelectorAll('[onchange]').forEach(element => {
            const onchange = element.getAttribute('onchange');

            if (onchange && onchange.includes('updateItemSubtotal')) {
                element.setAttribute(
                    'onchange',
                    onchange.replace(
                        /updateItemSubtotal\(\d+\)/g,
                        `updateItemSubtotal(${newId})`
                    )
                );
            }
        });
    });

    itemCounter = itemCards.length;
}
// 5. Photo Upload Preview Simulation

function initPhotoUploads() {
    const sheet = document.getElementById('photo-source-sheet');
    const panel = document.getElementById('photo-source-panel');
    const cameraBtn = document.getElementById('photo-source-camera');
    const galleryBtn = document.getElementById('photo-source-gallery');
    const cancelBtn = document.getElementById('photo-source-cancel');
    const backdrop = document.getElementById('photo-source-backdrop');

    if (!sheet || !panel || !cameraBtn || !galleryBtn || !cancelBtn || !backdrop) {
        console.error('HTML bottom sheet foto tidak ditemukan.');
        return;
    }

    if (sheet.dataset.initialized === 'true') return;
    sheet.dataset.initialized = 'true';

    let activeBox = null;
    let activeForm = null;
    let activeItemId = null;

    function bukaSheet() {
        sheet.classList.remove('hidden');

        requestAnimationFrame(() => {
            panel.classList.remove('translate-y-full');
        });
    }

    function tutupSheet() {
        panel.classList.add('translate-y-full');

        setTimeout(() => {
            sheet.classList.add('hidden');
        }, 300);
    }

    function buatInput(form, itemId, box, capture = false) {
        if (!form || !itemId || !box) return;

        const container = box.closest('.item-dark-container');

        if (!container) {
            console.error('Card barang tidak ditemukan.');
            return;
        }

        const input = document.createElement('input');

        input.type = 'file';
        input.name = `foto_before[${itemId}]`;
        input.accept = 'image/jpeg,image/png,image/webp';
        input.hidden = true;
        input.dataset.beforeUpload = 'true';
        input.dataset.itemId = String(itemId);

        if (capture) {
            input.setAttribute('capture', 'environment');
        }

        input.addEventListener('change', function () {
            const file = input.files?.[0];

            if (!file) {
                input.remove();
                return;
            }

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

            form.querySelectorAll('input[data-before-upload="true"]').forEach(oldInput => {
                if (oldInput !== input && oldInput.dataset.itemId === String(itemId)) {
                    oldInput.remove();
                }
            });

            const reader = new FileReader();

            reader.onload = function (event) {
                box.innerHTML = `
                    <img src="${event.target.result}"
                         class="w-full h-full object-cover rounded-2xl"
                         alt="Foto sebelum">
                `;

                box.classList.remove('border-dashed');
                box.dataset.photoSelected = 'true';
            };

            reader.readAsDataURL(file);
        });

        form.appendChild(input);
        input.click();
    }

    document.addEventListener('click', function (event) {
        const box = event.target.closest('.upload-box-trigger');

        if (!box) return;

        const container = box.closest('.item-dark-container');
        const form = box.closest('form');

        if (!container || !form) {
            console.error('Container barang atau form tidak ditemukan.');
            return;
        }

        const itemId = container.dataset.itemId;

        if (!itemId) {
            console.error('data-item-id tidak ditemukan.');
            return;
        }

        activeBox = box;
        activeForm = form;
        activeItemId = String(itemId);

        bukaSheet();
    });

    cameraBtn.addEventListener('click', function () {
        const box = activeBox;
        const form = activeForm;
        const itemId = activeItemId;

        tutupSheet();
        buatInput(form, itemId, box, true);
    });

    galleryBtn.addEventListener('click', function () {
        const box = activeBox;
        const form = activeForm;
        const itemId = activeItemId;

        tutupSheet();
        buatInput(form, itemId, box, false);
    });

    cancelBtn.addEventListener('click', tutupSheet);
    backdrop.addEventListener('click', tutupSheet);

    document.addEventListener('submit', function (event) {
        const form = event.target;

        if (!form.matches('form')) return;

        const containers = form.querySelectorAll('.item-dark-container');

        for (const container of containers) {
            const itemId = String(container.dataset.itemId);

            const existingPhoto = container.querySelector(
                '.upload-box-trigger img'
            );

            if (existingPhoto) {
                continue;
            }

            const input = form.querySelector(
                `input[data-before-upload="true"][data-item-id="${itemId}"]`
            );

            if (!input || !input.files || input.files.length === 0) {
                event.preventDefault();

                Swal.fire({
                    title: 'Foto Belum Diunggah!',
                    text: 'Silakan unggah foto Before untuk setiap barang sebelum menyimpan pesanan.',
                    icon: 'warning',
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'Mengerti'
                });

                const box = container.querySelector('.upload-box-trigger');

                if (box) {
                    box.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }

                return;
            }
        }
    });
}


function initPhotoAfterUploads() {
    const sheet = document.getElementById('photo-source-sheet-edit');
    const panel = document.getElementById('photo-source-panel-edit');
    const cameraBtn = document.getElementById('photo-source-camera-edit');
    const galleryBtn = document.getElementById('photo-source-gallery-edit');
    const cancelBtn = document.getElementById('photo-source-cancel-edit');
    const backdrop = document.getElementById('photo-source-backdrop-edit');

    if (!sheet || !panel || !cameraBtn || !galleryBtn || !cancelBtn || !backdrop) {
        console.error('HTML bottom sheet foto tidak ditemukan.');
        return;
    }

    if (sheet.dataset.afterInitialized === 'true') return;
    sheet.dataset.afterInitialized = 'true';

    let activeBox = null;
    let activeForm = null;
    let activeItemId = null;

    function bukaSheet() {
        sheet.classList.remove('hidden');

        requestAnimationFrame(() => {
            panel.classList.remove('translate-y-full');
        });
    }

    function tutupSheet() {
        panel.classList.add('translate-y-full');

        setTimeout(() => {
            sheet.classList.add('hidden');
        }, 300);
    }

    function buatInput(form, itemId, box, capture = false) {
        if (!form || !itemId || !box) return;

        const container = box.closest('.item-foto-after-container');

        if (!container) {
            console.error('Card barang tidak ditemukan.');
            return;
        }

        const input = document.createElement('input');

        input.type = 'file';
        input.name = `foto_after[${itemId}]`;
        input.accept = 'image/jpeg,image/png,image/webp';
        input.hidden = true;
        input.dataset.afterUpload = 'true';
        input.dataset.itemId = String(itemId);

        if (capture) {
            input.setAttribute('capture', 'environment');
        }

        input.addEventListener('change', function () {
            const file = input.files?.[0];

            if (!file) {
                input.remove();
                return;
            }

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

            form.querySelectorAll('input[data-after-upload="true"]').forEach(oldInput => {
                if (oldInput !== input && oldInput.dataset.itemId === String(itemId)) {
                    oldInput.remove();
                }
            });

            const reader = new FileReader();

            reader.onload = function (event) {
                box.innerHTML = `
                    <img src="${event.target.result}"
                         class="w-full h-full object-cover rounded-2xl"
                         alt="Foto sesudah dicuci">
                `;

                box.classList.remove('border-dashed');
                box.dataset.afterPhotoSelected = 'true';
            };

            reader.readAsDataURL(file);
        });

        form.appendChild(input);
        input.click();
    }

    document.addEventListener('click', function (event) {
        const box = event.target.closest('.upload-box-trigger-after');

        if (!box || !box.closest('[data-photo-type="after"]')) return;

        const container = box.closest('.item-foto-after-container');
        const form = box.closest('form');

        if (!container || !form) {
            console.error('Container barang atau form tidak ditemukan.');
            return;
        }

        const itemId = container.dataset.itemId;

        if (!itemId) {
            console.error('data-item-id tidak ditemukan.');
            return;
        }

        activeBox = box;
        activeForm = form;
        activeItemId = String(itemId);

        bukaSheet();
    });

    document.addEventListener('submit', function (event) {
        const form = event.target;

        if (form.id !== 'form-pesanan') return;

        const status = form.querySelector('[name="status"]')?.value;

        if (!['Selesai', 'Diambil'].includes(status)) return;

        const itemCards = form.querySelectorAll('.item-dark-container');
        const afterContainers = Array.from(
            form.querySelectorAll('.item-foto-after-container')
        );

        for (const itemCard of itemCards) {
            const itemId = itemCard.dataset.itemId;

            const afterContainer = afterContainers.find(
                container => container.dataset.itemId === itemId
            );

            const afterBox = afterContainer?.querySelector(
                '.upload-box-trigger-after'
            );

            const fotoAfter = afterBox?.querySelector('img');

            if (!fotoAfter) {
                event.preventDefault();

                Swal.fire({
                    icon: 'warning',
                    title: 'Foto After Belum Lengkap',
                    text: `Upload semua foto After sebelum status menjadi ${status}.`,
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#2563eb'
                }).then(() => {
                    afterBox?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                });

                return;
            }
        }
    });

    cameraBtn.addEventListener('click', function () {
        const box = activeBox;
        const form = activeForm;
        const itemId = activeItemId;

        tutupSheet();
        buatInput(form, itemId, box, true);
    });

    galleryBtn.addEventListener('click', function () {
        const box = activeBox;
        const form = activeForm;
        const itemId = activeItemId;

        tutupSheet();
        buatInput(form, itemId, box, false);
    });

    cancelBtn.addEventListener('click', tutupSheet);
    backdrop.addEventListener('click', tutupSheet);
}

// 6. Master-Detail Order Selection:
// - On Desktop: Seamless in-place switch on right panel (Persis 100% Desain Asli)
// - On Mobile: Navigate to detail_pesanan.php without scrolling
function initMasterDetailOrders() {
    const cards = document.querySelectorAll('.order-card-item');

    if (!cards.length) return;

    const el = {
        title: document.getElementById('detail-title'),
        subtitle: document.getElementById('detail-subtitle'),
        dateIn: document.getElementById('detail-date-in'),
        dateOut: document.getElementById('detail-date-out'),
        dateEstimasi: document.getElementById('detail-estimasi'),
        statusPay: document.getElementById('detail-status-pay'),
        dropPoint: document.getElementById('detail-drop-point'),
        status: document.getElementById('detail-status-select'),
        itemsCount: document.getElementById('detail-items-count'),
        items: document.getElementById('detail-items-container'),
        total: document.getElementById('detail-total'),
        whatsappCustomer: document.getElementById('detail-whatsapp-customer'),
        edit: document.getElementById('detail-edit-link'),
        invoice: document.getElementById('detail-invoice-link'),
        sendDocumentation: document.getElementById('btn-kirim-dokumentasi')
    };

    function rupiah(value) {
        return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
    }

    function tanggal(value) {
        if (!value) return 'Belum selesai';

        const date = new Date(value);

        return Number.isNaN(date.getTime())
            ? value
            : date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                timeZone: 'UTC'
            });
    }

    function formatNomorWhatsApp(nomor) {
        let hasil = String(nomor || '').replace(/\D/g, '');

        if (hasil.startsWith('0')) {
            hasil = '62' + hasil.substring(1);
        } else if (hasil.startsWith('8')) {
            hasil = '62' + hasil;
        }

        return hasil;
    }

    // function buatUrlFoto(foto) {
    //     if (!foto) return '';

    //     const path = String(foto);

    //     if (/^https?:\/\//i.test(path)) {
    //         return path;
    //     }

    //     return new URL('/' + path.replace(/^\/+/, ''), window.location.origin).href;
    // }

    function buatUrlFoto(foto) {
        if (!foto) return '';

        const path = String(foto);

        if (/^https?:\/\//i.test(path)) {
            return path;
        }

        return new URL(
            '/' + path.replace(/^\/+/, ''),
            'https://newer-enable-wheels-marc.trycloudflare.com'
        ).href;
    }

    function aturTombolDokumentasi(pesanan, barang) {
        if (!el.sendDocumentation) return;

        const nomor = formatNomorWhatsApp(pesanan.no_hp);

        const semuaFotoTersedia = barang.length > 0 &&
            barang.every(item => item.foto_sesudah);

        const bisaKirim = nomor.length >= 10 && semuaFotoTersedia;

        el.sendDocumentation.disabled = !bisaKirim;

        el.sendDocumentation.classList.toggle('bg-slate-200', !bisaKirim);
        el.sendDocumentation.classList.toggle('text-slate-400', !bisaKirim);
        el.sendDocumentation.classList.toggle('cursor-not-allowed', !bisaKirim);

        el.sendDocumentation.classList.toggle('bg-brand-yellow', bisaKirim);
        el.sendDocumentation.classList.toggle('text-slate-900', bisaKirim);
        el.sendDocumentation.classList.toggle('cursor-pointer', bisaKirim);

        el.sendDocumentation.onclick = null;

        if (!bisaKirim) return;


        el.sendDocumentation.onclick = function () {
            const daftarFoto = barang.map((item, index) => {
                return `${index + 1}. ${item.nama_barang || 'Barang'}\n${buatUrlFoto(item.foto_sesudah)}`;
            }).join('\n\n');

            const kodePesanan = String(pesanan.kode_pesanan || '-').replace(/^#+/, '');

            const pesan = [
                `Halo Kak ${pesanan.nama_customer || 'Customer'},`,
                '',
                'Terima kasih telah mempercayakan perawatan barang Anda kepada *KickDropShoe*.',
                '',
                'Kami informasikan bahwa pesanan Anda telah selesai dikerjakan.',
                '',
                '*DETAIL PESANAN*',
                `Kode Pesanan: #${kodePesanan}`,
                `Jumlah Barang: ${barang.length} item`,
                '',
                '*DOKUMENTASI FOTO AFTER*',
                '',
                daftarFoto,
                '',
                'Semoga hasil pengerjaan kami memuaskan dan membuat barang Anda kembali bersih serta nyaman digunakan.',
                '',
                'Terima kasih telah menggunakan layanan *KickDropShoe*!',
                '',
                'Salam bersih,',
                '*Tim KickDropShoe*'
            ].join('\n');

            const url = `https://wa.me/${nomor}?text=${encodeURIComponent(pesan)}`;

            window.open(url, '_blank', 'noopener,noreferrer');
        };
    }

    function buatElemen(tag, className, text) {
        const node = document.createElement(tag);

        node.className = className;
        node.textContent = text ?? '';

        return node;
    }

    function tampilkanBarang(barang) {
        if (!el.items) return;

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
                    buatElemen(
                        'p',
                        '',
                        'Catatan: ' + item.catatan
                    )
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

        if (el.sendDocumentation) {
            el.sendDocumentation.disabled = true;
            el.sendDocumentation.onclick = null;
        }

        if (!id) {
            console.error('ID pesanan tidak ditemukan pada kartu.');
            return;
        }

        if (window.innerWidth >= 1024) {
            cards.forEach(function (item) {
                item.classList.remove(
                    'border-brand-yellow',
                    'border-2',
                    'bg-yellow-50/30',
                    'shadow-sm',
                    'relative'
                );

                item.classList.add(
                    'border',
                    'border-slate-200',
                    'bg-white'
                );
            });

            card.classList.remove(
                'border',
                'border-slate-200',
                'bg-white'
            );

            card.classList.add(
                'border-2',
                'border-brand-yellow',
                'bg-yellow-50/30',
                'shadow-sm',
                'relative'
            );
        }

        if (el.title) {
            el.title.textContent = 'Memuat detail pesanan...';
        }

        if (el.items) {
            el.items.replaceChildren(
                buatElemen(
                    'p',
                    'text-xs text-slate-500',
                    'Mengambil data dari database...'
                )
            );
        }

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

            if (el.title) {
                el.title.textContent =
                    'Detail Pesanan ' + (p.kode_pesanan || '');
            }

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

            if (el.dateEstimasi) {
                el.dateEstimasi.textContent = p.estimasi_selesai
                    ? tanggal(p.estimasi_selesai)
                    : '-';
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
                    (p.status_bayar || '-') + (p.metode_bayar ? ' – ' + p.metode_bayar : '')
                );

                if (p.status_bayar === 'Lunas') {
                    payment.classList.add(
                        'text-emerald-600',
                        'bg-emerald-100'
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

            if (typeof tampilkanDokumentasiFoto === 'function') {
                tampilkanDokumentasiFoto(barang);
            }

            if (el.whatsappCustomer) {
                el.whatsappCustomer.textContent =
                    (p.nama_customer || '-') + ' • ' + (p.no_hp || '-');
            }

            if (el.edit) {
                el.edit.href =
                    '/pesanan/edit?id=' + encodeURIComponent(p.id_pesanan);
            }

            aturTombolDokumentasi(p, barang);

            if (el.invoice) {
                el.invoice.href =
                    '/invoice?id=' + encodeURIComponent(p.id_pesanan);
            }

        } catch (error) {
            console.error('Gagal memuat detail pesanan:', error);

            if (el.title) {
                el.title.textContent = 'Gagal memuat detail';
            }

            if (el.items) {
                el.items.replaceChildren(
                    buatElemen(
                        'p',
                        'text-xs text-red-600',
                        error.message || 'Terjadi kesalahan saat memuat data.'
                    )
                );
            }
        }
    }

    cards.forEach(function (card) {
        card.addEventListener('click', function () {
            if (window.innerWidth < 1024) {
                if (event.target.closest('a')) {
                    return;
                }

                window.location.href = '/pesanan/detail?id=' + encodeURIComponent(card.dataset.id);
                return;
            }

            pilihPesanan(card);
        });
    });

    if (window.innerWidth >= 1024) {
        pilihPesanan(cards[0]);
    }
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

function generateSpeedTreatments(itemId, selectedTreatmentId = 'reguler') {
    const container = document.querySelector(`#item-container-${itemId} .speed-container`);
    if (!container) return;

    const treatments = window.TREATMENT_KECEPATAN || [];

    const options = [
        {
            id_treatment_tambahan: 'reguler',
            nama_treatment: 'Reguler',
            harga_min: 0,
            estimasi_min_hari: 4,
            estimasi_max_hari: 5
        },
        ...treatments
    ];

    container.innerHTML = options.map(item => {
        const id = String(item.id_treatment_tambahan);
        const name = String(item.nama_treatment).toLowerCase();
        const checked = id === String(selectedTreatmentId);
        const price = Number(item.harga_min || 0);
        const min = Number(item.estimasi_min_hari || 0);
        const max = Number(item.estimasi_max_hari || min);

        return `
            <label class="p-3 border ${checked
                ? 'border-2 border-brand-yellow bg-brand-yellow/10'
                : 'border-slate-700'
            } rounded-xl flex flex-col items-center justify-center cursor-pointer transition">
                <input
                    type="radio"
                    name="speed[${itemId}]"
                    value="${id}"
                    data-speed-name="${name}"
                    data-price="${price}"
                    ${checked ? 'checked' : ''}
                    onchange="updateItemSubtotal(${itemId})"
                    class="text-brand-yellow mb-1"
                >
                <span class="speed-title text-[10px] font-bold ${checked ? 'text-brand-yellow' : 'text-slate-400'}">${item.nama_treatment}</span>
                ${id !== 'reguler' ? `
                    <span class="text-[9px] text-slate-400">
                        ${min}–${max} Hari • +${price.toLocaleString('id-ID')}
                    </span>
                ` : ''}
            </label>
        `;
    }).join('');

    const card = document.querySelector(`#item-container-${itemId}`);
    if (card) {
        updateSpeedAvailability(itemId, card.dataset.selectedCategory || '');
    }
}

function generateExtraTreatments(itemId, selectedTreatmentIds = [], savedPrices = {}) {
    const container = document.querySelector(
        `#item-container-${itemId} .extra-treatment-container`
    );

    if (!container) return;

    const treatments = window.TREATMENT_TAMBAHAN || [];
    const selectedIds = selectedTreatmentIds.map(String);

    container.innerHTML = treatments.map(item => {
        const id = String(item.id_treatment_tambahan);
        const checked = selectedIds.includes(id);
        const price = Number(item.harga_min || 0);
        const min = Number(item.estimasi_min_hari || 0);
        const max = Number(item.estimasi_max_hari || min);

        return `
            <label class="flex justify-between items-center p-3.5 border border-slate-700/90 bg-slate-800/50 rounded-xl cursor-pointer hover:border-slate-500 transition">
                <div class="flex items-center gap-3">
                    <input
                        type="checkbox"
                        name="treatment_tambahan[${itemId}][]"
                        value="${id}"
                        data-price="${price}"
                        data-manual="${item.harga_manual ? 1 : 0}"
                        ${checked ? 'checked' : ''}
                        onchange="updateItemSubtotal(${itemId})"
                        class="extra-check-${itemId} w-4 h-4 rounded text-brand-yellow bg-slate-900 border-slate-600 focus:ring-brand-yellow"
                    >
                    <span class="text-xs font-bold text-white">
                        ${item.nama_treatment} (${min}–${max} Hari)
                    </span>
                </div>
                <span class="text-xs font-bold text-brand-yellow">
                    ${item.harga_manual
                ? '+' + price.toLocaleString('id-ID') + ' – ' + Number(item.harga_max).toLocaleString('id-ID')
                : (price > 0 ? '+' + price.toLocaleString('id-ID') : 'Free')}
                </span>
            </label>
            ${manualPriceHtml(itemId, item, checked, savedPrices[id])}
        `;
    }).join('');
}

function initCustomerDropdown() {
    const container = document.getElementById('customer-field');
    const inputNama = document.getElementById('nama_pelanggan');
    const inputWa = document.getElementById('no_wa');
    const dropdown = document.getElementById('customer-dropdown');
    const toggleButton = document.getElementById('toggle-customer-dropdown');
    const options = document.querySelectorAll('.customer-option');
    const emptyMessage = document.getElementById('customer-empty');

    if (!container || !inputNama || !inputWa || !dropdown || !toggleButton) return;

    function filterCustomers() {
        const keyword = inputNama.value.toLowerCase().trim();
        let found = 0;

        options.forEach(option => {
            const nama = option.dataset.nama.toLowerCase();
            const noWa = option.dataset.noWa.toLowerCase();

            const cocok = nama.includes(keyword) || noWa.includes(keyword);

            option.classList.toggle('hidden', !cocok);

            if (cocok) found++;
        });

        emptyMessage.classList.toggle('hidden', found > 0);
    }

    function openDropdown() {
        dropdown.classList.remove('hidden');
        filterCustomers();
    }

    function closeDropdown() {
        dropdown.classList.add('hidden');
    }

    inputNama.addEventListener('focus', openDropdown);

    inputNama.addEventListener('input', function () {
        openDropdown();

        const namaSaatIni = this.value.trim();

        const pelanggan = Array.from(options).find(option => {
            return option.dataset.nama === namaSaatIni;
        });

        if (!pelanggan) {
            inputWa.value = '';
        }
    });

    toggleButton.addEventListener('click', function () {
        if (dropdown.classList.contains('hidden')) {
            openDropdown();
            inputNama.focus();
        } else {
            closeDropdown();
        }
    });

    options.forEach(option => {
        option.addEventListener('click', function () {
            inputNama.value = this.dataset.nama;
            inputWa.value = this.dataset.noWa;

            closeDropdown();
        });
    });

    document.addEventListener('click', function (event) {
        if (!container.contains(event.target)) {
            closeDropdown();
        }
    });
}

function initCustomerEditDropdown() {
    const container = document.getElementById('customer-edit-field');
    const inputNama = document.getElementById('nama_customer');
    const inputWa = document.getElementById('no_wa');
    const dropdown = document.getElementById('customer-edit-dropdown');
    const toggleButton = document.getElementById('toggle-customer-edit-dropdown');
    const options = document.querySelectorAll('.customer-edit-option');
    const emptyMessage = document.getElementById('customer-edit-empty');

    if (!container || !inputNama || !inputWa || !dropdown || !toggleButton) return;

    function filterCustomers() {
        const keyword = inputNama.value.toLowerCase().trim();
        let found = 0;

        options.forEach(option => {
            const nama = option.dataset.nama.toLowerCase();
            const noWa = option.dataset.noWa.toLowerCase();
            const cocok = nama.includes(keyword) || noWa.includes(keyword);
            option.classList.toggle('hidden', !cocok);
            if (cocok) found++;
        });

        emptyMessage.classList.toggle('hidden', found > 0);
    }

    function openDropdown() {
        dropdown.classList.remove('hidden');
        filterCustomers();
    }

    function closeDropdown() {
        dropdown.classList.add('hidden');
    }

    inputNama.addEventListener('focus', openDropdown);

    inputNama.addEventListener('input', function () {
        openDropdown();
        const namaSaatIni = this.value.trim();
        const pelanggan = Array.from(options).find(option => option.dataset.nama === namaSaatIni);
        if (!pelanggan) inputWa.value = '';
    });

    toggleButton.addEventListener('click', function () {
        if (dropdown.classList.contains('hidden')) {
            openDropdown();
            inputNama.focus();
        } else {
            closeDropdown();
        }
    });

    options.forEach(option => {
        option.addEventListener('click', function () {
            inputNama.value = this.dataset.nama;
            inputWa.value = this.dataset.noWa;
            closeDropdown();
        });
    });

    document.addEventListener('click', function (event) {
        if (!container.contains(event.target)) closeDropdown();
    });
}

document.addEventListener('click', function (event) {
    const button = event.target.closest('.cat-btn');
    if (!button) return;

    const container = button.closest('.item-dark-container');
    if (!container) return;

    updateSpeedAvailability(container.dataset.itemId, button.dataset.category, true);
});

document.addEventListener('DOMContentLoaded', () => {
    console.log('KickDropShoe Engine Loaded.');

    const selectedData = window.TREATMENT_SELECTED || {};

    document.querySelectorAll('.item-dark-container').forEach(card => {
        const itemId = card.dataset.itemId;
        const saved = selectedData[itemId] || {
            speed_id: null,
            extra_ids: []
        };

        generateSpeedTreatments(
            itemId,
            saved.speed_id ?? 'reguler'
        );

        generateExtraTreatments(
            itemId,
            saved.extra_ids,
            saved.extra_prices
        );

        generateEditCategoryButtons(itemId);
        loadServicesForItem(itemId, card.dataset.selectedCategory);
    });

    initCategoryButtons();
    initExtraTreatmentCalculations();
    initPhotoUploads();
    initPhotoAfterUploads();
    initOrderFilter();
    initMasterDetailOrders();
    initAddItemButton();
    initEditAddItemButton();
    recalculateGrandTotal();
    initCustomerDropdown();
    initCustomerEditDropdown();
});
