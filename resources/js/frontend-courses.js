document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const mobileFilterForm = document.getElementById('mobileFilterForm');
    const coursesWrapper = document.getElementById('coursesWrapper');
    const coursesContainer = document.getElementById('coursesContainer');
    let currentPage = 1;
    let isLoading = false;
    let hasMorePages = true;
    let currentFilters = {};

    // Initialize
    init();

    function init() {
        setupFilterListeners();
        setupInfiniteScroll();
        setupMobileFilters();
    }

    // Setup filter listeners
    function setupFilterListeners() {
        const forms = [filterForm, mobileFilterForm].filter(f => f);

        forms.forEach(form => {
            // Prevent default form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                currentPage = 1;
                hasMorePages = true;
                loadCourses(new FormData(this), true);
            });

            // Auto-submit on select change
            const selectInputs = form.querySelectorAll('select.filter-input');
            selectInputs.forEach(input => {
                input.addEventListener('change', function() {
                    currentPage = 1;
                    hasMorePages = true;
                    loadCourses(new FormData(form), true);
                });
            });

            // Debounce search input
            const keywordInput = form.querySelector('[name="keyword"]');
            if (keywordInput) {
                let searchTimeout;
                keywordInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        currentPage = 1;
                        hasMorePages = true;
                        loadCourses(new FormData(form), true);
                    }, 600); // 600ms debounce
                });
            }
        });

        // Reset button
        document.querySelectorAll('[id^="resetBtn"], .btn-filter-reset').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                forms.forEach(f => f && f.reset());
                currentPage = 1;
                hasMorePages = true;
                currentFilters = {};
                loadCourses(new FormData(), true);
            });
        });
    }

    // Setup infinite scroll
    function setupInfiniteScroll() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !isLoading && hasMorePages) {
                    currentPage++;
                    loadCourses(null, false); // Append mode
                }
            });
        }, {
            rootMargin: '100px' // Load before reaching bottom
        });

        // Observe scroll trigger
        const trigger = document.createElement('div');
        trigger.id = 'scroll-trigger';
        trigger.style.height = '1px';
        coursesWrapper.appendChild(trigger);
        observer.observe(trigger);
    }

    // Setup mobile filters
    function setupMobileFilters() {
        const mobileToggle = document.querySelector('.btn-mobile-filter');
        if (mobileToggle) {
            mobileToggle.addEventListener('click', function() {
                this.classList.toggle('active');
            });
        }
    }

    // Disable/Enable filters
    function setFiltersDisabled(disabled) {
        const forms = [filterForm, mobileFilterForm].filter(f => f);
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, select, button');
            inputs.forEach(input => {
                input.disabled = disabled;
                if (disabled) {
                    input.style.opacity = '0.6';
                    input.style.cursor = 'not-allowed';
                } else {
                    input.style.opacity = '';
                    input.style.cursor = '';
                }
            });
        });
    }

    // Load courses function
    function loadCourses(formData = null, replace = true) {
        if (isLoading) return;

        isLoading = true;
        setFiltersDisabled(true);

        // Get form data
        if (!formData) {
            const form = filterForm || mobileFilterForm;
            formData = form ? new FormData(form) : new FormData();
        }

        // Store current filters
        currentFilters = Object.fromEntries(formData);

        // Build URL with page
        const params = new URLSearchParams(formData);
        params.set('page', currentPage);
        const baseUrl = (filterForm || mobileFilterForm).getAttribute('data-url');
        const url = `${baseUrl}?${params.toString()}`;

        // Show loading
        if (replace) {
            showSkeletonLoader();
        } else {
            showLoadMoreSpinner();
        }

        // Fetch courses
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (replace) {
                // Replace all courses
                renderCourses(data.html, true);
            } else {
                // Append courses
                renderCourses(data.html, false);
            }

            // Update pagination state
            hasMorePages = data.hasMore;

            // Update URL
            if (replace) {
                const cleanUrl = url.replace('&page=1', '').replace('page=1&', '').replace('?page=1', '');
                window.history.pushState({}, '', cleanUrl || url);
            }

            isLoading = false;
            setFiltersDisabled(false);
        })
        .catch(error => {
            console.error('Error:', error);
            hideLoading();
            isLoading = false;
            setFiltersDisabled(false);
        });
    }

    // Render courses
    function renderCourses(html, replace) {
        const temp = document.createElement('div');
        temp.innerHTML = html;
        const newCourses = temp.querySelector('.row.g-4');

        if (replace) {
            // Replace with fade effect
            coursesContainer.style.opacity = '0';
            coursesContainer.style.transition = 'opacity 0.3s';

            setTimeout(() => {
                // Find and replace the course grid
                const existingGrid = coursesContainer.querySelector('.row.g-4');
                if (existingGrid && newCourses) {
                    existingGrid.innerHTML = newCourses.innerHTML;
                }

                // Update result info
                const resultInfo = temp.querySelector('.result-info');
                const existingInfo = coursesContainer.querySelector('.result-info');
                if (resultInfo && existingInfo) {
                    existingInfo.outerHTML = resultInfo.outerHTML;
                } else if (resultInfo) {
                    coursesContainer.insertBefore(resultInfo, coursesContainer.firstChild);
                }

                coursesContainer.style.opacity = '1';
                hideLoading();

                // Smooth scroll to results
                coursesWrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 300);
        } else {
            // Append courses
            const existingGrid = coursesContainer.querySelector('.row.g-4');
            if (existingGrid && newCourses) {
                const newCards = newCourses.querySelectorAll('.col-lg-4');
                newCards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    existingGrid.appendChild(card);

                    // Animate in with stagger
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100 + (index * 50));
                });
            }
            hideLoading();
        }
    }

    // Show skeleton loader
    function showSkeletonLoader() {
        const grid = coursesContainer.querySelector('.row.g-4');
        if (grid) {
            grid.innerHTML = Array(6).fill(0).map(() => `
                <div class="col-lg-4 col-md-6">
                    <div class="course-card-skeleton">
                        <div class="skeleton skeleton-img"></div>
                        <div class="skeleton-body">
                            <div class="skeleton skeleton-badge"></div>
                            <div class="skeleton skeleton-title"></div>
                            <div class="skeleton skeleton-text"></div>
                            <div class="skeleton skeleton-text"></div>
                            <div class="skeleton skeleton-button"></div>
                        </div>
                    </div>
                </div>
            `).join('');
        }
    }

    // Show load more spinner
    function showLoadMoreSpinner() {
        let spinner = document.getElementById('load-more-spinner');
        if (!spinner) {
            spinner = document.createElement('div');
            spinner.id = 'load-more-spinner';
            spinner.className = 'text-center py-4';
            spinner.innerHTML = `
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading more courses...</p>
            `;
            coursesWrapper.appendChild(spinner);
        }
    }

    // Hide loading
    function hideLoading() {
        const spinner = document.getElementById('load-more-spinner');
        if (spinner) {
            spinner.remove();
        }
    }
});
