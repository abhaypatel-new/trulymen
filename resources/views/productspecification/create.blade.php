{{ Form::open(array('url' => 'productspecification','enctype' => "multipart/form-data")) }}
<div class="modal-body">
    {{-- start for ai module--}}
    @php
        $plan= \App\Models\Utility::getChatGPTSettings();
    @endphp
   {{-- @if($plan->chatgpt == 1)
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="{{ route('generate',['productservice']) }}"
           data-bs-placement="top" data-title="{{ __('Generate content with AI') }}">
            <i class="fas fa-robot"></i> <span>{{__('Generate with AI')}}</span>
        </a>
    </div>
    @endif --}}
    {{-- end for ai module--}}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('name', __('Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                {{ Form::text('name', '', array('class' => 'form-control','required'=>'required', 'placeholder'=>__('Enter Specification Name'))) }}
            </div>
        </div>
        <div class="col-md-6 form-group">
            {{Form::label('pro_image',__('Product Image'),['class'=>'form-label'])}}
            <div class="choose-file ">
                <label for="pro_image" class="form-label">
                    <input type="file" class="form-control" name="pro_image" id="pro_image" data-filename="pro_image_create">
                    <img id="image" class="mt-3" style="width:25%;"/>

                </label>
            </div>
        </div>
         <div class="form-group col-md-6">
            {{ Form::label('group_id', __('Group'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::select('group_id', $group,null, array('class' => 'form-control select','required'=>'required')) }}

            <div class=" text-xs">
                {{__('Please add constant group. ')}}<a data-size="md" data-url="{{ route('groups.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" href="#"><b>{{__('Add Group')}}</b></a>
            </div>
        </div>
        <div id="divContainer"></div>
        <div class="form-group">
            <a class="btn btn-primary sm text-light" onclick="appendDiv()"><i class="ti ti-plus"></i> Add Sub-Specification</a>
         </div>
      
       
      
    </div>
     
     
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{Form::close()}}


<script>
    document.getElementById('pro_image').onchange = function () {
        var src = URL.createObjectURL(this.files[0])
        document.getElementById('image').src = src
    }

    //hide & show quantity
  function appendDiv() {
        $('#divContainer').append('<div class="add_contact"><div class="row"><div class="col-md-4"><div class="form-group"><label for="prefix" class="form-label">Prefix</label><input class="form-control" placeholder="Enter Prefix" name="prefix[]" type="text" id="prefix_name"></div></div><div class="col-md-4"><div class="form-group"><label for="sub_specification" class="form-label">Details</label><input class="form-control" placeholder="Enter Sub Specification" name="sub_specification[]" type="text" id="sub_specification"></div></div><div class="col-md-2"><div class="form-group"><label for="price" class="form-label">Price</label><input class="form-control" placeholder="Enter Price" name="price[]" type="text" id="price"></div></div><div class="col-md-2" style="padding-top: 25px;"><div class="form-group"><a class="mx-3 btn btn-primary sm  align-items-cente removeButton" title="remove"><label class="form-check-label " for="g_male"><i class="ti ti-trash text-white"></i></label></a></div></div></div></div>');
      
  }
  
   $(document).on('click', '.removeButton', function(e) {
   e.preventDefault();
   $(this).closest('.add_contact').remove();
   return true;
    });

    $(document).on('click', '.type', function ()
    {
        var type = $(this).val();
        if (type == 'product') {
            $('.quantity').removeClass('d-none')
            $('.quantity').addClass('d-block');
        } else {
            $('.quantity').addClass('d-none')
            $('.quantity').removeClass('d-block');
        }
    });
</script>
