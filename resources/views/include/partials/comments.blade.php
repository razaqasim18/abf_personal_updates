@foreach ($product->comments as $row)
    @if ($row->parent_id == null)
        @include('include.partials.comment', [
            'comment' => $row,
            'product' => $product,
        ])
    @endif
@endforeach
