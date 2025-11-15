// Filtre değiştirme
function filterCallbacks(filter) {
    const buttons = document.querySelectorAll('.filter-btn');
    buttons.forEach(btn => {
        btn.classList.remove('active');
        if (btn.getAttribute('data-filter') === filter) {
            btn.classList.add('active');
        }
    });

    const rows = document.querySelectorAll('.callback-row');
    rows.forEach(row => {
        const status = row.getAttribute('data-status');
        if (filter === 'all' || status === filter) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Kural ekleme modalı
function openAddRuleModal() {
    document.getElementById('ruleModalTitle').textContent = 'Yeni Kural Ekle';
    document.getElementById('ruleName').value = '';
    document.getElementById('ruleCondition').value = '';
    document.getElementById('ruleDelay').value = '';
    document.getElementById('ruleMaxAttempts').value = '';
    document.getElementById('ruleAttemptPeriod').value = 'day';
    document.getElementById('ruleTimeStart').value = '09:00';
    document.getElementById('ruleTimeEnd').value = '21:00';
    document.getElementById('ruleActive').checked = true;
    document.getElementById('ruleModal').classList.add('active');
}

// Kural düzenleme
function editRule(ruleId) {
    document.getElementById('ruleModalTitle').textContent = 'Kural Düzenle';
    // Demo data - gerçek uygulamada backend'den gelecek
    document.getElementById('ruleName').value = 'WhatsApp Satın Almadı';
    document.getElementById('ruleCondition').value = 'whatsapp_no_purchase';
    document.getElementById('ruleDelay').value = '15';
    document.getElementById('ruleMaxAttempts').value = '3';
    document.getElementById('ruleAttemptPeriod').value = 'day';
    document.getElementById('ruleTimeStart').value = '09:00';
    document.getElementById('ruleTimeEnd').value = '21:00';
    document.getElementById('ruleActive').checked = true;
    document.getElementById('ruleModal').classList.add('active');
}

// Kural kaydet
function saveRule(e) {
    e.preventDefault();
    const ruleName = document.getElementById('ruleName').value;
    alert('Kural "' + ruleName + '" kaydedildi (Backend entegrasyonu gerekli)');
    closeModal('ruleModal');
}

// Kural silme
function deleteRule(ruleId) {
    if (confirm('Bu kuralı silmek istediğinizden emin misiniz?')) {
        alert('Kural #' + ruleId + ' silindi (Backend entegrasyonu gerekli)');
        // Gerçek uygulamada backend'e istek gönderilecek ve kart silinecek
    }
}

// Arama detayı görüntüleme
function viewCallbackDetail(callbackId) {
    const modal = document.getElementById('callbackDetailModal');
    const content = document.getElementById('callbackDetailContent');

    // Örnek detay içeriği
    content.innerHTML = `
        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <div>
                    <h3 style="color: var(--blue); margin-bottom: 1rem;">
                        <i class="fas fa-user"></i> Müşteri Bilgileri
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div>
                            <span style="color: var(--text-secondary);">Ad Soyad:</span>
                            <strong style="color: var(--text-primary); margin-left: 0.5rem;">Ahmet Yılmaz</strong>
                        </div>
                        <div>
                            <span style="color: var(--text-secondary);">Telefon:</span>
                            <strong style="color: var(--text-primary); margin-left: 0.5rem;">0532 123 4567</strong>
                        </div>
                        <div>
                            <span style="color: var(--text-secondary);">Kaynak:</span>
                            <strong style="color: var(--text-primary); margin-left: 0.5rem;">
                                <i class="fab fa-whatsapp" style="color: #25d366;"></i> WhatsApp
                            </strong>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 style="color: var(--blue); margin-bottom: 1rem;">
                        <i class="fas fa-chart-line"></i> AI Değerlendirme
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div>
                            <span style="color: var(--text-secondary);">Satın Alma Olasılığı:</span>
                            <strong style="color: #10b981; margin-left: 0.5rem;">85%</strong>
                        </div>
                        <div>
                            <span style="color: var(--text-secondary);">Öncelik:</span>
                            <strong style="color: #ef4444; margin-left: 0.5rem;">Yüksek</strong>
                        </div>
                        <div>
                            <span style="color: var(--text-secondary);">Deneme Sayısı:</span>
                            <strong style="color: var(--text-primary); margin-left: 0.5rem;">1/3</strong>
                        </div>
                    </div>
                </div>
            </div>

            <h3 style="color: var(--blue); margin-bottom: 1rem;">
                <i class="fas fa-history"></i> Süreç Timeline
            </h3>

            <div style="position: relative; padding-left: 2rem;">
                <div style="position: absolute; left: 0.5rem; top: 0; bottom: 0; width: 2px; background: linear-gradient(180deg, #3b82f6 0%, rgba(59, 130, 246, 0.2) 100%);"></div>

                <div class="timeline-item" style="position: relative; padding: 1rem; margin-bottom: 1.5rem; background: rgba(59, 130, 246, 0.05); border-left: 3px solid #3b82f6; border-radius: 8px; margin-left: 1rem;">
                    <div style="position: absolute; left: -2.5rem; top: 1rem; width: 12px; height: 12px; background: #3b82f6; border-radius: 50%; border: 3px solid #1e2d44;"></div>
                    <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem;">WhatsApp Mesajı</div>
                    <div style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 0.5rem;">14.11.2025 - 10:30</div>
                    <div style="color: var(--text-secondary); font-size: 0.9rem;">"Denizcilik eğitimi hakkında bilgi almak istiyorum. Fiyatlar nedir?"</div>
                </div>

                <div class="timeline-item" style="position: relative; padding: 1rem; margin-bottom: 1.5rem; background: rgba(16, 185, 129, 0.05); border-left: 3px solid #10b981; border-radius: 8px; margin-left: 1rem;">
                    <div style="position: absolute; left: -2.5rem; top: 1rem; width: 12px; height: 12px; background: #10b981; border-radius: 50%; border: 3px solid #1e2d44;"></div>
                    <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem;">AI Yanıt Gönderildi</div>
                    <div style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 0.5rem;">14.11.2025 - 10:31</div>
                    <div style="color: var(--text-secondary); font-size: 0.9rem;">"Temel Denizcilik eğitimimiz 1500 TL..."</div>
                </div>

                <div class="timeline-item" style="position: relative; padding: 1rem; margin-bottom: 1.5rem; background: rgba(245, 158, 11, 0.05); border-left: 3px solid #f59e0b; border-radius: 8px; margin-left: 1rem;">
                    <div style="position: absolute; left: -2.5rem; top: 1rem; width: 12px; height: 12px; background: #f59e0b; border-radius: 50%; border: 3px solid #1e2d44;"></div>
                    <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem;">Kural Tetiklendi</div>
                    <div style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 0.5rem;">14.11.2025 - 16:30</div>
                    <div style="color: var(--text-secondary); font-size: 0.9rem;">"WhatsApp Satın Almadı" kuralı aktif - 15 dakika sonra arama planlandı</div>
                </div>

                <div class="timeline-item" style="position: relative; padding: 1rem; background: rgba(59, 130, 246, 0.05); border-left: 3px solid #60a5fa; border-radius: 8px; margin-left: 1rem; border-style: dashed;">
                    <div style="position: absolute; left: -2.5rem; top: 1rem; width: 12px; height: 12px; background: #60a5fa; border-radius: 50%; border: 3px solid #1e2d44; animation: pulse 2s infinite;"></div>
                    <div style="font-weight: 600; color: var(--blue); margin-bottom: 0.5rem;">
                        <i class="fas fa-clock"></i> Arama Bekliyor
                    </div>
                    <div style="color: var(--text-secondary); font-size: 0.9rem;">Planlanan: 14.11.2025 - 16:45</div>
                </div>
            </div>

            <div style="margin-top: 2rem; padding: 1rem; background: rgba(59, 130, 246, 0.05); border-radius: 8px;">
                <h4 style="color: var(--blue); margin-bottom: 0.75rem;">
                    <i class="fas fa-brain"></i> AI Özeti
                </h4>
                <p style="color: var(--text-secondary); line-height: 1.6;">
                    Müşteri denizcilik eğitimi hakkında bilgi almak istiyor. Fiyat sordu ancak satın almadı. 
                    Satın alma olasılığı yüksek. İlk görüşmede ilgi gösterdi, muhtemelen karşılaştırma yapıyor.
                    15 dakika içinde aranması öneriliyor.
                </p>
            </div>
        </div>
    `;

    modal.classList.add('active');
}

// Aramayı iptal et
function cancelCallback(callbackId) {
    if (confirm('Bu aramayı iptal etmek istediğinizden emin misiniz?')) {
        alert('Arama #' + callbackId + ' iptal edildi (Backend entegrasyonu gerekli)');
    }
}

// Modal kapat
function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

// Modal dışına tıklayınca kapat
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('active');
    }
});
