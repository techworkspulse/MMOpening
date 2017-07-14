@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            / <a href="/mmadmin">Registrants</a> / <a href="#">User</a>
            <br />
            <br />
        </div>
    </div>
    <div class="row">
            <div class="col-sm-12">
                    @section ('atable_panel_title','Registrant : '.$id)
                    @section ('atable_panel_body')
                    @foreach ($userDetails as $ud)
                    <table class="table table-condensed table-bordered table-striped">
                            <tbody>

                                    <tr>
                                            <td>Name</td>
                                            <td>{{ $ud->name }}</td>
                                    </tr>
                                    <tr>
                                            <td>IC/Passport</td>
                                            <td>{{ $ud->username }}</td>
                                    </tr>
                                    <tr>
                                            <td>Email</td>
                                            <td>{{ $ud->email }}</td>
                                    </tr>
                                    <tr>
                                            <td>Phone Number</td>
                                            <td>{{ $ud->PhoneNumber }}</td>
                                    </tr>
                                    <tr>
                                            <td>By CSA</td>
                                            <td>{{ ($ud->AdminName != null) ? $ud->AdminName : "N/A" }}</td>
                                    </tr>
                                    <tr>
                                            <td>Redeem Date</td>
                                            <td>{{ ($ud->RedeemDate != null) ? date('d F Y H:i:s', strtotime($ud->RedeemDate)) : "N/A" }}</td>
                                    </tr>
                                    <tr>
                                            <td>QR PDF Attachment</td>
                                            <td><a target="{{ ($ud->PDFFile != null) ? "_blank" : "_self" }}" href="{{ ($ud->PDFFile != null) ? '/tmp/' . $ud->PDFFile : '#' }}">{{ ($ud->PDFFile != null) ? "Download QR" : "N/A" }}</a></td>
                                    </tr>
                            </tbody>
                    </table>
                    <div class="div-center">
                            <button type="button" class="btn btn-success {{ ($ud->isRedeemed == 1) ? 'disabled' : 'btn-outline' }} btn-lg" {{ ($ud->isRedeemed != 1) ? 'onclick=claim(' . $ud->redeemID . ')' : '' }}>{{ ($ud->isRedeemed == 1) ? 'Claimed' : 'Claim' }}</button> 
                    </div>
                    @endforeach
                    @endsection
                    @include('widgets.panel', array('header'=>true, 'as'=>'atable'))
            </div>
    </div>
</div>
<script>
    function claim(uid) {
        document.location.href = '/mmadmin/claim/' + uid;
    }
</script>
@endsection
