<div class="tab-content">
    <div class="tab-pane fade" role="tabpanel" id="m-ticket"> 
        <div class="card w500p">
            <div class="card-body">
                <h5 class="card-title">團員寄票登記與查詢</h5>
                <h6 class="sub-title"></h6>
                <div class="mTicketBody">
                    <div class="card w500p">
                        <div class="card-body">
                            <form method="post" id="mTicketQueryForm">
                                @csrf
                                <div class="form-group">
                                  <label>樂器</label>
                                  <select id="instrument" class="selectionChange" name="instrument">
                                        <option value="0">請選擇樂器</option>
                                        @foreach ($instru as $key => $instr)
                                        <option value="{{ $key+1 }}">{{ str_pad($key+1, 2, '0', STR_PAD_LEFT) }} {{ $instr->instru_name }}</option>

                                        @endforeach
                                  </select>
                                </div>
                                <div class="form-group" style="display: none;">
                                    <label>團員名 </label><span class="showSelection"></span>
                                </div>
                                <div class="form-group mx-auto">
                                    <button class="btn btn-primary" type="button" id="mTicketQuery">查詢</button>
                                    <button class="btn btn-info" type="button" id="mTicketCreate">新增</button>
                                </div>
                            </form>
                            <div id="mTicketCreateResult"></div>
                            <div id="mTicketResult">
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
                                        <th>聯絡資訊</th>
                                        <th>action</th>
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
#mTicketResult {
    display: none;
}

.mTicket-create-form {
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

const fTicketTRTemplate = "<tr id='{code}-{index}'><td>{code}</td><td>{memberName}</td><td><input type='text' style='width: 100px;' name='ticketsName' value='{ticketsName}'></td><td><input type='text' style='width: 30px;' name='amount' value='{amount}'></td><td><input type='text' style='width: 130px;' name='contact' value='{contact}'></td><td><a class='fticketDel' href='#{code}-{index}'>刪除</a> | <a class='fticketSave' href='#{code}-{index}'>儲存</a></td></tr>";

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
          $("#mTicketCreateResult").html("");
        } else if (parseInt(instrument) == 14) {
          var input = "<input id='other-name' name='otherName' type='text' placeholder='請填入姓名'>";
          $(".showSelection").html(input);
          $(".showSelection").closest(".form-group").show();
          $("#mTicketCreateResult").html("");
        } else {
          $(".showSelection").html("");
          $(".showSelection").closest(".form-group").hide();
          $("#mTicketCreateResult").html("");
        }
    } else if (thisId == 'member-name') {
        $("#mTicketCreateResult").html("");
    }
});

$("#mTicketQueryForm").on("click", ".btn", function() {
    if ($(this).attr("id") == 'mTicketCreate') {
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
                $("#mTicketCreateResult").html("");
                $("#mTicketCreateResult").html(html);
            }
        });
    }
});

$("#mTicketCreateResult").on("click", ".btn", function() {
    if ($(this).attr("id") == 'mTicketCreateFormBtn') {
        $("#mTicketCreateResult").html("");
        $.ajax({
            type: 'POST',
            url: '/home/createForm',
            data: $("#mTicketCreateForm").serialize(),
            success: function(response) {
                $("#mTicketCreateResult").html(response);
            }
        });
    }
});

$("#mTicketQuery").on("click", function() {
    $("#mTicketCreateResult").html("");
    $.ajax({
        type: 'POST',
        url: '/home/getmTicket',
        data: $("#mTicketQueryForm").serialize(),
        success: function(response) {
            $("#mTicketResult").show();
            $("#mTicketResult table tbody").html("");

            var fTicketsNum = response.num,
                fTicketsCount = response.count,
                ticketData = response.data,
                rowResult = '';

            $("#fTicketsNum").html(fTicketsNum);
            $("#fTicketsCount").html(fTicketsCount);
            ticketData.forEach(function(item, index, array) {
                rowResult = fTicketTRTemplate.replace(/\{code\}/g, item.code)
                                             .replace(/\{memberName\}/g, item.member)
                                             .replace(/\{contact\}/g, item.contact ? item.contact : '')
                                             .replace(/\{ticketsName\}/g, item.tickets_name)
                                             .replace(/\{amount\}/g, item.amount)
                                             .replace(/\{index\}/g, index);
                $("#mTicketResult table tbody").append(rowResult);
            });
            
        }
    });
});
</script>