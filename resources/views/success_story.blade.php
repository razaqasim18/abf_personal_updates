@extends('layouts.eshop')
@section('style')
@endsection

@section('content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs SSImage d-flex justify-content-center align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-center align-items-center">
                    <a href="#">Leaders Stories</a>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->

    <!-- Start Success Stories -->
    <section id="success-stories" class="sectionOne">
        <div class="container">
            @foreach ($story as $row)
                <div class="row rshadow">
                    <div class="col-md-4 employes">
                        <img style="height: 260px;padding: 20px 0px; width: 200px;"
                            src="{{ $row->user->image ? asset('uploads/user_profile') . '/' . $row->user->image : asset('eshop/images/emp1.jpg ') }}"
                            alt="" />
                    </div>
                    <div class="col-md-8 empDetails">
                        <h4>{{ $row->user->name }}</h4>
                        {{-- <h5>Ceo</h5> --}}
                        <p>
                            {!! $row->description !!}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    <!--/ End Sucess Stories -->
@endsection

@section('script')
@endsection
