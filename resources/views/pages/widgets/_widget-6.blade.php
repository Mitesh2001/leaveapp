{{-- Advance Table Widget 2 --}}

<div class="card card-custom {{ @$class }}">
    {{-- Header --}}
    <div class="card-header border-0 pt-5">
        <h3 class="card-title remove-flex align-items-start flex-column">
            <span class="card-label font-weight-bolder text-dark">Products expiry (reminder)</span> 
        </h3>
        <div class="card-toolbar"> 
        </div>
    </div>

    {{-- Body --}}
    <div class="card-body pt-3 pb-0">
        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-vertical-center">
                <thead>
                    <tr>
                        <td>Product Name</td>
                        <td>Remider Qty</td>
                        <td>Current Stock (Qty)</td>
                        <td>Product Detail</td>
                    </tr>
                </thead>
                <tbody>

                    @if(count($stockremiders) > 0)

                    @foreach($stockremiders as $product)
                        <tr class="bg-danger text-light">
                            <td> {{ $product->name }}  </td>  
                            <td> {{ $product->expiry_reminder }}  </td>  
                            <td> {{ $product->qty }}  </td>   
                            <td> 
                                <a href="{{ url('admin/product/view/'.$product->external_id) }}">
                                    <i class="flaticon-eye text-white" data-toggle="tooltip" title="View Product"></i>
                                </a>  
                            </td>   
                        </tr> 
                        @endforeach

                    @else  
                    <tr>
                        <td colspan="9" class="text-center bg-light"> No product expiries</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
