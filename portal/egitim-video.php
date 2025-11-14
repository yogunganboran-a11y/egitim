<?php
require_once 'includes/auth-check.php';

$page_title = 'Eğitim Videosu';
$page_css = 'assets/css/egitim-video.css';

include 'includes/header.php';

$egitim_adi = "Temel Denizcilik";
?>

<!-- Video Container -->
<div class="video-container">
    <div class="video-wrapper" id="videoWrapper">
        <video id="educationVideo" class="video-player" playsinline webkit-playsinline x-webkit-airplay="deny">
            <source src="video.mp4" type="video/mp4">
            Tarayıcınız video etiketini desteklemiyor.
        </video>
        
        <!-- Play Overlay -->
        <div class="play-overlay" id="playOverlay">
            <button class="play-overlay-btn" id="playOverlayBtn">
                <i class="fas fa-play"></i>
            </button>
        </div>
        
        <!-- Custom Video Controls -->
        <div class="video-controls" id="videoControls">
            <button class="control-btn" id="playPauseBtn">
                <i class="fas fa-play"></i>
            </button>
            
            <div class="progress-container" id="progressContainer">
                <div class="progress-bar-video" id="progressBar">
                    <div class="progress-fill-video" id="progressFill"></div>
                </div>
            </div>
            
            <span class="time-display" id="timeDisplay">00:00 / 00:00</span>
            
            <button class="control-btn" id="volumeBtn">
                <i class="fas fa-volume-up"></i>
            </button>
            
            <button class="control-btn" id="fullscreenBtn">
                <i class="fas fa-expand"></i>
            </button>
        </div>
    </div>
    
    <!-- Test Mode Toggle -->
    <div class="test-mode">
        <label class="test-mode-switch">
            <input type="checkbox" id="testModeToggle">
            <span class="slider"></span>
        </label>
        <span>Test Modu (İleri sarma açık)</span>
    </div>
    
    <!-- Action Button -->
    <div class="video-actions">
        <button class="btn-continue" id="continueBtn" disabled>
            <i class="fas fa-lock"></i>
            <span>Videoyu sonuna kadar izleyin</span>
        </button>
    </div>
</div>

<script>
const video = document.getElementById('educationVideo');
const videoWrapper = document.getElementById('videoWrapper');
const playOverlay = document.getElementById('playOverlay');
const playOverlayBtn = document.getElementById('playOverlayBtn');
const videoControls = document.getElementById('videoControls');
const playPauseBtn = document.getElementById('playPauseBtn');
const progressContainer = document.getElementById('progressContainer');
const progressFill = document.getElementById('progressFill');
const timeDisplay = document.getElementById('timeDisplay');
const volumeBtn = document.getElementById('volumeBtn');
const fullscreenBtn = document.getElementById('fullscreenBtn');
const continueBtn = document.getElementById('continueBtn');
const testModeToggle = document.getElementById('testModeToggle');

let maxWatchedTime = 0;
let testMode = false;

video.controls = false;
video.setAttribute('playsinline', 'playsinline');
video.setAttribute('webkit-playsinline', 'webkit-playsinline');

// Prevent native fullscreen on iOS
video.addEventListener('webkitbeginfullscreen', (e) => {
    e.preventDefault();
    e.stopPropagation();
});

testModeToggle.addEventListener('change', (e) => {
    testMode = e.target.checked;
});

// Play overlay
playOverlayBtn.addEventListener('click', () => {
    video.play();
    playOverlay.style.display = 'none';
    videoControls.style.opacity = '1';
    playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
});

// Video click - always toggle play/pause
video.addEventListener('click', togglePlay);
playPauseBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    togglePlay();
});

videoWrapper.addEventListener('click', (e) => {
    if (e.target === videoWrapper || e.target === video) {
        togglePlay();
    }
});

function togglePlay() {
    if (video.paused) {
        video.play();
        playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
        playOverlay.style.display = 'none';
    } else {
        video.pause();
        playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
    }
}

// Mobil tam ekran düzeltmesi
document.addEventListener('fullscreenchange', adjustFullscreenLayout);
document.addEventListener('webkitfullscreenchange', adjustFullscreenLayout);

function adjustFullscreenLayout() {
    const isInFullscreen = isFullscreen();

    if (isInFullscreen) {
        // Mobil portrait mode için özel düzenleme
        if (window.innerHeight > window.innerWidth) {
            videoControls.style.position = 'fixed';
            videoControls.style.bottom = '20px';
            videoControls.style.left = '50%';
            videoControls.style.transform = 'translateX(-50%)';
            videoControls.style.width = '90%';
            videoControls.style.maxWidth = '500px';
            videoControls.style.zIndex = '10000';
        } else {
            // Landscape mode
            videoControls.style.position = 'fixed';
            videoControls.style.bottom = '0';
            videoControls.style.left = '50%';
            videoControls.style.transform = 'translateX(-50%)';
            videoControls.style.width = '90%';
            videoControls.style.maxWidth = '800px';
            videoControls.style.zIndex = '10000';
        }
    } else {
        videoControls.style.position = '';
        videoControls.style.bottom = '';
        videoControls.style.left = '';
        videoControls.style.transform = '';
        videoControls.style.width = '';
        videoControls.style.maxWidth = '';
        videoControls.style.zIndex = '';
    }
}

