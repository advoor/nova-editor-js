<div class="editor-js-block" style="{{ $styles }}">
    <div class="editor-js-image {{ $classes }}">
        @if (!empty($caption))
            <a href="{{ $caption }}">
        @endif
        <img src="{{ $file['url'] }}" alt="{{ $caption }}">
        @if (!empty($caption))
            </a>
        @endif
    </div>
</div>
