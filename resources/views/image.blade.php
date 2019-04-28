<div class="editor-js-block">
    <div class="editor-js-image {{ $classes }}">
        <img src="{{ $file['url'] }}" alt="{{ $caption }}">
        @if (!empty($caption))
            <caption>{{ $caption }}</caption>
        @endif
    </div>
</div>
