<?php
if (strpos($_SERVER['REQUEST_URI'], "layouts")) {
    exit(header("Location:../index.php"));
}

// 1. Ambil data Poliklinik Aktif dari Database
$query_chatbot_poli = bukaquery("SELECT kd_poli, nm_poli FROM poliklinik WHERE status='1' ORDER BY nm_poli");
$chatbot_polis = [];
while ($row = mysqli_fetch_array($query_chatbot_poli)) {
    $chatbot_polis[] = [
        'kd' => $row['kd_poli'],
        'nama' => $row['nm_poli']
    ];
}

// 2. Ambil data Jadwal Praktek Dokter dari Database
$query_chatbot_jadwal = bukaquery("
    SELECT 
        dokter.kd_dokter,
        dokter.nm_dokter,
        poliklinik.kd_poli,
        poliklinik.nm_poli,
        jadwal.hari_kerja,
        jadwal.jam_mulai,
        jadwal.jam_selesai
    FROM jadwal
    INNER JOIN dokter ON jadwal.kd_dokter = dokter.kd_dokter
    INNER JOIN poliklinik ON jadwal.kd_poli = poliklinik.kd_poli
    WHERE dokter.status = '1'
    ORDER BY 
        poliklinik.nm_poli,
        dokter.nm_dokter,
        FIELD(
            jadwal.hari_kerja,
            'SENIN',
            'SELASA',
            'RABU',
            'KAMIS',
            'JUMAT',
            'SABTU',
            'AKHAD'
        )
");
$chatbot_schedules = [];
while ($row = mysqli_fetch_array($query_chatbot_jadwal)) {
    // Format nama dokter agar lebih rapi
    $nama_dokter = preg_replace_callback(
        '/^(dr\.\s*)([^,]+)(.*)$/i',
        function ($match) {
            return $match[1] . ucwords(strtolower($match[2])) . $match[3];
        },
        $row['nm_dokter']
    );

    $chatbot_schedules[] = [
        'kd_dokter' => $row['kd_dokter'],
        'nama_dokter' => $nama_dokter,
        'kd_poli' => $row['kd_poli'],
        'nama_poli' => $row['nm_poli'],
        'hari' => $row['hari_kerja'],
        'jam' => date("H:i", strtotime($row['jam_mulai'])) . " - " . date("H:i", strtotime($row['jam_selesai']))
    ];
}

// Info instansi untuk chatbot
$instansi_nama = $_SESSION["nama_instansi"] ?? "RSI Mabarrot MWC NU Bungah";
$instansi_alamat = $_SESSION["alamat_instansi"] ?? "Jl. Raya Bungah No.46, Bungah";
$instansi_kab = $_SESSION["kabupaten"] ?? "Gresik";
$instansi_kontak = $_SESSION["kontak"] ?? "-";
$instansi_email = $_SESSION["email"] ?? "-";
?>

<!-- Chatbot Floating UI -->
<div id="epasien-chatbot-container" class="fixed bottom-6 right-6 z-100 flex flex-col items-end gap-3 font-sans">
    
    <!-- Floating Tip Badge (Muncul otomatis di awal) -->
    <div id="chatbot-tip-badge" class="bg-white border border-slate-200/80 px-4 py-2.5 rounded-2xl shadow-lg flex items-center gap-2 max-w-[240px] text-xs font-semibold text-slate-700 animate-bounce transition-all duration-500 mr-2">
        <span class="flex h-2 w-2 rounded-full bg-emerald-500"></span>
        <span>Ada yang bisa saya bantu? Tanya di sini!</span>
        <button onclick="closeChatbotTip(event)" class="text-slate-400 hover:text-slate-600 ml-1">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Trigger Button -->
    <button id="chatbot-trigger" onclick="toggleChatbot()" class="relative flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-tr from-nu-600 to-emerald-500 text-white shadow-xl shadow-emerald-700/20 hover:scale-105 border border-white/20 transition-all duration-300 cursor-pointer">
        <!-- Pulse effect rings -->
        <span class="absolute inset-0 rounded-full bg-emerald-500/30 animate-ping -z-10"></span>
        <i id="chatbot-trigger-icon" class="fas fa-comment-dots text-2xl transition-all duration-300"></i>
    </button>

    <!-- Chat Window Box -->
    <div id="chatbot-window" class="fixed bottom-24 right-6 z-100 w-96 max-w-[calc(100vw-32px)] h-[550px] max-h-[calc(100vh-120px)] flex flex-col rounded-[28px] bg-white/95 backdrop-blur-md border border-slate-200/80 shadow-2xl overflow-hidden transform scale-0 opacity-0 origin-bottom-right transition-all duration-300 pointer-events-none">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-nu-700 to-nu-600 text-white px-5 py-4 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white/10 border border-white/20 text-white text-xl">
                        <i class="fas fa-user-nurse"></i>
                    </span>
                    <span class="absolute -bottom-0.5 -right-0.5 flex h-3 w-3 rounded-full bg-emerald-400 border-2 border-nu-700"></span>
                </div>
                <div>
                    <h3 class="font-bold text-sm leading-tight tracking-wide">Asisten Virtual</h3>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                        <span class="text-[10px] text-emerald-200/90 font-medium uppercase tracking-wider">Aktif Melayani</span>
                    </div>
                </div>
            </div>
            
            <button onclick="toggleChatbot()" class="h-8 w-8 flex items-center justify-center rounded-xl bg-white/10 hover:bg-white/20 border border-white/5 transition-all text-white cursor-pointer">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Chat History Area -->
        <div id="chatbot-messages" class="flex-1 overflow-y-auto p-5 space-y-4 bg-slate-50/50">
            <!-- Messages are injected here by JS -->
        </div>

        <!-- Typing Indicator -->
        <div id="chatbot-typing" class="hidden px-5 py-3 bg-slate-50/50 border-t border-slate-100">
            <div class="flex items-center gap-2 text-slate-400 text-xs">
                <span class="flex h-6 w-6 items-center justify-center rounded-xl bg-white border border-slate-200/60 shadow-xs">
                    <i class="fas fa-user-nurse text-xs text-nu-600"></i>
                </span>
                <div class="flex gap-1 py-1 px-3 bg-white border border-slate-200/60 rounded-2xl rounded-tl-sm shadow-xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce" style="animation-delay: 0.1s"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce" style="animation-delay: 0.2s"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce" style="animation-delay: 0.3s"></span>
                </div>
            </div>
        </div>

        <!-- Input Area Footer -->
        <div class="p-3.5 bg-white border-t border-slate-200/80">
            <form id="chatbot-form" onsubmit="handleChatSubmit(event)" class="flex gap-2">
                <input id="chatbot-input" type="text" placeholder="Tanya sesuatu atau ketik nama dokter/poli..." autocomplete="off" class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:bg-white transition-all">
                <button type="submit" class="h-10 w-10 flex items-center justify-center rounded-2xl bg-nu-600 hover:bg-nu-700 text-white shadow-md shadow-emerald-700/10 active:scale-95 transition-all cursor-pointer">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
/* Custom style overrides for high premium quality experience */
#chatbot-messages::-webkit-scrollbar {
    width: 6px;
}
#chatbot-messages::-webkit-scrollbar-track {
    background: transparent;
}
#chatbot-messages::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 9999px;
}
#chatbot-messages::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

