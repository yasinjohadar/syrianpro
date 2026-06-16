<div class="page-header-premium mb-4">
    @if(!empty($breadcrumbs))
        <nav class="page-breadcrumb mb-2" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                @foreach($breadcrumbs as $crumb)
                    @if($loop->last)
                        <li class="breadcrumb-item active" aria-current="page">{{ $crumb['label'] }}</li>
                    @else
                        <li class="breadcrumb-item">
                            @if(!empty($crumb['url']))
                                <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                            @else
                                {{ $crumb['label'] }}
                            @endif
                        </li>
                    @endif
                @endforeach
            </ol>
        </nav>
    @endif
    <div class="d-md-flex d-block align-items-start justify-content-between gap-3">
        <div>
            <h4 class="page-title fw-bold fs-22 mb-1">{{ $title }}</h4>
            @if(!empty($subtitle))
                <p class="text-muted fs-13 mb-0">{!! $subtitle !!}</p>
            @endif
        </div>
        @if(!empty($actions))
            <div class="action-card flex-shrink-0">{!! $actions !!}</div>
        @endif
    </div>
</div>
