{{-- List Widget 3 --}}

<div class="card card-custom {{ @$class }}">
    {{-- Header --}}
    <div class="card-header border-0"> 
        <h3 class="card-title remove-flex align-items-start flex-column">
            <span class="card-label font-weight-bolder text-dark">Clients Birthdays</span>
            <span class="text-muted mt-3 font-weight-bold font-size-sm">Clients Birthdays within 15 days.</span>
        </h3>
        <div class="card-toolbar"> 
        </div>
    </div>

    {{-- Body --}}
    <div class="card-body pt-2">

        @if(count($birthdays) > 0)

            @foreach($birthdays as $client)
                {{-- Item --}}
                <div class="d-flex align-items-center mb-10">
                    {{-- Symbol --}}
                    <div class="symbol symbol-40 symbol-light-success mr-5">
                        <span class="symbol-label">
                            <img src="{{ asset('media/svg/avatars/009-boy-4.svg') }}" class="h-75 align-self-end"/>
                        </span>
                    </div>

                    {{-- Text --}}
                    <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                        <a href="#" class="text-dark text-hover-primary mb-1 font-size-lg">{{ $client->name }}</a> 
                        <span class="text-muted">{{ date('d-m-Y', strtotime($client->date_of_birth)) }}</span>
                    </div>

                    {{-- Dropdown --}}
                    <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title="View client details">
                        <a href="{{ url('clients/' . $client->external_id ."?ref=dashboard") }}" class="btn btn-hover-light-primary btn-sm btn-icon">
                            <i class="fas fa-eye"></i>
                        </a> 
                    </div>
                </div>
            @endforeach

        @else  
        <div class="align-items-center bg-light py-3">  
            <p class="text-center">No Birthdays</p>
        </div>
        @endif
 
    </div>
</div>