@keyframes pulse-ring {
    0% {
        transform: scale(0.95);
        opacity: 0.5;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.3;
    }
    100% {
        transform: scale(1.3);
        opacity: 0;
    }
}

.chatbot-pill {
    transition: all 0.2s ease;
    border: 1px solid #e2e8f0;
}
.chatbot-pill:hover {
    border-color: #a7f3d0;
    background-color: #ecfdf5;
    color: #065f46;
    transform: translateY(-1px);
}
</style>

<script>
// Data dinamis dari PHP
const CHATBOT_POLIS = <?php echo json_encode($chatbot_polis); ?>;
const CHATBOT_SCHEDULES = <?php echo json_encode($chatbot_schedules); ?>;

const INSTANSI_NAMA = <?php echo json_encode($instansi_nama); ?>;
const INSTANSI_ALAMAT = <?php echo json_encode($instansi_alamat); ?>;
const INSTANSI_KAB = <?php echo json_encode($instansi_kab); ?>;
const INSTANSI_KONTAK = <?php echo json_encode($instansi_kontak); ?>;
const INSTANSI_EMAIL = <?php echo json_encode($instansi_email); ?>;

let isChatbotOpen = false;
let hasGreeted = false;

// Sembunyikan floating tip badge setelah 8 detik otomatis jika tidak diclose
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        const badge = document.getElementById('chatbot-tip-badge');
        if (badge && !isChatbotOpen) {
            badge.style.opacity = '0';
            setTimeout(() => badge.remove(), 500);
        }
    }, 10000);
});

