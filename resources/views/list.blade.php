<div class="editor-js-block" style="{{ $styles }}">
    {{ ($style == 'unordered') ? '<ul>' : '<ol>' }}
    @foreach ($items as $item)
        <li>
            {{ $item }}
        </li>
    @endforeach
    {{ ($style == 'unordered') ? '</ul>' : '</ol>' }}
</div>
