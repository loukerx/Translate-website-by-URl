<?php 
$aaa = "http://".$_POST["urltext"];
//$homepage = file_get_contents('http://www.cnn.com/');
$homepage = file_get_contents($aaa);
var_dump($homepage);
?>

<script language="javascript">
//This array will hold the HTML that needs to be translated
var translationTexts = [];

//This array will hold a list of the objects that are being translated.
var translationOutput = [];

//This object will hold the original English translation to allow for it to be reset
var originalTexts = {};

//This variable holds the Bing API token
var tToken = "";




function translate2(targets, from, to, nextTranslation){
    console.log("JSON targets");
    console.log(targets);
  //      translationTexts.push(targets);
       
//define arrays
var firstStringContainer ="";
var secondStringContainer ="";
var stringPosition = 0;
var beforeBodyContainer = [];
var jsContainer = [];
  //js split html code
    beforeBodyContainer = targets.split("<body");

//split js
//get < ,check tag name
stringPosition = targets.indexOf("<");
if(str.substr(stringPosition, 5) == "<body")
{
      beforeBodyContainer = targets.split("<body");
      firstStringContainer = beforeBodyContainer[0];
      secondStringContainer = beforeBodyContainer[1];

    stringPosition = secondStringContainer.indexOf("<");
      //check script
      if(str.substr(stringPosition, 5) == "<scri")
      {
            jsContainer = targets.split("<scri");
            translationTexts.push(jsContainer[0]);
            secondStringContainer = jsContainer[1];
      }
}else 




//
//if <script  -> jump to "</scrip t>"
//else

 //   translationTexts = targets.split(" ");  



       if (targets.length > 10000) {

    }





        var textString = JSON.stringify(translationTexts).replace(/&amp;/g, "%26");
console.log(textString);


//The function to send the texts to Microsoft    
    function processTranslation(token) {

//This object will hold the various parameters required by the API
        var p = {},
 
//We are using the the TranslateArray method as this enables us to supply multiple individual elements to be translated. more info at http://msdn.microsoft.com/en-us/library/ff512407.aspx       
        requestStr = "http://api.microsofttranslator.com/V2/Ajax.svc/TranslateArray";

//Populate the object with required parmeters such as language codes, texts for translation and the token        
        p.contentType = 'text/html';
        p.texts = textString;
        p.from = from;
        p.to = to;
        p.appId = "Bearer " + token;
       
//Send it all to Bing and wait to see what happens. Because the token expires after 10 minutes, if there is an error we can reset the token variable which ensures it is refreshed before the next batch is processed.  If the call is successful, the data is sent to 'ajaxTranslateCallback' for processing.
        $.ajax({
            url: requestStr,
            type: "GET",
            data: p,
            dataType: 'jsonp',
            jsonp: 'oncomplete',
            jsonpCallback: 'ajaxTranslateCallback',
            success: nextTranslation,
            error: function () {
                tToken = '';
            console.log("error2");
                nextTranslation();
            }
        });
    }

//Check to see if a token already exists and if it doesn't, create it using translate.php.  Once the token is ready we can execute the 'processTranslation' function we just created.
        if (tToken !== '') {
        processTranslation(tToken);
    } else {

        var requestStr = "GetToken.php";
        $.ajax({
            url: requestStr,
            type: "GET",
            cache: true,
            dataType: 'json',
            success: function (data) {
                tToken = data.access_token;
                processTranslation(tToken);
            }
        });
    }
}



//Here is the main translation function
function translate(targets, from, to, nextTranslation) {

if(targets.length >0)
{
     translationTexts.push(targets);
     var textString = JSON.stringify(translationTexts).replace(/&amp;/g, "%26");
}
    
//The function to send the texts to Microsoft    
    function processTranslation(token) {
    var p = {},
 
      requestStr = "http://api.microsofttranslator.com/V2/Ajax.svc/TranslateArray";
        p.contentType = 'text/html';
        p.texts = textString;
        p.from = from;
        p.to = to;
        p.appId = "Bearer " + token;
       
//Send it all to Bing and wait to see what happens. Because the token expires after 10 minutes, if there is an error we can reset the token variable which ensures it is refreshed before the next batch is processed.  If the call is successful, the data is sent to 'ajaxTranslateCallback' for processing.
        $.ajax({
            url: requestStr,
            type: "GET",
            data: p,
            dataType: 'jsonp',
            jsonp: 'oncomplete',
            jsonpCallback: 'ajaxTranslateCallback',
            success: nextTranslation,
            error: function () {
                tToken = '';
      console.log("error1");
                nextTranslation();
            }
        });
    }

//Check to see if a token already exists and if it doesn't, create it using translate.php.  Once the token is ready we can execute the 'processTranslation' function we just created.
        if (tToken !== '') {
        processTranslation(tToken);
    } else {
        var requestStr = "GetToken.php";
        $.ajax({
            url: requestStr,
            type: "GET",
            cache: true,
            dataType: 'json',
            success: function (data) {
                tToken = data.access_token;
                processTranslation(tToken);
            }
        });
    }
}

//Once a batch has been successfully translated it is ready to be inserted back into the page. 
function ajaxTranslateCallback(response) {
console.log("response");
            console.log(response);
//Create an array from the API response
    var translationsArray = response,
    translations = "",
    i = '';
        console.log("translationArray");
               console.log(translationsArray);
//We now loop through the translation array and use the translationOutput array we created earlier to make sure they are each inserted back into the correct locations   
    for (i = 0; i < translationsArray.length; i++) {
        translations = translations + translationsArray[i].TranslatedText;
        document.write(translations);
      //  translationOutput[i].html(translationsArray[i].TranslatedText);
    }
}

//This function resets the document to English. 
function resetEnglish() {
    $.each(originalTexts, function (element, html) {
        $(element).html(html);
    });
    originalTexts = {};
}

function test(){
    var langCode ="zh-CHS";
console.log("test");
    var translationTexts =  <?php echo json_encode($homepage); ?>;
    translate(translationTexts, "en", langCode, function () {
  })
}

</script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script> 

<?php
echo "<script type='text/javascript'>test();</script>";
?>
