@extends('layouts.admin')
@section('content')
<div class="card ml-3 mr-3">
    <div class="card-header">
        Buyer & Supplier Profile :
    </div>
    <form method="post" action="{{route('admin.report.buyer-supplier-filter')}}" name="filter-buyer-supplier" id="filter-buyer-supplier" class="filter-buyer-supplier">
        @csrf
        <div class="row  p-3  bg-light">
            <div class="col-md-3">
                <div class="form-group">
                    <select name="user" class="form-control">
                        <option value="supplier">Supplier</option>
                        <option value="buyer">Buyer</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <input type="text" class="form-control" name="veepee" id="veepee" placeholder="Veepee ID" >
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <input type="text" class="form-control" name="firm" id="firm" placeholder="Firm Name" >
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <input type="submit" class="btn btn-success" value="Search" />
                </div>
            </div>
        </div>
    </form>
    <div class="row  pr-3 pl-3 pb-5">
        <div class="col-md-12 form-data"></div>
    </div>
</div>
@endsection