<div class="tab-pane fade" role="tabpanel" id="f-ticket"> 
    <div class="card w500p">
        <div class="card-body">
            <h5 class="card-title">親友寄票取票</h5>
            <h6 class="sub-title"></h6>
            <div class="fTicketBody"> 
                <div class="card w500p">
                    <div class="card-body">
                        <form id="fTicketQueryForm">
                            @csrf
                            <h3>序號搜尋</h3>
                            <div class="form-group">
                                <label>序號
                                  <input type="text" placeholder="(e.g. F010101) 共1+6碼" name='codeQuery'/>
                                </label>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="button" id="fTicketCodeQuery">搜尋</button>
                            </div>
                        </form>
                        <div id="fTicketCodeQueryResult">
                            <table class="table f-ticket table-dark">
                                <thead>
                                    <tr>
                                        <th>寄票人</th>
                                        <th>張數</th>
                                        <th>附註(聯絡資訊)</th>
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
                        <form id="fTicketAdvancedQueryForm">
                            @csrf
                            <h3>進階搜尋</h3>
                            <div class="form-group">
                              <label>樂器</label>
                              <select id="instrument" class="selectionChange" name="instrument">
                                    <option value="0">請選擇樂器</option>
                                    @foreach ($instru as $key => $name)
                                    <option value="{{ $key }}">{{ str_pad($key, 2, '0', STR_PAD_LEFT) }} {{ $name }}</option>

                                    @endforeach
                              </select>
                            </div>
                            <div class="form-group" style="display: none;">
                                <label>團員名 </label><span class="showSelection"></span>
                            </div>
                            <div class="form-group mx-auto">
                                <button class="btn btn-primary" type="button" id="fTicketAdvancedQuery">查詢</button>
                                <button class="btn btn-info" type="button" id="fTicketCreate">新增</button>
                            </div>
                        </form>
                        <div id="fTicketCreateResult"></div>
                        <div id="fTicketAdvancedQueryResult">
                            <hr/>
                            <ul>
                                <li>
                                    <label class="col-xs-4">親友寄票</label><span class="col-xs-8"> <span id="fTicketsNum"></span> 筆，共 <span id="fTicketsCount"></span> 張</span>
                                </li>
                            </ul>
                            <table class="table f-tickets table-dark">
                                <thead>
                                    <tr>
                                        <th>序號</th>
                                        <th>寄票團員</th>
                                        <th>寄票人</th>
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
#fTicketAdvancedQueryResult, #fTicketCodeQueryResult {
    display: none;
}

.fTicket-create-form {
    padding: 30px;
    border-radius: 10px;
    background-color: aliceblue;
}
</style>
<script type="text/javascript">
const member = {
  '1': ['江銨畯', '魯汝慧', '姚俐安', '柯穎穎'],
  '2': ['周芳如'],
  '3': ['連姵璇', '連姵瑜', '彭泰之', '彭霽維', '林家寅', '趙偉陵'],
  '4': ['塗永鈞'],
  '5': ['洪銘陽'],
  '6': ['許墨筑', '姚庭軒', '謝維漢', '林妤蒨'],
  '7': ['黃雅琪', '黃華譽', '羅大晏'],
  '8': ['黃思瑀', '陳宇聖', '王姵文', '邱詩容'],
  '9': ['謝維藩', '許瀞文', '羅雅馨'],
  '10': ['陳韋愷'],
  '11': ['黃騰寬', '林承儒'],
  '12': ['朱家恆', '李柏叡', '王敏安', '王郁文'],
  '13': ['許芳寧']
};

const fTicketTRTemplate = "<tr id='{code}-{index}'><td>{code}</td><td>{memberName}</td><td>{ticketsName}</td><td>{amount}</td><td><span style='margin-right: 10px; '{contact}</span>{btn}</td></tr>";

$(document).on("change", ".selectionChange", function(e) {
    var thisId = $(this).attr('id');
    var instrument = $(this).val();

    if (thisId == 'instrument') {
        if (instrument !== '0' && parseInt(instrument) <= 13) {
          var options = "<option value='0'>請選擇團員</option><option value='";
          member[instrument].forEach(function(value, index, arr) {
            options += index+1 + "'>" + value + "</option><option value='";
          });
          $(".showSelection").html("<select id='member-name' class='selectionChange' name='memberName'>" + options + "</select>");
          $(".showSelection").closest(".form-group").show();
          $("#fTicketCreateResult").html("");
        } else if (parseInt(instrument) == 14) {
          var input = "<input id='other-name' name='otherName' type='text' placeholder='請填入姓名'>";
          $(".showSelection").html(input);
          $(".showSelection").closest(".form-group").show();
          $("#fTicketCreateResult").html("");
        } else {
          $(".showSelection").html("");
          $(".showSelection").closest(".form-group").hide();
          $("#fTicketCreateResult").html("");
        }
    } else if (thisId == 'member-name') {
        $("#fTicketCreateResult").html("");
    }
});

