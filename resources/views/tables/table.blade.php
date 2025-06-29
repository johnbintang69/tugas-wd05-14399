<div class="card">
    @isset($title)
    <div class="card-header">
        <h3 class="card-title">{{ $title }}</h3>
    </div>
    @endisset
    <div class="card-body table-responsive">
        <table class="table table-hover text-nowrap {{ $tableClass ?? '' }}" id="{{ $tableId ?? '' }}">
            <thead>
                {!! $thead ?? '' !!}
            </thead>
            <tbody>
                {!! $tbody ?? '' !!}
            </tbody>
        </table>
    </div>
</div>
