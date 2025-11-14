// Eğitimler Sayfası - JavaScript

let currentCourseId = null;

// Eğitim görüntüle
function viewCourse(courseId) {
    currentCourseId = courseId;
    
    const demoData = {
        1: {
            title: 'Python ile Web Geliştirme',
            duration: '12 Saat',
            price: '499 ₺',
            oldPrice: '799 ₺',
            discount: '%37',
            thumbnail: 'https://via.placeholder.com/400x250/667eea/ffffff?text=Python',
            description: 'Python programlama dilini kullanarak modern web uygulamaları geliştirmeyi öğrenin.',
            students: 245,
            rating: 4.8
        },
        2: {
            title: 'JavaScript Modern Programlama',
            duration: '18 Saat',
            price: '599 ₺',
            oldPrice: '999 ₺',
            discount: '%40',
            thumbnail: 'https://via.placeholder.com/400x250/764ba2/ffffff?text=JavaScript',
            description: 'Modern JavaScript (ES6+) özellikleri ile profesyonel web geliştirme.',
            students: 532,
            rating: 4.9
        },
        3: {
            title: 'React ile Modern Web Uygulamaları',
            duration: '24 Saat',
            price: '799 ₺',
            oldPrice: '1299 ₺',
            discount: '%38',
            thumbnail: 'https://via.placeholder.com/400x250/f093fb/ffffff?text=React',
            description: 'React kütüphanesi ile single-page uygulamalar geliştirin.',
            students: 189,
            rating: 4.7
        },
        4: {
            title: 'Node.js Backend Geliştirme',
            duration: '16 Saat',
            price: '699 ₺',
            oldPrice: '1099 ₺',
            discount: '%36',
            thumbnail: 'https://via.placeholder.com/400x250/4facfe/ffffff?text=Node.js',
            description: 'Node.js ile RESTful API ve backend uygulamaları geliştirme.',
            students: 312,
            rating: 4.6
        }
    };
    
    const course = demoData[courseId];
    
    if (course) {
        document.getElementById('detailCourseTitle').textContent = course.title;
        document.getElementById('courseDetailContent').innerHTML = `
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <img src="${course.thumbnail}" alt="${course.title}" style="max-width: 100%; border-radius: 12px;">
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
                <div style="background: rgba(59, 130, 246, 0.1); padding: 1rem; border-radius: 8px;">
                    <div style="color: #8b9cbc; font-size: 0.875rem; margin-bottom: 0.5rem;">Süre</div>
                    <div style="color: #3b82f6; font-size: 1.25rem; font-weight: 600;">
                        <i class="far fa-clock"></i> ${course.duration}
                    </div>
                </div>
                
                <div style="background: rgba(16, 185, 129, 0.1); padding: 1rem; border-radius: 8px;">
                    <div style="color: #8b9cbc; font-size: 0.875rem; margin-bottom: 0.5rem;">Fiyat</div>
                    <div style="color: #10b981; font-size: 1.25rem; font-weight: 600;">
                        ${course.price} <span style="text-decoration: line-through; color: #8b9cbc; font-size: 0.9rem;">${course.oldPrice}</span>
                    </div>
                </div>
                
                <div style="background: rgba(249, 115, 22, 0.1); padding: 1rem; border-radius: 8px;">
                    <div style="color: #8b9cbc; font-size: 0.875rem; margin-bottom: 0.5rem;">İndirim</div>
                    <div style="color: #f97316; font-size: 1.25rem; font-weight: 600;">
                        ${course.discount}
                    </div>
                </div>
                
                <div style="background: rgba(139, 92, 246, 0.1); padding: 1rem; border-radius: 8px;">
                    <div style="color: #8b9cbc; font-size: 0.875rem; margin-bottom: 0.5rem;">Öğrenci</div>
                    <div style="color: #8b5cf6; font-size: 1.25rem; font-weight: 600;">
                        <i class="fas fa-users"></i> ${course.students}
                    </div>
                </div>
            </div>
            
            <div style="background: rgba(255, 255, 255, 0.03); padding: 1rem; border-radius: 8px;">
                <div style="color: #e9edef; font-size: 0.875rem; margin-bottom: 0.5rem; font-weight: 600;">Açıklama</div>
                <div style="color: #8b9cbc; line-height: 1.6;">${course.description}</div>
            </div>
        `;
        
        document.getElementById('courseDetailModal').classList.add('active');
    }
}

