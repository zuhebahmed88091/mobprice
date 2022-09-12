{{-- @if ($paginator->lastPage() > 1)
    <ul class="pagination">
        <li class="page-item{{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
            @if ($paginator->currentPage() == 1)
                <span aria-hidden="true" class="page-link">Previous</span>
            @else
                <a href="{{ $paginator->url($paginator->currentPage()-1) }}">Previous</a>
            @endif
        </li>
        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            <li class="page-item{{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
                @if ($paginator->currentPage() == $i)
                    <span class="page-link">{{ $i }}</span>
                @else
                    <a href="{{ $paginator->url($i) }}">{{ $i }}</a>
                @endif
            </li>
        @endfor
        <li class="page-item{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
            @if ($paginator->currentPage() == $paginator->lastPage())
                <span class="page-link" aria-hidden="true">Next</span>
            @else
                <a href="{{ $paginator->url($paginator->currentPage()+1) }}">Next</a>
            @endif
        </li>
    </ul>
@endif --}}

@if ($paginator->lastPage() > 1)
    <ul class="pagination">
        <li class="page-item{{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
            @if ($paginator->currentPage() == 1)
                <span aria-hidden="true" class="page-link">Previous</span>
            @else
                <a href="{{ $paginator->url($paginator->currentPage()-1) }}">Previous</a>
            @endif
        </li>
        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            <?php
            $link_limit = 7;
            $half_total_links = floor($link_limit / 2);
            $from = $paginator->currentPage() - $half_total_links;
            $to = $paginator->currentPage() + $half_total_links;
            if ($paginator->currentPage() < $half_total_links) {
                $to += $half_total_links - $paginator->currentPage();
            }
            if ($paginator->lastPage() - $paginator->currentPage() < $half_total_links) {
                $from -= $half_total_links - ($paginator->lastPage() - $paginator->currentPage()) - 1;
            }
            ?>
            @if ($from < $i && $i < $to)
                <li class="page-item{{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
                    @if ($paginator->currentPage() == $i)
                        <span class="page-link">{{ $i }}</span>
                    @else
                        <a href="{{ $paginator->url($i) }}">{{ $i }}</a>
                    @endif
                </li>
            @endif

        @endfor
        <li class="page-item{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
            @if ($paginator->currentPage() == $paginator->lastPage())
                <span class="page-link" aria-hidden="true">Next</span>
            @else
                <a href="{{ $paginator->url($paginator->currentPage()+1) }}">Next</a>
            @endif
        </li>
    </ul>
@endif