@extends('layouts.admin')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Buyer & Supplier Profile :
                </div>
                <div class="m-2">
                    <div class="row">                           
                        <div class="col-md-12">
                            <div class="card card-outline card-outline-tabs">
                                <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link text-dark active" id="basic-tab" data-toggle="pill" href="#basic" role="tab" aria-controls="basic" aria-selected="false">Basic Information</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-dark" id="live-tab" data-toggle="pill" href="#live" role="tab" aria-controls="live" aria-selected="true">Live Information</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" >
                                        <div class="tab-pane fade active show" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                                            <form method="post" action="{{route('admin.report.buyer-supplier-filter')}}" name="filter-buyer-supplier" id="filter-buyer-supplier" class="filter-buyer-supplier">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <select name="user" class="form-control">
                                                                <option value="supplier">Supplier</option>
                                                                <option value="buyer">Buyer</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" name="veepee" id="veepee" placeholder="Account ID" >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <input type="submit" class="btn btn-success" value="Search" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <div class="row  pr-2 pl-2 pb-2">
                                                <div class="col-md-12 form-data"></div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="live" role="tabpanel" aria-labelledby="live-tab">
                                            <div class="row">
                                                <div class="col-md-12 msg-container mb-3"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <select class="select2 select2-hidden-accessible live-info form-control " name="buyer_id" id="buyer_id"  style="width:100%">
                                                            <option value="">Select Buyer</option>
                                                            @foreach($buyers as $value)
                                                                <option value="{{$value->id}}">{{$value->name }} / {{$value->veepeeuser_id}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer"></div>
            </div>
        </div>
    </div>
@endsection