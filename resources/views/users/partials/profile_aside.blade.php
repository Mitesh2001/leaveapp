<!--begin::Aside-->
<div class="flex-row-auto offcanvas-mobile w-250px w-xxl-350px" id="kt_profile_aside">
    <!--begin::Profile Card-->
    <div class="card card-custom card-stretch">
        <!--begin::Body-->
        <div class="card-body pt-4"> 
            <!--begin::User-->
            <div class="align-items-center">
                <div class="mb-4">
                    @if ($user->profile_pic)
                    <img src="{{ asset($user->profile_pic) }}" class="rounded shadow profile_pic" alt="Profile Pic" height="80">
                    @else 
                    <img src="https://cricdaddy.com/wp-content/uploads/2020/08/blank-profile-picture-png.png" class="rounded shadow profile_pic" alt="Profile Pic" height="80">
                    @endif 
                </div>
                <div>
                    <a href="#" class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary">{{ $user->first_name ." ". $user->last_name }}</a>
                    <div class="text-muted">{{ $user->roles->first()->name }}</div> 
                </div>
            </div>
            <!--end::User-->
            <!--begin::Contact-->
            <div class="py-9">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="font-weight-bold mr-2">Primary Number:</span>
                    <a href="#" class="text-muted text-hover-primary">{{ $user->primary_number }}</a>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="font-weight-bold mr-2">Email :</span>
                    <span class="text-muted">{{ $user->email }}</span>
                </div>
            </div>
        </div>
        <!--end::Body-->
    </div>
    <!--end::Profile Card-->
</div>
<!--end::Aside-->