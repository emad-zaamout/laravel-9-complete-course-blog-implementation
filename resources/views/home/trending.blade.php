<div class="col-12 pb-4 g-0 border-bottom-black">
  <p class="text-muted fs-5">What's Trending</p>
  @foreach ($trending as $trend)
    <span onclick="redirectTo('{{ $trend['url'] }}')">
      <div class="card p-0 mt-4 small-card" style="min-height:140px;">
        <img src="{{ $trend['image_url_landscape'] }}" class="w-100 h-100 card-img" style="opacity: 0.5; min-height:140px;">
        <div class="card-img-overlay">
          <h5 class="card-title">{{ $trend['title'] }}</h5>
          <p class="card-text">{{ $trend['date'] }}</p>
        </div>
      </div>
    </span>
  @endforeach
</div>
