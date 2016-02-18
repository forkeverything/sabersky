<div class="photo-gallery">
    @foreach($photos as $photo)
        <a class="fancybox" rel="group" href="{{ $photo->path }}"><img src="{{ $photo->thumbnail_path }}" alt="" /></a>
    @endforeach
</div>