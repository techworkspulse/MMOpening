@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Search Users</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        (By IC/Passport, name, phone number or email)
                    </div>
                    <div class="form-group input-group">
                        <input class="form-control" id="inpSearch" name="inpSearch" type="text" value="{{ app('request')->input('src') }}" />
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" onclick="document.location.href = '/mmadmin/search?src=' + $('#inpSearch').val() + '&rs=' + $('#selRStatus').val();">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                   <div class="form-group">
                        (By redeem status)
                    </div>
                    <div class="form-group">
                        <select id="selRStatus" class="form-control">
                            <option value="">Please select</option>
                            <option value="1" {{ (app('request')->input('rs') == "1") ? "selected='selected'" : "" }}>Redeemed</option>
                            <option value="2" {{ (app('request')->input('rs') == "2") ? "selected='selected'" : "" }}>Unredeemed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-success" onclick="document.location.href = '/mmadmin/search?src=' + $('#inpSearch').val() + '&rs=' + $('#selRStatus').val();">Search</button>
                        <button type="button" class="btn btn-danger" onclick="document.location.href = '/mmadmin/';">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>        

    <div class="row">
        <div class="col-sm-12">
            @section ('cotable_panel_title','Registrants')
            @section ('cotable_panel_body')
            <div class="form-group input-group">
                <button type="button" class="btn btn-warning" onclick="window.open('/mmadmin/searchexport?src={{ $_GET['src'] }}&rs={{ $_GET['rs'] }}');">Export to Excel</button>
            </div>
            <table class="table table-bordered alternate">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>IC/Passport</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr class="info">
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->PhoneNumber }}</td>
                        <td>{{ ($user->isRedeemed == 1) ? "Redeemed at " . date('d F Y H:i:s', strtotime($user->RedeemedDate)) : "N/A" }}</td>
                        <td><button type="button" class="btn btn-warning btn-circle" onclick="view({{ $user->id }}, '/mmadmin')"><i class="fa fa-eye"></i></button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endsection
            @include('widgets.panel', array('header'=>true, 'as'=>'cotable'))
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
