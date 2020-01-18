@if ($action == 'get' && empty($message))
<form id="fTicketCreateForm" class="fTicket-create-form" method="post">
	@csrf
	<div class="form-group">
		<input type="hidden" name="fTicket[instrument]" value="{{$instrument}}">
		<input type="hidden" name="fTicket[memberName]" value="{{$memberName}}">
		<label>寄票人<input type="text" name="fTicket[ticketsName]"></label>
		<label>連絡電話<input type="text" name="fTicket[contact]"></label>
	</div>
	<div class="form-group">
		<label>張數<input type="text" name="fTicket[amount]"></label>
	</div>
	<div class="form-group">
		<button id="fTicketCreateFormBtn" class="btn btn-primary" type="button">送出</button>
	</div>
</form>
@elseif ($action == 'post' || isset($message))
<div class="alert alert-info">{{$message}}</div>
@endif