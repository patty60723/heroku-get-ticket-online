@if ($action == 'get' && empty($message))
<form id="mTicketCreateForm" class="mTicket-create-form" method="post">
	@csrf
	<div class="form-group">
		<input type="hidden" name="mTicket[instrument]" value="{{$instrument}}">
		<input type="hidden" name="mTicket[memberName]" value="{{$memberName}}">
		<label>寄票人<input type="text" name="mTicket[ticketsName]"></label>
		<label>連絡電話<input type="text" name="mTicket[contact]"></label>
	</div>
	<div class="form-group">
		<label>張數<input type="text" name="mTicket[amount]"></label>
	</div>
	<div class="form-group">
		<button id="mTicketCreateFormBtn" class="btn btn-primary" type="button">送出</button>
	</div>
</form>
@elseif ($action == 'post' || isset($message))
<div class="alert alert-info">{{$message}}</div>
@endif