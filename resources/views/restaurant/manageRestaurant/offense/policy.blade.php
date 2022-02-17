@extends('restaurant.app2')

@section('content')
<div class="container mx-auto store-hours">
    @if (session()->has('added'))
        <script>
            Swal.fire(
                'Policy Added',
                '',
                'success'
            );
        </script>
    @endif
    @if (session()->has('deleted'))
        <script>
            Swal.fire(
                'Policy Deleted',
                '',
                'success'
            );
        </script>
    @endif
    @if (session()->has('edited'))
        <script>
            Swal.fire(
                'Policy edited',
                '',
                'success'
            );
        </script>
    @endif
    <div class="w-11/12 mx-auto mt-10 font-Montserrat">
        <div class="w-full mt-5 bg-adminViewAccountHeaderColor2 p-5 rounded-xl shadow-adminDownloadButton">
            <form action="/restaurant/manage-restaurant/offense/policy/add" method="POST" enctype="multipart/form-data" id="policy-add-form">
                @csrf
                <textarea type="text" name="policyDesc" placeholder="Write a policy..." class="border {{ $errors->has('policyDesc') ? 'border-red-600 focus:border-red-600' : 'border-gray-400 focus:border-black' }} rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none" rows="5"></textarea>
                <span class="mt-2 text-red-600 italic text-sm">@error('policyDesc'){{ "Policy is required" }}@enderror</span>
                <div class="text-right mt-2">
                    <button id="btn-policy-add" class="bg-submitButton text-white w-36 h-9 rounded-md font-Montserrat hover:bg-btnHoverColor transition duration-200 ease-in-out">Add</button>
                </div>
            </form>
        </div>
        
        <p class="text-4xl mt-10">Restaurant Policy</p>
        <div class="w-11/12 mx-auto mt-5">
            @if (!$policies->isEmpty())
                    @php
                        $count = 1
                    @endphp
                    @foreach ($policies as $policy)
                        <div class="bg-white grid grid-cols-policyGrid rounded-lg p-5 mb-5 tr">
                            <p class="td" hidden>{{ $policy->id }}</p>
                            <p class="td" hidden>{{ $policy->policyDesc }}</p>
                            <p>{{ $count }}.</p>
                            <p class="text-justify">{{ $policy->policyDesc }}</p>
                            <div class="text-center">
                                <button class="btn-edit-item text-white bg-manageRestaurantSidebarColorActive inline-block text-center leading-9 w-9 h-9 rounded-full  hover:text-gray-300 hover:bg-hoverManageRestaurantSidebarColorActive transition duration-200 ease-in-out"><i class="fas fa-edit"></i></button>
                                <a href="/restaurant/manage-restaurant/offense/policy/delete/{{ $policy->id }}"  class="btn-delete text-white bg-submitButton hover:text-gray-300 hover:bg-darkerSubmitButton transition duration-200 ease-in-out inline-block text-center leading-9 w-9 h-9 rounded-full"><i class="fas fa-trash-alt"></i></a>
                            </div>
                        </div>
                        @php
                            $count++
                        @endphp
                    @endforeach
            @endif
        </div>

        @if ($policies->isEmpty())
            <div class="mx-auto w-full mt-2 grid grid-cols-1 items-center bg-red-400 h-14 shadow-xl rounded-lg">
                <div class="text-center text-white">There are no Policies right now</div>
            </div>
        @endif

        <div class="edit-form" id="edit-form">
            <div class="modal-header flex justify-end px-4 py-2">
                <button id="btn-close" class="close-btn text-xl font-bold">&times;</button>
            </div>
            <h1 class="text-center text-submitButton font-bold text-2xl">Edit Policy</h1>
            <form action="/restaurant/manage-restaurant/offense/policy/edit" id="policy-edit-form" method="POST">
                @csrf
                <input type="text" name="updatePolicyId" id="updatePolicyId" hidden>
                <div class="w-1/2 mx-auto mt-5">
                    <textarea type="text" name="updatePolicyDesc" id="updatePolicyDesc" class="border border-gray-400 focus:border-black rounded-lg w-full py-1 px-2 text-sm text-gray-700 focus:outline-none" rows="5" required></textarea>
                </div>
                <div class="text-center mt-5">
                    <button id="btn-policy-edit" class="bg-submitButton text-white rounded-w-9/12 w-32 h-10 text-sm" type="submit">Update</button>
                </div>
            </form>
        </div> 
        <div class="overlay"></div>
    </div>
</div>
@endsection