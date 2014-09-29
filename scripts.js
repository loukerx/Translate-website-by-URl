
$(document).ready(function() {

    $("#click").click(function() {

        //  $("#tag").attr("src",$("#urltext").val())
        $("#tag").attr("src","http://www.mq.edu.au");
           // $("#tag").append(data);
         //  translate(['#tag'], "en", "zh-CHS", function () {});
        //拿到data以后就直接插入到指定的div里面，加入div id为tag

    });

    document.getElementById('tag').onload = function() { 
        console.log(document.getElementById("tag").contentWindow.document);
    console.log("dfsfds");
    $("#intro").html($("#tag").contents().find("body").html());        
    translate(['#intro'], "en", "zh-CHS", function () {}); }
});


//This array will hold the HTML that needs to be translated
var translationTexts = [];

//This array will hold a list of the objects that are being translated.
var translationOutput = [];

//This object will hold the original English translation to allow for it to be reset
var originalTexts = {};

//This variable holds the Bing API token
var tToken = '';

//Here is the main translation function
function translate(targets, from, to, nextTranslation) {

//Reset the two arrays holding the elements to be translated and translations from previous batches
    translationOutput.length = 0;
    translationTexts.length = 0;

//Iterate over each of the target elements for the current batch and push them into the arrays. 
    $.each(targets, function (index, element) {
        if ($(element).length) {
            var elementHTML = $(element).html();
            translationTexts.push(elementHTML);
            translationOutput.push($(element));
            originalTexts[element] = $(element).html();
        }
    });
    
//The array for translation is going to be sent as JSON so it is important to format it with JSON.stringify to avoid it causing mischief.  This of course excludes IE7 from the party but there are scripts that bring it in from the cold if you can be bothered.  I also encode any ampersands in the HTML as they can also cause JSON to choke.
    var textString = JSON.stringify(translationTexts).replace(/&amp;/g, "%26");
    console.log(textString);

//Check that the length of the batch is not too long for Bing API and if it is discard it and move on to the next batch
    if (textString.length > 10000) {
        nextTranslation();
        return;
    }
    
//The function to send the texts to Microsoft    
    function processTranslation(token) {

//This object will hold the various parameters required by the API
        var p = {};
 
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
                nextTranslation();
            }
        });
    }

//Check to see if a token already exists and if it doesn't, create it using translate.php.  Once the token is ready we can execute the 'processTranslation' function we just created.
        if (tToken !== '') {
        processTranslation(tToken);
    } else {
        var requestStr = "translate.php";
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

//Create an array from the API response
    var translationsArray = response,
    translations = "",
    i = '';
            console.log(response);
//We now loop through the translation array and use the translationOutput array we created earlier to make sure they are each inserted back into the correct locations   
    for (i = 0; i < translationsArray.length; i++) {
        translations = translations + translationsArray[i].TranslatedText;
        translationOutput[i].html(translationsArray[i].TranslatedText);
    }
}

//This function resets the document to English. 
function resetEnglish() {
    $.each(originalTexts, function (element, html) {
        $(element).html(html);
    });
    originalTexts = {};
}




