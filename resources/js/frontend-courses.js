document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const coursesWrapper = document.getElementById('coursesWrapper');
    const coursesContainer = document.getElementById('coursesContainer');
    const resetBtn = document.getElementById('resetBtn');

    // Handle filter form submission
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            loadCourses(new FormData(this));
        });

        // Auto-submit on filter change
        const filterInputs = document.querySelectorAll('.filter-input');
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                filterForm.dispatchEvent(new Event('submit'));
            });
        });

        // Handle keyword search with delay
        const keywordInput = document.querySelector('[name="keyword"]');
        let searchTimeout;
        if (keywordInput) {
            keywordInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    filterForm.dispatchEvent(new Event('submit'));
                }, 500);
            });
        }
    }

    // Handle reset button
    if (resetBtn) {
        resetBtn.addEventListener('click', function(e) {
            e.preventDefault();
            filterForm.reset();
            loadCourses(new FormData());
        });
    }

    // Load courses function
    function loadCourses(formData) {
        // Show loading first
        showLoading();

        // Then scroll to courses area
        setTimeout(() => {
            coursesWrapper.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }, 100);

        // Build query string
        const params = new URLSearchParams(formData);
        const baseUrl = filterForm.getAttribute('data-url');
        const url = `${baseUrl}?${params.toString()}`;

        // Fetch courses
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parse the response
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.querySelector('#coursesContainer');

            if (newContent) {
                // Update content with fade animation
                coursesContainer.style.opacity = '0';
                setTimeout(() => {
                    coursesContainer.innerHTML = newContent.innerHTML;
                    coursesContainer.style.opacity = '1';
                    coursesContainer.classList.add('fade-in');

                    // Re-attach pagination listeners
                    attachPaginationListeners();

                    // Smooth scroll to top of results
                    coursesWrapper.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });

                    // Update URL without reload
                    window.history.pushState({}, '', url);

                    // Hide loading
                    hideLoading();
                }, 300);
            }
        })
        .catch(error => {
            console.error('Error loading courses:', error);
            hideLoading();
            // Fallback: redirect to the URL directly
            window.location.href = url;
        });
    }

    // Handle pagination clicks
    function attachPaginationListeners() {
        const paginationLinks = document.querySelectorAll('#paginationContainer .page-link');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                if (url && !this.parentElement.classList.contains('disabled')) {
                    loadCoursesFromUrl(url);
                }
            });
        });
    }

    // Load courses from URL
    function loadCoursesFromUrl(url) {
        // Show loading first
        showLoading();

        // Then scroll to top
        setTimeout(() => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }, 100);

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.querySelector('#coursesContainer');

            if (newContent) {
                coursesContainer.style.opacity = '0';
                setTimeout(() => {
                    coursesContainer.innerHTML = newContent.innerHTML;
                    coursesContainer.style.opacity = '1';
                    coursesContainer.classList.add('fade-in');
                    attachPaginationListeners();

                    // Scroll to top smoothly
                    window.scrollTo({ top: 0, behavior: 'smooth' });

                    window.history.pushState({}, '', url);

                    // Hide loading
                    hideLoading();
                }, 300);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            hideLoading();
            // Fallback: redirect to the URL directly
            window.location.href = url;
        });
    }

    // Show loading overlay
    function showLoading() {
        // Remove existing overlay if any
        hideLoading();

        const overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        overlay.id = 'loadingOverlay';
        overlay.innerHTML = `
            <div class="loading-spinner">
                <div class="spinner"></div>
                <p>Loading Courses...</p>
            </div>
        `;
        coursesWrapper.appendChild(overlay);
    }

    // Hide loading overlay
    function hideLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.remove();
        }
    }

    // Initial pagination listeners
    attachPaginationListeners();
});
