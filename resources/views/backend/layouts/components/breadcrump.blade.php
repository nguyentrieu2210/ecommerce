<div class="row wrapper border-bottom white-bg page-heading">
    @php
        $title = 'breadcrump.' . $config['module'] . '.' . $config['method'] . '.title';
    @endphp
    <div class="col-lg-10">
        <h2>{{ __($title) }}</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ Route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="active">
                <strong>{{ __($title) }}</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">
        <div class="title-action">
            @if($config['method'] == 'index')
            <a href="invoice_print.html" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Xuất Excel
            </a>
            @endif
        </div>
    </div>
</div>