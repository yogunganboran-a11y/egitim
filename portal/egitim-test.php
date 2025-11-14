<?php
require_once 'includes/auth-check.php';

$page_title = 'Eğitim Testi';
$page_css = 'assets/css/egitim-test.css';

include 'includes/header.php';

$egitim_adi = "Temel Denizcilik";

// Sorular
$sorular = [
    [
        'soru' => 'Denizcilik temel bilgilerinden hangisi doğrudur?',
        'secenekler' => [
            'A' => 'Gemiler sağdan sollama yapar',
            'B' => 'Gemiler soldan sollama yapar',
            'C' => 'Gemiler karşıdan sollama yapar',
            'D' => 'Sollama kuralı yoktur'
        ],
        'dogru' => 'B'
    ],
    [
        'soru' => 'Can yeleği kullanımında en önemli kural nedir?',
        'secenekler' => [
            'A' => 'Renkli olması',
            'B' => 'Doğru beden ölçüsünde olması',
            'C' => 'Pahalı olması',
            'D' => 'Yeni model olması'
        ],
        'dogru' => 'B'
    ],
    [
        'soru' => 'Denizde acil durum sinyali nasıl verilir?',
        'secenekler' => [
            'A' => 'SOS sinyali ile',
            'B' => 'Bayrak sallayarak',
            'C' => 'Düdük çalarak',
            'D' => 'Hepsi'
        ],
        'dogru' => 'D'
    ],
    [
        'soru' => 'Gemi rotası belirlenirken en önemli faktör nedir?',
        'secenekler' => [
            'A' => 'Hava durumu',
            'B' => 'Deniz akıntıları',
            'C' => 'Yakıt tasarrufu',
            'D' => 'Hepsi'
        ],
        'dogru' => 'D'
    ],
    [
        'soru' => 'Denizde güvenlik ekipmanlarından hangisi zorunludur?',
        'secenekler' => [
            'A' => 'Can yeleği',
            'B' => 'Yangın söndürücü',
            'C' => 'İlk yardım çantası',
            'D' => 'Hepsi'
        ],
        'dogru' => 'D'
    ]
];
?>

<!-- Test Container -->
<div class="test-container">
    <!-- Question Card -->
    <div class="question-card" id="questionCard">
        <div class="question-header">
            <span class="question-number" id="questionNumber">Soru 1/5</span>
        </div>
        <h3 class="question-text" id="questionText"></h3>
        <div class="answers" id="answersContainer"></div>
    </div>
    
    <!-- Navigation -->
    <div class="test-navigation">
        <button class="btn-prev" id="prevBtn" style="display: none;">
            <i class="fas fa-arrow-left"></i> Önceki Soru
        </button>
        <button class="btn-next" id="nextBtn" style="display: none;">
            Sonraki Soru <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</div>

<script>
const sorular = <?php echo json_encode($sorular); ?>;
let currentQuestion = 0;
let dogruSayisi = 0;
let yanlisSayisi = 0;
let cevaplar = [];

function showQuestion() {
    const soru = sorular[currentQuestion];
    
    document.getElementById('questionNumber').textContent = `Soru ${currentQuestion + 1}/${sorular.length}`;
    document.getElementById('questionText').textContent = soru.soru;
    
    const answersContainer = document.getElementById('answersContainer');
    answersContainer.innerHTML = '';
    
    // Show previous answers if any
    const previousAnswer = cevaplar[currentQuestion];
    
    Object.entries(soru.secenekler).forEach(([key, value]) => {
        const label = document.createElement('label');
        label.className = 'answer-option';
        
        // Apply previous state if navigating back
        if (previousAnswer) {
            if (key === soru.dogru) {
                label.classList.add('correct');
            } else if (previousAnswer.yanlis.includes(key)) {
                label.classList.add('wrong');
            }
        }
        
        label.innerHTML = `
            <span class="option-letter">${key}</span>
            <span class="option-text">${value}</span>
        `;
        
        if (!previousAnswer) {
            label.onclick = () => checkAnswer(key, label);
        }
        
        answersContainer.appendChild(label);
    });
    
    // Show/hide navigation buttons
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    prevBtn.style.display = 'flex';
    nextBtn.style.display = 'flex';
    
    if (currentQuestion === 0) {
        prevBtn.disabled = true;
    } else {
        prevBtn.disabled = false;
    }
    
    if (cevaplar[currentQuestion] && currentQuestion < sorular.length - 1) {
        nextBtn.disabled = false;
    } else {
        nextBtn.disabled = true;
    }
}

function checkAnswer(selected, labelElement) {
    const soru = sorular[currentQuestion];
    
    if (!cevaplar[currentQuestion]) {
        cevaplar[currentQuestion] = {
            dogru: false,
            yanlis: []
        };
    }
    
    if (selected === soru.dogru) {
        // Doğru cevap
        labelElement.classList.add('correct');
        if (!cevaplar[currentQuestion].dogru) {
            dogruSayisi++;
            cevaplar[currentQuestion].dogru = true;
        }
        
        // Enable next button immediately
        if (currentQuestion < sorular.length - 1) {
            document.getElementById('nextBtn').disabled = false;
        } else {
            // Small delay before finishing to show correct answer
            setTimeout(() => {
                finishTest();
            }, 500);
        }
    } else {
        // Yanlış cevap
        labelElement.classList.add('wrong');
        if (!cevaplar[currentQuestion].yanlis.includes(selected)) {
            yanlisSayisi++;
            cevaplar[currentQuestion].yanlis.push(selected);
        }
    }
}

document.getElementById('prevBtn').addEventListener('click', () => {
    if (currentQuestion > 0) {
        currentQuestion--;
        showQuestion();
    }
});

document.getElementById('nextBtn').addEventListener('click', () => {
    if (currentQuestion < sorular.length - 1) {
        currentQuestion++;
        showQuestion();
    }
});

function finishTest() {
    const puan = Math.round((dogruSayisi / sorular.length) * 100);
    window.location.href = `egitim-test-sonuc.php?dogru=${dogruSayisi}&yanlis=${yanlisSayisi}&puan=${puan}`;
}

// İlk soruyu göster
showQuestion();
</script>

<?php include 'includes/footer.php'; ?>