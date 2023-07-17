@extends('layouts.app')

@section('content')

<!--<写真の位置情報をJavaScriptオブジェクトとして作成 -->
<script>
let locations = [
    @foreach($photos as $photo)

    { "id": "{{ $photo->id }}", "lat": {{ $photo->latitude }}, "lng": {{ $photo->longitude }} },
    @endforeach
];
</script>
 <!--<script src="{{ asset('/js/result.js') }}"></script>-->

<section class="text-gray-600 body-font">
  <div class="container px-5 py-24 mx-auto">
    <div class="flex flex-col text-center mb-20">
      <h1 class="text-2xl font-medium title-font mb-4 text-gray-900">{{ $user->name }}さんの投稿</h1>
      <!--<p class="lg:w-2/3 mx-auto leading-relaxed text-base">Whatever cardigan tote bag tumblr hexagon brooklyn asymmetrical gentrify, subway tile poke farm-to-table. Franzen you probably haven't heard of them.</p>-->
    </div>
    <div class="flex flex-wrap -m-4">
       @foreach($photos as $photo)
         <div class="p-4 lg:w-1/4 md:w-1/2">
           <div class="h-full flex flex-col items-center text-center">
             <img alt="team" class="flex-shrink-0 rounded-lg w-full h-56 object-cover object-center mb-4" src="{{ Storage::url($photo->filename )}}" style="object-fit: cover; width: 200px; height:200px;">

             <!-- Map link -->
             <a href="#" class="show-map" data-lat="{{ $photo->latitude }}" data-lng="{{ $photo->longitude }}" data-photo-id="{{ $photo->id }}">Mapを表示</a>

             <div class="w-full">
               <h2 class="title-font font-medium text-lg text-gray-900">タイトル: {{ $photo->title }}</h2>
               <h3 class="text-gray-500 mb-3">名前: {{ $user->name }}</h3>
               <p class="mb-4">{{ $photo->caption }}</p>

               <form method="POST" action="{{ route('photo.destroy', ['id' => $photo->id]) }}">
                   @csrf
                   @method('DELETE')

               @if(Auth::user()->id == $photo->user_id)
                   <button type="submit" class="btn btn-danger bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('本当に削除しますか')">
                       削除
                   </button>
               @endif
               </form>

               <form method="GET" action="{{ route('photo.edit', ['photo' => $photo->id]) }}">
                   @csrf
                   @method('GET')

               @if(Auth::user()->id == $photo->user_id)
                   <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded">
                       編集
                   </button>
               @endif
               </form>
             </div>
           </div>
         </div>
       @endforeach
    </div>
  </div>
</section>

<!-- モーダルの基本的なHTML -->
<div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mapModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  $('.show-map').on('click', function(e) {
    e.preventDefault();

    var lat = $(this).data('lat');
    var lng = $(this).data('lng');
    var photoId = $(this).data('photo-id');

    var iframe = `<iframe
      id="map-${photoId}"
      width="100%"
      height="450"
      frameborder="0"
      style="border:0"
      src="https://www.google.com/maps/embed/v1/view?zoom=13&center=${lat},${lng}&key={{ config('services.google-map.apikey') }}"
      allowfullscreen>
    </iframe>`;

    $('#mapModal .modal-body').html(iframe);

    $('#mapModal').modal('show');
  });
});
</script>

@endsection