// Detay modalından düzenle
function editCourseFromDetail() {
    closeModal('courseDetailModal');
    if (currentCourseId) {
        editCourse(currentCourseId);
    }
}

// Detay modalından sil
function deleteCourseFromDetail() {
    closeModal('courseDetailModal');
    if (currentCourseId) {
        deleteCourse(currentCourseId);
    }
}

let currentStep = 1;
let totalSteps = 2;
let courseData = {
    title: '',
    duration: '',
    thumbnail: null,
    price: '',
    oldPrice: '',
    video: null,
    preventSeek: false,
    questions: []
};

// Modal aç
function openAddCourseModal() {
    currentStep = 1;
    courseData = {
        title: '',
        duration: '',
        thumbnail: null,
        price: '',
        oldPrice: '',
        video: null,
        preventSeek: false,
        questions: []
    };
    
    document.getElementById('addCourseModal').classList.add('active');
    updateStepIndicator();
    showStep(1);
}

// Modal kapat
function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

// Adım göster
function showStep(step) {
    document.querySelectorAll('.form-step').forEach(s => s.classList.remove('active'));
    document.getElementById(`step${step}`).classList.add('active');
    currentStep = step;
    updateStepIndicator();
}

// Adım göstergesi güncelle
function updateStepIndicator() {
    document.querySelectorAll('.step').forEach((step, index) => {
        const stepNum = index + 1;
        step.classList.remove('active', 'completed');
        
        if (stepNum < currentStep) {
            step.classList.add('completed');
        } else if (stepNum === currentStep) {
            step.classList.add('active');
        }
    });
    
    // Butonları güncelle
    document.querySelector('.btn-prev').style.display = currentStep === 1 ? 'none' : 'flex';
    document.querySelector('.btn-next').style.display = currentStep === totalSteps ? 'none' : 'flex';
    document.querySelector('.btn-complete').style.display = currentStep === totalSteps ? 'flex' : 'none';
}

// Sonraki adım
function nextStep() {
    if (!validateCurrentStep()) return;
    
    if (currentStep < totalSteps) {
        currentStep++;
        showStep(currentStep);
    }
}

// Önceki adım
function prevStep() {
    if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
    }
}

// Mevcut adımı doğrula
function validateCurrentStep() {
    if (currentStep === 1) {
        const title = document.getElementById('courseTitle').value.trim();
        const duration = document.getElementById('courseDuration').value.trim();
        const price = document.getElementById('coursePrice').value.trim();
        
        if (!title) {
            showNotification('Lütfen eğitim adını girin', 'error');
            return false;
        }
        
        if (!duration) {
            showNotification('Lütfen eğitim süresini girin', 'error');
            return false;
        }
        
        if (!price) {
            showNotification('Lütfen fiyat girin', 'error');
            return false;
        }
        
        // Verileri kaydet
        courseData.title = title;
        courseData.duration = duration;
        courseData.price = price;
        courseData.oldPrice = document.getElementById('courseOldPrice').value.trim();
    }
    
    return true;
}

// Dosya yükleme önizlemesi
function handleThumbnailUpload(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    if (!file.type.startsWith('image/')) {
        showNotification('Lütfen bir resim dosyası seçin', 'error');
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('thumbnailPreview').innerHTML = `<img src="${e.target.result}" alt="Kapak">`;
        document.getElementById('thumbnailPreviewContainer').classList.add('active');
        courseData.thumbnail = e.target.result;
    };
    reader.readAsDataURL(file);
}

// Video yükleme
function handleVideoUpload(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    if (!file.type.startsWith('video/')) {
        showNotification('Lütfen bir video dosyası seçin', 'error');
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('videoPreview').innerHTML = `
            <video controls>
                <source src="${e.target.result}" type="${file.type}">
            </video>
        `;
        document.getElementById('videoPreviewContainer').classList.add('active');
        courseData.video = e.target.result;
    };
    reader.readAsDataURL(file);
}

