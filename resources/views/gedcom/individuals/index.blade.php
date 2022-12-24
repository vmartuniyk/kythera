        
        <!-- jQuery/jQueryUI -->
        {!! HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') !!}
        {!! HTML::script('//code.jquery.com/ui/1.11.1/jquery-ui.js') !!}
        
        <!-- DataTables -->
        {!! HTML::style('//cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.css') !!}
        {!! HTML::script('//cdn.datatables.net/1.10.2/js/jquery.dataTables.js') !!}
        {!! HTML::script('//cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js') !!}
        
        


<div class="page-header">
    <h3>
        {!! $title !!}
    </h3>
    <h4>
        {!! $count !!} {!! $subtitle !!}
    </h4>
</div>

<table id="individuals" class="table table-striped table-hover">
    <thead>
        <tr>
            @if($source == 'individuals/data')
                <th>@lang('gedcom/gedcoms/table.file_name')</th>
            @endif
            <th>@lang('gedcom/gedcoms/table.key')</th>
            <th>@lang('common/common.first_name')</th>
            <th>@lang('common/common.last_name')</th>
            <th>@lang('common/common.sex')</th>
        </tr>
    </thead>
</table>

@include('layouts.table', array('ajax_source' => $source, 'id' => 'individuals'))

