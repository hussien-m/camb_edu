document.addEventListener("DOMContentLoaded",function(){const g=document.getElementById("filterForm"),p=document.getElementById("mobileFilterForm"),L=document.getElementById("coursesWrapper"),c=document.getElementById("coursesContainer");let a=1,f=!1,i=!0,m={};B();function B(){S(),I(),C(),x()}function S(){const e=new URLSearchParams(window.location.search),o={};for(const[s,l]of e.entries())s!=="page"&&l&&(o[s]=l);if(Object.keys(o).length===0){const s=g||p;if(s){const l=new FormData(s);for(const[n,t]of l.entries())t&&(o[n]=t)}}m=o,console.log("🔧 Initial filters:",m)}function I(){const e=[g,p].filter(o=>o);e.forEach(o=>{o.addEventListener("submit",function(n){n.preventDefault(),a=1,i=!0,y(new FormData(this),!0)}),o.querySelectorAll("select.filter-input").forEach(n=>{n.addEventListener("change",function(){a=1,i=!0,y(new FormData(o),!0)})});const l=o.querySelector('[name="keyword"]');if(l){let n;l.addEventListener("input",function(){clearTimeout(n),n=setTimeout(()=>{a=1,i=!0,y(new FormData(o),!0)},600)})}}),document.querySelectorAll('[id^="resetBtn"], .btn-filter-reset').forEach(o=>{o.addEventListener("click",function(s){s.preventDefault(),e.forEach(l=>l&&l.reset()),a=1,i=!0,m={},y(new FormData,!0)})})}function C(){const e=document.createElement("div");e.id="loadMoreContainer",e.className="text-center mt-5",e.innerHTML=`
            <button type="button" id="loadMoreBtn" class="btn btn-primary btn-lg px-5 py-3">
                <i class="fas fa-plus-circle me-2"></i>
                <span class="load-more-text">Load More Courses</span>
                <span class="load-more-count ms-2"></span>
            </button>
        `,L.appendChild(e),document.getElementById("loadMoreBtn").addEventListener("click",function(){console.log("🔘 Load More clicked:",{isLoading:f,hasMorePages:i,currentPage:a}),!f&&i?(a++,console.log("➡️ Loading page:",a),y(null,!1)):console.log("⛔ Cannot load more:",{isLoading:f,hasMorePages:i})}),P()}function P(){const e=document.getElementById("coursesContainer"),o=e?e.querySelector(".result-info"):null;if(console.log("🔍 Checking initial pagination..."),o){const s=o.textContent,l=s.match(/Showing \d+-(\d+) of (\d+)/);if(console.log("📝 Result info text:",s),console.log("🔢 Match:",l),l){const n=parseInt(l[1]),t=parseInt(l[2]);i=n<t,console.log("📊 Initial state:",{showing:n,total:t,hasMorePages:i});const r=document.getElementById("loadMoreContainer");if(r&&(r.style.display=i?"block":"none",i)){const u=t-n,b=Math.min(u,12),h=document.getElementById("loadMoreBtn");h&&(h.innerHTML=`
                                <i class="fas fa-plus-circle me-2"></i>
                                <span class="load-more-text">Load More Courses</span>
                                <span class="load-more-count ms-2">(${b} more)</span>
                            `)}}}}function x(){const e=document.querySelector(".btn-mobile-filter");e&&e.addEventListener("click",function(){this.classList.toggle("active")})}function M(e){[g,p].filter(s=>s).forEach(s=>{s.querySelectorAll("input, select, button").forEach(n=>{n.disabled=e,e?(n.style.opacity="0.6",n.style.cursor="not-allowed"):(n.style.opacity="",n.style.cursor="")})})}function y(e=null,o=!0){if(f)return;if(f=!0,M(!0),!e)if(Object.keys(m).length>0&&!o){e=new FormData;for(const[t,r]of Object.entries(m))r&&e.append(t,r)}else{const t=g||p;e=t?new FormData(t):new FormData}m=Object.fromEntries(e);const s=new URLSearchParams(m);s.set("page",a);const n=`${(g||p).getAttribute("data-url")}?${s.toString()}`;console.log("🌐 Request URL:",n),console.log("📋 Filters:",m),console.log("📄 Page:",a),o?F():T(),fetch(n,{headers:{"X-Requested-With":"XMLHttpRequest",Accept:"application/json"}}).then(t=>t.json()).then(t=>{if(console.log("📦 Response:",{success:t.success,hasMore:t.hasMore,currentPage:t.currentPage,lastPage:t.lastPage,total:t.total,coursesInResponse:t.html?"yes":"no"}),!t.success){console.error("Failed to load courses"),f=!1,M(!1);return}if(i=t.currentPage<t.lastPage,console.log("📊 State after update:",{hasMorePages:i,currentPage:a,lastPage:t.lastPage,total:t.total,calculation:`${t.currentPage} < ${t.lastPage} = ${i}`}),o?k(t.html,!0,t):k(t.html,!1,t),q(t),o){const r=n.replace("&page=1","").replace("page=1&","").replace("?page=1","");window.history.pushState({},"",r||n)}f=!1,M(!1)}).catch(t=>{console.error("Error:",t),v(),f=!1,M(!1)})}function k(e,o,s){const l=document.createElement("div");l.innerHTML=e;const n=l.querySelector(".row.g-4");if(o)c.style.opacity="0",c.style.transition="opacity 0.3s",setTimeout(()=>{const t=c.querySelector(".row.g-4");t&&n&&(t.innerHTML=n.innerHTML);const r=l.querySelector(".result-info"),u=c.querySelector(".result-info");r&&u?u.outerHTML=r.outerHTML:r&&c.insertBefore(r,c.firstChild),c.style.opacity="1",v(),L.scrollIntoView({behavior:"smooth",block:"start"})},300);else{const t=c.querySelector(".row.g-4");if(t&&n){const u=n.querySelectorAll(".col-lg-4");if(u.length===0){console.log("No new courses to append"),v();return}const b=new Set;t.querySelectorAll("a[href]").forEach(d=>{b.add(d.getAttribute("href"))});let h=0;u.forEach((d,H)=>{const w=d.querySelector("a[href]"),E=w?w.getAttribute("href"):null;E&&b.has(E)||(d.style.opacity="0",d.style.transform="translateY(20px)",t.appendChild(d),h++,setTimeout(()=>{d.style.transition="all 0.5s ease",d.style.opacity="1",d.style.transform="translateY(0)"},100+h*50))})}const r=c.querySelector(".result-info h5");if(r&&s){const u=t.querySelectorAll(".col-lg-4").length;r.innerHTML=`
                    <i class="fas fa-graduation-cap"></i>
                    Showing 1-${u} of ${s.total} courses
                `}v()}}function F(){const e=c.querySelector(".row.g-4");e&&(e.innerHTML=Array(6).fill(0).map(()=>`
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
            `).join(""))}function q(e){const o=document.getElementById("loadMoreContainer"),s=document.getElementById("loadMoreBtn");if(!o||!s)return;const l=e.currentPage<e.lastPage;if(console.log("🔄 Update button:",{currentPage:e.currentPage,lastPage:e.lastPage,total:e.total,actuallyHasMore:l}),l){o.style.display="block",o.className="text-center mt-5";const n=e.currentPage*12,t=e.total-n,r=Math.min(t,12);s.disabled=!1,s.innerHTML=`
                <i class="fas fa-plus-circle me-2"></i>
                <span class="load-more-text">Load More Courses</span>
                <span class="load-more-count ms-2">(${r} more)</span>
            `}else o.style.display="block",o.className="text-center mt-5",o.innerHTML=`
                <div class="end-of-results">
                    <div class="end-icon mb-3">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h5 class="mb-2">You've reached the end!</h5>
                    <p class="text-muted mb-0">You've viewed all ${e.total||0} available courses</p>
                </div>
            `}function T(){const e=document.getElementById("loadMoreBtn");e&&(e.disabled=!0,e.innerHTML=`
                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                Loading...
            `)}function v(){const e=document.getElementById("load-more-spinner");e&&e.remove()}});
