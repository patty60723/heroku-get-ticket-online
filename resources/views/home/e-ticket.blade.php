<div class="tab-pane fade" role="tabpanel" id="e-ticket"> 
    <div class="card w500p">
        <div class="card-body">
            <h5 class="card-title">線上索票取票</h5>
            <h6 class="sub-title"></h6>
            <div class="eTicketBody"> 
                <div class="card w500p">
                    <div class="card-body">
                        <div id="eTicketQueryForm">
                            @csrf
                            <div class="form-group">
                                <label>QRcode
                                  <input type="text" name='eticket_qrcode' id="eticket-qrcode"/>
                                </label>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="button" id="eTicketCodeQuery">搜尋</button>
                            </div>
                        </div>
                        <div id="eTicketCodeQueryResult">
                            <table class="table e-ticket table-dark">
                                <thead>
                                    <tr>
                                        <th>姓名/暱稱</th>
                                        <th>Email</th>
                                        <th>電話</th>
                                        <th>張數</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card w500p">
                    <div class="card-body">
                        <form id="eTicketAdvancedQueryForm">
                            @csrf
                            <h3>進階搜尋</h3>
                            <div class="form-group">
                                <label>搜尋mail</label>
                                <input name="advancedTicketEmail"/>
                            </div>
                            <div class="form-group">
                                <label>搜尋電話</label>
                                <input name="advancedTicketPhone"/>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-info" type="button" id="eTicketAdvancedQueryBtn">搜尋</button>
                            </div>
                        </form>
                        <div id="eTicketAdvancedQueryResult">
                            <table class="table e-ticket table-dark">
                                <thead>
                                    <tr>
                                        <th>姓名/暱稱</th>
                                        <th>Email</th>
                                        <th>電話</th>
                                        <th>張數</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
#eTicketAdvancedQueryResult, #eTicketCodeQueryResult {
    display: none;
}
</style>

<script type="text/javascript">
const eTicketTRTemplate = "<tr><td>{name}</td><td>{email}</td><td>{phone}</td><td>{amount}</td><td>{btn}</td></tr>";

$("#e-ticket").on("click", ".btn-success.btn-get-ticket", function() {
    if ($(this).data('code')) {
        $.ajax({
            type: 'GET',
            url: '/home/checkoutTicket',
            data: {
                'code': $(this).data('code')
            },
            success: function(response) {
                if (response.status) {
                    alert('已取票完成');
                } else {
                    alert(response.error);
                }
            }
        })
    }
});

$("#eTicketAdvancedQueryBtn").on("click", function() {
    $("#eTicketAdvancedQueryResult table tbody").html("");
    $.ajax({
        type: 'POST',
        url: '/home/geteTicket',
        data: $("#eTicketAdvancedQueryForm").serialize(),
        success: function(response) {
            $("#eTicketAdvancedQueryResult").show();

            var ticketData = response.data,
                rowResult = '';

            ticketData.forEach(function(item, index, array) {
                var btn = '';
                if (item.status == 0) {
                    btn = "<button class='btn btn-success btn-get-ticket' data-code='" + item.code + "'>取票</button>";
                } else if (item.status == 1) {
                    btn = "<button class='btn btn-danger'>已於" + (item.get_time ? item.get_time : ' 未知時候 ') + '完成取票</button>';
                }
                rowResult = eTicketTRTemplate.replace(/\{name\}/g, item.name)
                                             .replace(/\{email\}/g, item.email)
                                             .replace(/\{phone\}/g, item.phone)
                                             .replace(/\{amount\}/g, item.amount)
                                             .replace(/\{btn\}/g, btn);

                $("#eTicketAdvancedQueryResult table tbody").append(rowResult);
            });
            
        }
    });
});

$("#eTicketCodeQuery").on("click", function() {
    var sendData = {};

    sendData[$("#eTicketQueryForm").find("input[type='hidden']").attr("name")] = $("#eTicketQueryForm").find("input[type='hidden']").val();
    sendData[$("#eTicketQueryForm").find("input[type='text']").attr("name")] = $("#eTicketQueryForm").find("input[type='text']").val();

    $.ajax({
        type: 'POST',
        url: '/home/geteTicketByCode',
        data: sendData,
        success: function(response) {
            if (response.data) {
                $("#eTicketCodeQueryResult table tbody").html("");
                $("#eTicketCodeQueryResult").show();
                var result = '',
                    item = response.data;

                if (item.status == 0) {
                    btn = "<button class='btn btn-success btn-get-ticket' data-code='" + item.code + "'>取票</button>";
                } else if (item.status == 1) {
                    btn = "<button class='btn btn-danger'>已於" + (item.get_time ? item.get_time : ' 未知時候 ') + '完成取票</button>';
                }

                result = eTicketTRTemplate.replace(/\{name\}/g, item.name)
                                          .replace(/\{email\}/g, item.email)
                                          .replace(/\{phone\}/g, item.phone)
                                          .replace(/\{amount\}/g, item.amount)
                                          .replace(/\{btn\}/g, btn);

                $("#eTicketCodeQueryResult table tbody").append(result);
            } else {
                $("#eTicketCodeQueryResult table tbody").html('<td colspan="5"><div class="alert alert-danger">找不到此序號，請重新確認</div></td>');
            }
        }
    });
});
</script>