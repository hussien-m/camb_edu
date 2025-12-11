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
        setupLoadMoreButton();
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

    // Setup Load More button
    function setupLoadMoreButton() {
        // Create Load More button
        const loadMoreContainer = document.createElement('div');
        loadMoreContainer.id = 'loadMoreContainer';
        loadMoreContainer.className = 'text-center mt-5';
        loadMoreContainer.innerHTML = `
            <button type="button" id="loadMoreBtn" class="btn btn-primary btn-lg px-5 py-3">
                <i class="fas fa-plus-circle me-2"></i>
                <span class="load-more-text">Load More Courses</span>
                <span class="load-more-count ms-2"></span>
            </button>
        `;
        coursesWrapper.appendChild(loadMoreContainer);

        // Button click handler
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        loadMoreBtn.addEventListener('click', function() {
            if (!isLoading && hasMorePages) {
                currentPage++;
                loadCourses(null, false); // Append mode
            }
        });

        // Check initial state - show/hide based on hasMorePages
        // Get initial pagination state from page
        checkInitialPagination();
    }

    // Check if there are more pages on initial load
    function checkInitialPagination() {
        const coursesContainer = document.getElementById('coursesContainer');
        const resultInfo = coursesContainer ? coursesContainer.querySelector('.result-info') : null;

        if (resultInfo) {
            // Try to detect if there are more pages
            const text = resultInfo.textContent;
            const match = text.match(/Showing \d+-(\d+) of (\d+)/);
            if (match) {
                const showing = parseInt(match[1]);
                const total = parseInt(match[2]);
                hasMorePages = showing < total;

                const loadMoreContainer = document.getElementById('loadMoreContainer');
                if (loadMoreContainer) {
                    loadMoreContainer.style.display = hasMorePages ? 'block' : 'none';

                    if (hasMorePages) {
                        const remaining = total - showing;
                        const nextBatch = Math.min(remaining, 12);
                        const loadMoreBtn = document.getElementById('loadMoreBtn');
                        if (loadMoreBtn) {
                            loadMoreBtn.innerHTML = `
                                <i class="fas fa-plus-circle me-2"></i>
                                <span class="load-more-text">Load More Courses</span>
                                <span class="load-more-count ms-2">(${nextBatch} more)</span>
                            `;
                        }
                    }
                }
            }
        }
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

            // Update Load More button
            updateLoadMoreButton(data);

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

    // Update Load More button
    function updateLoadMoreButton(data) {
        const loadMoreContainer = document.getElementById('loadMoreContainer');
        const loadMoreBtn = document.getElementById('loadMoreBtn');

        if (!loadMoreContainer || !loadMoreBtn) return;

        if (data.hasMore) {
            loadMoreContainer.style.display = 'block';

            // Calculate remaining courses
            const loaded = currentPage * 12; // 12 per page
            const remaining = data.total - loaded;
            const nextBatch = Math.min(remaining, 12);

            // Update button text
            loadMoreBtn.disabled = false;
            loadMoreBtn.innerHTML = `
                <i class="fas fa-plus-circle me-2"></i>
                <span class="load-more-text">Load More Courses</span>
                <span class="load-more-count ms-2">(${nextBatch} more)</span>
            `;
        } else {
            loadMoreContainer.style.display = 'none';
        }
    }

    // Show load more spinner
    function showLoadMoreSpinner() {
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        if (loadMoreBtn) {
            loadMoreBtn.disabled = true;
            loadMoreBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                Loading...
            `;
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
