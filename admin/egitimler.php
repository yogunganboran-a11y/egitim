<?php
$page_title = 'Eğitimler';
include 'includes/header.php';

// Demo eğitim verileri
$courses = [
    [
        'id' => 1,
        'title' => 'Python ile Web Geliştirme',
        'duration' => '12 Saat',
        'thumbnail' => 'https://via.placeholder.com/400x250/667eea/ffffff?text=Python',
        'price' => '499 ₺',
        'old_price' => '799 ₺',
        'discount' => '%37',
        'students' => 245,
        'rating' => 4.8
    ],
    [
        'id' => 2,
        'title' => 'JavaScript Modern Programlama',
        'duration' => '18 Saat',
        'thumbnail' => 'https://via.placeholder.com/400x250/764ba2/ffffff?text=JavaScript',
        'price' => '599 ₺',
        'old_price' => '999 ₺',
        'discount' => '%40',
        'students' => 532,
        'rating' => 4.9
    ],
    [
        'id' => 3,
        'title' => 'React ile Modern Web Uygulamaları',
        'duration' => '24 Saat',
        'thumbnail' => 'https://via.placeholder.com/400x250/f093fb/ffffff?text=React',
        'price' => '799 ₺',
        'old_price' => '1299 ₺',
        'discount' => '%38',
        'students' => 189,
        'rating' => 4.7
    ],
    [
        'id' => 4,
        'title' => 'Node.js Backend Geliştirme',
        'duration' => '16 Saat',
        'thumbnail' => 'https://via.placeholder.com/400x250/4facfe/ffffff?text=Node.js',
        'price' => '699 ₺',
        'old_price' => '1099 ₺',
        'discount' => '%36',
        'students' => 312,
        'rating' => 4.6
    ]
];
?>

<link rel="stylesheet" href="assets/css/egitimler.css">

<div class="page-header">
    <div>
        <h1><i class="fas fa-graduation-cap"></i> Eğitimler</h1>
        <p class="page-subtitle">Eğitim içeriklerinizi yönetin</p>
    </div>
    
    <button class="add-course-btn" onclick="openAddCourseModal()">
        <i class="fas fa-plus"></i>
        Yeni Eğitim Ekle
    </button>
</div>

<!-- Eğitim Kartları -->
<div class="courses-grid">
    <?php foreach ($courses as $course): ?>
    <div class="course-card" data-course-id="<?php echo $course['id']; ?>">
        <div class="card-header">
            <div class="education-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h3><?php echo $course['title']; ?></h3>
        </div>

        <div class="education-features">
            <div class="feature">
                <i class="far fa-clock"></i>
                <span><?php echo $course['duration']; ?></span>
            </div>
            <div class="feature">
                <i class="fas fa-users"></i>
                <span><?php echo $course['students']; ?> Öğrenci</span>
            </div>
        </div>

        <div class="card-footer">
            <div class="price-section">
                <?php if($course['old_price']): ?>
                <span class="old-price"><?php echo $course['old_price']; ?></span>
                <?php endif; ?>
                <span class="price"><?php echo $course['price']; ?></span>
            </div>

            <div class="course-actions">
                <button class="course-action-btn" onclick="editCourse(<?php echo $course['id']; ?>)">
                    <i class="fas fa-edit"></i>
                    Düzenle
                </button>
                <button class="course-action-btn delete" onclick="deleteCourse(<?php echo $course['id']; ?>)">
                    <i class="fas fa-trash"></i>
                    Sil
                </button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Eğitim Ekleme Modal -->
