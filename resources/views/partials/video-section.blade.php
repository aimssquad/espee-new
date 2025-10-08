@if($videoSetting && $videoSetting->is_active)
<section class="video-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                @if($videoSetting->title)
                    <h2 class="display-5 fw-bold mb-4">{{ $videoSetting->title }}</h2>
                @endif

                @if($videoSetting->description)
                    <p class="lead mb-4">{{ $videoSetting->description }}</p>
                @endif

                @if($videoSetting->button_text && $videoSetting->button_url)
                    <a href="{{ $videoSetting->button_url }}" class="btn btn-primary btn-lg">
                        {{ $videoSetting->button_text }}
                    </a>
                @endif
            </div>

            <div class="col-lg-6">
                <div class="video-container position-relative">
                    @if($videoSetting->video_type === 'youtube' && $videoSetting->youtube_embed_url)
                        <div class="video-wrapper">
                            <iframe
                                src="{{ $videoSetting->youtube_embed_url }}"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                                class="w-100"
                                style="height: 400px; border-radius: 10px;">
                            </iframe>
                        </div>
                    @elseif($videoSetting->video_type === 'upload' && $videoSetting->video_url)
                        <video
                            class="w-100"
                            controls
                            style="height: 400px; border-radius: 10px;"
                            @if($videoSetting->autoplay) autoplay @endif
                            @if($videoSetting->muted) muted @endif
                            @if($videoSetting->loop) loop @endif>
                            <source src="{{ $videoSetting->video_url }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @else
                        <!-- Fallback content -->
                        <div class="video-placeholder bg-light d-flex align-items-center justify-content-center"
                             style="height: 400px; border-radius: 10px;">
                            <div class="text-center">
                                <i class="fas fa-video fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Video content will appear here</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@else
<!-- Debug: No video setting or not active -->
<section class="video-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="display-5 fw-bold mb-4">ELEVATE YOUR STYLE</h2>
                <p class="lead mb-4">Discover our premium collection of designer sunglasses and optical frames. Crafted with precision and style for the modern lifestyle.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                    EXPLORE COLLECTION
                </a>
            </div>
            <div class="col-lg-6">
                <div class="video-container position-relative">
                    <div class="video-placeholder bg-light d-flex align-items-center justify-content-center"
                         style="height: 400px; border-radius: 10px;">
                        <div class="text-center">
                            <i class="fas fa-video fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Video content will appear here</p>
                            <small class="text-muted">Configure video in admin panel</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
