document.addEventListener("DOMContentLoaded",function(){const m=document.getElementById("filterForm"),f=document.getElementById("mobileFilterForm"),h=document.getElementById("coursesWrapper"),i=document.getElementById("coursesContainer");let c=1,d=!1,a=!0;L();function L(){b(),E(),k()}function b(){const e=[m,f].filter(n=>n);e.forEach(n=>{n.addEventListener("submit",function(t){t.preventDefault(),c=1,a=!0,u(new FormData(this),!0)}),n.querySelectorAll("select.filter-input").forEach(t=>{t.addEventListener("change",function(){c=1,a=!0,u(new FormData(n),!0)})});const r=n.querySelector('[name="keyword"]');if(r){let t;r.addEventListener("input",function(){clearTimeout(t),t=setTimeout(()=>{c=1,a=!0,u(new FormData(n),!0)},600)})}}),document.querySelectorAll('[id^="resetBtn"], .btn-filter-reset').forEach(n=>{n.addEventListener("click",function(s){s.preventDefault(),e.forEach(r=>r&&r.reset()),c=1,a=!0,u(new FormData,!0)})})}function E(){const e=document.createElement("div");e.id="loadMoreContainer",e.className="text-center mt-5",e.innerHTML=`
            <button type="button" id="loadMoreBtn" class="btn btn-primary btn-lg px-5 py-3">
                <i class="fas fa-plus-circle me-2"></i>
                <span class="load-more-text">Load More Courses</span>
                <span class="load-more-count ms-2"></span>
            </button>
        `,h.appendChild(e),document.getElementById("loadMoreBtn").addEventListener("click",function(){!d&&a&&(c++,u(null,!1))}),B()}function B(){const e=document.getElementById("coursesContainer"),n=e?e.querySelector(".result-info"):null;if(n){const r=n.textContent.match(/Showing \d+-(\d+) of (\d+)/);if(r){const t=parseInt(r[1]),o=parseInt(r[2]);a=t<o;const l=document.getElementById("loadMoreContainer");if(l&&(l.style.display=a?"block":"none",a)){const g=o-t,S=Math.min(g,12),v=document.getElementById("loadMoreBtn");v&&(v.innerHTML=`
                                <i class="fas fa-plus-circle me-2"></i>
                                <span class="load-more-text">Load More Courses</span>
                                <span class="load-more-count ms-2">(${S} more)</span>
                            `)}}}}function k(){const e=document.querySelector(".btn-mobile-filter");e&&e.addEventListener("click",function(){this.classList.toggle("active")})}function p(e){[m,f].filter(s=>s).forEach(s=>{s.querySelectorAll("input, select, button").forEach(t=>{t.disabled=e,e?(t.style.opacity="0.6",t.style.cursor="not-allowed"):(t.style.opacity="",t.style.cursor="")})})}function u(e=null,n=!0){if(d)return;if(d=!0,p(!0),!e){const o=m||f;e=o?new FormData(o):new FormData}Object.fromEntries(e);const s=new URLSearchParams(e);s.set("page",c);const t=`${(m||f).getAttribute("data-url")}?${s.toString()}`;n?w():I(),fetch(t,{headers:{"X-Requested-With":"XMLHttpRequest",Accept:"application/json"}}).then(o=>o.json()).then(o=>{if(n?M(o.html,!0):M(o.html,!1),a=o.hasMore,C(o),n){const l=t.replace("&page=1","").replace("page=1&","").replace("?page=1","");window.history.pushState({},"",l||t)}d=!1,p(!1)}).catch(o=>{console.error("Error:",o),y(),d=!1,p(!1)})}function M(e,n){const s=document.createElement("div");s.innerHTML=e;const r=s.querySelector(".row.g-4");if(n)i.style.opacity="0",i.style.transition="opacity 0.3s",setTimeout(()=>{const t=i.querySelector(".row.g-4");t&&r&&(t.innerHTML=r.innerHTML);const o=s.querySelector(".result-info"),l=i.querySelector(".result-info");o&&l?l.outerHTML=o.outerHTML:o&&i.insertBefore(o,i.firstChild),i.style.opacity="1",y(),h.scrollIntoView({behavior:"smooth",block:"start"})},300);else{const t=i.querySelector(".row.g-4");t&&r&&r.querySelectorAll(".col-lg-4").forEach((l,g)=>{l.style.opacity="0",l.style.transform="translateY(20px)",t.appendChild(l),setTimeout(()=>{l.style.transition="all 0.5s ease",l.style.opacity="1",l.style.transform="translateY(0)"},100+g*50)}),y()}}function w(){const e=i.querySelector(".row.g-4");e&&(e.innerHTML=Array(6).fill(0).map(()=>`
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
            `).join(""))}function C(e){const n=document.getElementById("loadMoreContainer"),s=document.getElementById("loadMoreBtn");if(!(!n||!s))if(e.hasMore){n.style.display="block";const r=c*12,t=e.total-r,o=Math.min(t,12);s.disabled=!1,s.innerHTML=`
                <i class="fas fa-plus-circle me-2"></i>
                <span class="load-more-text">Load More Courses</span>
                <span class="load-more-count ms-2">(${o} more)</span>
            `}else n.style.display="none"}function I(){const e=document.getElementById("loadMoreBtn");e&&(e.disabled=!0,e.innerHTML=`
                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                Loading...
            `)}function y(){const e=document.getElementById("load-more-spinner");e&&e.remove()}});