<div class="modal course-modal" id="addCourseModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Yeni Eğitim Ekle</h2>
            <button class="close-modal" onclick="closeModal('addCourseModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Adım Göstergesi -->
        <div class="modal-steps">
            <div class="step active">
                <div class="step-circle">1</div>
                <span class="step-label">Temel Bilgiler</span>
            </div>
            <div class="step">
                <div class="step-circle">2</div>
                <span class="step-label">İçerik & Test</span>
            </div>
        </div>
        
        <!-- Adım 1: Temel Bilgiler -->
        <div class="form-step active" id="step1">
            <div class="form-group">
                <label>Eğitim Adı *</label>
                <input type="text" id="courseTitle" placeholder="Örn: Python ile Web Geliştirme" required>
            </div>

            <div class="form-group">
                <label>Eğitim Adı Kısaltması *</label>
                <input type="text" id="courseShortName" placeholder="Örn: Python Web" required>
            </div>

            <div class="form-group">
                <label>Eğitim Süresi (Saat) *</label>
                <input type="number" id="courseDuration" placeholder="12" min="1" required>
            </div>

            <div class="form-group">
                <label>Sertifika Çıkış Zamanı *</label>
                <div class="form-row">
                    <div class="form-group" style="margin-bottom: 0; flex: 1;">
                        <label style="font-size: 0.875rem; color: #8b9cbc;">Gün Sayısı</label>
                        <input type="number" id="certificateDays" class="form-control" placeholder="0" min="0" max="365" required>
                        <small style="color: #8b9cbc; font-size: 0.75rem; display: block; margin-top: 0.25rem;">0-365 gün</small>
                    </div>

                    <div class="form-group" style="margin-bottom: 0; flex: 1;">
                        <label style="font-size: 0.875rem; color: #8b9cbc;">Saat:Dakika</label>
                        <input type="time" id="certificateTime" class="form-control" value="09:00" required>
                        <small style="color: #8b9cbc; font-size: 0.75rem; display: block; margin-top: 0.25rem;">SS:DD formatında</small>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Fiyat (₺) *</label>
                    <input type="number" id="coursePrice" placeholder="499" required>
                </div>

                <div class="form-group">
                    <label>İndirimli Fiyat (₺)</label>
                    <input type="number" id="courseOldPrice" placeholder="799">
                </div>
            </div>
        </div>
        
        <!-- Adım 2: İçerik & Test -->
        <div class="form-step" id="step2">
            <div class="form-group">
                <label>Eğitim Videosu *</label>
                <div class="video-upload-area" onclick="document.getElementById('videoUpload').click()">
                    <i class="fas fa-video" style="color: #8b5cf6;"></i>
                    <p>Video dosyası yüklemek için tıklayın</p>
                    <small style="color: #8b9cbc;">Desteklenen formatlar: MP4, WebM, AVI</small>
                    <input type="file" id="videoUpload" accept="video/*" onchange="handleVideoUpload(event)" style="display: none;">
                </div>
                <div class="video-preview" id="videoPreviewContainer">
                    <div id="videoPreview"></div>
                </div>
            </div>

            <div class="form-group">
                <label>Test Soruları</label>
                <div class="questions-container" id="questionsContainer">
                    <!-- Sorular buraya eklenecek -->
                </div>
                <button type="button" class="add-question-btn" onclick="addQuestion()">
                    <i class="fas fa-plus-circle"></i>
                    Yeni Soru Ekle
                </button>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="modal-footer">
            <button type="button" class="btn-prev" onclick="prevStep()">
                <i class="fas fa-arrow-left"></i>
                Geri
            </button>
            <button type="button" class="btn-next" onclick="nextStep()">
                Sonraki Adım
                <i class="fas fa-arrow-right"></i>
            </button>
            <button type="button" class="btn-complete" onclick="completeCourse()" style="display: none;">
                <i class="fas fa-check"></i>
                Tamamla
            </button>
        </div>
    </div>
</div>

<script src="assets/js/egitimler.js"></script>

<!-- Eğitim Detay Modal -->
<div class="modal course-detail-modal" id="courseDetailModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="detailCourseTitle">Eğitim Detayları</h2>
            <button class="close-modal" onclick="closeModal('courseDetailModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div id="courseDetailContent" style="padding: 1rem;">
            <!-- Eğitim detayları buraya gelecek -->
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="editCourseFromDetail()" style="flex: 1;">
                <i class="fas fa-edit"></i>
                Düzenle
            </button>
            <button type="button" class="btn btn-danger" onclick="deleteCourseFromDetail()" style="flex: 1; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <i class="fas fa-trash"></i>
                Sil
            </button>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>