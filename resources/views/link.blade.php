@php($imageUrl = $meta['imageUrl'] ?? Arr::get($meta, 'image.url'))
<div class="editor-js-block">
    <div class="editor-js-link">
        <a href="{{ $link }}" class="editor-js-link-content">
            @if (!empty($imageUrl))
                <div class="editor-js-link-image" style="background-image: url('{{ $imageUrl }}');"></div>
            @endif

            <h4>{{ $meta['title'] }}</h4>
            <p>
                {{ $meta['description'] }}
            </p>
            <p>
                <small>{{ $link }}</small>
            </p>
        </a>
    </div>
</div>