// Soru ekle
function addQuestion() {
    const questionIndex = courseData.questions.length;
    const questionHtml = `
        <div class="question-item" data-question-index="${questionIndex}">
            <div class="question-header">
                <span class="question-number">Soru ${questionIndex + 1}</span>
                <button class="remove-question" onclick="removeQuestion(${questionIndex})">
                    <i class="fas fa-trash"></i> Sil
                </button>
            </div>
            
            <div class="form-group">
                <label>Soru Metni</label>
                <input type="text" class="question-text" placeholder="Sorunuzu yazın..." required>
            </div>
            
            <div class="form-group">
                <label>Cevap Şıkları (Doğru cevabı işaretleyin)</label>
                <div class="answers-list" id="answers-${questionIndex}">
                    <div class="answer-item">
                        <input type="radio" name="correct-${questionIndex}" value="0" required>
                        <input type="text" placeholder="A) Şık" required>
                    </div>
                    <div class="answer-item">
                        <input type="radio" name="correct-${questionIndex}" value="1" required>
                        <input type="text" placeholder="B) Şık" required>
                    </div>
                    <div class="answer-item">
                        <input type="radio" name="correct-${questionIndex}" value="2" required>
                        <input type="text" placeholder="C) Şık" required>
                    </div>
                    <div class="answer-item">
                        <input type="radio" name="correct-${questionIndex}" value="3" required>
                        <input type="text" placeholder="D) Şık" required>
                    </div>
                </div>
                <button type="button" class="add-answer-btn" onclick="addAnswer(${questionIndex})">
                    <i class="fas fa-plus"></i> Şık Ekle
                </button>
            </div>
        </div>
    `;
    
    document.getElementById('questionsContainer').insertAdjacentHTML('beforeend', questionHtml);
    courseData.questions.push({ text: '', answers: [], correctAnswer: 0 });
}

// Soru sil
function removeQuestion(index) {
    const questionItem = document.querySelector(`.question-item[data-question-index="${index}"]`);
    if (questionItem) {
        questionItem.remove();
        courseData.questions.splice(index, 1);
        updateQuestionNumbers();
    }
}

// Soru numaralarını güncelle
function updateQuestionNumbers() {
    document.querySelectorAll('.question-item').forEach((item, index) => {
        item.setAttribute('data-question-index', index);
        item.querySelector('.question-number').textContent = `Soru ${index + 1}`;
    });
}

// Şık ekle
function addAnswer(questionIndex) {
    const answersList = document.getElementById(`answers-${questionIndex}`);
    const answerCount = answersList.querySelectorAll('.answer-item').length;
    const letters = ['A', 'B', 'C', 'D', 'E', 'F'];
    
    if (answerCount >= 6) {
        showNotification('Maksimum 6 şık ekleyebilirsiniz', 'warning');
        return;
    }
    
    const answerHtml = `
        <div class="answer-item">
            <input type="radio" name="correct-${questionIndex}" value="${answerCount}" required>
            <input type="text" placeholder="${letters[answerCount]}) Şık" required>
        </div>
    `;
    
    answersList.insertAdjacentHTML('beforeend', answerHtml);
}

// Eğitimi tamamla
function completeCourse() {
    // Video kontrolü
    if (!courseData.video) {
        showNotification('Lütfen bir eğitim videosu yükleyin', 'error');
        return;
    }
    
    // Sorular kontrolü
    const questions = [];
    document.querySelectorAll('.question-item').forEach((item, index) => {
        const questionText = item.querySelector('.question-text').value.trim();
        const answers = [];
        const answerInputs = item.querySelectorAll('.answer-item input[type="text"]');
        const correctRadio = item.querySelector(`input[name="correct-${index}"]:checked`);
        
        if (!questionText) {
            showNotification(`Lütfen ${index + 1}. soruyu doldurun`, 'error');
            return;
        }
        
        if (!correctRadio) {
            showNotification(`Lütfen ${index + 1}. soru için doğru cevabı işaretleyin`, 'error');
            return;
        }
        
        answerInputs.forEach(input => {
            if (input.value.trim()) {
                answers.push(input.value.trim());
            }
        });
        
        if (answers.length < 2) {
            showNotification(`${index + 1}. soru için en az 2 şık ekleyin`, 'error');
            return;
        }
        
        questions.push({
            text: questionText,
            answers: answers,
            correctAnswer: parseInt(correctRadio.value)
        });
    });
    
    if (questions.length === 0) {
        showNotification('Lütfen en az 1 test sorusu ekleyin', 'error');
        return;
    }
    
    courseData.questions = questions;
    courseData.preventSeek = document.getElementById('preventSeek').checked;
    
    // Backend'e gönder
    saveCourse();
}

