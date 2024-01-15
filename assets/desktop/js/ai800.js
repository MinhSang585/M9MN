/**
 * Created by 1040170 on 2018/10/4.
 */
function getCsOnDutyAiOld(chatfrom, infoValue){
    var domain =  window.location.protocol + "//" + window.location.host;
    var gid ='14';

    var returnData = ajaxPost("/asp/query800live.php");


    if(returnData){
        if(returnData =="请先登入"){
            gid ='14';
        }else{
            gid = returnData;
        }

    }

    var serviceURL = "https://chatai.l8serviceqy8.com/chat/chatClient/chatbox.jsp?companyID=9037&configID=117&operatorId="+gid+"&live800_domain=" + domain;
    if(chatfrom != null && chatfrom != ''){
        serviceURL += "&chatfrom=" + chatfrom;
    }
    if(infoValue != null && infoValue != ''){
        serviceURL += "&info=" + infoValue;
    }

    window.open(serviceURL);

}

function getCsOnDutyAi(chatfrom, infoValue){

    var returnData = ajaxPost("/asp/query800live.php");
    var serviceURL ='';

    //console.log(returnData);
    if(returnData){
        if(returnData =="请先登入"){
            serviceURL = "https://chatai.l8serviceqy8.com/chat/chatClient/chatbox.jsp?companyID=9037&configID=14";
        }else{
            serviceURL = returnData;
        }

    }else{
        serviceURL = "https://chatai.l8serviceqy8.com/chat/chatClient/chatbox.jsp?companyID=9037&configID=14";
    }

    if(chatfrom != null && chatfrom != ''){
        serviceURL += "&chatfrom=" + chatfrom;
    }

    window.open(serviceURL);

}