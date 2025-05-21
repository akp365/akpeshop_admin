@extends('layout')
@prepend('styles')
<style>
    .ck-editor__editable_inline {
        min-height: 550px;
    }
</style>
@endprepend
@section('content')

<h4 class="page-section-heading">Add New Page To Website</h4>
<div class="panel panel-default">
    <div class="panel-body">
        <form action="{{ route('new-page') }}" method="POST">
            @csrf
            <div class="form-group form-control-default required">
                <label for="exampleInputEmail1">Page Name</label>
                <input name="pageTitle" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Name...">
            </div>

            <div class="form-group required">
                <label for="exampleInputEmail1">Page Content</label>
                <textarea name="pageContent" class="form-control required" id="pageContent"></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Submit</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#pageContent').summernote({ height: 300  });
</script>
@stop