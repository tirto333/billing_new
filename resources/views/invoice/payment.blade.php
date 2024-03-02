{{-- <div class="card bg-none card-box">
{{ Form::open(array('route' => array('invoice.payment', $invoice->id),'method'=>'post','enctype' => 'multipart/form-data')) }}
    <div class="row">
        <div class="form-group  col-md-6">
            {{ Form::label('date', __('Date')) }}
            {{ Form::text('date', '', array('class' => 'form-control pc-datepicker-1','required'=>'required')) }}
        </div>

        <div class="form-group  col-md-6">
            {{ Form::label('amount', __('Amount')) }}
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <i class="far fa-money-bill-alt"></i>
                    </div>
                </div>
                {{ Form::text('amount',$invoice->getDue(), array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        
        <div class="form-group  col-md-6">
            <div class="input-group">
                {{ Form::label('account_id', __('Account')) }}
                {{ Form::select('account_id',$accounts,null, array('class' => 'form-control select2','required'=>'required')) }}
            </div>
        </div>

        <div class="form-group  col-md-6">
            {{ Form::label('reference', __('Reference')) }}
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <i class="far fa-sticky-note"></i>
                    </div>
                </div>
                {{ Form::text('reference', '', array('class' => 'form-control')) }}
            </div>
        </div>
        <div class="form-group  col-md-12">
            {{ Form::label('description', __('Description')) }}
            {{ Form::textarea('description', '', array('class' => 'form-control','rows'=>3)) }}
        </div>
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                {{ Form::label('add_receipt', __('Payment Receipt'), ['class' => 'form-label']) }}
                <input type="file" name="add_receipt" id="image" class="custom-input-file" accept="image/*, .txt, .rar, .zip" >
                <label for="image">
                    <i class="fa fa-upload"></i>
                    <span>Choose a file…</span>
                </label>
            </div>
        </div>
        <div class="col-md-12 px-0">
            <input type="submit" value="{{__('Add')}}" class="btn-create badge-blue">
            <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
{{ Form::close() }}
</div> --}}






{{ Form::open(array('route' => array('invoice.payment', $invoice->id),'method'=>'post','enctype' => 'multipart/form-data')) }}
<div class="modal-body">

<div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('date', __('Date'),['class'=>'form-label']) }}
            <div class="form-icon-user">
                {{Form::date('date',null,array('class'=>'form-control','required'=>'required'))}}
                <!-- {{ Form::text('date', null, array('class' => 'form-control pc-datepicker-1','required'=>'required')) }} -->
            </div>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}
            <div class="form-icon-user">
                {{ Form::text('amount',$invoice->getDue(), array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="form-group col-md-6">

                {{ Form::label('account_id', __('Account'),['class'=>'form-label']) }}
                {{ Form::select('account_id',$accounts,null, array('class' => 'form-control select2', 'required'=>'required')) }}

        </div>

        <div class="form-group col-md-6">
            {{ Form::label('reference', __('Reference'),['class'=>'form-label']) }}
            <div class="form-icon-user">
                {{ Form::text('reference', '', array('class' => 'form-control')) }}
            </div>
        </div>
        <div class="form-group  col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-label']) }}
            {{ Form::textarea('description', '', array('class' => 'form-control','rows'=>3)) }}
        </div>

        <div class="form-group col-md-12">
            {{ Form::label('add_receipt', __('Payment Receipt'), ['class' => 'form-label']) }}
            <div class="choose-file form-group">
                <label for="image" class="form-label">
                    <input type="file" name="add_receipt" id="files" class="form-control" accept="image/*, .txt, .rar, .zip" >
                </label>
                <p class="upload_file"></p>
                <img id="image" class="mt-2" style="width:25%;"/>
            </div>
        </div>
        
    </div>

</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Add')}}" class="btn btn-primary">
</div>
{{ Form::close() }}

<script>
       document.querySelector(".pc-datepicker-1").flatpickr({
            mode: "range"
        });
</script>
<script>
    document.getElementById('files').onchange = function () {
    var src = URL.createObjectURL(this.files[0])
    document.getElementById('image').src = src
    }
</script>