function closeChatbotTip(e) {
    if (e) e.stopPropagation();
    const badge = document.getElementById('chatbot-tip-badge');
    if (badge) {
        badge.style.opacity = '0';
        setTimeout(() => badge.remove(), 500);
    }
}

function toggleChatbot() {
    isChatbotOpen = !isChatbotOpen;
    const windowEl = document.getElementById('chatbot-window');
    const iconEl = document.getElementById('chatbot-trigger-icon');
    const triggerEl = document.getElementById('chatbot-trigger');
    
    // Close the tip badge if open
    closeChatbotTip();

    if (isChatbotOpen) {
        // Open animation
        windowEl.classList.remove('scale-0', 'opacity-0', 'pointer-events-none');
        windowEl.classList.add('scale-100', 'opacity-100');
        iconEl.className = 'fas fa-times text-2xl rotate-90';
        triggerEl.classList.remove('bg-gradient-to-tr', 'from-nu-600', 'to-emerald-500');
        triggerEl.classList.add('bg-slate-700');
        
        // Initial greeting
        if (!hasGreeted) {
            sendInitialGreeting();
            hasGreeted = true;
        }
        
        // Scroll to bottom
        setTimeout(() => {
            const msgs = document.getElementById('chatbot-messages');
            msgs.scrollTop = msgs.scrollHeight;
        }, 100);
    } else {
        // Close animation
        windowEl.classList.remove('scale-100', 'opacity-100');
        windowEl.classList.add('scale-0', 'opacity-0', 'pointer-events-none');
        iconEl.className = 'fas fa-comment-dots text-2xl';
        triggerEl.classList.remove('bg-slate-700');
        triggerEl.classList.add('bg-gradient-to-tr', 'from-nu-600', 'to-emerald-500');
    }
}