$("#f-ticket").on("click", ".btn-success.btn-get-ticket", function() {
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

$("#fTicketAdvancedQueryForm").on("click", ".btn", function() {
    if ($(this).attr("id") == 'fTicketCreate') {
        var data = {},
            instrument = $("#instrument").val(),
            memberName = parseInt(instrument) > 13 ? $("#other-name").val() : $("#member-name").val();
        $.ajax({
            type: 'GET',
            url: '/home/createForm',
            data: {
                'instrument': instrument,
                'memberName': memberName
            },
            success: function(html) {
                $("#fTicketCreateResult").html("");
                $("#fTicketCreateResult").html(html);
            }
        });
    }
});

$("#fTicketCreateResult").on("click", ".btn", function() {
    if ($(this).attr("id") == 'fTicketCreateFormBtn') {
        $.ajax({
            type: 'POST',
            url: '/home/createForm',
            data: $("#fTicketCreateForm").serialize(),
            success: function(response) {
                $("#fTicketCreateResult").html("");
                $("#fTicketCreateResult").html(response);
            }
        });
    }
});

$("#fTicketAdvancedQuery").on("click", function() {
    $("#fTicketCreateResult").html("");
    $.ajax({
        type: 'POST',
        url: '/home/getfTicket',
        data: $("#fTicketAdvancedQueryForm").serialize(),
        success: function(response) {
            $("#fTicketAdvancedQueryResult").show();
            $("#fTicketAdvancedQueryResult table tbody").html("");

            var fTicketsNum = response.num,
                fTicketsCount = response.count,
                ticketData = response.data,
                rowResult = '';

            $("#fTicketsNum").html(fTicketsNum);
            $("#fTicketsCount").html(fTicketsCount);
            ticketData.forEach(function(item, index, array) {
                var btn = '';
                if (item.status == 0) {
                    btn = "<button class='btn btn-success btn-get-ticket' data-code='" + item.code + "'>取票</button>";
                } else if (item.status == 1) {
                    btn = "<button class='btn btn-danger'>已於" + (item.get_time ? item.get_time : ' 未知時候 ') + '完成取票</button>';
                }
                rowResult = fTicketTRTemplate.replace(/\{code\}/g, item.code)
                                             .replace(/\{memberName\}/g, item.member)
                                             .replace(/\{contact\}/g, item.contact ? item.contact : '')
                                             .replace(/\{ticketsName\}/g, item.tickets_name)
                                             .replace(/\{amount\}/g, item.amount)
                                             .replace(/\{index\}/g, index)
                                             .replace(/\{btn\}/g, btn);

                $("#fTicketAdvancedQueryResult table tbody").append(rowResult);
            });
            
        }
    });
});

$("#fTicketCodeQuery").on("click", function() {
    $.ajax({
        type: 'POST',
        url: '/home/getfTicketByCode',
        data: $("#fTicketQueryForm").serialize(),
        success: function(response) {
            if (response.data) {
                $("#fTicketCodeQueryResult table tbody").html("");
                var result = '',
                    item = response.data;

                result = "<tr><td>" + item.ticketsName + "</td><td>" + item.amount + "</td><td>" + (item.contact ? "<span style='margin-right: 10px;'>" + item.contact + "</span>" : "");
                if (item.status == 0) {
                    result += "<button class='btn btn-success btn-get-ticket' data-code='" + item.code + "'>取票</button>";
                } else if (item.status == 1) {
                    result += "<button class='btn btn-danger'>已於" + (item.get_time ? item.get_time : ' 未知時候 ') + '完成取票</button>';
                }

                result += "</td>";

                $("#fTicketCodeQueryResult table tbody").append(result);
                $("#fTicketCodeQueryResult").show();
            } else {
                $("#fTicketCodeQueryResult table tbody").html('<td colspan="3"><div class="alert alert-danger">找不到此序號，請重新確認</div></td>');
                $("#fTicketCodeQueryResult").show();
            }
        }
    });
});
</script>