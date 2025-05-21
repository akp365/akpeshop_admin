@extends('layout')
@prepend('styles')
<style>
    .ck-editor__editable_inline {
        min-height: 700px;
    }
</style>
@endprepend
@section('content')

<h4 class="page-section-heading">Ediing Page</h4>
<div class="panel panel-default">
    <div class="panel-body">
        <form action="{{ route('edit-page', ['pageId' => $itemDetails['page_id'] ]) }}" method="POST">
            @csrf
            <div class="form-group form-control-default required">
                <label for="exampleInputEmail1">Page Name</label>
                <input name="pageTitle" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Name..." value="{{ $itemDetails['title'] }}">
            </div>

            <div class="form-group required">
                <label for="exampleInputEmail1">Page Content</label>
                <textarea name="pageContent" class="form-control required" id="pageContent">{{ $itemDetails['description'] }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Update</button>
            <a style="margin-top: 20px;" class="btn btn-default" href="{{ route( 'pages' ) }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#pageContent').summernote({ height: 300  });
</script>
@stop