function appendMessage(sender, content, isHtml = false) {
    const messagesContainer = document.getElementById('chatbot-messages');
    if (!messagesContainer) return;

    const messageDiv = document.createElement('div');
    messageDiv.className = `flex ${sender === 'user' ? 'justify-end' : 'justify-start'} items-start gap-2.5 animate-fadeIn`;
    
    // Animasi fadeIn kustom via CSS class
    messageDiv.style.animation = 'fadeIn 0.25s ease forwards';

    const avatarHtml = sender === 'bot' 
        ? `<span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-xl bg-emerald-50 border border-emerald-100 text-nu-600 text-[10px]"><i class="fas fa-user-nurse"></i></span>`
        : '';

    const bubbleClasses = sender === 'user'
        ? 'bg-nu-600 text-white rounded-2xl rounded-tr-sm shadow-sm font-medium'
        : 'bg-white border border-slate-200 text-slate-800 rounded-2xl rounded-tl-sm shadow-xs font-normal';

    const textEl = document.createElement('div');
    textEl.className = `px-3.5 py-2.5 text-xs max-w-[280px] break-words ${bubbleClasses}`;
    
    if (isHtml) {
        textEl.innerHTML = content;
    } else {
        textEl.textContent = content;
    }

    if (sender === 'bot') {
        messageDiv.innerHTML = avatarHtml;
        messageDiv.appendChild(textEl);
    } else {
        messageDiv.appendChild(textEl);
    }

    messagesContainer.appendChild(messageDiv);
    
    // Auto scroll to bottom
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function showTyping(show) {
    const typingEl = document.getElementById('chatbot-typing');
    if (!typingEl) return;
    if (show) {
        typingEl.classList.remove('hidden');
        const msgs = document.getElementById('chatbot-messages');
        msgs.scrollTop = msgs.scrollHeight;
    } else {
        typingEl.classList.add('hidden');
    }
}

function sendInitialGreeting() {
    showTyping(true);
    setTimeout(() => {
        showTyping(false);
        const greetingText = `Halo! Selamat datang di Portal E-Pasien <b>${INSTANSI_NAMA}</b>. Saya Asisten Virtual yang siap membantu Anda menjawab pertanyaan dengan cepat. <br><br>Pilih salah satu menu di bawah ini untuk memulai:`;
        appendMessage('bot', greetingText, true);
        showMenuOptions();
    }, 700);
}

function showMenuOptions() {
    const menuContainer = document.createElement('div');
    menuContainer.className = 'grid gap-2 mt-2';
    
    const options = [
        { text: 'Ada poli spesialis apa saja?', action: 'ask_polis' },
        { text: 'Info jadwal poli (...nama poli)', action: 'ask_jadwal_pilihan' },
        { text: 'Gimana cara daftar poliklinik?', action: 'ask_daftar' },
        { text: 'Dimana alamat rumah sakit?', action: 'ask_alamat' }
    ];

    options.forEach(opt => {
        const btn = document.createElement('button');
        btn.className = 'chatbot-pill text-left px-3.5 py-2.5 bg-white text-slate-700 font-semibold rounded-2xl text-xs hover:shadow-sm transition-all duration-200 border border-slate-200 cursor-pointer flex justify-between items-center';
        btn.innerHTML = `<span>${opt.text}</span> <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>`;
        btn.onclick = () => handleMenuAction(opt.text, opt.action);
        menuContainer.appendChild(btn);
    });

    const messagesContainer = document.getElementById('chatbot-messages');
    messagesContainer.appendChild(menuContainer);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function handleMenuAction(userText, action) {
    // Tampilkan pesan user
    appendMessage('user', userText);
    
    showTyping(true);
    
    setTimeout(() => {
        showTyping(false);
        
        switch (action) {
            case 'ask_polis':
                respondPolis();
                break;
            case 'ask_jadwal_pilihan':
                respondJadwalPilihan();
                break;
            case 'ask_daftar':
                respondDaftar();
                break;
            case 'ask_alamat':
                respondAlamat();
                break;
        }
    }, 600);
}

function respondPolis() {
    if (CHATBOT_POLIS.length === 0) {
        appendMessage('bot', 'Saat ini tidak ada data poliklinik aktif di sistem kami.');
        return;
    }
    
    let poliList = CHATBOT_POLIS.map(p => `• <b>${p.nama}</b>`).join('<br>');
    let responseHtml = `Kami melayani berbagai poliklinik spesialis yang didukung oleh dokter-dokter profesional di bidangnya. Berikut adalah daftar poliklinik spesialis yang tersedia:<br><br>${poliList}<br><br>Apakah Anda ingin melihat jadwal dokter untuk salah satu poliklinik di atas? Silakan klik tombol <b>Info Jadwal Poli</b>.`;
    appendMessage('bot', responseHtml, true);
    
    // Tampilkan tombol menu kembali
    showOptionsShortcut();
}

function respondJadwalPilihan() {
    if (CHATBOT_POLIS.length === 0) {
        appendMessage('bot', 'Tidak ada data poliklinik aktif untuk menampilkan jadwal.');
        return;
    }

    appendMessage('bot', 'Silakan pilih poliklinik berikut untuk melihat jadwal dokter praktek:');
    
    const poliGrid = document.createElement('div');
    poliGrid.className = 'grid grid-cols-2 gap-2 mt-2';

    CHATBOT_POLIS.forEach(p => {
        const btn = document.createElement('button');
        btn.className = 'chatbot-pill text-center px-2 py-2 bg-white text-slate-700 font-semibold rounded-xl text-[11px] border border-slate-200 transition-all hover:scale-101 cursor-pointer truncate';
        btn.textContent = p.nama;
        btn.onclick = () => {
            appendMessage('user', `Info jadwal poli ${p.nama}`);
            showTyping(true);
            setTimeout(() => {
                showTyping(false);
                respondJadwalPoli(p.nama);
            }, 600);
        };
        poliGrid.appendChild(btn);
    });

    const messagesContainer = document.getElementById('chatbot-messages');
    messagesContainer.appendChild(poliGrid);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function respondJadwalPoli(namaPoli) {
    const schedules = CHATBOT_SCHEDULES.filter(s => s.nama_poli.toLowerCase().trim() === namaPoli.toLowerCase().trim());
    
    if (schedules.length === 0) {
        appendMessage('bot', `Maaf, saat ini belum ada dokter atau jadwal praktek terdaftar untuk <b>Poli ${namaPoli}</b>.`, true);
        showOptionsShortcut();
        return;
    }

    // Kelompokkan jadwal berdasarkan dokter
    const docMap = {};
    schedules.forEach(s => {
        if (!docMap[s.nama_dokter]) {
            docMap[s.nama_dokter] = [];
        }
        docMap[s.nama_dokter].push(s);
    });

    let html = `Berikut adalah jadwal praktek dokter spesialis di <b>Poli ${namaPoli}</b>:<br><br>`;
    
    for (const [dokter, sesi] of Object.entries(docMap)) {
        html += `<div class="bg-emerald-50/50 p-2.5 rounded-xl border border-emerald-100/70 mb-2">`;
        html += `<p class="font-bold text-slate-800 text-[11px] flex items-center gap-1.5"><i class="fas fa-user-md text-emerald-700 text-xs"></i> ${dokter}</p>`;
        html += `<div class="mt-1.5 space-y-1">`;
        sesi.forEach(s => {
            html += `<div class="flex justify-between text-[10px] text-slate-600 bg-white px-2 py-1 rounded-md shadow-3xs border border-slate-100">`;
            html += `<span class="font-medium text-slate-700">${s.hari}</span>`;
            html += `<span class="font-semibold text-emerald-800">${s.jam} WIB</span>`;
            html += `</div>`;
        });
        html += `</div></div>`;
    }

    appendMessage('bot', html, true);
    showOptionsShortcut();
}

function respondDaftar() {
    const daftarText = `Cara pendaftaran poliklinik di <b>${INSTANSI_NAMA}</b> sangat praktis melalui portal E-Pasien ini:<br><br>` +
        `<b>1. Pendaftaran Online (GUEST / PENGUNJUNG):</b><br>` +
        `• Buka menu <b>Pendaftaran Pemeriksaan</b> (atau akses link <a href="?act=PendaftaranPeriksa" class="text-emerald-700 font-bold hover:underline">Daftar Sekarang</a>).<br>` +
        `• Isi Nama Lengkap, Alamat, No. HP, Email aktif, Poliklinik tujuan, serta tanggal rencana periksa.<br>` +
        `• Masukkan kode Captcha verifikasi, lalu klik <b>Kirim Booking</b>.<br>` +
        `• Simpan/Cetak bukti booking untuk verifikasi admin.<br><br>` +
        `<b>2. Pendaftaran Pasien Terdaftar (LOGIN):</b><br>` +
        `• Jika sudah terdaftar, klik tombol <b>Login</b> (atau akses <a href="?act=LoginPasien" class="text-emerald-700 font-bold hover:underline">Halaman Login</a>).<br>` +
        `• Masuk menggunakan No. Rekam Medis / email dan kata sandi Anda.<br>` +
        `• Lakukan pendaftaran cepat di menu <b>Booking Registrasi</b> tanpa perlu menginput ulang data diri Anda.<br><br>` +
        `<i>Pendaftaran online dibuka 24 jam dan paling lambat dilakukan H-1 dari tanggal rencana pemeriksaan.</i>`;
        
    appendMessage('bot', daftarText, true);
    showOptionsShortcut();
}

function respondAlamat() {
    const mapsLink = "https://maps.google.com/?q=" + encodeURIComponent(INSTANSI_NAMA + " " + INSTANSI_ALAMAT + " " + INSTANSI_KAB);
    const iframeSrc = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31678.848019044606!2d112.53642909999999!3d-7.0262069!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e77e32ae7de3303%3A0x1913161fe0903272!2sRSI%20MABARROT%20MWC%20NU%20Bungah!5e0!3m2!1sen!2sid!4v1778582397809!5m2!1sen!2sid";
    
    const alamatText = `🏥 <b>${INSTANSI_NAMA}</b><br><br>` +
        `📍 <b>Alamat Lengkap:</b><br>${INSTANSI_ALAMAT}, ${INSTANSI_KAB}<br>` +
        `<iframe src="${iframeSrc}" class="w-full h-44 rounded-2xl mt-3 border border-slate-200 shadow-xs animate-fadeIn" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe><br>` +
        `📞 <b>Kontak/Telepon:</b><br>${INSTANSI_KONTAK}<br><br>` +
        `📧 <b>Email Resmi:</b><br>${INSTANSI_EMAIL}<br><br>` +
        `🗺️ <b>Navigasi Langsung:</b><br><a href="${mapsLink}" target="_blank" rel="noreferrer" class="inline-flex items-center gap-1.5 mt-2 bg-emerald-50 text-emerald-800 hover:bg-emerald-100 px-3.5 py-1.5 rounded-xl border border-emerald-200 text-xs font-bold transition-all"><i class="fas fa-location-dot"></i> Buka Google Maps</a>`;
        
    appendMessage('bot', alamatText, true);
    showOptionsShortcut();
}

function showOptionsShortcut() {
    const container = document.createElement('div');
    container.className = 'flex flex-wrap gap-2.5 mt-1';

    const btn = document.createElement('button');
    btn.className = 'px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-[10px] transition-all cursor-pointer flex items-center gap-1.5 border border-slate-200/80';
    btn.innerHTML = `<i class="fas fa-home text-[9px]"></i> Menu Utama`;
    btn.onclick = () => {
        appendMessage('user', 'Menu Utama');
        showTyping(true);
        setTimeout(() => {
            showTyping(false);
            appendMessage('bot', 'Pilih menu yang Anda butuhkan:', false);
            showMenuOptions();
        }, 500);
    };

    container.appendChild(btn);
    
    const messagesContainer = document.getElementById('chatbot-messages');
    messagesContainer.appendChild(container);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function handleChatSubmit(e) {
    if (e) e.preventDefault();
    
    const inputEl = document.getElementById('chatbot-input');
    if (!inputEl) return;
    
    const query = inputEl.value.trim();
    if (query === '') return;
    
    // Tampilkan pesan user
    appendMessage('user', query);
    inputEl.value = '';
    
    showTyping(true);
    
    setTimeout(() => {
        showTyping(false);
        processUserQuery(query);
    }, 700);
}

function processUserQuery(query) {
    const q = query.toLowerCase().trim();
    
    // 1. Cek Pertanyaan Alamat / Lokasi
    if (q.includes('alamat') || q.includes('lokasi') || q.includes('dimana') || q.includes('tempat') || q.includes('peta') || q.includes('maps')) {
        respondAlamat();
        return;
    }
    
    // 2. Cek Pertanyaan Pendaftaran / Booking
    if (q.includes('daftar') || q.includes('booking') || q.includes('registrasi') || q.includes('cara daft')) {
        respondDaftar();
        return;
    }
    
    // 3. Cek Pertanyaan Poli Spesialis
    if (q.includes('ada poli') || q.includes('jenis poli') || q.includes('spesialis apa') || q.includes('daftar poli') || q.includes('poli apa')) {
        respondPolis();
        return;
    }

    // 4. Bangun kata kunci cerdas (Smart Query Parsing)
    let searchTerms = [];
    
    // Pemetaan Sinonim Spesialis/Poli
    if (q.includes('anak') || q.includes('pediat') || q.includes('bayi')) {
        searchTerms.push('anak');
    }
    if (q.includes('kandungan') || q.includes('kebidanan') || q.includes('obgyn') || q.includes('obsgyn') || q.includes('hamil') || q.includes('melahirkan')) {
        searchTerms.push('kandungan');
        searchTerms.push('kebidanan');
    }
    if (q.includes('dalam') || q.includes('interna') || q.includes('internist')) {
        searchTerms.push('dalam');
    }
    if (q.includes('jantung') || q.includes('kardiologi') || q.includes('cardio')) {
        searchTerms.push('jantung');
    }
    if (q.includes('saraf') || q.includes('neurolog') || q.includes('neuro')) {
        searchTerms.push('saraf');
    }
    if (q.includes('bedah') || q.includes('operasi') || q.includes('surgery')) {
        searchTerms.push('bedah');
    }
    if (q.includes('tht') || q.includes('t.h.t') || q.includes('telinga') || q.includes('hidung') || q.includes('tenggorok')) {
        searchTerms.push('tht');
    }
    if (q.includes('orthopedi') || q.includes('ortopedi') || q.includes('tulang')) {
        searchTerms.push('orthopedi');
    }
    if (q.includes('gigi') || q.includes('dentist') || q.includes('dental')) {
        searchTerms.push('gigi');
    }
    if (q.includes('paru') || q.includes('pulmonolog') || q.includes('nafas') || q.includes('napas')) {
        searchTerms.push('paru');
    }
    if (q.includes('rehab') || q.includes('fisioterapi') || q.includes('rehabilitasi')) {
        searchTerms.push('rehabilitasi');
        searchTerms.push('fisioterapi');
    }
    if (q.includes('umum') || q.includes('igd') || q.includes('ugd') || q.includes('darurat')) {
        searchTerms.push('umum');
        searchTerms.push('igd');
        searchTerms.push('ugd');
    }

    // Jika tidak ada kata spesialis/poli yang cocok di atas, ambil dari input teks langsung dengan membuang noise words
    if (searchTerms.length === 0) {
        const blacklist = [
            'dr', 'dokter', 'jadwal', 'praktek', 'prakteknya', 'hari', 'jam', 'info', 'tanya', 'mau', 'cari', 
            'ada', 'pada', 'yang', 'untuk', 'di', 'ke', 'dan', 'dengan', 'poli', 'poliklinik', 'spesialis', 
            'saya', 'bisa', 'tolong', 'tampilkan'
        ];
        searchTerms = q.split(/[\s,./#\-_]+/)
            .filter(term => term.length > 2 && !blacklist.includes(term));
    }

    // Jika setelah disaring tidak ada kata kunci pencarian, tampilkan pilihan
    if (searchTerms.length === 0) {
        appendMessage('bot', 'Anda ingin mencari jadwal dokter? Silakan ketik nama dokter atau nama poliklinik spesialis yang Anda cari (misal: "anak" atau "spesialis kandungan" atau nama dokter).');
        respondJadwalPilihan();
        return;
    }

    // Filter jadwal yang cocok (dokter atau poliklinik)
    let matches = CHATBOT_SCHEDULES.filter(s => {
        return searchTerms.some(term => {
            return s.nama_dokter.toLowerCase().includes(term) || s.nama_poli.toLowerCase().includes(term);
        });
    });

    if (matches.length > 0) {
        // Kelompokkan hasil pencarian berdasarkan dokter + poli
        const resultsMap = {};
        matches.forEach(m => {
            const key = `${m.nama_dokter} (${m.nama_poli})`;
            if (!resultsMap[key]) {
                resultsMap[key] = [];
            }
            resultsMap[key].push(m);
        });

        let responseHtml = `Saya menemukan jadwal dokter yang cocok dengan pencarian Anda: <br><br>`;
        
        for (const [dokterPoli, sesi] of Object.entries(resultsMap)) {
            responseHtml += `<div class="bg-emerald-50/50 p-2.5 rounded-xl border border-emerald-100/70 mb-2">`;
            responseHtml += `<p class="font-bold text-slate-800 text-[11px] flex items-center gap-1.5"><i class="fas fa-user-md text-emerald-700 text-xs"></i> ${dokterPoli}</p>`;
            responseHtml += `<div class="mt-1.5 space-y-1">`;
            sesi.forEach(s => {
                responseHtml += `<div class="flex justify-between text-[10px] text-slate-600 bg-white px-2 py-1 rounded-md shadow-3xs border border-slate-200/40">`;
                responseHtml += `<span class="font-semibold text-slate-700 flex items-center gap-1"><span class="w-1 h-1 rounded-full bg-amber-500"></span>${s.hari}</span>`;
                responseHtml += `<span class="font-semibold text-emerald-800 bg-emerald-50/50 px-1.5 py-0.5 rounded border border-emerald-100/50">${s.jam} WIB</span>`;
                responseHtml += `</div>`;
            });
            responseHtml += `</div></div>`;
        }

        appendMessage('bot', responseHtml, true);
        showOptionsShortcut();
        return;
    }

    // Jika tidak ditemukan hasil pencarian
    const errorMsg = `Maaf, saya tidak menemukan jadwal dokter atau informasi yang sesuai dengan kata kunci <b>"${query}"</b>. <br><br>Coba gunakan kata kunci pencarian yang lebih singkat (misal: "anak", "kandungan", atau nama belakang dokter). Atau silakan pilih menu cepat berikut:`;
    appendMessage('bot', errorMsg, true);
    showMenuOptions();
}
</script>

<style>
/* CSS Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fadeIn {
    animation: fadeIn 0.25s ease forwards;
}
</style>
