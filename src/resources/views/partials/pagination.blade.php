@if($paginator->hasPages())
<nav class="pagination">
    @if($paginator->onFirstPage())<span class="disabled">‹ Précédent</span>@else<a href="{{ $paginator->previousPageUrl() }}">‹ Précédent</a>@endif
    <span class="muted">Page {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>
    @if($paginator->hasMorePages())<a href="{{ $paginator->nextPageUrl() }}">Suivant ›</a>@else<span class="disabled">Suivant ›</span>@endif
</nav>
@endif
