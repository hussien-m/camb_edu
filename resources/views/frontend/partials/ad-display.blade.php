@php
    $target = $ad->open_in_new_tab ? '_blank' : '_self';
    $hasLink = !empty($ad->link);
@endphp

<div class="ad-wrapper ad-type-{{ $ad->type }}" data-ad-id="{{ $ad->id }}">
    @if($hasLink)
        <a href="{{ route('ad.click', $ad) }}" target="{{ $target }}" class="ad-link" rel="nofollow">
    @endif

    @if(!empty($ad->image))
        <div class="ad-image-container">
            <img src="{{ asset('storage/' . $ad->image) }}" 
                 alt="{{ $ad->title ?? 'Advertisement' }}" 
                 class="ad-image"
                 loading="lazy">
            @if(!empty($ad->title) || !empty($ad->description))
                <div class="ad-overlay">
                    @if(!empty($ad->title))
                        <h3 class="ad-title">{{ $ad->title }}</h3>
                    @endif
                    @if(!empty($ad->description))
                        <p class="ad-description">{{ $ad->description }}</p>
                    @endif
                </div>
            @endif
        </div>
    @elseif(!empty($ad->html_content))
        <div class="ad-html-wrapper">
            {!! $ad->html_content !!}
        </div>
    @elseif(!empty($ad->title) || !empty($ad->description))
        <div class="ad-text-container">
            @if(!empty($ad->title))
                <h3 class="ad-title-text">{{ $ad->title }}</h3>
            @endif
            @if(!empty($ad->description))
                <p class="ad-description-text">{{ $ad->description }}</p>
            @endif
            @if($hasLink)
                <span class="ad-cta">
                    <i class="fas fa-arrow-right"></i> Learn More
                </span>
            @endif
        </div>
    @endif

    @if($hasLink)
        </a>
    @endif
    
    {{-- Track view --}}
    <img src="{{ route('ad.view', $ad) }}" width="1" height="1" style="display:none;" alt="">
</div>

<style>
    .ad-wrapper {
        width: 100%;
        margin: 0 auto;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .ad-wrapper:hover {
        transform: translateY(-2px);
    }
    
    .ad-link {
        display: block;
        text-decoration: none;
        color: inherit;
        width: 100%;
    }
    
    /* Image Ad */
    .ad-image-container {
        position: relative;
        width: 100%;
        overflow: hidden;
        border-radius: 16px;
    }
    
    .ad-image {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.5s ease;
    }
    
    .ad-wrapper:hover .ad-image {
        transform: scale(1.05);
    }
    
    .ad-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.4) 50%, transparent 100%);
        padding: 30px 25px 25px;
        color: white;
    }
    
    .ad-overlay .ad-title {
        margin: 0 0 10px 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: #ffffff;
        text-shadow: 0 2px 8px rgba(0,0,0,0.5);
    }
    
    .ad-overlay .ad-description {
        margin: 0;
        font-size: 1rem;
        color: rgba(255,255,255,0.95);
        line-height: 1.6;
    }
    
    /* HTML Ad */
    .ad-html-wrapper {
        width: 100%;
        padding: 20px;
        background: #ffffff;
        border-radius: 16px;
    }
    
    /* Text Ad */
    .ad-text-container {
        padding: 40px 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        text-align: center;
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }
    
    .ad-title-text {
        margin: 0 0 15px 0;
        font-size: 1.8rem;
        font-weight: 700;
        color: #ffffff;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .ad-description-text {
        margin: 0 0 25px 0;
        font-size: 1.1rem;
        color: rgba(255,255,255,0.95);
        line-height: 1.7;
    }
    
    .ad-cta {
        display: inline-block;
        padding: 12px 30px;
        background: rgba(255,255,255,0.25);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255,255,255,0.5);
        border-radius: 50px;
        color: #ffffff;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .ad-cta:hover {
        background: rgba(255,255,255,0.35);
        border-color: rgba(255,255,255,0.8);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .ad-cta i {
        margin-left: 8px;
        transition: transform 0.3s ease;
    }
    
    .ad-cta:hover i {
        transform: translateX(5px);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .ad-text-container {
            padding: 30px 20px;
        }
        
        .ad-title-text {
            font-size: 1.4rem;
        }
        
        .ad-description-text {
            font-size: 1rem;
        }
        
        .ad-overlay {
            padding: 20px 15px 15px;
        }
        
        .ad-overlay .ad-title {
            font-size: 1.2rem;
        }
    }
</style>
