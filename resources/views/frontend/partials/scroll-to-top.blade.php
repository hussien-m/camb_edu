<!-- Scroll to Top Button -->
<button id="scrollToTopBtn" class="scroll-to-top-btn" title="Back to Top">
    <i class="fas fa-arrow-up"></i>
</button>

<style>
/* Scroll to Top Button */
.scroll-to-top-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 55px;
    height: 55px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0e6b50 0%, #1a9b74 100%);
    color: white;
    border: none;
    font-size: 1.3rem;
    cursor: pointer;
    box-shadow: 0 5px 25px rgba(14, 107, 80, 0.5);
    opacity: 0;
    visibility: hidden;
    transform: scale(0);
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    z-index: 99999;
}

.scroll-to-top-btn.show {
    opacity: 1;
    visibility: visible;
    transform: scale(1);
}

.scroll-to-top-btn:hover {
    background: linear-gradient(135deg, #1a9b74 0%, #0c5d45 100%);
    transform: scale(1.1) translateY(-5px);
    box-shadow: 0 8px 30px rgba(14, 107, 80, 0.6);
}

.scroll-to-top-btn:active {
    transform: scale(0.95);
}

.scroll-to-top-btn i {
    animation: arrowUp 1.5s ease-in-out infinite;
}

@keyframes arrowUp {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-5px);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .scroll-to-top-btn {
        width: 50px;
        height: 50px;
        bottom: 20px;
        right: 20px;
        font-size: 1.1rem;
    }
}

/* Extra styling for better UX */
.scroll-to-top-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: inherit;
    transform: translate(-50%, -50%);
    z-index: -1;
    opacity: 0;
    transition: all 0.3s ease;
}

.scroll-to-top-btn:hover::before {
    opacity: 0.3;
    width: 120%;
    height: 120%;
}
</style>

<script>
// Scroll to Top Button Functionality
(function() {
    const scrollBtn = document.getElementById('scrollToTopBtn');
    
    // Show/Hide button based on scroll position
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollBtn.classList.add('show');
        } else {
            scrollBtn.classList.remove('show');
        }
    });
    
    // Scroll to top when clicked
    scrollBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
})();
</script>