video.addEventListener('pause', () => {
    playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
});

video.addEventListener('play', () => {
    playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
    playOverlay.style.display = 'none';
});

// Update progress
video.addEventListener('timeupdate', () => {
    if (!testMode && video.currentTime > maxWatchedTime + 0.5) {
        video.currentTime = maxWatchedTime;
        return;
    }
    
    const percentage = (video.currentTime / video.duration) * 100;
    progressFill.style.width = percentage + '%';
    
    const current = formatTime(video.currentTime);
    const duration = formatTime(video.duration);
    timeDisplay.textContent = `${current} / ${duration}`;
    
    if (video.currentTime > maxWatchedTime) {
        maxWatchedTime = video.currentTime;
        const watchPercentage = (maxWatchedTime / video.duration) * 100;
        
        if (watchPercentage >= 99) {
            continueBtn.disabled = false;
            continueBtn.innerHTML = '<i class="fas fa-arrow-right"></i><span>Teste Geç</span>';
            continueBtn.onclick = () => {
                window.location.href = 'egitim-test.php?id=1';
            };
        }
    }
});

video.addEventListener('seeking', () => {
    if (!testMode && video.currentTime > maxWatchedTime) {
        video.currentTime = maxWatchedTime;
    }
});

video.addEventListener('seeked', () => {
    if (!testMode && video.currentTime > maxWatchedTime) {
        video.currentTime = maxWatchedTime;
    }
});

progressContainer.addEventListener('click', (e) => {
    const rect = progressContainer.getBoundingClientRect();
    const percentage = (e.clientX - rect.left) / rect.width;
    const newTime = percentage * video.duration;
    
    if (testMode || newTime <= maxWatchedTime) {
        video.currentTime = newTime;
    }
});

volumeBtn.addEventListener('click', () => {
    if (video.muted) {
        video.muted = false;
        volumeBtn.innerHTML = '<i class="fas fa-volume-up"></i>';
    } else {
        video.muted = true;
        volumeBtn.innerHTML = '<i class="fas fa-volume-mute"></i>';
    }
});

// Fullscreen with iOS support
fullscreenBtn.addEventListener('click', () => {
    if (!isFullscreen()) {
        enterFullscreen();
    } else {
        exitFullscreen();
    }
});

function isFullscreen() {
    return !!(document.fullscreenElement || document.webkitFullscreenElement || 
              document.mozFullScreenElement || document.msFullscreenElement ||
              videoWrapper.classList.contains('manual-fullscreen'));
}

function enterFullscreen() {
    // Try native fullscreen first (desktop)
    if (videoWrapper.requestFullscreen) {
        videoWrapper.requestFullscreen();
        fullscreenBtn.innerHTML = '<i class="fas fa-compress"></i>';
    } else if (videoWrapper.webkitRequestFullscreen) {
        videoWrapper.webkitRequestFullscreen();
        fullscreenBtn.innerHTML = '<i class="fas fa-compress"></i>';
    } else if (videoWrapper.mozRequestFullScreen) {
        videoWrapper.mozRequestFullScreen();
        fullscreenBtn.innerHTML = '<i class="fas fa-compress"></i>';
    } else {
        // Fallback: Manual fullscreen for iOS
        videoWrapper.classList.add('manual-fullscreen');
        document.body.style.overflow = 'hidden';
        fullscreenBtn.innerHTML = '<i class="fas fa-compress"></i>';
    }
}

function exitFullscreen() {
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
    } else if (document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
    } else {
        // Manual fullscreen exit
        videoWrapper.classList.remove('manual-fullscreen');
        document.body.style.overflow = '';
    }
    fullscreenBtn.innerHTML = '<i class="fas fa-expand"></i>';
}

document.addEventListener('fullscreenchange', updateFullscreenBtn);
document.addEventListener('webkitfullscreenchange', updateFullscreenBtn);
document.addEventListener('mozfullscreenchange', updateFullscreenBtn);
document.addEventListener('msfullscreenchange', updateFullscreenBtn);

function updateFullscreenBtn() {
    if (!isFullscreen()) {
        fullscreenBtn.innerHTML = '<i class="fas fa-expand"></i>';
    }
}

function formatTime(seconds) {
    if (isNaN(seconds)) return '00:00';
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
}
</script>

<?php include 'includes/footer.php'; ?>