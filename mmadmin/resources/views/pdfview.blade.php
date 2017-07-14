<style type="text/css">
	table td, table th{
		border:1px solid black;
	}
</style>
<div class="container">

	<br/>

	
        <div style="margin: auto;width: 50%;text-align: center;">
            {{ $tq_message }}
            <br />
            <br />
            <img src="https://api.qrserver.com/v1/create-qr-code/?data=http://192.168.1.117:8008/mmadmin/user/{{ $id_user }}&amp;size=150x150&amp;color=F26B3C&amp;qzone=1&amp;margin=0" alt="" />
            <br />
            <br />
        </div>
</div>