<div class="col-12 mt-4">
  <p class="text-muted fs-5">Tags</p>
  @foreach ($tags as $tag)
    <a href="{{ $tag['url'] }}">
      <span class="badge bg-secondary">{{ $tag['name'] }}</span>
    </a>
  @endforeach
</div>
