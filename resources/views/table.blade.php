<div class="editor-js-block">
    <table class="editor-js-table">
        @foreach ($content as $row)
            <tr>
                @foreach ($row as $content)
                    <td>
                        {{ $content }}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </table>
</div>