// Eğitimi kaydet
function saveCourse() {
    // AJAX ile backend'e gönder
    console.log('Kaydedilecek eğitim:', courseData);
    
    showNotification('Eğitim başarıyla eklendi!', 'success');
    
    // Modal'ı kapat
    closeModal('addCourseModal');
    
    // Sayfayı yenile (veya dinamik olarak ekle)
    setTimeout(() => {
        location.reload();
    }, 1000);
    
    /*
    fetch('ajax/add_course.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(courseData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Eğitim başarıyla eklendi!', 'success');
            closeModal('addCourseModal');
            location.reload();
        }
    });
    */
}

// Eğitim düzenle
function editCourse(courseId) {
    // Modal'ı aç
    currentStep = 1;
    document.getElementById('addCourseModal').classList.add('active');
    updateStepIndicator();
    showStep(1);
    
    // Modal başlığını değiştir
    document.querySelector('#addCourseModal .modal-header h2').textContent = 'Eğitimi Düzenle';
    
    // Backend'den eğitim verilerini al
    // Geçici olarak demo veri
    const demoData = {
        1: {
            title: 'Python ile Web Geliştirme',
            duration: '12 Saat',
            price: '499',
            oldPrice: '799',
            thumbnail: 'https://via.placeholder.com/400x250/667eea/ffffff?text=Python'
        },
        2: {
            title: 'JavaScript Modern Programlama',
            duration: '18 Saat',
            price: '599',
            oldPrice: '999',
            thumbnail: 'https://via.placeholder.com/400x250/764ba2/ffffff?text=JavaScript'
        },
        3: {
            title: 'React ile Modern Web Uygulamaları',
            duration: '24 Saat',
            price: '799',
            oldPrice: '1299',
            thumbnail: 'https://via.placeholder.com/400x250/f093fb/ffffff?text=React'
        },
        4: {
            title: 'Node.js Backend Geliştirme',
            duration: '16 Saat',
            price: '699',
            oldPrice: '1099',
            thumbnail: 'https://via.placeholder.com/400x250/4facfe/ffffff?text=Node.js'
        }
    };
    
    const courseData = demoData[courseId];
    
    if (courseData) {
        // Form alanlarını doldur
        document.getElementById('courseTitle').value = courseData.title;
        document.getElementById('courseDuration').value = courseData.duration;
        document.getElementById('coursePrice').value = courseData.price;
        document.getElementById('courseOldPrice').value = courseData.oldPrice;
        
        // Kapak görselini göster
        if (courseData.thumbnail) {
            document.getElementById('thumbnailPreview').innerHTML = `<img src="${courseData.thumbnail}" alt="Kapak">`;
            document.getElementById('thumbnailPreviewContainer').classList.add('active');
        }
    }
    
    /*
    // Gerçek backend entegrasyonu:
    fetch('ajax/get_course.php?id=' + courseId)
        .then(response => response.json())
        .then(data => {
            document.getElementById('courseTitle').value = data.title;
            document.getElementById('courseDuration').value = data.duration;
            // ... diğer alanlar
        });
    */
}

// Eğitim sil
function deleteCourse(courseId) {
    if (!confirm('Bu eğitimi silmek istediğinize emin misiniz?')) {
        return;
    }
    
    // AJAX ile sil
    console.log('Silinecek eğitim ID:', courseId);
    
    showNotification('Eğitim silindi', 'success');
    
    // Kartı kaldır
    const card = document.querySelector(`[data-course-id="${courseId}"]`);
    if (card) {
        card.remove();
    }
    
    /*
    fetch('ajax/delete_course.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ course_id: courseId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Eğitim silindi', 'success');
            location.reload();
        }
    });
    */
}

// Modal dışına tıklayınca kapat
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('active');
    }
});