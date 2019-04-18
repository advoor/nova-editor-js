<div class="editor-js-link">
    <a href="{{ $link }}">
        <h4>{{ $meta['title'] }}</h4>
        <p>
            {{ $meta['description'] }}
        </p>
        <p>
            <small>{{ $link }}</small>
        </p>

        @if (!empty($meta['image']['url']))
            <img src="{{ $meta['image']['url'] }}"/>
        @endif
    </a>
</div>
