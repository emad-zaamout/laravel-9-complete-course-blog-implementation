<!DOCTYPE html>
<html>

<head>
  @include('layout.head')
</head>

<body class="bg-light">
  @include('layout.navbar')
  <div class="container ps-5 pe-5">
    <div class="row">
      <div class="col-12 p-2 text-center mt-4 mb-4 border-bottom-black">
            <h1 class="font-weight-bolder">{{ $blog["title"] }}</h1>
            <p>{{ $blog["description"] }}</p>
            <p>
                @if($blog["author_image_url"] !== null)
                    <img class="rounded-circle" height="35" width="35" src="{{ $blog['author_image_url'] }}">
                @endif
                <span class="ps-1">{{ $blog["author"] }}</span>
            </p>

      </div>
      <div class="col-12 p-2 mt-4 mb-4 border-bottom-black">
            <p>{!!  $blog["content"] !!}</p>
      </div>
    </div>
  </div>
  <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
