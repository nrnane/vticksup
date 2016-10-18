$.base_url = "http://vaazu.com/support/index.php/";
function ticket_status_press(value){
    var Data = $('#TicketStatusForm').serialize();
    $.ajax({
        type:"POST",
        url:$.base_url+"ajax/ticket_status",
        data:Data,
        cache:false,
        success:function(html){
            console.log(html);
            //$('#chat-message').val('')
        }
    });
}


function bindClicks() {
        //$("ul.pagination li a").click(paginationClick);		
}

function paginationClick() {
        var href = $(this).attr('href');
        $("#rounded-corner").css("opacity","0.4");


        $.ajax({
                type: "GET",
                url: href,			
                data: {},
                success: function(response)
                {				
                        //alert(response);
                        $("#rounded-corner").css("opacity","1");
                        $("#divID").html(response);
                        bindClicks();
                }
        });

        return false;
}

bindClicks();

/*
$('#searchProject').change(function(){
   // var project = $(this).val();
    //var status = $("#searchStatus").val();
    //var search = $("#searchSearch").val();
    searchTickets();
    
});
$('#searchStatus').change(function(){
    searchTickets();
    
});
$('#searchSearch').keyup(function(){
   // var project = $(this).val();
    //var status = $("#searchStatus").val();
    //var search = $("#searchSearch").val();
    searchTickets();
    
});

$('#fromDate').change(function(){
    var fromDate = $('#fromDate').val();
    var toDate = $('#toDate').val();
    
    if(fromDate!='' && toDate!=''){
        searchTickets();
    }
});

$('#toDate').change(function(){
    var fromDate = $('#fromDate').val();
    var toDate = $('#toDate').val();
    
    if(fromDate!='' && toDate!=''){
        searchTickets();
    }
});

function searchTickets() {

        $("#rounded-corner").css("opacity","0.4");


        $.ajax({
                type: "POST",
                url: $.base_url+"ajax/searchTickets",			
                data: $('#TicketSearchForm').serialize(),
                success: function(response)
                {	
                    console.log(response);
                        //alert(response);
                        $("#rounded-corner").css("opacity","1");
                        $("body").html(response);
                        //bindClicks();
                }
        });

        return false;
}
 */