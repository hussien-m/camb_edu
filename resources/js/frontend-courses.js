document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const mobileFilterForm = document.getElementById('mobileFilterForm');
    const coursesWrapper = document.getElementById('coursesWrapper');
    const coursesContainer = document.getElementById('coursesContainer');
    let currentPage = 1;
    let isLoading = false;
    let hasMorePages = true;
    let currentFilters = {};
    let currentBaseUrl = '/courses'; // Track current URL for Load More

    // Initialize
    init();

    function init() {
        // Get initial filters from URL/form on page load
        initializeFilters();
        setupFilterListeners();
        setupLoadMoreButton();
        setupMobileFilters();
    }

    // Initialize filters from current page URL or form values
    function initializeFilters() {
        // Get current URL path and search params
        const currentPath = window.location.pathname;
        const urlParams = new URLSearchParams(window.location.search);

        // Store current base URL (without page parameter)
        currentBaseUrl = currentPath === '/courses' ? '/courses' : currentPath;

        // If has keyword param, include it in base URL
        const keyword = urlParams.get('keyword');
        if (keyword) {
            currentBaseUrl = `${currentPath}?keyword=${encodeURIComponent(keyword)}`;
        }

        // Store filters from URL
        const filters = {};
        for (const [key, value] of urlParams.entries()) {
            if (key !== 'page' && value) {
                filters[key] = value;
            }
        }

        // If no URL params, get from form
        if (Object.keys(filters).length === 0) {
            const form = filterForm || mobileFilterForm;
            if (form) {
                const formData = new FormData(form);
                for (const [key, value] of formData.entries()) {
                    if (value) {
                        filters[key] = value;
                    }
                }
            }
        }

        currentFilters = filters;
        console.log('🔧 Initial state:', { currentBaseUrl, currentFilters });
    }

    // Setup filter listeners
    function setupFilterListeners() {
        const forms = [filterForm, mobileFilterForm].filter(f => f);

        forms.forEach(form => {
            // Prevent default form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                handleFilterSubmit(form);
            });

            // Auto-submit on select change
            const selectInputs = form.querySelectorAll('select.filter-input');
            selectInputs.forEach(input => {
                input.addEventListener('change', function() {
                    handleFilterSubmit(form);
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
                const href = btn.getAttribute('href') || '/courses';
                window.location.href = href;
            });
        });
    }

    // Handle filter submission with SEO-friendly URLs
    function handleFilterSubmit(form) {
        const formData = new FormData(form);
        const levelId = formData.get('level_id');
        const categoryId = formData.get('category_id');
        const keyword = formData.get('keyword');

        // Get slugs
        let levelSlug = null;
        let categorySlug = null;

        if (levelId) {
            const levelSelect = form.querySelector('[name="level_id"]');
            const selectedOption = levelSelect.options[levelSelect.selectedIndex];
            levelSlug = selectedOption.dataset.slug;
        }

        if (categoryId) {
            const categorySelect = form.querySelector('[name="category_id"]');
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            categorySlug = selectedOption.dataset.slug;
        }

        // Reset page
        currentPage = 1;
        hasMorePages = true;

        // Build SEO-friendly URL and use AJAX
        let targetUrl = '/courses';

        if (categoryId && levelId && categorySlug && levelSlug) {
            // Category + Level
            targetUrl = `/courses/category/${categorySlug}/level/${levelSlug}`;
        } else if (levelId && levelSlug) {
            // Only Level
            targetUrl = `/courses/level/${levelSlug}`;
        } else if (categoryId && categorySlug) {
            // Only Category
            targetUrl = `/courses/category/${categorySlug}`;
        } else if (keyword) {
            // Only keyword
            targetUrl = `/courses?keyword=${encodeURIComponent(keyword)}`;
        }

        // Store current base URL for Load More
        currentBaseUrl = targetUrl;

        // Update URL without reload
        window.history.pushState({}, '', targetUrl);

        // Load courses with AJAX
        loadCoursesFromUrl(targetUrl, true);
    }

    // Load courses from a specific URL using AJAX
    function loadCoursesFromUrl(url, replace = true) {
        if (isLoading) return;

        isLoading = true;
        setFiltersDisabled(true);

        // Show loading
        if (replace) {
            showSkeletonLoader();
        } else {
            showLoadMoreSpinner();
        }

        // Add page parameter if needed
        const separator = url.includes('?') ? '&' : '?';
        const requestUrl = currentPage > 1 ? `${url}${separator}page=${currentPage}` : url;

        console.log('🌐 AJAX Request URL:', requestUrl);

        // Fetch courses via AJAX
        fetch(requestUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('📦 Response:', {
                success: data.success,
                currentPage: data.currentPage,
                lastPage: data.lastPage,
                total: data.total,
                hasMore: data.hasMore
            });

            if (!data.success) {
                console.error('Failed to load courses');
                isLoading = false;
                setFiltersDisabled(false);
                return;
            }

            // Update pagination state from server response
            currentPage = data.currentPage;
            hasMorePages = data.currentPage < data.lastPage;

            if (replace) {
                renderCourses(data.html, true, data);
            } else {
                renderCourses(data.html, false, data);
            }

            updateLoadMoreButton(data);
            isLoading = false;
            setFiltersDisabled(false);
        })
        .catch(error => {
            console.error('❌ Error:', error);
            isLoading = false;
            setFiltersDisabled(false);
            hideSkeletonLoader();
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
            console.log('🔘 Load More clicked:', { isLoading, hasMorePages, currentPage, currentBaseUrl });

            if (!isLoading && hasMorePages) {
                currentPage++;
                console.log('➡️ Loading page:', currentPage);
                // Use loadCoursesFromUrl with current base URL to preserve filters
                loadCoursesFromUrl(currentBaseUrl, false); // Append mode
            } else {
                console.log('⛔ Cannot load more:', { isLoading, hasMorePages });
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

        console.log('🔍 Checking initial pagination...');

        if (resultInfo) {
            // Try to detect if there are more pages
            const text = resultInfo.textContent;
            const match = text.match(/Showing \d+-(\d+) of (\d+)/);

            console.log('📝 Result info text:', text);
            console.log('🔢 Match:', match);

            if (match) {
                const showing = parseInt(match[1]);
                const total = parseInt(match[2]);
                hasMorePages = showing < total;

                console.log('📊 Initial state:', { showing, total, hasMorePages });

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
            // If no formData provided, use stored filters or get from form
            if (Object.keys(currentFilters).length > 0 && !replace) {
                // Use stored filters for "Load More"
                formData = new FormData();
                for (const [key, value] of Object.entries(currentFilters)) {
                    if (value) formData.append(key, value);
                }
            } else {
                // Get fresh data from form for new search
                const form = filterForm || mobileFilterForm;
                formData = form ? new FormData(form) : new FormData();
            }
        }

        // Store current filters
        currentFilters = Object.fromEntries(formData);

        // Build URL with page
        const params = new URLSearchParams(currentFilters);
        params.set('page', currentPage);
        const baseUrl = (filterForm || mobileFilterForm).getAttribute('data-url');
        const url = `${baseUrl}?${params.toString()}`;

        // Update browser URL without reloading
        if (replace && currentPage === 1) {
            const cleanParams = new URLSearchParams(currentFilters);
            const displayUrl = cleanParams.toString() ? `${baseUrl}?${cleanParams.toString()}` : baseUrl;
            window.history.pushState({}, '', displayUrl);
        }

        console.log('🌐 Request URL:', url);
        console.log('📋 Filters:', currentFilters);
        console.log('📄 Page:', currentPage);        // Show loading
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
            console.log('📦 Response:', {
                success: data.success,
                hasMore: data.hasMore,
                currentPage: data.currentPage,
                lastPage: data.lastPage,
                total: data.total,
                coursesInResponse: data.html ? 'yes' : 'no'
            });

            // Check if we actually got data
            if (!data.success) {
                console.error('Failed to load courses');
                isLoading = false;
                setFiltersDisabled(false);
                return;
            }

            // Update pagination state FIRST
            // Use lastPage as the authoritative source
            hasMorePages = data.currentPage < data.lastPage;

            console.log('📊 State after update:', {
                hasMorePages,
                currentPage,
                lastPage: data.lastPage,
                total: data.total,
                calculation: `${data.currentPage} < ${data.lastPage} = ${hasMorePages}`
            });            if (replace) {
                // Replace all courses
                renderCourses(data.html, true, data);
            } else {
                // Append if there are more pages
                renderCourses(data.html, false, data);
            }

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
    function renderCourses(html, replace, data) {
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

                // Check if we actually have new cards
                if (newCards.length === 0) {
                    console.log('No new courses to append');
                    hideLoading();
                    return;
                }

                // Get existing course links to prevent duplicates
                const existingLinks = new Set();
                existingGrid.querySelectorAll('a[href]').forEach(link => {
                    existingLinks.add(link.getAttribute('href'));
                });

                let addedCount = 0;
                newCards.forEach((card, index) => {
                    // Check if this course is already displayed
                    const cardLink = card.querySelector('a[href]');
                    const href = cardLink ? cardLink.getAttribute('href') : null;

                    if (href && existingLinks.has(href)) {
                        return; // Skip duplicate
                    }

                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    existingGrid.appendChild(card);
                    addedCount++;

                    // Animate in with stagger
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100 + (addedCount * 50));
                });
            }

            // Update result info with correct total count
            const existingInfo = coursesContainer.querySelector('.result-info h5');
            if (existingInfo && data) {
                const totalShown = existingGrid.querySelectorAll('.col-lg-4').length;
                existingInfo.innerHTML = `
                    <i class="fas fa-graduation-cap"></i>
                    Showing 1-${totalShown} of ${data.total} courses
                `;
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

        // Recalculate based on fresh data
        const actuallyHasMore = data.currentPage < data.lastPage;

        console.log('🔄 Update button:', {
            currentPage: data.currentPage,
            lastPage: data.lastPage,
            total: data.total,
            actuallyHasMore
        });

        if (actuallyHasMore) {
            loadMoreContainer.style.display = 'block';
            loadMoreContainer.className = 'text-center mt-5';

            // Calculate remaining courses
            const loaded = data.currentPage * 12; // 12 per page
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
            // Show end message instead of hiding
            loadMoreContainer.style.display = 'block';
            loadMoreContainer.className = 'text-center mt-5';
            loadMoreContainer.innerHTML = `
                <div class="end-of-results">
                    <div class="end-icon mb-3">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h5 class="mb-2">You've reached the end!</h5>
                    <p class="text-muted mb-0">You've viewed all ${data.total || 0} available courses</p>
                </div>
            `;
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
